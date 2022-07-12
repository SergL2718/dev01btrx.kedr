<?php
/*
 * Изменено: 14 марта 2022, понедельник
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

namespace Native\App\Sale;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Event;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\NotImplementedException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use Exception;
use Native\App\Helper;
use Native\App\Provider\Boxberry;
use Native\App\Provider\Cdek;
use Native\App\Provider\RussianPost;

class EventHandler
{
    // Массив для хранения заказов, которые получаем при работе методов класса
    // Массив необходим для того, чтобы не приходилось получать данные по заказу
    // По которому их уже получали ранее
    private static array $orders = [];

    // Массив для хранения товаров из заказов, которые получаем при работе методов класса
    // Массив необходим для того, чтобы не приходилось получать данные по товарам
    // По которым их уже получали ранее
    private static array $products = [];

    /**
     * @param $orderId
     * @param $paid
     *
     * @return void
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws ArgumentTypeException
     * @throws LoaderException
     * @throws NotImplementedException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function OnSalePayOrder($orderId, $paid)
    {
        // Если в заказе имеются электронные товары
        // Тогда необходимо сгенерировать уникальные ссылки на эти товары и отправить их покупателю
        // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/27137/
        self::generateLinks($orderId, $paid);

        if ($paid === 'Y') {
            // Если в заказе имеются сертификаты
            // Тогда необходимо сгенерировать купоны и отправить их покупателю на емаил
            // http://task.anastasia.ru/issues/4648
            self::generateCoupons($orderId);

            // Для самовывоза и оплаты по счету отправляем иное письмо
            // https://megre.ru/bitrix/admin/message_edit.php?lang=ru&ID=38
            // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/18707/
            \Bitrix\Main\Loader::includeModule('sale');
            \Bitrix\Main\Loader::includeModule('currency');
            $order = \Bitrix\Sale\Order::load($orderId);
            $deliveryCode = \Native\App\Sale\DeliverySystem::getInstance()->getCodeById($order->getField('DELIVERY_ID'));
            if ($deliveryCode === DeliverySystem::PICKUP_NSK) {
                $fields['ORDER_ID'] = $order->getField('ACCOUNT_NUMBER');
                $fields['USER_NAME'] = $order->getPropertyCollection()->getItemByOrderPropertyCode('NAME')->getValue();
                $fields['USER_EMAIL'] = $order->getPropertyCollection()->getItemByOrderPropertyCode('EMAIL')->getValue();
                $fields['PRODUCTS_PRICE'] = \CCurrencyLang::CurrencyFormat($order->getPrice() - $order->getField('PRICE_DELIVERY'), $order->getCurrency());
                $fields['ORDER_PRICE'] = \CCurrencyLang::CurrencyFormat($order->getPrice(), $order->getCurrency());
                $fields['DELIVERY_PERIOD'] = new \Bitrix\Main\Type\DateTime();
                $fields['DELIVERY_PERIOD']->add('1 days 4 hours');
                $fields['DELIVERY_PERIOD'] = FormatDate('d F', $fields['DELIVERY_PERIOD']);
                do {
                    $fields['DELIVERY_PERIOD'] = new \Bitrix\Main\Type\DateTime();
                    $fields['DELIVERY_PERIOD']->add('1 days 4 hours');
                    $fields['DELIVERY_PERIOD'] = FormatDate('d F', $fields['DELIVERY_PERIOD']);
                } while (\Native\App\Helper::getInstance()->isWeekend($fields['DELIVERY_PERIOD']));
                if (defined('ADMIN_SECTION') && ADMIN_SECTION === true) {
                    $fields['SITE_ID'] = Helper::SITE_ID;
                } else {
                    $fields['SITE_ID'] = SITE_ID;
                }
                \Bitrix\Main\Mail\Event::sendImmediate([
                    'EVENT_NAME' => 'NATIVE.APP',
                    'MESSAGE_ID' => 162,
                    'LANGUAGE_ID' => LANGUAGE_ID,
                    'LID' => $fields['SITE_ID'],
                    'C_FIELDS' => $fields,
                ]);
            }
        }
    }

    /**
     * Происходит в конце сохранения, когда заказ и все связанные сущности уже сохранены.
     *
     * ENTITY    Объект заказа.
     * VALUES    Старые значения полей заказа.
     * IS_NEW    Принимает одно из двух значений: true - если заказ новый, false - если нет. Использование данного флага позволяет избавиться от зацикливаний при вызове сохранения заказа в событии.
     *
     * @url https://dev.1c-bitrix.ru/api_d7/bitrix/sale/events/order_saved.php
     *
     * @param Event $event
     */
    public function OnSaleOrderSaved(Event $event)
    {
        $order = $event->getParameter('ENTITY');
        $oldValues = $event->getParameter('VALUES');
        $values = $order->getFields()->getValues();

        $data = [
            'order' => $order,
            'fields' => $values,
            'old' => $oldValues,
        ];

        // Отправка данных по посылкам в службу доставки
        self::sendParcelDataToDeliveryService($data);

        // Получаем данные по посылкам из службы доставки
        self::getDataParcelsFromDeliveryService($data);

        // Отправляем данные по заказу в сделку Битрикс24
        // https://megre.bitrix24.ru/workgroups/group/57/tasks/task/view/3143/
        // Устарел в связи с установкой модуля: https://megre.ru/bitrix/admin/sprod_integr_settings.php?lang=ru
        //Order::getInstance()->sendDataToDeal(['order' => $order, 'fields' => $values, 'old' => $oldValues]);

        // Создадим событие на отправку письма с запросом отзыва о товаре
        self::createRequestReviewsFromBuyers($data);
    }

    private static function sendParcelDataToDeliveryService($data)
    {
        // Необходимо создать документ посылки в сервисе Почты России
        // http://task.anastasia.ru/issues/4825
        try {
            RussianPost::getInstance()->createParcel($data);
        } catch (SystemException|LoaderException $e) {
        }

        // Необходимо создать документ посылки в сервисе Боксбери
        // http://task.anastasia.ru/issues/3940
        try {
            Boxberry::getInstance()->createParcel($data);
        } catch (SystemException $e) {
        }

        // Необходимо создать документ посылки в сервисе СДЭК
        // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/16249/
        try {
            Cdek::getInstance()->createParcel($data);
        } catch (SystemException $e) {
        }
    }

    private static function getDataParcelsFromDeliveryService($data)
    {
        // Необходимо подгрузить информацию по посылкам из сервиса Почты России в заказ
        // http://task.anastasia.ru/issues/4826
        try {
            RussianPost::getInstance()->getParcel($data);
        } catch (SystemException $e) {
        }

        // Необходимо подгрузить информацию по посылкам из сервиса Boxberry в заказ
        // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/11995/
        try {
            Boxberry::getInstance()->getParcel($data);
        } catch (SystemException $e) {
        }

        // Необходимо подгрузить информацию по посылкам из сервиса СДЭК в заказ
        // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/16251/
        try {
            Cdek::getInstance()->getParcel($data);
        } catch (SystemException $e) {
        }
    }

    /**
     * @param array $params
     *
     * @return void
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws Exception
     */
    private static function createRequestReviewsFromBuyers(array $params): void
    {
        $fields =& $params['fields'];
        if (
            $fields['STATUS_ID'] !== 'F' || $fields['PAYED'] !== 'Y'
        ) {
            return;
        }
        Loader::includeModule('highloadblock');
        $r = HighloadBlockTable::getList([
            'select' => ['ID'],
            'filter' => ['NAME' => 'Events'],
            'limit' => 1,
            'cache' => ['ttl' => 86400000],
        ]);
        if ($r->getSelectedRowsCount() > 0) {
            $date = new DateTime();
            $r = $r->fetchRaw();
            $r = HighloadBlockTable::compileEntity($r['ID']);
            $events = $r->getDataClass();
            // Проверим наличие события по заказу
            $r = $events::getList([
                'select' => [
                    'ID',
                    'UF_ENTITY_ID',
                ],
                'filter' => [
                    '=UF_EVENT_CODE' => 'REVIEW',
                    '=UF_ENTITY_CODE' => 'ORDER',
                    '=UF_ENTITY_ID' => $fields['ID'],
                ],
                'limit' => 1,
            ]);
            // События нет - добавим его
            if ($r->getSelectedRowsCount() === 0) {
                $events::add([
                    'UF_EVENT_CODE' => 'REVIEW',
                    'UF_DATE_CREATE' => new DateTime(),
                    'UF_DATE_EXEC' => $date->add('7 days'),
                    'UF_ENTITY_CODE' => 'ORDER',
                    'UF_ENTITY_ID' => $fields['ID'],
                    'UF_STATUS' => false,
                ]);
            }
        }
    }

    /**
     * Добавление кнопки при просмотре заказа в админ разделе
     */
    public function OnAdminSaleOrderView()
    {
        if ($_GET['ID']) {
            $GLOBALS['APPLICATION']->AddHeadScript('/local/js/order.min.js');
        }
    }

    /**
     * @link http://task.anastasia.ru/issues/4648
     * Если в заказе имеются сертификаты
     * Тогда необходимо сгенерировать купоны и отправить их покупателю на емаил
     *
     * @param $orderId
     *
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws LoaderException
     * @throws NotImplementedException
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentOutOfRangeException
     * @throws ArgumentTypeException
     */
    private function generateCoupons($orderId)
    {
        \Bitrix\Main\Loader::IncludeModule('sale');
        \Bitrix\Main\Loader::IncludeModule('iblock');

        $order = isset(self::$orders[$orderId]) ? self::$orders[$orderId] : \Bitrix\Sale\Order::load($orderId);

        // Получим товары заказа и найдём сертификаты
        $certificates = [];
        $nominal = [];
        $basket = $order->getBasket();
        foreach ($basket as $item) {
            $id =& $item->getProductId();
            self::$products[$id]['ID'] = $id;
            self::$products[$id]['QUANTITY'] = $item->getQuantity();
        }
        $res = \CIBlockElement::GetList([], ['ID' => array_keys(self::$products), '>PROPERTY_NOMINAL_CERTIFICATE' => 0], false, [], ['ID', 'NAME', 'PROPERTY_NOMINAL_CERTIFICATE']);
        while ($product = $res->fetch()) {
            $product['PROPERTY_NOMINAL_CERTIFICATE_VALUE'] = str_replace(' ', '', $product['PROPERTY_NOMINAL_CERTIFICATE_VALUE']);
            $nominal[] = 'CERTIFICATE_' . $product['PROPERTY_NOMINAL_CERTIFICATE_VALUE'];

            $certificates[$product['ID']] = $product;
            $certificates[$product['ID']]['QUANTITY'] = self::$products[$product['ID']]['QUANTITY'];
        }

        // Сертификатов в заказе нет - выходим
        if (count($certificates) === 0) return;

        // Для сохранения списка купонов и последующего дополнения комментария заказа
        $log = [];

        // Получим ID скидок/правил по коду и номиналу
        $discounts = [];
        $discountIterator = \Bitrix\Sale\Internals\DiscountTable::getList([
            'select' => ['ID', 'XML_ID'],
            'filter' => [
                'XML_ID' => $nominal,
            ],
        ]);
        while ($discount = $discountIterator->fetch()) {
            $nominal = str_replace('CERTIFICATE_', '', $discount['XML_ID']);
            $discounts[$nominal] = $discount['ID'];
        }

        // Создадим купоны для правил
        $orderAccountNumber = $order->getField('ACCOUNT_NUMBER');
        // Срок действия сертификатов
        $today = new \Bitrix\Main\Type\DateTime();
        $dateFrom = clone($today);
        $dateTo = $today->add('+6 MONTH');

        foreach ($certificates as $certificate) {
            $nominal =& $certificate['PROPERTY_NOMINAL_CERTIFICATE_VALUE'];
            $quantity = $certificate['QUANTITY'];

            for ($i = 0; $i < $quantity; $i++) {
                $coupon = \Bitrix\Sale\Internals\DiscountCouponTable::generateCoupon(true);

                $fields = [
                    'COUPON' => $coupon,
                    'DISCOUNT_ID' => $discounts[$nominal],
                    'MAX_USE' => 1,
                    'ACTIVE_FROM' => $dateFrom,
                    'ACTIVE_TO' => $dateTo,
                    'TYPE' => \Bitrix\Sale\Internals\DiscountCouponTable::TYPE_ONE_ORDER,
                    'DESCRIPTION' => 'Заказ: №' . $orderAccountNumber,
                ];

                // Добавим купон
                \Bitrix\Sale\Internals\DiscountCouponTable::add($fields);

                // Запишем в лог
                $log[$certificate['NAME']]['NOMINAL'] = $nominal;
                $log[$certificate['NAME']]['COUPONS'][] = $coupon;
            }
        }

        // Купоны добавлены не были - выходим
        if (count($log) === 0) return;

        // Сформируем список купонов
        // Чтобы отправить его на емаил покупателю и добавить в комментарий для менеджера к заказу
        $dateFrom = $dateFrom->format('d.m.Y');
        $dateTo = $dateTo->format('d.m.Y');
        $couponListForEmail = '';
        $couponListForManager = '';
        $counterCertificate = 1;
        foreach ($log as $certificateName => $arCertificate) {

            $counterCoupon = 1;

            if ($counterCertificate !== 1) {
                $couponListForManager .= "\n";
                $couponListForEmail .= '<br><br>';
            }

            $couponListForManager .= $counterCertificate . '. ' . $certificateName . "\n";

            $couponListForEmail .= 'Ваш подарочный сертификат на сумму ' . $arCertificate['NOMINAL'] . ' рублей.';
            $couponListForEmail .= '<br><br><br>';
            $couponListForEmail .= '<img src="https://megre.ru/email/src/img/sale/certificates/cert' . $arCertificate['NOMINAL'] . '.png" alt="">';
            $couponListForEmail .= '<br><br>';

            foreach ($arCertificate['COUPONS'] as $coupon) {
                $couponListForManager .= $counterCertificate . '.' . $counterCoupon . '. ' . $coupon . "\n";

                $couponListForEmail .= '<br>';
                $couponListForEmail .= 'Код купона: <span style="color:#9cba49;">' . $coupon . '</span>';
                $couponListForEmail .= '<br>';
                $couponListForEmail .= 'Срок действия с ' . $dateFrom . ' по ' . $dateTo;
                $couponListForEmail .= '<br>';

                $counterCoupon++;
            }

            $counterCertificate++;
        }

        // Отправляем список сертификатов/купонов на емаил покупателю
        // Получим данные покупателя
        $customer = [];
        $properties = $order->getPropertyCollection()->getArray()['properties'];
        $need = [
            'EMAIL' => true,
            'NAME' => true,
        ];

        foreach ($properties as $property) {
            if (!$need[$property['CODE']]) continue;
            $code = trim($property['CODE']);
            $value = trim($property['VALUE'][0]);
            $customer[$code] = $value;
        }

        // Отправляем письмо с сертификатами
        \Bitrix\Main\Mail\Event::sendImmediate([
            'EVENT_NAME' => 'PROMO_CODE',
            'MESSAGE_ID' => 148,
            'LANGUAGE_ID' => LANGUAGE_ID,
            'LID' => \Zk\Main\Helper::siteId(),
            'C_FIELDS' => [
                'PROMO_CODE' => $couponListForEmail,
                'EMAIL' => $customer['EMAIL'],
                'NAME' => $customer['NAME'],
                'ORDER_ID' => $orderAccountNumber,
            ],
        ]);

        // Сохраним список сертификатов в комментарий менеджера к заказу
        $managerComment = $order->getField('COMMENTS');
        $managerComment .= "\n" . '---------------------------';
        $managerComment .= "\n" . 'Сертификаты:';
        //$couponListForManager = str_replace('<br>', "\n", $couponListForManager);
        //$couponListForManager = str_replace('&nbsp;', "   ", $couponListForManager);
        $managerComment .= "\n" . $couponListForManager;
        $order->setField('COMMENTS', $managerComment);
        $order->save();
    }

    /**
     * Если в заказе имеются электронные товары
     * Тогда необходимо сгенерировать уникальные ссылки на эти товары и отправить их покупателю
     * @link https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/27137/
     *
     * @param $orderId
     * @param $paid
     */
    private function generateLinks($orderId, $paid)
    {
        \Bitrix\Main\Loader::includeModule('highloadblock');
        $storage = \Bitrix\Highloadblock\HighloadBlockTable::getList(['select' => ['ID'], 'filter' => ['NAME' => 'Access'], 'limit' => 1, 'cache' => ['ttl' => 86400000]]);
        if ($storage->getSelectedRowsCount() === 0) {
            return;
        }
        $storage = $storage->fetchRaw()['ID'];
        $storage = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($storage);
        $storage = $storage->getDataClass();
        \Bitrix\Main\Loader::includeModule('iblock');
        \Bitrix\Main\Loader::includeModule('catalog');
        \Bitrix\Main\Loader::includeModule('sale');
        $order = \Bitrix\Sale\Order::load($orderId);
        $basket = $order->getBasket();
        $links = [];
        foreach ($basket as $item) {
            $product = \CCatalogProduct::GetByIDEx($item->getProductId());
            // Если товар является электронным и доступен только по ссылке для скачивания
            if ($product['PRODUCT']['TYPE'] == \Bitrix\Catalog\ProductTable::TYPE_OFFER && !isset($product['PROPERTIES']['DOWNLOAD_LINK'])) {
                if ($parent = \CCatalogSku::GetProductInfo($product['ID'])) {
                    if (!isset($arResult['PARENTS'][$parent['ID']])) {
                        $p = \CIBlockElement::GetProperty($parent['IBLOCK_ID'], $parent['ID'], ['sort' => 'asc'], ['CODE' => 'DOWNLOAD_LINK']);
                        if ($p->SelectedRowsCount() > 0) {
                            $p = $p->Fetch();
                            $arResult['PARENTS'][$parent['ID']]['PROPERTIES'][$p['CODE']] = $p;
                        }
                    }
                    if (isset($arResult['PARENTS'][$parent['ID']]['PROPERTIES']['DOWNLOAD_LINK'])) {
                        $product['PROPERTIES']['DOWNLOAD_LINK'] = $arResult['PARENTS'][$parent['ID']]['PROPERTIES']['DOWNLOAD_LINK'];
                    }
                }
            }
            if ($product['PROPERTIES']['DOWNLOAD_LINK']['VALUE']) {
                $links[$product['ID']] = [
                    'ID' => $product['ID'],
                    'NAME' => $product['NAME'],
                    'LINK' => $product['PROPERTIES']['DOWNLOAD_LINK']['VALUE'],
                    'DATE_PAYED' => $order->getField('DATE_PAYED'),
                ];
            }
        }
        if (!empty($links)) {
            $propertyListLinks = $order->getPropertyCollection()->getItemByOrderPropertyCode('LIST_LINKS');
            // Удалим оплаченный доступ к товарам
            if ($paid === 'N') {
                $ids = [];
                foreach ($links as $link) {
                    $ids[] = $link['ID'];
                }
                $r = $storage::getList([
                    'select' => [
                        'ID',
                    ],
                    'filter' => [
                        '=UF_ENTITY_CODE' => 'PRODUCT',
                        '=UF_ENTITY_ID' => $ids,
                        '=UF_USER_ID' => $order->getUserId(),
                    ],
                ]);
                if ($r->getSelectedRowsCount() > 0) {
                    while ($i = $r->fetchRaw()) {
                        $storage::delete($i['ID']);
                    }
                }
                $propertyListLinks->setValue(false);
                $order->save();
                return;
            }
            // Выдадим доступ к товарам
            $date = new \Bitrix\Main\Type\DateTime();
            $propertyLinks = [];
            foreach ($links as $link) {
                $dateTo = clone $link['DATE_PAYED'];
                $dateTo = $dateTo->add('100 years');
                $token = generate_token($link['USER_ID'] . $link['XML_ID']);
                $r = $storage::add([
                    'UF_DATE_CREATE' => $date,
                    'UF_DATE_FROM' => $link['DATE_PAYED'],
                    'UF_DATE_TO' => $dateTo,
                    'UF_USER_ID' => (int)$order->getUserId(),
                    'UF_ENTITY_CODE' => 'PRODUCT',
                    'UF_ENTITY_ID' => $link['ID'],
                    'UF_TOKEN' => $token,
                ]);
                if ($r->isSuccess()) {
                    $propertyLinks[$link['ID']] = '/access/' . $token;
                }
            }
            if (!empty($propertyLinks)) {
                $propertyListLinks->setValue($propertyLinks);
                $order->save();
                $user = \Bitrix\Main\UserTable::getList([
                    'select' => ['NAME', 'EMAIL'],
                    'filter' => ['=ID' => $order->getUserId()],
                    'limit' => 1,
                ])->fetchRaw();
                $counter = 1;
                $emailListLinks = [];
                $emailListLinks[] = '<div style="margin: 5px 0;">';
                foreach ($links as $link) {
                    $emailListLinks[] = '<div>';
                    $emailListLinks[] = $counter . '. ';
                    $emailListLinks[] = '<a href="' . (\Bitrix\Main\Application::getInstance()->getContext()->getRequest()->isHttps() ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . $propertyLinks[$link['ID']] . '" target="_blank" style="text-decoration: underline; color: #035b2c;">';
                    $emailListLinks[] = $link['NAME'];
                    $emailListLinks[] = '</a>';
                    $emailListLinks[] = '</div>';
                    $counter++;
                }
                $emailListLinks[] = '</div>';
                $emailListLinks = implode('', $emailListLinks);
                $email = [
                    'MESSAGE_ID' => 168, // https://megre.ru/bitrix/admin/message_edit.php?lang=ru&ID=168
                    'EVENT_NAME' => 'NATIVE.APP',
                    'LANGUAGE_ID' => 'ru',
                    'LID' => 'zg',
                    'C_FIELDS' => [
                        'USER_NAME' => $user['NAME'],
                        'USER_EMAIL' => $user['EMAIL'],
                        'ORDER_ID' => $order->getField('ACCOUNT_NUMBER'),
                        'LIST_LINKS' => $emailListLinks,
                    ],
                ];
                $r = \Bitrix\Main\Mail\Event::send($email);
                if (!$r->isSuccess()) {
                    //pr($r->getErrorMessages());
                }
            }
        }
    }
}

