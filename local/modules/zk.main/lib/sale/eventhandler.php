<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Zk\Main\Sale;


use Bitrix\Sale\BasketItem;
use Native\App\Provider\Boxberry;
use Zk\Main\Helper;

class EventHandler
{
    // Массив для хранения заказов, которые получаем при работе методов класса
    // Массив необходим для того, чтобы не приходилось получать данные по заказу
    // По которому их уже получали ранее
    private static $orders = [];

    // Массив для хранения товаров из заказов, которые получаем при работе методов класса
    // Массив необходим для того, чтобы не приходилось получать данные по товарам
    // По которым их уже получали ранее
    private static $products = [];

    /**
     * Вызывается после формирования всех данных компонента на этапе заполнения формы заказа, может быть использовано для модификации данных.
     * Аналог устаревшего события OnSaleComponentOrderOneStepProcess.
     *
     * @param $order - Объект заказа \Bitrix\Sale\Order
     * @param $arUserResult - Массив arUserResult компонента, содержащий текущие выбранные пользовательские данные.
     * @param $request - Обьект \Bitrix\Main\HttpRequest
     * @param $arParams - Массив параметров компонента
     * @param $arResult - Массив arResult компонента
     */
    public function OnSaleComponentOrderResultPrepared($order, &$arUserResult, $request, &$arParams, &$arResult)
    {
        // code ...
    }

    /**
     * Событие вызывается при обновлении корзины.
     *
     * @param \Bitrix\Main\Event $event
     */
    /*public function OnSaleBasketItemRefreshData(\Bitrix\Main\Event $event)
    {
        //Basket::getInstance()->checkRules();
    }*/

    /**
     * Вызывается перед изменением записи в корзине, может быть использовано для отмены или модификации данных.
     *
     * @param $ID
     * @param $arFields
     */
    /*public function OnBeforeBasketUpdate($ID, &$arFields)
    {
        // Проверка дополнительных правил корзины
        //Basket::getInstance()->checkRules($ID, $arFields);
    }*/

    /**
     * Вызывается после изменения записи в корзине.
     *
     * @param $ID
     * @param $arFields
     */
    /*public function OnBasketUpdate($ID, $arFields)
    {
        // Проверка дополнительных правил корзины
        //Basket::getInstance()->checkRules();
    }*/

    /**
     * Вызывается перед добавлением записи в корзину, может быть использовано для отмены или модификации данных.
     *
     * @param $arFields
     * @deprecated since 2020-11-12
     */
    public function OnBeforeBasketAdd(&$arFields)
    {
        return true;
        // Если Разрешена покупка при отсутствии товара и остаток товара равен 0
        // Тогда установим товару отрицательный остаток -1
        self::checkAvailable($arFields);
    }

    /**
     * Вызывается после изменения флага оплаты заказа.
     * @param $orderId - Идентификатор заказа.
     * @param $paid - Флаг оплаты (Y - выставление оплаты, N - снятие оплаты)
     *
     * @deprecated since 2020-11-12
     */
    public function OnSalePayOrder($orderId, $paid)
    {
        return true;
        // Обработчики для акций
        //self::lottery($orderId, $paid);
        //self::september2018($orderId, $paid);

        // При оплате заказа отправим запрос на создание посылки в Boxberry
        // http://task.anastasia.ru/issues/3940
        // Устарел с 17.02.2020
        // Новый метод: Boxberry::getInstance()->createParcel()
        // /local/modules/native.app/lib/provider/boxberry.php
        //self::boxberryParselCreate($orderId, $paid);

        // Если в заказе имеются сертификаты
        // Тогда необходимо сгенерировать купоны и отправить их покупателю на емаил
        // http://task.anastasia.ru/issues/4648
        self::generateCoupons($orderId, $paid);
    }


