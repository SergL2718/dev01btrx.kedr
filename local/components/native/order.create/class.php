<?php
/*
 * Изменено: 03 марта 2022, четверг
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

use Bitrix\Catalog\ProductTable;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Db\SqlQueryException;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Mail\Event;
use Bitrix\Main\NotImplementedException;
use Bitrix\Main\NotSupportedException;
use Bitrix\Main\ObjectException;
use Bitrix\Main\ObjectNotFoundException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\SystemException;
use Bitrix\Main\UI\Extension;
use Bitrix\Main\UserTable;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketBase;
use Bitrix\Sale\DiscountCouponsManager;
use Bitrix\Sale\Fuser;
use Bitrix\Sale\Internals\OrderTable;
use Native\App\Foundation\Bitrix24;
use Native\App\Helper;
use Native\App\Provider\Bitrix24\Contact;
use Native\App\Provider\Boxberry;
use Native\App\Provider\Cdek;
use Native\App\Provider\RussianPost;
use Native\App\Sale\DeliverySystem;
use Native\App\Sale\Discount;
use Native\App\Sale\Document;
use Native\App\Sale\Location;
use Native\App\Sale\Order;
use Native\App\Sale\PaymentSystem;
use Native\App\Sale\Person;

class OrderCreate extends CBitrixComponent implements Controllerable
{
    private ?object $basket = null;
    private ?float $price = null; // сумма товаров в корзине
    private ?float $weight = null; // вес товаров в корзине
    private ?float $volume = null; // объем товаров в корзине
    private ?object $order = null; // объект заказа
    private ?int $fUserId = null; // ID владельца корзины
    //private ?array $productsExcludedFromDiscount = null; // товары исключенные из скидок корзины

    private ?bool $canUseFreeDelivery = null;
    private ?array $products = null; // товары корзины + исключенные товары (если они имеются)
    private ?string $coupon = null; // примененный купон к корзине
    private ?bool $hasMaxDiscount = null; // имеется ли максимальная скидка 20%
    private ?bool $hasOnlyElectronicProducts = null; // имеются только электронные товары

    public function executeComponent()
    {
        $this->setFrameMode(false);

        global $USER;
        $arResult =& $this->arResult;

        // Локальное хранилище
        $storage = $_COOKIE[$this->getStorageCode()];

        if ($storage) {
            $storage = json_decode($storage, true);
        }

        $arResult['ORDER'] = [
            'BASKET' => $this->getProducts(),
            'COUPON' => $this->getAppliedCoupon(),
            'TOTAL' => [
                'AMOUNT' => [
                    'VALUE' => $this->getPrice(),
                    'WITHOUT_CURRENCY' => CCurrencyLang::CurrencyFormat($this->getPrice(), $this->getCurrency(), false),
                ],
            ],
            'LAST' => $this->getLast(),
        ];

        setcookie(Option::get('main', 'cookie_name') . '_LAST_ORDER', '', time() + 8640000, '/'); // удалим куки

        $arResult['FREE_DELIVERY']['CAN_USE'] = $this->canUseFreeDelivery();

        // Шаги для работы формы заказа
        $arResult['STEP'] = [
            'current' => $storage['currentStep'] ?? 'customer', // customer || delivery || payment || total
            'initial' => 'customer',
            'final' => 'total',
            'list' => [
                'customer',
                'delivery',
                'payment',
                'total',
            ],
        ];

        // Если локация была изменена принудительно, тогда вернем покупателя на шаг Доставка
        if (
            $storage['name'] &&
            $storage['lastName'] &&
            $storage['secondName'] &&
            $arResult['STEP']['current'] === 'total' &&
            (
                !$storage['zip'] ||
                !$arResult['ORDER']['LAST']['LOCATION']['ZIP']
            )
        ) {
            $arResult['STEP']['current'] = 'delivery';
        }

        // Коды форм на каждом шаге для формы заказа
        $arResult['FORM'] = [
            'customer' => [
                'code' => 'order-customer',
                'type' => [
                    'personal' => Person::PHYSICAL_CODE,
                    'legal' => Person::LEGAL_CODE,
                ],
            ],
            'delivery' => [
                'code' => 'order-delivery',
            ],
            'payment' => [
                'code' => 'order-payment',
            ],
            'total' => [
                'code' => 'order-total',
            ],
        ];

        // Определяем условия работы для выбора локации
        $arResult['LOCATION'] = [
            // Отключаем поля выбора адреса для региона Москва
            'INPUT' => [
                'ACTIVE' => Location::getCurrentCityCode() === Location::MSK && $arResult['ORDER']['LAST']['LOCATION']['CITY']['LOWER'] === Location::MOSCOW_CITY_TITLE_LOWER ? 'N' : 'Y',
            ],
        ];
        // Массив данных для обработки через Javascript
        $arResult['JS'] = [
            'userId' => $USER->IsAuthorized() ? $USER->GetID() : '',
            'component' => $this->getName(),
            'order' => [
                'type' => $this->arParams['ORDER']['TYPE'],
                'create' => [
                    'method' => 'createOrder',
                ],
            ],
            'basket' => [
                'amount' => $this->getPrice(),
            ],
            'path' => [
                'catalog' => '/catalog/',
                'basket' => '/personal/basket/',
            ],
            'delivery' => [
                'list' => $this->arParams['DELIVERY'],
                'calculate' => [
                    'method' => 'calculateDeliveryPrice',
                ],
            ],
            'payment' => [
                'list' => $this->arParams['PAYMENT'],
            ],
            'storage' => [
                'code' => $this->getStorageCode(),
                'data' => [],
            ],
            'form' => $arResult['FORM'],
            'step' => $arResult['STEP'],
            'last' => [
                'location' => [
                    'city' => [
                        'value' => $arResult['ORDER']['LAST']['LOCATION']['CITY']['VALUE'],
                        'lower' => $arResult['ORDER']['LAST']['LOCATION']['CITY']['LOWER'],
                    ],
                ],
                'delivery' => [
                    'code' => $arResult['ORDER']['LAST']['DELIVERY']['CODE'],
                ],
                'boxberry' => $arResult['ORDER']['LAST']['BOXBERRY'],
                'cdek' => $arResult['ORDER']['LAST']['CDEK'],
            ],
            'location' => [
                'current' => Location::getCurrentCityCode() === Location::NSK ? Location::OTHER : Location::getCurrentCityCode(),
                'list' => Location::getList(),
                'cookie' => Location::COOKIE_CITY_CODE,
            ],
            'yandex' => [
                'currencyCode' => $this->getCurrency(),
                'coupon' => $this->getAppliedCoupon(),
                'products' => $this->getYandexProductList(),
            ],
        ];
        // Активируем шаблон компонента
        $this->includeComponentTemplate();
    }

    // ====================================================================    Обработка и формирование параметров

    public function onPrepareComponentParams($arParams): array
    {
        // Если был выполнен аякс-запрос
        // Тогда не обрабатываем параметры - они уже были обработаны ранее
        if ($_SERVER['HTTP_BX_AJAX'] === 'true') {
            return $arParams;
        }

        /*
         * ============================================================
         * ДОРАБОТАТЬ ОЧИСТКУ ТОВАРОВ, КОТОРЫЕ НЕ ПРОХОДЯТ ПО АКЦИЯМ
         * НАПРИМЕР, УДАЛЕНИЕ ПОДАРКОВ
         * ============================================================
         */

        $this->checkingExistBasketProducts();
        //$this->checkingExcludedProducts();
        $this->connectYandexServices();
        $this->connectComponentLibrary();
        try {
            $this->determineOrderType($arParams);
        } catch (SqlQueryException|ObjectException $e) {
        }
        $this->generateStorageCode($arParams);
        $this->countries($arParams);
        try {
            $this->payment($arParams);
        } catch (ArgumentException $e) {
        }
        $this->delivery($arParams);
        return $arParams;
    }

    /**
     * Проверим наличие товаров в корзине
     */
    private function checkingExistBasketProducts()
    {
        if ($this->isBasketEmpty()) {
            LocalRedirect('/personal/basket/');
        }
    }

    /**
     * @url https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/2021/
     * @description Проверка корзины на наличие исключенных из скидок товаров
     * Если такие товары имеются, тогда исключим их из корзины и обработаем иначе
     */
    /*private function checkingExcludedProducts()
    {
        $basket = $this->getBasket();
        $reloadBasket = false;
        $dateObject = new \Bitrix\Main\Type\DateTime();

        foreach ($basket as $item) {
            $product = CCatalogProduct::GetByIDEx($item->getProductId());
            if ($product['PROPERTIES']['EXCLUDE_FROM_DISCOUNT']['VALUE_XML_ID'] !== 'Y') {
                continue;
            }
            if ($product['PRODUCT']['QUANTITY'] <= 0 && $product['PRODUCT']['CAN_BUY_ZERO'] === 'Y') {
                $product['PRODUCT']['QUANTITY'] = 10000000;
            }
            $measureRatio = Application::getConnection()->query('select RATIO from ' . MeasureRatioTable::getTableName() . ' where PRODUCT_ID="' . $product['ID'] . '" limit 1')->fetchRaw()['RATIO'];
            $arPrice = array_shift($product['PRICES']);
            if (Application::getConnection()->add('app_product_excluded_from_discount', [
                'DATE_INSERT' => $dateObject,
                'FUSER_ID' => $this->getFUserId(),
                'ROW_ID' => $item->getId(),
                'PRODUCT_ID' => $product['ID'],
                'QUANTITY' => $item->getQuantity(),
                'MEASURE_RATIO' => $measureRatio,
                'AVAILABLE_QUANTITY' => $product['PRODUCT']['QUANTITY'],
                'PRICE' => $arPrice['PRICE'],
                'WEIGHT' => $product['PRODUCT']['WEIGHT'],
                'WIDTH' => $product['PRODUCT']['WIDTH'],
                'HEIGHT' => $product['PRODUCT']['HEIGHT'],
                'LENGTH' => $product['PRODUCT']['LENGTH'],
                'NAME' => $product['NAME'],
                'PRODUCT_TYPE_ID' => $product['PRODUCT']['TYPE'],
                'EXTERNAL_ID' => $product['EXTERNAL_ID'],
            ])) {
                CSaleBasket::Delete($item->getId());
                $reloadBasket = true;
            }
        }
        if ($reloadBasket) LocalRedirect($_SERVER['REQUEST_URI']);
    }*/

    private function connectYandexServices()
    {
        try {
            Asset::getInstance()->addJs('https://api-maps.yandex.ru/2.1/?ns=Yandex&apikey=' . Option::get('fileman', 'yandex_map_api_key') . '&lang=' . $this->getLanguageId() . '_' . mb_strtoupper($this->getLanguageId()));
        } catch (ArgumentNullException|ArgumentOutOfRangeException $e) {
        }
    }

    private function connectComponentLibrary()
    {
        Extension::load('ui.notification');
        CJSCore::Init(['phone_number']);
    }

    /**
     * Код хранилища данных заказа в локальной памяти
     *
     * @param $arParams
     */
    private function generateStorageCode(&$arParams)
    {
        $arParams['STORAGE']['CODE'] = $this->getStorageCode();
    }

    /**
     * Метод для определения типа заказа на основании товара корзины
     * Может принимать значения: internet || retail || combine || moscow
     *
     * @param $arParams
     *
     * @return array
     * @throws SqlQueryException|ObjectException
     */
    private function determineOrderType(&$arParams): array
    {
        if (Location::getCurrentCityCode() === Location::MSK) {
            $arParams['ORDER']['TYPE'] = Order::TYPE_MOSCOW;
            return $arParams;
        }

        $arParams['ORDER']['TYPE'] = null;

        $basket = $this->getBasket();
        $count = $basket->count();

        // Если штатная корзина пустая
        // Но имеются исключенные товары из скидок корзины
        // Тогда обработаем их
        /*if ($count === 0 && $count = count($this->getProductsExcludedFromDiscount()) > 0) {

            if ($count > 1) {
                // массив для хранения типов товара
                $types = [];
                foreach ($this->getProductsExcludedFromDiscount() as $product) {

                    $type = $this->getProductType($product['PRODUCT_ID']);

                    if ($type === Order::TYPE_COMBINE) {
                        $arParams['ORDER']['TYPE'] = $type;
                        break;
                    }

                    $types[$type] = true;
                }

                if ($arParams['ORDER']['TYPE'] === null) {
                    if (count($types) > 1) {
                        $arParams['ORDER']['TYPE'] = Order::TYPE_COMBINE;
                    } else {
                        $arParams['ORDER']['TYPE'] = array_key_first($types);
                    }
                }

            } else {
                $arParams['ORDER']['TYPE'] = $this->getProductType($this->getProductsExcludedFromDiscount()[0]['PRODUCT_ID']);
            }

            if ($arParams['ORDER']['TYPE'] === null || empty($arParams['ORDER']['TYPE'])) {
                $arParams['ORDER']['TYPE'] = Order::TYPE_INTERNET;
            }
            return $arParams;
        }*/

        // Проверим штатную корзину
        if ($count > 1) {
            // массив для хранения типов товара
            $types = [];
            foreach ($basket as $item) {

                $type = $this->getProductType($item->getProductId());

                if ($type === Order::TYPE_COMBINE) {
                    $arParams['ORDER']['TYPE'] = $type;
                    break;
                }

                $types[$type] = true;
            }

            // Если имеются еще и исключенные товары из скидок корзины
            // Тогда учтем еще и их
            /*if (count($this->getProductsExcludedFromDiscount()) > 0) {
                foreach ($this->getProductsExcludedFromDiscount() as $product) {

                    $type = $this->getProductType($product['PRODUCT_ID']);

                    if ($type === Order::TYPE_COMBINE) {
                        $arParams['ORDER']['TYPE'] = $type;
                        break;
                    }

                    $types[$type] = true;
                }
            }*/

            if ($arParams['ORDER']['TYPE'] === null) {
                if (count($types) > 1) {
                    $arParams['ORDER']['TYPE'] = Order::TYPE_COMBINE;
                } else {
                    $arParams['ORDER']['TYPE'] = array_key_first($types);
                }
            }

        } else {
            $arParams['ORDER']['TYPE'] = $this->getProductType($basket[0]->getProductId());
        }

        if ($arParams['ORDER']['TYPE'] === null || empty($arParams['ORDER']['TYPE'])) {
            $arParams['ORDER']['TYPE'] = Order::TYPE_INTERNET;
        }

        return $arParams;
    }

    private function basketHasOnlyElectronicProducts(): bool
    {
        if ($this->hasOnlyElectronicProducts === null) {
            $this->hasOnlyElectronicProducts = false;
            $arResult = [
                'PARENTS' => [],
                'TYPES' => [],
            ];
            $basket = $this->getBasket();
            foreach ($basket as $item) {
                $product = CCatalogProduct::GetByIDEx($item->getProductId());
                // Если товар является электронным и доступен только по ссылке для скачивания
                if ($product['PRODUCT']['TYPE'] == ProductTable::TYPE_OFFER && !isset($product['PROPERTIES']['DOWNLOAD_LINK'])) {
                    if ($parent = \CCatalogSku::GetProductInfo($product['ID'])) {
                        if (!isset($arResult['PARENTS'][$parent['ID']])) {
                            $p = CIBlockElement::GetProperty($parent['IBLOCK_ID'], $parent['ID'], ['sort' => 'asc'], ['CODE' => 'DOWNLOAD_LINK']);
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
                    $arResult['electronic'] = true;
                } else {
                    $arResult['default'] = true;
                }
                if (isset($arResult['electronic']) && isset($arResult['default'])) {
                    $this->hasOnlyElectronicProducts = false;
                    break;
                }
            }
            if (isset($arResult['electronic']) && !isset($arResult['default'])) {
                $this->hasOnlyElectronicProducts = true;
            }
        }
        return $this->hasOnlyElectronicProducts;
    }

    /**
     * Страны в которые доступна доставка
     *
     * @param $arParams
     */
    private function countries(&$arParams)
    {
        $arParams['COUNTRY']['LIST'] = DeliverySystem::getInstance()->getCountryList();

        $clientCountryCode = 'RU';

        if (function_exists('geoip_country_code_by_name')) {
            $clientCountryCode = geoip_country_code_by_name($_SERVER['REMOTE_ADDR']);
        }

        if (!$clientCountryCode || !isset($arParams['COUNTRY']['LIST'][$clientCountryCode])) {
            $clientCountryCode = 'RU';
        }

        $arParams['COUNTRY']['CLIENT'] = [
            'ID' => $arParams['COUNTRY']['LIST'][$clientCountryCode]['id'],
            'CODE' => $clientCountryCode,
            'NAME' => $arParams['COUNTRY']['LIST'][$clientCountryCode]['name'],
        ];
    }

    /**
     * Метод для получения параметров платёжных систем
     *
     * @param $arParams
     *
     * @throws ArgumentException
     */
    private function payment(&$arParams)
    {
        $configs = [
            PaymentSystem::IN_STORE => PaymentSystem::getInstance()->getServiceByCode(PaymentSystem::IN_STORE),
        ];
        $filter = ['ACTIVE' => 'Y'];
        // Если заказ создается не только в интернете
        // Тогда изменим список доступных платежных систем
        if (
            $arParams['ORDER']['TYPE'] === Order::TYPE_RETAIL ||
            $arParams['ORDER']['TYPE'] === Order::TYPE_COMBINE
        ) {
            $filter['XML_ID'] = [
                PaymentSystem::CARD_FAKE_CODE,
                PaymentSystem::IN_STORE,
            ];
        } else {
            $filter['!XML_ID'] = 'acquiring-terminal-msk';
        }
        $r = Bitrix\Sale\PaySystem\Manager::getList([
            'select' => ['ID', 'NAME', 'DESCRIPTION', 'LOGOTIP', 'XML_ID'],
            'filter' => $filter,
            'order' => ['SORT' => 'ASC'],
            'cache' => ['ttl' => 86400000],
        ]);
        while ($ar = $r->fetchRaw()) {
            $code =& $ar['XML_ID'];
            $ar['CODE'] = $code;
            if ($ar['LOGOTIP']) {
                $ar['IMAGE'] = CFile::ResizeImageGet($ar['LOGOTIP'], ['width' => 220, 'height' => 220])['src'];
            }
            unset($ar['XML_ID'], $ar['LOGOTIP']);
            if ($configs[$code]) {
                $ar = array_merge($ar, $configs[$code]);
            }
            $arParams['PAYMENT'][$code] = $ar;
        }
    }

    /**
     * Метод для получения параметров способов доставки
     *
     * @param $arParams
     */
    private function delivery(&$arParams)
    {
        if ($this->basketHasOnlyElectronicProducts()) {
            $configs = [
                DeliverySystem::WITHOUT_DELIVERY => DeliverySystem::getInstance()->getServiceByCode(DeliverySystem::WITHOUT_DELIVERY),
            ];
            // Получим данные по службам доставок
            $filter = ['ACTIVE' => 'Y', '=XML_ID' => DeliverySystem::WITHOUT_DELIVERY];
        } else {
            $configs = [
                // NSK
                DeliverySystem::COURIER_NSK => DeliverySystem::getInstance()->getServiceByCode(DeliverySystem::COURIER_NSK),
                DeliverySystem::PICKUP_NSK => DeliverySystem::getInstance()->getServiceByCode(DeliverySystem::PICKUP_NSK),
                DeliverySystem::COURIER_NSK_FREE => DeliverySystem::getInstance()->getServiceByCode(DeliverySystem::COURIER_NSK_FREE),

                // BERDSK
                DeliverySystem::COURIER_BERDSK => DeliverySystem::getInstance()->getServiceByCode(DeliverySystem::COURIER_BERDSK),
                DeliverySystem::COURIER_BERDSK_FREE => DeliverySystem::getInstance()->getServiceByCode(DeliverySystem::COURIER_BERDSK_FREE),

                // MSK
                DeliverySystem::PICKUP_MSK_NOVOSLOBODSKAYA => DeliverySystem::getInstance()->getServiceByCode(DeliverySystem::PICKUP_MSK_NOVOSLOBODSKAYA),
                DeliverySystem::COURIER_MSK_INSIDE_MKAD => DeliverySystem::getInstance()->getServiceByCode(DeliverySystem::COURIER_MSK_INSIDE_MKAD),
                DeliverySystem::COURIER_MSK_INSIDE_MKAD_FREE => DeliverySystem::getInstance()->getServiceByCode(DeliverySystem::COURIER_MSK_INSIDE_MKAD_FREE),
                DeliverySystem::COURIER_MSK_OUTSIDE_MKAD => DeliverySystem::getInstance()->getServiceByCode(DeliverySystem::COURIER_MSK_OUTSIDE_MKAD),

                RussianPost::EMS => RussianPost::getInstance()->getServiceByCode(RussianPost::EMS),
                RussianPost::CLASSIC => RussianPost::getInstance()->getServiceByCode(RussianPost::CLASSIC),
                RussianPost::AIR => RussianPost::getInstance()->getServiceByCode(RussianPost::AIR),
                RussianPost::SURFACE => RussianPost::getInstance()->getServiceByCode(RussianPost::SURFACE),
                RussianPost::CLASSIC_FREE => RussianPost::getInstance()->getServiceByCode(RussianPost::CLASSIC_FREE),

                Boxberry::COURIER => Boxberry::getInstance()->getServiceByCode(Boxberry::COURIER),
                Boxberry::POINT => Boxberry::getInstance()->getServiceByCode(Boxberry::POINT),
                Boxberry::POINT_FREE => Boxberry::getInstance()->getServiceByCode(Boxberry::POINT_FREE),

                'cdek-store-to-store' => Cdek::getInstance()->getServiceByCode('cdek-store-to-store'),
                'cdek-store-to-door' => Cdek::getInstance()->getServiceByCode('cdek-store-to-door'),
            ];

            // Если нельзя использовать бесплатную доставку
            if ($this->canUseFreeDelivery($arParams) === false) {
                unset(
                    $configs[RussianPost::CLASSIC_FREE],
                    $configs[Boxberry::POINT_FREE],
                    $configs[DeliverySystem::COURIER_MSK_INSIDE_MKAD_FREE],
                    $configs[DeliverySystem::COURIER_NSK_FREE],
                    $configs[DeliverySystem::COURIER_BERDSK_FREE]
                );
            } else {
                $configs['cdek-store-to-store']['price'] = 'free';
            }

            // Отключаем лишние службы для Москвы
            if (Location::getCurrentCityCode() === Location::MSK) {
                unset(
                    $configs[DeliverySystem::PICKUP_NSK],
                    $configs[DeliverySystem::COURIER_NSK],
                    $configs[DeliverySystem::COURIER_NSK_FREE],
                    $configs[DeliverySystem::COURIER_BERDSK],
                    $configs[DeliverySystem::COURIER_BERDSK_FREE],
                    $configs[RussianPost::EMS],
                    $configs[RussianPost::CLASSIC],
                    $configs[RussianPost::AIR],
                    $configs[RussianPost::SURFACE],
                    $configs[RussianPost::CLASSIC_FREE]
                );
            }

            // Если была применена скидка 20%
            // Тогда поменяем максимальную сумму от которой доступны доставки
            if ($this->hasMaxDiscount()) {
                foreach ($configs as &$config) {
                    if (isset($config['restriction']['country'])) {
                        foreach ($config['restriction']['country'] as &$country) {
                            if (isset($country['minPrice']) && $country['minPrice'] === 20000) {
                                $country['minPrice'] = $country['minPrice'] - ($country['minPrice'] * 0.2);
                            }
                        }
                    }
                }
            }

            // Получим данные по службам доставок
            $filter = ['ACTIVE' => 'Y', 'XML_ID' => array_keys($configs)];
            // Если заказ создается не только в интернете
            // Тогда изменим список доступных платежных систем
            if (
                $arParams['ORDER']['TYPE'] === Order::TYPE_RETAIL ||
                $arParams['ORDER']['TYPE'] === Order::TYPE_COMBINE
            ) {
                $filter['XML_ID'] = [
                    DeliverySystem::PICKUP_NSK,
                    DeliverySystem::COURIER_NSK,
                    DeliverySystem::COURIER_NSK_FREE,
                    DeliverySystem::COURIER_BERDSK,
                    DeliverySystem::COURIER_BERDSK_FREE,
                ];
            }
        }

        try {
            $r = Bitrix\Sale\Delivery\Services\Table::getList([
                'select' => [
                    'ID',
                    'NAME',
                    'DESCRIPTION',
                    'LOGOTIP',
                    'XML_ID',
                    'CONFIG',
                ],
                'filter' => $filter,
                'order' => [
                    'SORT' => 'ASC',
                ],
                'cache' => ['ttl' => 86400000],
            ]);
            while ($ar = $r->fetchRaw()) {
                $code =& $ar['XML_ID'];
                if (isset($configs[$code]['active']) && $configs[$code]['active'] !== true) continue;
                if ($ar['LOGOTIP']) {
                    $ar['IMAGE'] = \CFile::ResizeImageGet($ar['LOGOTIP'], ['width' => 80, 'height' => 80])['src'];
                }
                $ar['CODE'] = $code;
                $ar['CONFIG'] = unserialize($ar['CONFIG']);
                if ($ar['CONFIG']['MAIN']['PRICE'] > 0 && !$configs[$code]['price']) {
                    $ar['price'] = $ar['CONFIG']['MAIN']['PRICE'];
                }
                if ($ar['CONFIG']['MAIN']['PERIOD']) {
                    if ($ar['CONFIG']['MAIN']['PERIOD']['FROM'] > 0) {
                        $ar['period']['min'] = $ar['CONFIG']['MAIN']['PERIOD']['FROM'];
                    }
                    if ($ar['CONFIG']['MAIN']['PERIOD']['TO'] > 0) {
                        $ar['period']['max'] = $ar['CONFIG']['MAIN']['PERIOD']['TO'];
                    }
                }
                unset($ar['LOGOTIP'], $ar['CONFIG']);
                if ($configs[$code]) {
                    $ar = array_merge($ar, $configs[$code]);
                }
                $arParams['DELIVERY'][$code] = $ar;
            }

        } catch (ObjectPropertyException|SystemException $e) {
        }
    }

    /**
     * Настройка доступа к ajax-интерфейсу
     *
     * @return array[][]
     */
    public function configureActions(): array
    {
        return [
            'checkAbilityAddToBasket' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([
                        ActionFilter\HttpMethod::METHOD_POST,
                    ]),
                    new ActionFilter\Csrf(),
                ],
            ],
            'boxberryProvider' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([
                        ActionFilter\HttpMethod::METHOD_POST,
                    ]),
                    new ActionFilter\Csrf(),
                ],
            ],
            'russianPostProvider' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([
                        ActionFilter\HttpMethod::METHOD_POST,
                    ]),
                    new ActionFilter\Csrf(),
                ],
            ],
            'deliverySystemProvider' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([
                        ActionFilter\HttpMethod::METHOD_POST,
                    ]),
                    new ActionFilter\Csrf(),
                ],
            ],
            'cdekProvider' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([
                        ActionFilter\HttpMethod::METHOD_POST,
                    ]),
                    new ActionFilter\Csrf(),
                ],
            ],
            'calculateDeliveryPrice' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([
                        ActionFilter\HttpMethod::METHOD_POST,
                    ]),
                    new ActionFilter\Csrf(),
                ],
            ],
            'createOrder' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([
                        ActionFilter\HttpMethod::METHOD_POST,
                    ]),
                    new ActionFilter\Csrf(),
                ],
            ],
        ];
    }

    // ====================================================================    Обработка данных корзины

    private function isBasketEmpty()
    {
        try {
            if ($this->getBasket()->count() > 0 /*|| count($this->getProductsExcludedFromDiscount()) > 0*/) {
                return false;
            }
        } catch (SqlQueryException $e) {
        }
        return true;
    }

    /**
     * @return BasketBase|object|null
     * @throws ObjectException
     */
    private function getBasket()
    {
        if ($this->basket === null) {
            global $USER;
            try {
                Loader::includeModule('sale');
                Bitrix\Sale\Compatible\DiscountCompatibility::stopUsageCompatible();
                $this->order = \Bitrix\Sale\Order::create(SITE_ID, $USER->GetID());
                // Basket
                $basket = Bitrix\Sale\Basket::loadItemsForFUser($this->getFUserId(), SITE_ID);
                $this->order->appendBasket($basket);
                $this->basket = $basket;
            } catch (LoaderException|ArgumentTypeException|ArgumentException|NotImplementedException|ObjectNotFoundException|NotSupportedException $e) {
            }
        }
        return $this->basket;
    }

    private function getOrder()
    {
        if ($this->order === null) {
            try {
                $this->getBasket();
            } catch (ObjectException $e) {
            }
        }
        return $this->order;
    }

    private function getAppliedCoupon()
    {
        if ($this->coupon === null) {
            $discount = $this->getOrder()->getDiscount()->getApplyResult(true);
            if ($discount) {
                $coupon = array_shift($discount['COUPON_LIST']);
                $this->coupon = $coupon['COUPON'] ?? '';
            }
        }
        return $this->coupon;
    }

    private function getPrice()
    {
        if ($this->price === null) {
            $this->price = $this->getBasket()->getPrice();
            /*if ($products = $this->getProductsExcludedFromDiscount()) {
                foreach ($products as $product) {
                    $this->price += $product['PRICE'] * $product['QUANTITY'];
                }
            }*/
        }
        return $this->price;
    }

    private function getWeight()
    {
        if ($this->weight === null) {
            $this->weight = $this->getBasket()->getWeight();
            /*if ($products = $this->getProductsExcludedFromDiscount()) {
                foreach ($products as $product) {
                    $this->weight += $product['WEIGHT'] * $product['QUANTITY'];
                }
            }*/
        }
        return $this->weight;
    }

    private function getVolume()
    {
        if ($this->volume === null) {
            try {
                $basket = $this->getBasket()->getOrderableItems();
                foreach ($basket as $item) {
                    $quantity = $item->getQuantity();
                    $dimensions = $item->getField('DIMENSIONS');
                    if (!isset($dimensions['LENGTH'])) {
                        $dimensions = unserialize($dimensions);
                    }
                    $length = $dimensions['LENGTH'] / 10;
                    $width = $dimensions['WIDTH'] / 10;
                    $height = $dimensions['HEIGHT'] / 10;
                    $volume = $length * $width * $height;
                    $this->volume += $volume * $quantity;
                }
            } catch (ArgumentNullException $e) {
            }

            /*try {
                if ($products = $this->getProductsExcludedFromDiscount()) {
                    foreach ($products as $product) {
                        $length = $product['LENGTH'] / 10;
                        $width = $product['WIDTH'] / 10;
                        $height = $product['HEIGHT'] / 10;
                        $volume = $length * $width * $height;
                        $this->volume += $volume * $product['QUANTITY'];
                    }
                }
            } catch (SqlQueryException $e) {
            }*/
        }
        return $this->volume;
    }

    private function getCurrency()
    {
        return CurrencyManager::getBaseCurrency();
    }

    private function getStorageCode(): string
    {
        return Option::get('main', 'cookie_name') . '_ORDER_STORAGE';
    }

    /**
     * Товары исключенные из скидки/скидок корзины
     * @url https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/2021/
     *
     * @return array
     * @throws SqlQueryException
     */
    /*private function getProductsExcludedFromDiscount()
    {
        return [];

        if ($this->productsExcludedFromDiscount === null) {
            $sql = '
            select
            ID, ROW_ID, PRODUCT_ID, NAME, PRODUCT_TYPE_ID, PRICE, QUANTITY, WEIGHT, WIDTH, HEIGHT, LENGTH, EXTERNAL_ID
            from
            app_product_excluded_from_discount
            where FUSER_ID="' . $this->getFUserId() . '"
            ';
            $this->productsExcludedFromDiscount = Application::getConnection()->query($sql)->fetchAll();
        }
        return $this->productsExcludedFromDiscount;
    }*/

    /**
     * Метод возвращает товары корзины
     *
     * @param false $simple флаг указывающий делать простую выборку товаров или со всеми данными
     *
     * @return null
     * @throws SqlQueryException|ObjectException
     */
    private function getProducts(bool $simple = false)
    {
        if ($this->products === null) {

            // Производим выборку товаров со всеми данными
            if ($simple === false) {
                $reloadBasket = false;

                $basket = $this->getBasket();
                foreach ($basket as $product) {
                    $rowId = $product->getId();
                    $quantity = $product->getQuantity();
                    $price = $product->getPrice();
                    $basePrice = $product->getBasePrice();
                    $amount = $price * $quantity;
                    $product = CCatalogProduct::GetByIDEx($product->getProductId());
                    $discount = $price !== $basePrice ? 100 - (($price / $basePrice) * 100) : 0;

                    // Проверим, является ли товар подарком
                    $product['GIFT'] = 'N';
                    if (mb_strpos(strtolower($product['NAME']), 'подарок') !== false || $basePrice < 1) {
                        $product['GIFT'] = 'Y';
                    }

                    if ($product['GIFT'] === 'Y' && !$this->getAppliedCoupon()) {
                        CSaleBasket::Delete($rowId);
                        $reloadBasket = true;
                        break;
                    }

                    if ($product['DETAIL_PICTURE']) {
                        $product['DETAIL_PICTURE_SRC'] = CFile::ResizeImageGet($product['DETAIL_PICTURE'], ['width' => 100, 'height' => 100])['src'];
                    } else if ($product['PREVIEW_PICTURE']) {
                        $product['DETAIL_PICTURE_SRC'] = CFile::ResizeImageGet($product['PREVIEW_PICTURE'], ['width' => 100, 'height' => 100])['src'];
                    } // Торговое предложение
                    else if ($product['PRODUCT']['TYPE'] == ProductTable::TYPE_OFFER) {
                        if ($parent = \CCatalogSku::GetProductInfo($product['ID'])) {
                            $parent = ElementTable::getList([
                                'select' => ['ID', 'PREVIEW_PICTURE', 'DETAIL_PICTURE'],
                                'filter' => ['=ID' => $parent['ID']],
                                'limit' => 1,
                            ])->fetchRaw();
                            if ($parent['DETAIL_PICTURE']) {
                                $product['DETAIL_PICTURE_SRC'] = CFile::ResizeImageGet($parent['DETAIL_PICTURE'], ['width' => 100, 'height' => 100])['src'];
                            } else if ($parent['PREVIEW_PICTURE']) {
                                $product['DETAIL_PICTURE_SRC'] = CFile::ResizeImageGet($parent['PREVIEW_PICTURE'], ['width' => 100, 'height' => 100])['src'];
                            }
                        }
                    }

                    $this->products[$rowId] = [
                        'ID' => $product['ID'],
                        'NAME' => $product['NAME'],
                        'DETAIL_PAGE_URL' => $product['DETAIL_PAGE_URL'],
                        'DETAIL_PICTURE_SRC' => $product['DETAIL_PICTURE_SRC'],
                        'GIFT' => $product['GIFT'],
                        'PROPERTIES' => [
                            'CHANNEL_SALE' => $product['PROPERTIES']['CHANNEL_SALE'],
                        ],
                        'QUANTITY' => $quantity,
                        'PRICE' => [
                            'VALUE' => $price,
                            'WITHOUT_CURRENCY' => CCurrencyLang::CurrencyFormat($price, $this->getCurrency(), false),
                            'BASE' => [
                                'WITHOUT_CURRENCY' => CCurrencyLang::CurrencyFormat($basePrice, $this->getCurrency(), false),
                            ],
                            'DISCOUNT' => [
                                'PERCENT' => [
                                    'VALUE' => $discount,
                                    'FORMATTED' => $discount . '%',
                                ],
                            ],
                        ],
                        'AMOUNT' => [
                            'VALUE' => $amount,
                            'WITHOUT_CURRENCY' => CCurrencyLang::CurrencyFormat($amount, $this->getCurrency(), false),
                        ],
                        'TYPE' => $product['PRODUCT']['TYPE'],
                    ];
                }

                if ($reloadBasket) LocalRedirect($_SERVER['REQUEST_URI']);

                /*if ($this->getProductsExcludedFromDiscount()) {
                    foreach ($this->getProductsExcludedFromDiscount() as $product) {
                        $rowId = $product['ROW_ID'];
                        $quantity = $product['QUANTITY'];
                        $price = $product['PRICE'];
                        $basePrice = $product['PRICE'];
                        $amount = $price * $quantity;
                        $product = CCatalogProduct::GetByIDEx($product['PRODUCT_ID']);
                        $product['GIFT'] = 'N';
                        if ($product['DETAIL_PICTURE']) {
                            $product['DETAIL_PICTURE_SRC'] = CFile::ResizeImageGet($product['DETAIL_PICTURE'], ['width' => 100, 'height' => 100])['src'];
                        } elseif ($product['PREVIEW_PICTURE']) {
                            $product['DETAIL_PICTURE_SRC'] = CFile::ResizeImageGet($product['PREVIEW_PICTURE'], ['width' => 100, 'height' => 100])['src'];
                        } // Торговое предложение
                        elseif ($product['PRODUCT']['TYPE'] == ProductTable::TYPE_OFFER) {
                            if ($parent = \CCatalogSku::GetProductInfo($product['ID'])) {
                                $parent = ElementTable::getList([
                                    'select' => ['ID', 'PREVIEW_PICTURE', 'DETAIL_PICTURE'],
                                    'filter' => ['=ID' => $parent['ID']],
                                    'limit' => 1
                                ])->fetchRaw();
                                if ($parent['DETAIL_PICTURE']) {
                                    $product['DETAIL_PICTURE_SRC'] = CFile::ResizeImageGet($parent['DETAIL_PICTURE'], ['width' => 100, 'height' => 100])['src'];
                                } elseif ($parent['PREVIEW_PICTURE']) {
                                    $product['DETAIL_PICTURE_SRC'] = CFile::ResizeImageGet($parent['PREVIEW_PICTURE'], ['width' => 100, 'height' => 100])['src'];
                                }
                            }
                        }
                        $this->products[$rowId] = [
                            'ID' => $product['ID'],
                            'NAME' => $product['NAME'],
                            'DETAIL_PAGE_URL' => $product['DETAIL_PAGE_URL'],
                            'DETAIL_PICTURE_SRC' => $product['DETAIL_PICTURE_SRC'],
                            'GIFT' => $product['GIFT'],
                            'PROPERTIES' => [
                                'CHANNEL_SALE' => $product['PROPERTIES']['CHANNEL_SALE']
                            ],
                            'QUANTITY' => $quantity,
                            'PRICE' => [
                                'VALUE' => $price,
                                'WITHOUT_CURRENCY' => CCurrencyLang::CurrencyFormat($price, $this->getCurrency(), false),
                                'BASE' => [
                                    'WITHOUT_CURRENCY' => CCurrencyLang::CurrencyFormat($basePrice, $this->getCurrency(), false),
                                ],
                                'DISCOUNT' => [
                                    'PERCENT' => [
                                        'VALUE' => 0
                                    ]
                                ]
                            ],
                            'AMOUNT' => [
                                'VALUE' => $amount,
                                'WITHOUT_CURRENCY' => CCurrencyLang::CurrencyFormat($amount, $this->getCurrency(), false),
                            ],
                            'TYPE' => $product['PRODUCT']['TYPE'],
                        ];
                    }
                }*/

                ksort($this->products);
            } // Выборка товаров с упрощенным набором данных
            else {
                $basket = $this->getBasket();
                foreach ($basket as $product) {

                    $dimensions = $product->getField('DIMENSIONS');

                    // Некоторые товары почему-то хранят габариты в сериализованном виде
                    if (!isset($dimensions['WIDTH'])) {
                        $dimensions = unserialize($dimensions);
                    }

                    $this->products[$product->getProductId()] = [
                        'PRODUCT_ID' => $product->getProductId(),
                        'NAME' => $product->getField('NAME'),
                        'QUANTITY' => $product->getQuantity(),
                        'WEIGHT' => $product->getWeight(),
                        'LENGTH' => $dimensions['LENGTH'],
                        'WIDTH' => $dimensions['WIDTH'],
                        'HEIGHT' => $dimensions['HEIGHT'],
                        'VOLUME' => $dimensions['LENGTH'] * $dimensions['WIDTH'] * $dimensions['HEIGHT'],
                        'TYPE' => Application::getConnection()->query('select TYPE from b_catalog_product where ID="' . $product->getProductId() . '" limit 1')->fetchRaw()['TYPE'],
                    ];
                }

                /*if ($this->getProductsExcludedFromDiscount()) {
                    foreach ($this->getProductsExcludedFromDiscount() as $product) {
                        $this->products[$product['PRODUCT_ID']] = [
                            'PRODUCT_ID' => $product['PRODUCT_ID'],
                            'NAME' => $product['NAME'],
                            'QUANTITY' => $product['QUANTITY'],
                            'WEIGHT' => $product['WEIGHT'],
                            'LENGTH' => $product['LENGTH'],
                            'WIDTH' => $product['WIDTH'],
                            'HEIGHT' => $product['HEIGHT'],
                            'VOLUME' => $product['LENGTH'] * $product['WIDTH'] * $product['HEIGHT'],
                            'TYPE' => $product['PRODUCT_TYPE_ID'],
                        ];
                    }
                }*/
            }
        }
        return $this->products;
    }

    private function getProductType($id)
    {
        $iblockId = \CIBlockElement::GetIBlockByID($id);
        $type = \CIBlockElement::GetProperty($iblockId, $id, ['sort' => 'asc'], ['CODE' => 'CHANNEL_SALE'])->fetch()['VALUE_XML_ID'];
        return !empty($type) ? $type : \Native\App\Catalog\Product::TYPE_INTERNET;
    }

    private function getYandexProductList()
    {
        $counter = 1;
        $list = [];
        try {
            foreach ($this->getProducts() as $product) {
                $list[] = [
                    'id' => $product['ID'],
                    'name' => str_replace(['&quot;', '"'], '', $product['NAME']),
                    'price' => $product['PRICE']['VALUE'],
                    'quantity' => $product['QUANTITY'],
                    'position' => $counter,
                ];
                $counter++;
            }
        } catch (SqlQueryException $e) {
        }
        return $list;
    }

    // ====================================================================    Обработка данных по заказу

    /**
     * Метод для получения данных по последнему заказу покупателя
     *
     * @return array
     * @throws ArgumentException
     * @throws NotImplementedException
     */
    private function getLast()
    {
        global $USER;

        // Локальное хранилище
        $storage = $_COOKIE[$this->getStorageCode()];

        if ($storage) {
            $storage = json_decode($storage, true);
            if (!is_array($storage) || count($storage) === 0) {
                unset($storage);
            }
        }

        $data = [
            'ID' => null,
            'CUSTOMER' => [
                'TYPE' => $storage['customerType'],
                'NAME' => $storage['name'],
                'LAST_NAME' => $storage['lastName'],
                'SECOND_NAME' => $storage['secondName'],
                'EMAIL' => $storage['email'],
                //'CONFIRM_EMAIL' => $storage['confirmEmail'],
                'PHONE' => $storage['phone'],
                'COMPANY_NAME' => $storage['companyName'],
                'COMPANY_ADR' => $storage['companyAddress'],
                'INN' => $storage['inn'],
                'KPP' => $storage['kpp'],
            ],
            'AGREEMENT' => [
                'PROCESSING_DATA' => $storage['agreementProcessingPersonalData'],
                'SUBSCRIBE' => $storage['agreementSubscribe'] ?? true,
            ],
            'LOCATION' => [
                'FULL' => $storage['fullAddress'],
                'COUNTRY' => [
                    'CODE' => $storage['countryCode'],
                ],
                'ZIP' => $storage['zip'],
                'CITY' => [
                    'VALUE' => $storage['city'],
                ],
                'STREET' => $storage['street'],
                'BUILDING' => $storage['building'],
                'ROOM' => $storage['room'],
                //'LATITUDE' => $storage['latitude'],
                //'LONGITUDE' => $storage['longitude'],
            ],
            'PAYMENT' => [
                'CODE' => $storage['payment'],
            ],
            'DELIVERY' => [
                'CODE' => $storage['delivery'],
            ],
            'BOXBERRY' => [
                'POINT' => [
                    'ID' => $storage['boxberryPointId'],
                    'ADDRESS' => $storage['boxberryPointAddress'],
                ],
            ],
            'CDEK' => [
                'POINT' => [
                    'ID' => $storage['cdekPointId'],
                    'ADDRESS' => $storage['cdekPointAddress'],
                ],
            ],
        ];

        if ($USER->isAuthorized()) {
            $order = \Bitrix\Sale\Order::loadByFilter([
                'select' => ['ID'],
                'filter' => ['=USER_ID' => $USER->GetID()],
                'order' => ['ID' => 'DESC'],
                'limit' => 1,
            ]);
            if ($order) {
                $order = $order[0];
                $data['ID'] = $order->getField('ACCOUNT_NUMBER');
                $personTypeId = $order->getPersonTypeId();

                $propertyList = [];
                $propertyCollection = $order->getPropertyCollection()->getArray()['properties'];
                $allowed = [
                    'EMAIL' => true,
                    'PHONE' => true,
                    'FIRST_NAME' => true,
                    'NAME' => true,
                    'SECOND_NAME' => true,
                    'DELIVERY_ADDRESS' => true,
                    'COUNTRY_CODE' => true,
                    'ZIP' => true,
                    'CITY' => true,
                    'STREET' => true,
                    'HOUSE' => true,
                    'APARTMENT' => true,
                    'COMPANY_NAME' => true,
                    'COMPANY_ADR' => true,
                    'INN' => true,
                    'KPP' => true,
                    'BOXBERRY_POINT_ID' => true,
                    'BOXBERRY_POINT' => true,
                    'CDEK_POINT_ID' => true,
                    'CDEK_POINT' => true,
                ];
                foreach ($propertyCollection as $property) {
                    $code =& $property['CODE'];
                    $value =& $property['VALUE'][0];
                    if ($allowed[$code] && $property['PERSON_TYPE_ID'] == $personTypeId) {
                        $propertyList[$code] = $value;
                    }
                }
                $data['CUSTOMER'] = [
                    'TYPE' => $storage['customerType'] ? $storage['customerType'] : Person::getInstance()->getCodeById($order->getPersonTypeId()),
                    'NAME' => $storage['name'] ? $storage['name'] : $propertyList['NAME'],
                    'LAST_NAME' => $storage['lastName'] ? $storage['lastName'] : $propertyList['FIRST_NAME'],
                    'SECOND_NAME' => $storage['secondName'] ? $storage['secondName'] : $propertyList['SECOND_NAME'],
                    'EMAIL' => $storage['email'] ? $storage['email'] : $propertyList['EMAIL'],
                    'PHONE' => $storage['phone'] ? $storage['phone'] : $propertyList['PHONE'],
                    'COMPANY_NAME' => $storage['companyName'] ? $storage['companyName'] : $propertyList['COMPANY_NAME'],
                    'COMPANY_ADR' => $storage['companyAddress'] ? $storage['companyAddress'] : $propertyList['COMPANY_ADR'],
                    'INN' => $storage['inn'] ? $storage['inn'] : $propertyList['INN'],
                    'KPP' => $storage['kpp'] ? $storage['kpp'] : $propertyList['KPP'],
                ];

                $data['LOCATION'] = [
                    'FULL' =>
                        $storage['fullAddress'] ? $storage['fullAddress'] : implode(', ', [
                            DeliverySystem::getInstance()->getCountryNameByCode($propertyList['COUNTRY_CODE']),
                            $propertyList['CITY'],
                            $propertyList['STREET'],
                        ]),
                    'COUNTRY' => [
                        'CODE' => $storage['countryCode'] ? $storage['countryCode'] : $propertyList['COUNTRY_CODE'],
                    ],
                    'ZIP' => $storage['zip'] ? $storage['zip'] : $propertyList['ZIP'],
                    'CITY' => [
                        'VALUE' => $storage['city'] ? $storage['city'] : $propertyList['CITY'],
                    ],
                    'STREET' => $storage['street'] ? $storage['street'] : $propertyList['STREET'],
                    'BUILDING' => $storage['building'] ? $storage['building'] : $propertyList['HOUSE'],
                    'ROOM' => $storage['room'] ? $storage['room'] : $propertyList['APARTMENT'],
                    //'LATITUDE' => $storage['latitude'] ? $storage['latitude'] : $propertyList['LATITUDE'],
                    //'LONGITUDE' => $storage['longitude'] ? $storage['longitude'] : $propertyList['LONGITUDE'],
                ];

                $data['BOXBERRY'] = [
                    'POINT' => [
                        'ID' => $propertyList['BOXBERRY_POINT_ID'] ?: $storage['boxberryPointId'],
                        'ADDRESS' => $propertyList['BOXBERRY_POINT'] ?: $storage['boxberryPointAddress'],
                    ],
                ];

                $data['CDEK'] = [
                    'POINT' => [
                        'ID' => $propertyList['CDEK_POINT_ID'] ?: $storage['cdekPointId'],
                        'ADDRESS' => $propertyList['CDEK_POINT'] ?: $storage['cdekPointAddress'],
                    ],
                ];

                $data['AGREEMENT']['PROCESSING_DATA'] = true;

                $data['PAYMENT'] = [
                    'CODE' => $storage['payment'] ?: PaymentSystem::getInstance()->getCodeById($order->getField('PAY_SYSTEM_ID')),
                ];

                $data['DELIVERY'] = [
                    'CODE' => $storage['delivery'] ?: DeliverySystem::getInstance()->getCodeById($order->getField('DELIVERY_ID')),
                ];

            } else {
                if ($USER->GetFirstName()) {
                    $data['CUSTOMER']['NAME'] = $storage['name'] ?: $USER->GetFirstName();
                }
                if ($USER->GetLastName()) {
                    $data['CUSTOMER']['LAST_NAME'] = $storage['lastName'] ?: $USER->GetLastName();
                }
                if ($USER->GetSecondName()) {
                    $data['CUSTOMER']['SECOND_NAME'] = $storage['secondName'] ?: $USER->GetSecondName();
                }
                if ($USER->GetEmail()) {
                    $data['CUSTOMER']['EMAIL'] = $storage['email'] ?: $USER->GetEmail();
                }
            }
        }

        $data['CUSTOMER']['TYPE'] = $data['CUSTOMER']['TYPE'] ?: Person::PHYSICAL_CODE;

        // Добавим название города в нижнем регистре
        // Чтобы потом использовать его при проверке условий
        $data['LOCATION']['CITY']['LOWER'] = mb_strtolower($data['LOCATION']['CITY']['VALUE']);

        // Обработаем условия
        if (
            empty($data['LOCATION']['FULL']) ||
            empty($data['LOCATION']['COUNTRY']['CODE']) ||
            empty($data['LOCATION']['ZIP']) ||
            empty($data['LOCATION']['CITY']) ||
            empty($data['LOCATION']['STREET'])
        ) {
            $data['LOCATION'] = [
                'FULL' => '',
                'COUNTRY' => '',
                'ZIP' => '',
                'CITY' => '',
                'STREET' => '',
                'BUILDING' => '',
                'ROOM' => '',
                //'LATITUDE' => '',
                //'LONGITUDE' => '',
            ];
            $data['DELIVERY']['CODE'] = '';
            $data['BOXBERRY'] = '';
            $data['CDEK'] = '';

            $storage['delivery'] = '';
            $storage['boxberryPointId'] = '';
            $storage['boxberryPointAddress'] = '';
            $storage['cdekPointId'] = '';
            $storage['cdekPointAddress'] = '';
        }

        if ($data['LOCATION']['CITY']['LOWER']) {
            $data['LOCATION']['CITY']['LOWER'] = trim(str_replace(['г.', 'г '], '', $data['LOCATION']['CITY']['LOWER']));
        }

        // Проверим условия использования служб доставки
        if ($data['DELIVERY']['CODE']) {

            $restriction = $this->arParams['DELIVERY'][$data['DELIVERY']['CODE']]['restriction'];

            if (
                // Если служба доставки отсутствует в доступном списке служб
                // Но, ранее была выбрана - отключим ее
                !isset($this->arParams['DELIVERY'][$data['DELIVERY']['CODE']])
                ||
                // Если у службы установлены какие-то ограничения
                (
                    isset($restriction['country']) &&
                    (
                        !isset($restriction['country'][$data['LOCATION']['COUNTRY']['CODE']]['access']) ||
                        $restriction['country'][$data['LOCATION']['COUNTRY']['CODE']]['access'] !== true
                    )
                )
                // Если имеется ограничение по городу
                ||
                (
                    isset($restriction['city-deny'][$data['LOCATION']['CITY']['LOWER']]) &&
                    $restriction['city-deny'][$data['LOCATION']['CITY']['LOWER']] === true
                )
                ||
                (
                    isset($restriction['city'][$data['LOCATION']['CITY']['LOWER']]) &&
                    $restriction['city'][$data['LOCATION']['CITY']['LOWER']] !== true
                )
                // Если при последнем заказе была использована служба с Бесплатной доставкой
                // А сейчас она не может быть использована
                // Тогда удалим службу доставки из данных по последнему заказу
                ||
                (
                    $this->canUseFreeDelivery() === false && $data['DELIVERY']['CODE'] === RussianPost::CLASSIC_FREE
                )
            ) {
                $data['DELIVERY']['CODE'] = false;
                $storage['delivery'] = '';
            }

            // Если при последнем заказе была использована платная служба доставки
            // А сейчас покупателю доступна Бесплатная доставка
            // Тогда установим Бесплатную службу доставки в качестве основной
            if ($this->canUseFreeDelivery() === true && $data['DELIVERY']['CODE'] === RussianPost::CLASSIC) {
                $data['DELIVERY']['CODE'] = RussianPost::CLASSIC_FREE;
                $storage['delivery'] = RussianPost::CLASSIC_FREE;
            }
        }

        // Проверим условия использования способов оплаты
        if ($data['PAYMENT']['CODE']) {

            $restriction = $this->arParams['PAYMENT'][$data['PAYMENT']['CODE']]['restriction'];

            if (
                // Если служба доставки отсутствует в доступном списке служб
                // Но, ранее была выбрана - отключим ее
                !isset($this->arParams['PAYMENT'][$data['PAYMENT']['CODE']])
                ||
                // Если у службы установлены какие-то ограничения
                (
                    isset($restriction['country']) &&
                    (
                        !isset($restriction['country'][$data['LOCATION']['COUNTRY']['CODE']]['access']) ||
                        $restriction['country'][$data['LOCATION']['COUNTRY']['CODE']]['access'] !== true
                    )
                )
                // Если имеется ограничение по городу
                ||
                isset($restriction['city-deny'][$data['LOCATION']['CITY']['LOWER']]) &&
                $restriction['city-deny'][$data['LOCATION']['CITY']['LOWER']] === true
                ||
                (
                    isset($restriction['city'][$data['LOCATION']['CITY']['LOWER']]) &&
                    $restriction['city'][$data['LOCATION']['CITY']['LOWER']] !== true
                )
                ||
                // Если имеется ограничение по службе доставки
                (
                    !empty($data['DELIVERY']['CODE'])
                    &&
                    isset($item['restriction']['delivery'])
                    &&
                    !in_array($data['DELIVERY']['CODE'], $restriction['delivery'])
                )
            ) {
                $data['PAYMENT']['CODE'] = false;
                $storage['payment'] = '';
            }
        }

        $data['CUSTOMER']['NAME'] = urldecode($data['CUSTOMER']['NAME']);
        $data['CUSTOMER']['LAST_NAME'] = urldecode($data['CUSTOMER']['LAST_NAME']);
        $data['CUSTOMER']['SECOND_NAME'] = urldecode($data['CUSTOMER']['SECOND_NAME']);
        $data['CUSTOMER']['COMPANY_NAME'] = urldecode($data['CUSTOMER']['COMPANY_NAME']);
        $data['CUSTOMER']['COMPANY_ADR'] = urldecode($data['CUSTOMER']['COMPANY_ADR']);
        $data['LOCATION']['FULL'] = urldecode($data['LOCATION']['FULL']);
        $data['LOCATION']['CITY']['VALUE'] = urldecode($data['LOCATION']['CITY']['VALUE']);
        $data['LOCATION']['CITY']['LOWER'] = urldecode($data['LOCATION']['CITY']['LOWER']);
        $data['LOCATION']['STREET'] = urldecode($data['LOCATION']['STREET']);
        $data['LOCATION']['BUILDING'] = urldecode($data['LOCATION']['BUILDING']);
        $data['LOCATION']['ROOM'] = urldecode($data['LOCATION']['ROOM']);

        if (isset($data['BOXBERRY']['POINT']['ADDRESS'])) {
            $data['BOXBERRY']['POINT']['ADDRESS'] = urldecode($data['BOXBERRY']['POINT']['ADDRESS']);
        }

        if (isset($data['CDEK']['POINT']['ADDRESS'])) {
            $data['CDEK']['POINT']['ADDRESS'] = urldecode($data['CDEK']['POINT']['ADDRESS']);
        }

        // Фиксим проблему незаполненных данных в локальной памяти
        if (!$storage['customerType']) {
            $storage['customerType'] = $data['CUSTOMER']['TYPE'];
        }
        if (!$storage['name']) {
            $storage['name'] = $data['CUSTOMER']['NAME'];
        }
        if (!$storage['lastName']) {
            $storage['lastName'] = $data['CUSTOMER']['LAST_NAME'];
        }
        if (!$storage['secondName']) {
            $storage['secondName'] = $data['CUSTOMER']['SECOND_NAME'];
        }
        if (!$storage['email']) {
            $storage['email'] = $data['CUSTOMER']['EMAIL'];
        }
        /*if (!$storage['confirmEmail']) {
            $storage['confirmEmail'] = $data['CUSTOMER']['EMAIL'];
        }*/
        if (!$storage['phone']) {
            $storage['phone'] = $data['CUSTOMER']['PHONE'];
        }
        if (!$storage['companyName']) {
            $storage['companyName'] = $data['CUSTOMER']['COMPANY_NAME'];
        }
        if (!$storage['companyAddress']) {
            $storage['companyAddress'] = $data['CUSTOMER']['COMPANY_ADR'];
        }
        if (!$storage['inn']) {
            $storage['inn'] = $data['CUSTOMER']['INN'];
        }
        if (!$storage['kpp']) {
            $storage['kpp'] = $data['CUSTOMER']['KPP'];
        }
        if (!$storage['agreementProcessingPersonalData']) {
            $storage['agreementProcessingPersonalData'] = $data['AGREEMENT']['PROCESSING_DATA'];
        }
        if (!$storage['fullAddress']) {
            $storage['fullAddress'] = $data['LOCATION']['FULL'];
        }
        if (!$storage['countryCode']) {
            $storage['countryCode'] = $data['LOCATION']['COUNTRY']['CODE'];
        }
        if (!$storage['zip']) {
            $storage['zip'] = $data['LOCATION']['ZIP'];
        }
        if (!$storage['city']) {
            $storage['city'] = $data['LOCATION']['CITY']['VALUE'];
        }
        if (!$storage['street']) {
            $storage['street'] = $data['LOCATION']['STREET'];
        }
        if (!$storage['building']) {
            $storage['building'] = $data['LOCATION']['BUILDING'];
        }
        if (!$storage['room']) {
            $storage['room'] = $data['LOCATION']['ROOM'];
        }
        if (!$storage['boxberryPointId']) {
            $storage['boxberryPointId'] = $data['BOXBERRY']['POINT']['ID'];
        }
        if (!$storage['boxberryPointAddress']) {
            $storage['boxberryPointAddress'] = $data['BOXBERRY']['POINT']['ADDRESS'];
        }
        if (!$storage['cdekPointId']) {
            $storage['cdekPointId'] = $data['CDEK']['POINT']['ID'];
        }
        if (!$storage['cdekPointAddress']) {
            $storage['cdekPointAddress'] = $data['CDEK']['POINT']['ADDRESS'];
        }
        if (!$storage['delivery'] && $data['DELIVERY']['CODE'] !== false) {
            $storage['delivery'] = $data['DELIVERY']['CODE'];
        }
        if (!$storage['payment'] && $data['PAYMENT']['CODE'] !== false) {
            $storage['payment'] = $data['PAYMENT']['CODE'];
        }

        // Если текущая локация выбрана Москва
        // Но город из локального хранилища или из последнего заказа
        // Не совпадают
        // Тогда отменяем старый город и страну - заменяем их на Москву и Россию
        if (
            Location::getCurrentCityCode() === Location::MSK &&
            (
                $data['LOCATION']['CITY']['LOWER'] !== Location::MOSCOW_CITY_TITLE_LOWER
                ||
                mb_strtolower($storage['city']) !== Location::MOSCOW_CITY_TITLE_LOWER
            )
        ) {
            $id = $data['ID'];
            $customer = $data['CUSTOMER'];
            $agreement = $data['AGREEMENT'];
            $oldStorage = $storage;
            $data = [];
            $storage = [];
            $data['ID'] = $id;
            $data['CUSTOMER'] = $customer;
            $data['AGREEMENT'] = $agreement;
            $data['LOCATION']['FULL'] = DeliverySystem::getInstance()->getCountryNameByCode('RU') . ', ' . Location::MOSCOW_CITY_TITLE_NORMAL;
            $data['LOCATION']['COUNTRY']['CODE'] = 'RU';
            $data['LOCATION']['CITY'] = [
                'VALUE' => Location::MOSCOW_CITY_TITLE_NORMAL,
                'LOWER' => Location::MOSCOW_CITY_TITLE_LOWER,
            ];
            $storage['fullAddress'] = $data['LOCATION']['FULL'];
            $storage['countryCode'] = 'RU';
            $storage['city'] = Location::MOSCOW_CITY_TITLE_NORMAL;
            $storage['customerType'] = $oldStorage['customerType'];
            $storage['name'] = $oldStorage['name'];
            $storage['lastName'] = $oldStorage['lastName'];
            $storage['secondName'] = $oldStorage['secondName'];
            $storage['email'] = $oldStorage['email'];
            $storage['phone'] = $oldStorage['phone'];
            $storage['companyName'] = $oldStorage['companyName'];
            $storage['companyAddress'] = $oldStorage['companyAddress'];
            $storage['inn'] = $oldStorage['inn'];
            $storage['kpp'] = $oldStorage['kpp'];
            $storage['agreementProcessingPersonalData'] = $oldStorage['agreementProcessingPersonalData'];
            $storage['currentStep'] = $oldStorage['currentStep'];

            if (
                $storage['name'] &&
                $storage['lastName'] &&
                $storage['secondName'] &&
                $storage['agreementProcessingPersonalData'] &&
                $storage['currentStep'] !== 'delivery'
            ) {
                $storage['currentStep'] = 'delivery';
            }
        }

        if (is_array($storage)) {
            $storage = json_encode($storage, JSON_UNESCAPED_UNICODE);
            setcookie($this->getStorageCode(), $storage, time() + 8640000, '/');
        }

        return $data;
    }

    private function getFUserId()
    {
        if ($this->fUserId === null) {
            $this->fUserId = Fuser::getId();
        }
        return $this->fUserId;
    }

    private function canUseFreeDelivery($arParams = [])
    {
        if ($this->canUseFreeDelivery === null) {
            $this->canUseFreeDelivery = false;
            if (
                $arParams['ORDER']['TYPE'] &&
                (
                    $arParams['ORDER']['TYPE'] === Order::TYPE_RETAIL ||
                    $arParams['ORDER']['TYPE'] === Order::TYPE_COMBINE
                )) {
                return $this->canUseFreeDelivery;
            }
            if (!$this->hasMaxDiscount() && $this->getPrice() >= DeliverySystem::getInstance()->getAmountForFree()) {
                $this->canUseFreeDelivery = true;
            }
        }
        return $this->canUseFreeDelivery;
    }

    private function hasMaxDiscount()
    {
        if ($this->hasMaxDiscount === null) {
            $this->hasMaxDiscount = isset($this->getDiscountList()[Discount::getInstance()->getIdByPercent(20)]);
        }
        return $this->hasMaxDiscount;
    }

    private function getDiscountList()
    {
        return $this->getOrder()->getDiscount()->getApplyResult()['FULL_DISCOUNT_LIST'];
    }

    // ====================================================================    Обработка Ajax-запросов

    /**
     * Метод для получения стоимости доставки
     *
     * @param $request
     *
     * @return array
     */
    public function calculateDeliveryPriceAction($request)
    {
        $response = [];
        if (!isset($request['deliveries']) || empty($request['deliveries'])) {
            return [
                'error' => true,
                'message' => 'Отсутствуют службы доставки',
            ];
        }
        $deliveries = $request['deliveries'];
        unset($request['deliveries']);
        foreach ($deliveries as $delivery) {
            if (!isset($delivery['provider']) || !isset($delivery['method']['getDeliveryPrice'])) continue;
            $provider = $delivery['provider'] . 'Action';
            $request['method'] = $delivery['method']['getDeliveryPrice'];
            $request['delivery'] = $delivery['CODE'];
            $response[$delivery['CODE']] = $this->$provider($request);
        }
        return $response;
    }

    /**
     * Метод-провайдер для выполнения запросов к Delivery System
     * Для обработки стандартных служб доставки
     *
     * @param $request
     *
     * @return array
     */
    public function deliverySystemProviderAction($request)
    {
        if (!isset($request['method'])) return [];

        // Удалим название метода из массива
        $method = $request['method'];
        unset($request['method']);

        $instance = DeliverySystem::getInstance();
        return $instance->$method($request);
    }

    /**
     * Метод-провайдер для выполнения запросов к Boxberry
     *
     * @param $request
     *
     * @return array
     */
    public function boxberryProviderAction($request)
    {
        if (!isset($request['method'])) return [];

        // Удалим название метода из массива
        $method = $request['method'];
        unset($request['method']);

        // Добавим в массив запроса общий дополнительные данные
        $request['basket'] = [
            'amount' => $this->getPrice(),
            'weight' => $this->getWeight(),
            'volume' => $this->getVolume(),
        ];

        $instance = Boxberry::getInstance();
        return $instance->$method($request);
    }

    /**
     * Метод-провайдер для выполнения запросов к Почте России
     *
     * @param $request
     *
     * @return array
     * @throws SqlQueryException
     */
    public function russianPostProviderAction($request)
    {
        if (!isset($request['method'])) return [];
        // Удалим название метода из массива
        $method = $request['method'];
        unset($request['method']);
        // Добавим в массив запроса общий дополнительные данные
        $request['date'] = date('Ymd');
        $request['basket'] = [
            'products' => $this->getProducts(true),
            'weight' => $this->getWeight(),
        ];
        $instance = RussianPost::getInstance();
        $response = $instance->$method($request);
        $response['basket'] = $request['basket'];
        return $response;
    }

    /**
     * Метод-провайдер для выполнения запросов к CDEK
     *
     * @param $request
     *
     * @return array
     */
    public function cdekProviderAction($request)
    {
        if (!isset($request['method'])) return [];

        // Удалим название метода из массива
        $method = $request['method'];
        unset($request['method']);

        // Добавим в массив запроса общий дополнительные данные
        $request['basket'] = [
            'amount' => $this->getPrice(),
            'weight' => $this->getWeight(),
            'volume' => $this->getVolume(),
        ];

        $instance = Cdek::getInstance();
        return $instance->$method($request);
    }

    /**
     * Метод создания заказа
     *
     * @param $request
     *
     * @return array|string[]
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws NotImplementedException
     * @throws SystemException
     * @throws ArgumentTypeException
     * @throws SqlQueryException
     * @throws LoaderException
     * @throws NotSupportedException
     * @throws Exception
     */
    public function createOrderAction($request): array
    {
        if (!isset($request['agreement']['processingPersonalData']) || $request['agreement']['processingPersonalData'] !== 'true') {
            return [
                'error' => true,
                'message' => 'Не выдано согласие на обработку персональных данных',
            ];
        }
        if (empty($request['contacts']['email'])) {
            return [
                'error' => true,
                'message' => 'Не указан E-mail',
            ];
        }
        if (!filter_var($request['contacts']['email'], FILTER_VALIDATE_EMAIL)) {
            return [
                'error' => true,
                'message' => 'E-mail некорректный',
            ];
        }
        if (
            !$this->canUseFreeDelivery()
            && $request['delivery']['price'] <= 0
            && $request['delivery']['code'] !== DeliverySystem::WITHOUT_DELIVERY
            && $request['delivery']['code'] !== DeliverySystem::PICKUP_MSK_NOVOSLOBODSKAYA
            && $request['delivery']['code'] !== DeliverySystem::COURIER_MSK_INSIDE_MKAD_FREE
            && $request['delivery']['code'] !== DeliverySystem::COURIER_MSK_OUTSIDE_MKAD
            && $request['delivery']['code'] !== DeliverySystem::PICKUP_NSK
            && $request['delivery']['code'] !== DeliverySystem::COURIER_NSK
            && $request['delivery']['code'] !== DeliverySystem::COURIER_NSK_FREE
            && $request['delivery']['code'] !== DeliverySystem::COURIER_BERDSK
            && $request['delivery']['code'] !== DeliverySystem::COURIER_BERDSK_FREE
        ) {
            return [
                'error' => true,
                'message' => 'Некорректная стоимость доставки',
            ];
        }

        // Проверить текущего пользователя
        // Если не авторизован, тогда найдем в базе
        // Если имеется, создадим заказ на него, плюс авторизуем его
        // Если пользователя нет, тогда создадим, оформим заказ на него, и авторизуем
        $user = $this->getUserId($request);
        if (isset($user['error'])) {
            return [
                'error' => true,
                'message' => $user['message'],
            ];
        }
        global $USER;
        if (!$USER->IsAuthorized()) {
            $USER->Authorize($user['id'], true);
        }

        Loader::IncludeModule('sale');
        Loader::IncludeModule('catalog');

        $type = $request['order']['type']; // тип заказа: retail | internet | combine | moscow
        Bitrix\Sale\Compatible\DiscountCompatibility::stopUsageCompatible();
        $order = \Bitrix\Sale\Order::create(SITE_ID, $user['id']);
        $basket = Basket::loadItemsForFUser($this->getFUserId(), SITE_ID);
        $personTypeId = Person::getInstance()->getIdByCode($request['customer']['type']);
        //$excluded = $this->getProductsExcludedFromDiscount(); // товары исключенные из скидок корзины
        $order->setField('CURRENCY', $this->getCurrency());
        $order->setPersonTypeId($personTypeId);
        $order->setBasket($basket);
        // Корзина
        if ($basket->count() === 0 /*&& empty($excluded)*/) {
            return [
                'error' => true,
                'message' => 'Не удалось обработать товары',
            ];
        }
        // Книга в подарок
        // Должно работать только до 8 августа 2021
        /*if (strtolower($this->getAppliedCoupon()) === 'ribakova' && $this->getPrice() >= 1500) {
            $giftId = 8315;
            $gift = CCatalogProduct::GetByIDEx($giftId);
            $basketItem = $basket->createItem('catalog', $giftId);
            $basketItem->setFields([
                'NAME' => Helper::getInstance()->clearQuotes($gift['NAME']),
                'QUANTITY' => 1,
                'CURRENCY' => $this->getCurrency(),
                'LID' => SITE_ID,
                'PRODUCT_PROVIDER_CLASS' => '\Bitrix\Catalog\Product\CatalogProvider',
                'PRODUCT_XML_ID' => $gift['EXTERNAL_ID'],
                'CUSTOM_PRICE' => 'Y',
                'PRICE' => $gift['PRICE'],
                'BASE_PRICE' => $gift['BASE_PRICE'],
                'WEIGHT' => $gift['WEIGHT'],
            ]);
        }*/
        // Батончик в подарок
        // Товары которые должны быть в корзине
        /*$totalQuantityBars = 0;
        $productsCondition = [
            8189 => 8189,
            8190 => 8190, // если товар в условии и как подарочный - после обмена с crm, количество у подарка замещается
            8191 => 8191,
            8192 => 8192,
        ];
        foreach ($basket as $item) {
            if (isset($productsCondition[$item->getProductId()])) {
                $totalQuantityBars += $item->getQuantity();
            }
        }
        if ($totalQuantityBars > 0) {
            // За каждый 3 батончик выдаем 1 подарочный
            $totalQuantityBars = intdiv($totalQuantityBars, 3);
            if ($totalQuantityBars > 0) {
                $giftId = 8190; // если товар в условии и как подарочный - после обмена с crm, количество у подарка замещается
                $gift = CCatalogProduct::GetByIDEx($giftId);
                $basketItem = $basket->createItem('catalog', $giftId);
                $basketItem->setFields([
                    'NAME'                   => Helper::getInstance()->clearQuotes($gift['NAME']),
                    'DETAIL_PAGE_URL'        => $gift['DETAIL_PAGE_URL'],
                    'QUANTITY'               => $totalQuantityBars,
                    'CURRENCY'               => $this->getCurrency(),
                    'LID'                    => SITE_ID,
                    'PRODUCT_PROVIDER_CLASS' => '\Bitrix\Catalog\Product\CatalogProvider',
                    'PRODUCT_XML_ID'         => $gift['EXTERNAL_ID'],
                    'CUSTOM_PRICE'           => 'Y',
                    'PRICE'                  => 0,
                    'BASE_PRICE'             => 0,
                    'WEIGHT'                 => $gift['PRODUCT']['WIDTH'],
                    'DIMENSIONS'             => serialize([
                        'WIDTH'  => $gift['PRODUCT']['WIDTH'],
                        'HEIGHT' => $gift['PRODUCT']['HEIGHT'],
                        'LENGTH' => $gift['PRODUCT']['LENGTH'],
                    ]),
                ]);
            }
        }*/
        // Кофе в подарок
        // Товары которые должны быть в корзине
        if (time() > strtotime('20.01.2022') && time() < strtotime('27.01.2022')) {
            $totalQuantityProducts = 0;
            $productsCondition = [
                110549 => true,
                110550 => true,
            ];
            foreach ($basket as $item) {
                if (isset($productsCondition[$item->getProductId()])) {
                    $totalQuantityProducts += $item->getQuantity();
                }
            }
            if ($totalQuantityProducts > 0) {
                $totalQuantityProducts = intdiv($totalQuantityProducts, 3); // за каждые 3 шт. выдаем 1 шт. подарочный
                if ($totalQuantityProducts > 0) {
                    $giftId = 109961; // если товар в условии и как подарочный - после обмена с crm, количество у подарка замещается
                    $gift = CCatalogProduct::GetByIDEx($giftId);
                    $basketItem = $basket->createItem('catalog', $giftId);
                    $basketItem->setFields([
                        'QUANTITY' => $totalQuantityProducts,
                        'PRICE' => 0,
                        'BASE_PRICE' => 0,
                        'NAME' => Helper::getInstance()->clearQuotes($gift['NAME']),
                        'DETAIL_PAGE_URL' => $gift['DETAIL_PAGE_URL'],
                        'CURRENCY' => $this->getCurrency(),
                        'LID' => SITE_ID,
                        'PRODUCT_PROVIDER_CLASS' => '\Bitrix\Catalog\Product\CatalogProvider',
                        'PRODUCT_XML_ID' => $gift['EXTERNAL_ID'],
                        'CUSTOM_PRICE' => 'Y',
                        'WEIGHT' => $gift['PRODUCT']['WIDTH'],
                        'DIMENSIONS' => serialize([
                            'WIDTH' => $gift['PRODUCT']['WIDTH'],
                            'HEIGHT' => $gift['PRODUCT']['HEIGHT'],
                            'LENGTH' => $gift['PRODUCT']['LENGTH'],
                        ]),
                    ]);
                }
            }
            /*foreach ($basket as $item) {
                if (isset($productsCondition[$item->getProductId()])) {
                    $totalQuantityProducts += $item->getQuantity();
                }
                if ($totalQuantityProducts === 3) {
                    break;
                }
            }
            if ($totalQuantityProducts >= 3) {
                $giftId = 109961; // если товар в условии и как подарочный - после обмена с crm, количество у подарка замещается
                $gift = CCatalogProduct::GetByIDEx($giftId);
                $basketItem = $basket->createItem('catalog', $giftId);
                $basketItem->setFields([
                    'NAME' => Helper::getInstance()->clearQuotes($gift['NAME']),
                    'DETAIL_PAGE_URL' => $gift['DETAIL_PAGE_URL'],
                    'QUANTITY' => 1,
                    'CURRENCY' => $this->getCurrency(),
                    'LID' => SITE_ID,
                    'PRODUCT_PROVIDER_CLASS' => '\Bitrix\Catalog\Product\CatalogProvider',
                    'PRODUCT_XML_ID' => $gift['EXTERNAL_ID'],
                    'CUSTOM_PRICE' => 'Y',
                    'PRICE' => 0,
                    'BASE_PRICE' => 0,
                    'WEIGHT' => $gift['PRODUCT']['WIDTH'],
                    'DIMENSIONS' => serialize([
                        'WIDTH' => $gift['PRODUCT']['WIDTH'],
                        'HEIGHT' => $gift['PRODUCT']['HEIGHT'],
                        'LENGTH' => $gift['PRODUCT']['LENGTH'],
                    ]),
                ]);
            }*/
        }
        // Комментарий
        // Если заказ не для розничного магазина, тогда оставим комментарий менеджеру
        if ($type !== Order::TYPE_RETAIL) {
            $comment = $this->getCommentForOrder($request, $basket->getPrice());
            if ($comment['manager']) {
                $order->setField('COMMENTS', $comment['manager']);
            }
            if ($comment['customer']) {
                $order->setField('USER_DESCRIPTION', $comment['customer']);
            }
        }
        // Оплата
        $payment = $order->getPaymentCollection()->createItem(Bitrix\Sale\PaySystem\Manager::getObjectById($request['payment']['id']));
        $payment->setField('CURRENCY', $order->getCurrency());
        $payment->setField('SUM', $basket->getPrice() + $request['delivery']['price']);
        // Доставка
        $shipment = $order->getShipmentCollection()->createItem(Bitrix\Sale\Delivery\Services\Manager::getObjectById($request['delivery']['id']));
        $shipmentItemCollection = $shipment->getShipmentItemCollection();
        foreach ($basket as $item) {
            $shipmentItemCollection->createItem($item)->setQuantity($item->getQuantity());
        }
        $shipment->setField('CUSTOM_PRICE_DELIVERY', 'Y');
        $shipment->setField('BASE_PRICE_DELIVERY', $request['delivery']['price']);
        $shipment->setField('PRICE_DELIVERY', $request['delivery']['price']);
        // Свойства
        $propertyCollection = $order->getPropertyCollection();
        foreach ($propertyCollection as $property) {
            $ar = $property->getProperty();
            switch ($ar['CODE']) {
                case 'PAYER':
                    if ($request['payment']['code'] === PaymentSystem::BILL_CODE) {
                        if ($request['customer']['type'] === Person::PHYSICAL_CODE) {
                            $payer = [
                                $request['customer']['lastName'],
                                $request['customer']['name'],
                                $request['customer']['secondName'],
                            ];
                            $payer = implode(' ', $payer);
                            $payer .= ', ' . $request['contacts']['phone'];

                        } else {
                            $payer = [
                                $request['customer']['companyName'],
                                $request['customer']['inn'],
                                $request['customer']['kpp'],
                                $request['customer']['companyAddress'],
                            ];
                            $payer = implode(', ', $payer);
                        }
                        $property->setField('VALUE', $payer);
                    }
                    break;
                case 'COMPANY_NAME':
                    if ($request['customer']['companyName'] && $request['customer']['companyName'] !== 'false') {
                        $property->setField('VALUE', $request['customer']['companyName']);
                    }
                    break;
                case 'COMPANY_ADR':
                    if ($request['customer']['companyAddress'] && $request['customer']['companyAddress'] !== 'false') {
                        $property->setField('VALUE', $request['customer']['companyAddress']);
                    }
                    break;
                case 'INN':
                    if ($request['customer']['inn'] && $request['customer']['inn'] !== 'false') {
                        $property->setField('VALUE', $request['customer']['inn']);
                    }
                    break;
                case 'KPP':
                    if ($request['customer']['kpp'] && $request['customer']['kpp'] !== 'false') {
                        $property->setField('VALUE', $request['customer']['kpp']);
                    }
                    break;
                case 'FIO':
                    $property->setField('VALUE', trim($request['customer']['lastName'] . ' ' . $request['customer']['name'] . ' ' . $request['customer']['secondName']));
                    break;
                case 'FIRST_NAME':
                    $property->setField('VALUE', $request['customer']['lastName']);
                    break;
                case 'NAME':
                    $property->setField('VALUE', $request['customer']['name']);
                    break;
                case 'SECOND_NAME':
                    $property->setField('VALUE', $request['customer']['secondName']);
                    break;
                case 'EMAIL':
                    $property->setField('VALUE', $request['contacts']['email']);
                    break;
                case 'PHONE':
                    $property->setField('VALUE', $request['contacts']['phone']);
                    break;
                case 'COUNTRY_CODE':
                    $property->setField('VALUE', $request['location']['country']['code']);
                    break;
                case 'COUNTRY_NAME':
                    $property->setField('VALUE', DeliverySystem::getInstance()->getCountryNameByCode($request['location']['country']['code']));
                    break;
                case 'ZIP':
                    $property->setField('VALUE', $request['location']['zip']);
                    break;
                case 'CITY':
                    $property->setField('VALUE', $request['location']['city']);
                    break;
                case 'STREET':
                    $property->setField('VALUE', $request['location']['street']);
                    break;
                case 'HOUSE':
                    $property->setField('VALUE', $request['location']['building']);
                    break;
                case 'APARTMENT':
                    $property->setField('VALUE', $request['location']['room']);
                    break;
                case 'DELIVERY_ADDRESS':
                    $deliveryAddress = [];
                    if ($request['location']['zip']) $deliveryAddress[] = $request['location']['zip'];
                    if ($request['location']['city']) $deliveryAddress[] = $request['location']['city'];
                    if ($request['location']['street']) $deliveryAddress[] = $request['location']['street'];
                    if ($request['location']['building']) $deliveryAddress[] = $request['location']['building'];
                    if ($request['location']['room']) $deliveryAddress[] = $request['location']['room'];
                    $deliveryAddress = implode(', ', $deliveryAddress);
                    $property->setField('VALUE', trim($deliveryAddress));
                    break;
                case 'DELIVERY_PERIOD':
                    $property->setField('VALUE', trim($request['delivery']['period']));
                    break;
                case 'BOXBERRY_POINT_ID':
                    if (
                        (
                            $request['delivery']['code'] === Boxberry::POINT ||
                            $request['delivery']['code'] === Boxberry::POINT_FREE
                        ) &&
                        $request['boxberry']['point']['id'] && $request['boxberry']['point']['id'] !== 'false') {
                        $property->setField('VALUE', $request['boxberry']['point']['id']);
                    }
                    break;
                case 'BOXBERRY_POINT':
                    if (
                        (
                            $request['delivery']['code'] === Boxberry::POINT ||
                            $request['delivery']['code'] === Boxberry::POINT_FREE
                        ) &&
                        $request['boxberry']['point']['address'] && $request['boxberry']['point']['address'] !== 'false') {
                        $property->setField('VALUE', $request['boxberry']['point']['address']);
                    }
                    break;
                case 'CDEK_POINT_ID':
                    if ($request['delivery']['code'] === 'cdek-store-to-store' && $request['cdek']['point']['id'] && $request['cdek']['point']['id'] !== 'false') {
                        $property->setField('VALUE', $request['cdek']['point']['id']);
                    }
                    break;
                case 'CDEK_POINT':
                    if ($request['delivery']['code'] === 'cdek-store-to-store' && $request['cdek']['point']['address'] && $request['cdek']['point']['address'] !== 'false') {
                        $property->setField('VALUE', $request['cdek']['point']['address']);
                    }
                    break;
                case 'SYS_ORDER_TYPE':
                    $property->setField('VALUE', $type);
                    break;
                default:
                    break;
            }
        }
        // Сохраняем заказ
        $r = $order->save();
        if (!$r->isSuccess()) {
            return [
                'error' => true,
                'message' => 'Не удалось оформить заказ',
            ];
        }
        // Обновим номер заказа
        $accountNumber = $this->updateOrderAccountNumber($order, $type, $request['location']['city']);
        // Обновим заказ с учетом товаров исключенных из скидок
        //$this->updateOrderTakingProductsExcludedFromDiscount($order,$payment);
        // Отправим письмо пользователю
        $fields = [
            'MESSAGE_ID' => false,
            'ORDER_ID' => $accountNumber,
            'ORDER_DATE' => date('d.m.Y H:i'),
            'USER_NAME' => $request['customer']['name'],
            'USER_EMAIL' => $request['contacts']['email'],
            'DELIVERY_PERIOD' => date('d.m.Y', strtotime('+7 days')) . '-' . date('d.m.Y', strtotime('+10 days')),
            'DELIVERY_PRICE' => CCurrencyLang::CurrencyFormat($order->getField('PRICE_DELIVERY'), $order->getCurrency()),
            'PRODUCTS_PRICE' => CCurrencyLang::CurrencyFormat($order->getPrice() - $order->getField('PRICE_DELIVERY'), $order->getCurrency()),
            'ORDER_PRICE' => CCurrencyLang::CurrencyFormat($order->getPrice(), $order->getCurrency()),
        ];
        switch ($request['payment']['code']) {
            case PaymentSystem::BILL_CODE: // Банковский счет
                // Сформируем счет в PDF
                $document = new Document();
                if ($document->pdf(null, $order)) {
                    $fields['FILE'] = [$document->convertToJpg()];
                }
                switch ($request['delivery']['code']) {
                    case DeliverySystem::PICKUP_NSK:
                        $fields['MESSAGE_ID'] = 161;
                        unset($fields['DELIVERY_PERIOD']);
                        break;
                    case DeliverySystem::PICKUP_MSK_NOVOSLOBODSKAYA:
                        $fields['MESSAGE_ID'] = 163;
                        unset($fields['DELIVERY_PERIOD']);
                        break;
                    case DeliverySystem::COURIER_MSK_OUTSIDE_MKAD:
                        $fields['MESSAGE_ID'] = 164;
                        $fields['DELIVERY_PRICE'] = 'уточняется';
                        unset($fields['DELIVERY_PERIOD']);
                        break;
                    case DeliverySystem::COURIER_MSK_INSIDE_MKAD:
                    case DeliverySystem::COURIER_MSK_INSIDE_MKAD_FREE:
                        $fields['MESSAGE_ID'] = 164;
                        unset($fields['DELIVERY_PERIOD']);
                        break;
                    default:
                        $fields['MESSAGE_ID'] = 157;
                        if (strlen($request['delivery']['period']) > 3) { // укажем срок доставки в письме, такой же, как и при оформлении заказа
                            $fields['DELIVERY_PERIOD'] = $request['delivery']['period'];
                        }
                }
                break;
            case PaymentSystem::IN_STORE: // Оплата в магазине
                $fields['MESSAGE_ID'] = 159;
                $fields['DELIVERY_PERIOD'] = date('d.m.Y', strtotime('+1 days'));
                break;
        }
        $this->sendEmail($fields);
        // !!! - Для способа оплаты Оплата картой, письмо отправляется со старницы: /personal/order/payment/success/index.php, после фактического оплаты заказа
        // Подпишем пользователя, если он отметил галочку
        if (isset($request['agreement']['subscribe']) && $request['agreement']['subscribe'] === 'true') {
            $this->subscribe($user['id'], $request);
        }
        // Данные для ответа клиенту
        $response = [
            'success' => true,
            'orderId' => $accountNumber,
            'message' => 'Заказ ' . $accountNumber . ' успешно оформлен',
        ];

        // Очищаем примененные купоны
        DiscountCouponsManager::clear(true);
        // Очистим временную таблицу исключенных товаров из скидок
        //Application::getConnection()->queryExecute('delete from app_product_excluded_from_discount where FUSER_ID="' . $this->getFUserId() . '"');
        // Очистим куки с дополнительными правилами корзины
        //setcookie('BITRIX_BASKET_RULES', serialize([]), time() + 8640000, '/personal/basket');
        // Установим константу с номером последнего оформленного заказа, чтобы потом использовать на странице оплаты (успешной/не успешной)
        setcookie(Option::get('main', 'cookie_name') . '_LAST_ORDER', serialize(['ACCOUNT_NUMBER' => $accountNumber, 'ID' => $order->getId()]), time() + 8640000, '/');

        //$logRequests = $_SERVER['DOCUMENT_ROOT'] . '/personal/order/create/logs/' . date('dmY') . '.log';
        //$log[] = 'Дата: ' . date('d.m.Y H:i:s');
        //$log[] = print_r($request, true);
        //$log[] = print_r($response, true);

        // Если была выбрана оплата по карте
        // Тогда сформируем ссылку на оплату по карте и выполним редирект на нее
        if ($request['payment']['code'] === PaymentSystem::CARD_CODE) {
            if ($url = Order::getInstance()->getPaymentSberbankUrl($order->getId())) {
                $response['redirect'] = $url;
                //$log[] = '$url: ' . $url;
            } else {
                return [
                    'success' => true,
                    'redirect' => '/personal/order/payment/error/',
                ];
            }
        }
        //$log[] = '================================';
        //$log[] = PHP_EOL . PHP_EOL;
        //\Bitrix\Main\IO\File::putFileContents($logRequests, implode(PHP_EOL, $log), \Bitrix\Main\IO\File::APPEND);
        return $response;
    }

    /**
     * Получение ID текущего пользователя
     * Если пользователя нет, тогда добавляем его
     *
     * @param $request
     *
     * @return array
     * @throws ArgumentTypeException
     * @throws Exception
     */
    private function getUserId($request): array
    {
        global $USER;
        if ($USER->isAuthorized()) {
            return [
                'id' => $USER->GetID(),
            ];
        }
        // Проверим наличие пользователя в базе, по емаилу
        if (!$request['contacts']['email']) {
            return [
                'error' => true,
                'message' => 'Не указан E-mail',
            ];
        };
        $email = $request['contacts']['email'];
        $user = UserTable::getList([
            'select' => ['ID'],
            'filter' => ['=EMAIL' => $email],
            'order' => ['ID' => 'desc'],
            'limit' => 1,
        ]);
        if ($user->getSelectedRowsCount() > 0) {
            return [
                'id' => $user->fetchRaw()['ID'],
            ];
        }
        // Если пользователь не был найден в базе, тогда зарегистриуем его
        $user = new \CUser;
        $groups = COption::GetOptionString('main', 'new_user_registration_def_group', '');
        $password = $this->getPassword();
        $userId = $user->Add([
            'LOGIN' => $email,
            'EMAIL' => $email,
            'NAME' => $request['customer']['name'],
            'LAST_NAME' => $request['customer']['lastName'],
            'SECOND_NAME' => $request['customer']['secondName'],
            'PERSONAL_MOBILE' => $request['contacts']['phone'],
            'PERSONAL_ZIP' => $request['location']['zip'],
            'PERSONAL_CITY' => $request['location']['city'],
            'PERSONAL_STREET' => $request['location']['street'] . ', ' . $request['location']['building'] . ', ' . $request['location']['room'],
            'LID' => SITE_ID,
            'LANGUAGE_ID' => LANGUAGE_ID,
            'ACTIVE' => 'Y',
            'PASSWORD' => $password,
            'CONFIRM_PASSWORD' => $password,
            'GROUP_ID' => !empty($groups) ? explode(',', $groups) : [],
            'USER_IP' => $_SERVER['REMOTE_ADDR'],
            'USER_HOST' => @gethostbyaddr($_SERVER['REMOTE_ADDR']),
        ]);
        if (intval($userId) > 0) {
            // Отправляем письмо о регистрации
            $fields = [
                'EVENT_ID' => 'AUTHORIZE',
                'MESSAGE_ID' => 147,
                'NAME' => $request['customer']['name'] ?? '',
                'LAST_NAME' => $request['customer']['lastName'] ?? '',
                'USER_EMAIL' => $email,
                'LOGIN' => $email,
                'PASSWORD' => $password,
            ];
            $this->sendEmail($fields);
            if (COption::GetOptionString('main', 'event_log_register', 'N') === 'Y') {
                CEventLog::Log('SECURITY', 'USER_REGISTER', 'main', $userId);
            }
            return [
                'id' => $userId,
            ];
        }
        if ($user->LAST_ERROR) {
            if (COption::GetOptionString('main', 'event_log_register_fail', 'N') === 'Y') {
                CEventLog::Log('SECURITY', 'USER_REGISTER_FAIL', 'main', $email, $user->LAST_ERROR);
            }
            return [
                'error' => true,
                'message' => $user->LAST_ERROR,
            ];
        }
        return [
            'error' => true,
            'message' => 'Не удалось зарегистрировать данные покупателя',
        ];
    }

    /**
     * Подписка пользователя на рассылки
     *
     * @param $userId
     * @param $request
     *
     * @throws LoaderException
     */
    private function subscribe($userId, $request)
    {
        Bitrix\Main\Loader::includeModule('subscribe');

        $rubricId = 4;
        $arFields = [
            'USER_ID' => $userId,
            'FORMAT' => 'html',
            'EMAIL' => $request['contacts']['email'],
            'ACTIVE' => 'Y',
            'CONFIRMED' => 'Y',
            'SEND_CONFIRM' => 'N',
            'RUB_ID' => [$rubricId],
        ];
        $subscription = new CSubscription;
        $id = $subscription->Add($arFields);

        if ($id > 0) {

            $bitrix24 = new Bitrix24();
            $contact = Contact::getInstance();

            if ($user = $contact->getByEmail($request['contacts']['email'])) {

                $arSubscribe = $user[$bitrix24->getFieldCode('subscriptionName')];
                $arLang = $user[$bitrix24->getFieldCode('subscriptionLanguage')];

                if (!in_array($bitrix24->getSubscriptionId('megre.ru'), $arSubscribe) || !in_array($bitrix24->getSubscriptionLanguageId('russian'), $arLang)) {

                    if (!in_array($bitrix24->getSubscriptionId('megre.ru'), $arSubscribe)) {
                        $arSubscribe[] = $bitrix24->getSubscriptionId('megre.ru');
                    }

                    if (!in_array($bitrix24->getSubscriptionLanguageId('russian'), $arLang)) {
                        $arLang[] = $bitrix24->getSubscriptionLanguageId('russian');
                    }

                    $contact->update($user['ID'], [
                        $bitrix24->getFieldCode('subscriptionName') => $arSubscribe,
                        $bitrix24->getFieldCode('subscriptionLanguage') => $arLang,
                    ]);
                }

            } else {

                $user['NAME'] = $request['customer']['name'];
                $user['LAST_NAME'] = $request['customer']['lastName'];
                $user['SECOND_NAME'] = $request['customer']['secondName'];
                $user['EMAIL'] = [['VALUE' => $request['contacts']['email'], 'VALUE_TYPE' => 'WORK']];

                $user[$bitrix24->getFieldCode('subscriptionName')][$bitrix24->getSubscriptionId('megre.ru')] = $bitrix24->getSubscriptionId('megre.ru');
                $user[$bitrix24->getFieldCode('subscriptionLanguage')][$bitrix24->getSubscriptionLanguageId('russian')] = $bitrix24->getSubscriptionLanguageId('russian');

                $contact->export($user);
            }
        }
    }

    /**
     * Генерация пароля для нового пользователя
     *
     * @return string|null
     */
    private function getPassword(): ?string
    {
        $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP!+=*&?><%#";
        $max = 10;
        $size = StrLen($chars) - 1;
        $password = null;
        while ($max--) {
            $password .= $chars[rand(0, $size)];
        }
        return $password;
    }

    /**
     * Метод формирует комментарий-описание для заказа
     *
     * @param $request
     * @param $basketPrice
     *
     * @return array
     * @throws ObjectException
     * @throws SqlQueryException
     */
    private function getCommentForOrder($request, $basketPrice = 0): array
    {
        $customerComment = $request['comment'];
        $managerComment = '';
        // Если уже имеются заранее посчитанные посылки
        // Тогда напишем особенный комментарий для менеджера
        if (count($request['delivery']['parcels']) > 0) {
            $totalQuantity = 0;
            $totalWeight = 0;
            $totalVolume = 0;
            foreach ($request['delivery']['parcels'] as $number => $parcel) {
                $managerComment .= "\n" . '#' . $number . '. ' . $parcel['name'] . ' – ';
                $managerComment .= ($parcel['dimensions']['length'] / 10) . '*' . ($parcel['dimensions']['width'] / 10) . '*' . ($parcel['dimensions']['height'] / 10) . ' - ' . ($parcel['volume'] / 1000000000) . ' м3 – ';
                $managerComment .= ($parcel['weight'] / 1000) . ' кг';
                $managerComment .= "\n" . '|----- Товары (' . count($parcel['products']['list']) . 'шт):';

                foreach ($parcel['products']['list'] as $product) {
                    $product['name'] = Helper::getInstance()->clearQuotes($product['name']);
                    $managerComment .= "\n" . '|-------- ' . $product['name'] . ' – ' . $product['quantity'] . ' шт';
                    $managerComment .= "\n" . '|------------- Габариты – ' . ($product['dimensions']['length'] / 10) . '*' . ($product['dimensions']['width'] / 10) . '*' . ($product['dimensions']['height'] / 10) . ' см';
                    $managerComment .= "\n" . '|------------- Объём – ' . (round($product['volume'] / 1000000000, 4)) . ' м3';
                    $managerComment .= "\n" . '|------------- Вес – ' . ($product['weight'] / 1000) . ' кг';
                    // Если товар является Комплектом, тогда запишем его название, в верхнем регистре, в комментарий покупателя
                    if ($product['type'] == ProductTable::TYPE_SET) {
                        $customerComment .= "\n" . mb_strtoupper($product['name']);
                    }
                }
                $managerComment .= "\n" . '|----------------------------------------------------------';
                $managerComment .= "\n" . '| Единиц товара – ' . $parcel['products']['quantity'] . ' шт';
                $managerComment .= "\n" . '| Объём товара – ' . (round($parcel['products']['volume'] / 1000000000, 4)) . ' м3';
                $managerComment .= "\n" . '| Вес посылки – ' . (($parcel['products']['weight'] + $parcel['weight']) / 1000) . ' кг';

                $totalQuantity += $parcel['products']['quantity'];
                $totalWeight += $parcel['products']['weight'] + $parcel['weight'];
                $totalVolume += $parcel['products']['volume'];

                $managerComment .= "\n";
                $managerComment .= "\n";
            }

            $managerComment .= "\n" . '|------------------- Итого по заказу -------------------';
            $managerComment .= "\n" . '| Единиц товара – ' . $totalQuantity . ' шт';
            $managerComment .= "\n" . '| Объём товара – ' . (round($totalVolume / 1000000000, 4)) . ' м3';
            $managerComment .= "\n" . '| Вес посылок – ' . ($totalWeight / 1000) . ' кг';

            //$realOrderWeight = $totalWeight;

        } // Если в заказе нет посылок
        else {
            $basket = $this->getBasket();
            //$excluded = $this->getProductsExcludedFromDiscount();
            $totalWeight = $this->getWeight();
            $totalQuantity = 0;
            $totalVolume = 0;

            $managerComment .= "\n" . '|----- Товары (' . ($basket->count() /*+ count($excluded)*/) . 'шт):';

            foreach ($basket as $item) {
                $name = $item->getField('NAME');
                $quantity = $item->getQuantity();
                $dimensions = $item->getField('DIMENSIONS');
                $type = Application::getConnection()->query('select TYPE from b_catalog_product where ID="' . $item->getProductId() . '" limit 1')->fetchRaw()['TYPE'];

                $name = Helper::getInstance()->clearQuotes($name);

                // Некоторые товары почему-то хранят габариты в сериализованном виде
                if (!isset($dimensions['WIDTH'])) {
                    $dimensions = unserialize($dimensions);
                }

                $width =& $dimensions['WIDTH'];
                $height =& $dimensions['HEIGHT'];
                $length =& $dimensions['LENGTH'];

                $volume = $quantity * ($length * $width * $height);

                $totalQuantity += $quantity;
                $totalVolume += $volume;

                $managerComment .= "\n" . '|-------- ' . $name . ' – ' . $quantity . ' шт';
                $managerComment .= "\n" . '|------------- Габариты – ' . ($length / 10) . '*' . ($width / 10) . '*' . ($height / 10) . ' см';
                $managerComment .= "\n" . '|------------- Объём – ' . (round($volume / 1000000000, 4)) . ' м3';
                $managerComment .= "\n" . '|------------- Вес – ' . ($quantity * ($item->getField('WEIGHT') / 1000)) . ' кг';

                // Если товар является Комплектом, тогда запишем его название, в верхнем регистре, в комментарий покупателя
                if ($type == ProductTable::TYPE_SET) {
                    $customerComment .= "\n" . mb_strtoupper($name);
                }
            }

            /*if (count($excluded) > 0) {
                foreach ($excluded as $product) {
                    $product['NAME'] = Helper::getInstance()->clearQuotes($product['NAME']);

                    $volume = $product['QUANTITY'] * ($product['LENGTH'] * $product['WIDTH'] * $product['HEIGHT']);

                    $totalQuantity += $product['QUANTITY'];
                    $totalVolume += $volume;

                    $managerComment .= "\n" . '|-------- ' . $product['NAME'] . ' – ' . $product['QUANTITY'] . ' шт';
                    $managerComment .= "\n" . '|------------- Габариты – ' . ($product['LENGTH'] / 10) . '*' . ($product['WIDTH'] / 10) . '*' . ($product['HEIGHT'] / 10) . ' см';
                    $managerComment .= "\n" . '|------------- Объём – ' . (round($volume / 1000000000, 4)) . ' м3';
                    $managerComment .= "\n" . '|------------- Вес – ' . ($product['QUANTITY'] * ($product['WEIGHT'] / 1000)) . ' кг';

                    // Если товар является Комплектом, тогда запишем его название, в верхнем регистре, в комментарий покупателя
                    if ($product['PRODUCT_TYPE_ID'] == ProductTable::TYPE_SET) {
                        $customerComment .= "\n" . mb_strtoupper($product['NAME']);
                    }
                }
            }*/
            $managerComment .= "\n";
            $managerComment .= "\n";

            $managerComment .= "\n" . '|------------------- Итого по заказу -------------------';
            $managerComment .= "\n" . '| Единиц товара – ' . $totalQuantity . ' шт';
            $managerComment .= "\n" . '| Объём товара – ' . (round($totalVolume / 1000000000, 4)) . ' м3';
            $managerComment .= "\n" . '| Вес посылок – ' . ($totalWeight / 1000) . ' кг';
        }
        if (time() > strtotime('02.03.2022 00:00:00') && time() < strtotime('11.03.2022 00:00:00') && $basketPrice > 4999) {
            $customerComment = trim($customerComment);
            if (empty($customerComment)) {
                //$customerComment .= 'ПОДАРОК';
                $customerComment .= 'ЯЩИК ПА';
            } else {
                //$customerComment .= "\n" . 'ПОДАРОК';
                $customerComment .= "\n" . 'ЯЩИК ПА';
            }
        }
        return [
            'customer' => $customerComment,
            'manager' => $managerComment,
        ];
    }

    /**
     * Метод для генерации и установки номера заказа
     * В зависимости от типа заказа добавим суффикс к номеру заказа
     *
     * @param        $order
     * @param        $type
     * @param string $city
     *
     * @return string
     * @throws Exception
     */
    private function updateOrderAccountNumber($order, $type, string $city = ''): string
    {
        $accountNumber = $order->getField('ACCOUNT_NUMBER');
        // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/20847/
        if (mb_strpos(mb_strtolower($city), Location::MOSCOW_CITY_TITLE_LOWER) !== false) {
            $accountNumber .= '-' . Order::SUFFIX_MOSCOW;
        } else if ($type === Order::TYPE_MOSCOW) {
            $accountNumber .= '-' . Order::SUFFIX_MOSCOW;
        } else if ($type === Order::TYPE_RETAIL) {
            $accountNumber .= '-' . Order::SUFFIX_RETAIL;
        } else if ($type === Order::TYPE_INTERNET) {
            $accountNumber .= '-' . Order::SUFFIX_INTERNET;
        } else {
            $accountNumber .= '-' . Order::SUFFIX_COMBINE;
        }
        OrderTable::update($order->getId(), [
            'ACCOUNT_NUMBER' => $accountNumber,
        ]);
        return $accountNumber;
    }

    /**
     * Метод для проверки возможности добавления товара в корзину
     * Вызывается при добавлении товара из каталога
     *
     * @param $productId
     *
     * @return array|bool[]
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws ArgumentTypeException
     * @throws LoaderException
     * @throws NotImplementedException
     */
    public function checkAbilityAddToBasketAction($productId)
    {
        Loader::IncludeModule('iblock');

        $product = \CIBlockElement::GetList([], ['ID' => $productId], false, ['nTopCount' => 1], ['ID', 'PROPERTY_ONLY_SINGLE_PRODUCT_IN_ORDER', 'PROPERTY_REDIRECT_LINK'])->fetch();

        if (strlen($product['PROPERTY_REDIRECT_LINK_VALUE']) > 0) {
            return [
                'redirect' => trim($product['PROPERTY_REDIRECT_LINK_VALUE']),
            ];
            //LocalRedirect();
            //die;
        }

        Loader::IncludeModule('sale');
        $basket = Basket::loadItemsForFUser($this->getFUserId(), SITE_ID);

        // Если текущий товар можно добавить в корзину без каких-либо ограничений
        if ($product['PROPERTY_ONLY_SINGLE_PRODUCT_IN_ORDER_VALUE'] === null) {

            // Проверим корзину на тот момент, чтобы в ней отсутствовали товары, которые должны быть только отдельным заказом
            // Если такие товары в корзине уже имеются, тогда не даем положить текущий товар в корзину
            foreach ($basket->getOrderableItems() as $item) {

                $item = CIBlockElement::GetList([], ['ID' => $item->getField('PRODUCT_ID')], false, ['nTopCount' => 1], ['ID', 'PROPERTY_ONLY_SINGLE_PRODUCT_IN_ORDER'])->fetch();

                if ($item['PROPERTY_ONLY_SINGLE_PRODUCT_IN_ORDER_VALUE'] !== null) {
                    return [
                        'canAdd' => false,
                        'message' => 'Товар не может быть добавлен. В корзине уже имеется товар, который должен быть оформлен отдельным заказом.',
                    ];
                }
            }

            return [
                'canAdd' => true,
            ];
        }

        if ($basket->isEmpty() === true) {
            return [
                'canAdd' => true,
            ];
        }

        // Возможно, сейчас в корзине лежит именно текущий товар
        // Поэтому нужно разрешить добавление товара
        if ($basket->count() === 1) {
            foreach ($basket->getOrderableItems() as $product) {
                if ($product->getField('PRODUCT_ID') === $productId) {
                    return [
                        'canAdd' => true,
                    ];
                }
            }
        }

        return [
            'canAdd' => false,
            'message' => 'Товар не может быть добавлен. Данный товар должен быть оформлен отдельным заказом.',
        ];
    }

    private function sendEmail($arFields)
    {
        if ($arFields['MESSAGE_ID']) {
            $data = [
                'EVENT_NAME' => 'NATIVE.APP',
                'MESSAGE_ID' => $arFields['MESSAGE_ID'],
                'LANGUAGE_ID' => LANGUAGE_ID,
                'LID' => SITE_ID,
                'C_FIELDS' => $arFields,
            ];
            if ($arFields['EVENT_ID']) {
                $data['EVENT_NAME'] = $arFields['EVENT_ID'];
            }
            if ($arFields['FILE']) {
                $data['FILE'] = $arFields['FILE'];
            }
            if (Event::sendImmediate($data) === 'Y') {
                if ($data['FILE']) {
                    foreach ($data['FILE'] as $fileId) {
                        \CFile::Delete($fileId);
                    }
                }
            }
        }
    }
}