    /**
     * @link http://task.anastasia.ru/issues/4648
     * Если в заказе имеются сертификаты
     * Тогда необходимо сгенерировать купоны и отправить их покупателю на емаил
     *
     * @param $orderId
     * @param $paid
     */
    private function generateCoupons($orderId, $paid)
    {
        if ($paid !== 'Y') return;

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
                'XML_ID' => $nominal
            ]
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
                    'DESCRIPTION' => 'Заказ: №' . $orderAccountNumber
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
                'ORDER_ID' => $orderAccountNumber
            ]
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
     * @link http://task.anastasia.ru/issues/3940
     * При оплате заказа отправим запрос на создание посылки в Boxberry
     *
     * https://boxberry.ru/business_solutions/it_solutions/1089980/
     *
     * @param $orderId
     * @param $paid
     * @deprecated
     */
    private static function boxberryParselCreate($orderId, $paid)
    {
        if ($paid !== 'Y') return;

        \Bitrix\Main\Loader::IncludeModule('sale');
        $BOX_WEIGHT = 200;
        //$BOXBERRY_POINT_RECEPTION_PARCELS_CODE = 54341; // От 630121 индекса - Новосибирск Карла Маркса_5434_С
        $BOXBERRY_POINT_RECEPTION_PARCELS_CODE = 54511; // Забор с улицы Троллейная
        $BOXBERRY_COURIER_DELIVERY_ID = 148;
        $BOXBERRY_POINT_DELIVERY_ID = 150;

        $order = isset(self::$orders[$orderId]) ? self::$orders[$orderId] : \Bitrix\Sale\Order::load($orderId);

        $deliveryId = $order->getField('DELIVERY_ID');

        if ($deliveryId != $BOXBERRY_COURIER_DELIVERY_ID && $deliveryId != $BOXBERRY_POINT_DELIVERY_ID) return;

        $fields = [];
        $fields['order_id'] = $order->getField('ACCOUNT_NUMBER');
        $fields['payment_sum'] = 0; // С покупателя при получении заказа деньги не берем

        $fields['price'] = $order->getPrice();
        $fields['delivery_sum'] = $order->getField('PRICE_DELIVERY');

        if ($deliveryId == $BOXBERRY_POINT_DELIVERY_ID) {
            $fields['vid'] = 1;
        } else if ($deliveryId == $BOXBERRY_COURIER_DELIVERY_ID) {
            $fields['vid'] = 2;
        }

        $customer = [];
        $properties = $order->getPropertyCollection()->getArray()['properties'];
        $need = [
            // Физическое лицо
            'EMAIL' => true,
            'PHONE' => true,
            'FIRST_NAME' => true,
            'NAME' => true,
            'SECOND_NAME' => true,
            'ZIP' => true,
            'CITY' => true,
            'STREET' => true,
            'HOUSE' => true,
            'APARTMENT' => true,
            // Юридическое лицо
            'COMPANY_NAME' => true,
            'COMPANY_ADR' => true,
            'INN' => true,
            'KPP' => true,
            // Boxberry
            'BOXBERRY_POINT_ID' => true,
            'BOXBERRY_POINT' => true,
        ];
        foreach ($properties as $property) {
            if (!$need[$property['CODE']]) continue;
            $code = trim($property['CODE']);
            $value = trim($property['VALUE'][0]);
            $customer[$code] = $value;
        }

        $fields['shop']['name1'] = $BOXBERRY_POINT_RECEPTION_PARCELS_CODE;
        if ($deliveryId == $BOXBERRY_POINT_DELIVERY_ID) {
            $fields['shop']['name'] = $customer['BOXBERRY_POINT_ID'];
        }

        $fields['customer'] = [
            'fio' => trim($customer['FIRST_NAME'] . ' ' . $customer['NAME'] . ' ' . $customer['SECOND_NAME']),
            'phone' => $customer['PHONE'],
            'email' => $customer['EMAIL'],
            'name' => $customer['COMPANY_NAME'],
            'address' => $customer['COMPANY_ADR'],
            'inn' => $customer['INN'],
            'kpp' => $customer['KPP']
        ];

        if ($deliveryId == $BOXBERRY_COURIER_DELIVERY_ID) {
            $fields['kurdost'] = [
                'index' => $customer['ZIP'],
                'citi' => $customer['CITY'],
                'addressp' => trim($customer['STREET'] . ', ' . $customer['HOUSE'] . ', ' . $customer['APARTMENT'])
            ];
        }

        $fields['items'] = [];
        $basket = $order->getBasket();
        foreach ($basket as $item) {
            $fields['items'][] = [
                'id' => $item->getProductId(),
                'name' => $item->getField('NAME'),
                'price' => $item->getPrice(),
                'quantity' => $item->getQuantity()
            ];
        }

        $weight = $order->getBasket()->getWeight();
        if ($weight > 0 && $weight <= 500) {
            $weight += $BOX_WEIGHT;
        } else if ($weight > 500 && $weight <= 1000) {
            $weight += $BOX_WEIGHT * 1.5;
        } else if ($weight > 1000 && $weight <= 1500) {
            $weight += $BOX_WEIGHT * 2;
        } else if ($weight > 1500) {
            $weight += $BOX_WEIGHT * 2.5;
        }
        $fields['weights'] = [
            'weight' => $weight
        ];

        $token = '157d7bda66b201a7055b59fb8b8edadc';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.boxberry.ru/json.php');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'token' => $token,
            'method' => 'ParselCreate',
            'sdata' => json_encode($fields)
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($ch), 1);
        curl_close($ch);

        // Запишем информацию в комментарий по отгрузке
        if (!$response['err']) {
            $shipmentData = [
                'STATUS_ID' => 'DS', // Передан в службу доставки
                //'TRACKING_NUMBER' => $response['track'],
                'COMMENTS' => $response['track']// . '; ' . $response['label']
            ];
        } else {
            $shipmentData = [
                'COMMENTS' => $response['err']
            ];
        }
        $shipmentCollection = $order->getShipmentCollection();
        foreach ($shipmentCollection as $shipment) {
            if ($shipment->getPrice() == $order->getField('PRICE_DELIVERY')) {
                $shipment->setFields($shipmentData);
                $shipment->save();
            }
        }
    }

    /**
     * Если Разрешена покупка при отсутствии товара и остаток товара равен 0
     * Тогда установим товару отрицательный остаток -1
     *
     * http://task.anastasia.ru/issues/4305
     */
    private static function checkAvailable($fields)
    {
        $product = \Bitrix\Main\Application::getConnection()->query('select QUANTITY, CAN_BUY_ZERO from b_catalog_product where ID="' . $fields['PRODUCT_ID'] . '" limit 1')->fetch();
        if ($product['QUANTITY'] == 0 && $product['CAN_BUY_ZERO'] === 'Y') {
            \Bitrix\Main\Application::getConnection()->queryExecute('update b_catalog_product set QUANTITY="-1" where ID="' . $fields['PRODUCT_ID'] . '" limit 1');
        }
    }

    /**
     * @link http://task.anastasia.ru/issues/3556
     * После оплаты заказа пользователю необходимо выслать купон на получение подарка
     * Период розыгрыша 01.09.18 - 30.09.18
     * После, данный обработчик можно отключить
     *
     * @param $orderId
     * @param $paid
     */
    private static function september2018($orderId, $paid)
    {
        /*$date = date('Y-m-d');
        $from = '2018-09-01';
        $to = '2018-10-01';
        if (($date >= $from && $date <= $to) && $paid == 'Y') {
            require $_SERVER['DOCUMENT_ROOT'] . '/local/work/september2018/class.php';
            \Promotion::getInstance()->run($orderId);
        }*/
    }

    /**
     * @link http://task.anastasia.ru/issues/3365
     * В связи с условиями розыгрыша
     * После оплаты заказа пользователю необходимо выслать купон за каждую 1000 руб стоимости заказа
     * Период розыгрыша 10.06.18 - 10.08.18
     * После, данный обработчик можно отключить
     *
     * @param $orderId
     * @param $paid
     *
     * @deprecated
     */
    private static function lottery($orderId, $paid)
    {
        /*$currentDate = date('Y-m-d');
        $promoDateFrom = '2018-06-10';
        $promoDateTo = '2018-08-10';
        if ($currentDate >= $promoDateFrom && $currentDate <= $promoDateTo) {
            if ($paid == 'Y') {
                // was moved to trash
                require $_SERVER['DOCUMENT_ROOT'] . '/local/work/promo/10.06.18-10.08.18.php';
                \Dealer::getInstance()->generate('order', $orderId);
            }
        }*/
    }
}
