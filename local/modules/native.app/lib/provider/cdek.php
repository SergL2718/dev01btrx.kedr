<?php
/*
 * Изменено: 29 декабря 2021, среда
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */


namespace Native\App\Provider;


use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Db\SqlQueryException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use Bitrix\Sale\Delivery\Services\Table as DeliveryServices;
use Bitrix\Main\PhoneNumber\Parser as PhoneParser;
use Bitrix\Main\PhoneNumber\Format as PhoneFormat;
use Native\App\Delivery\Base;
use Native\App\Delivery\PriceTable;
use Native\App\Provider\Boxberry\PointsTable;
use Native\App\Provider\Cdek\ApiTokenTable;
use Native\App\Provider\Cdek\LocationTable;
use Native\App\Provider\Cdek\PointTable;
use Native\App\Provider\Cdek\PostalCodeTable;
use Native\App\Provider\Cdek\TrackNumberTable;
use Native\App\Request;
use Native\App\Sale\DeliverySystem;

/**
 * Class Cdek
 * @package Native\App\Provider
 */
class Cdek extends Base
{
    private static ?Cdek $instance = null;

    private array $settings = [
        'connect' => [
            'account' => '6e46b0aa794d62278c48ebfb57734d41',
            'password' => 'a0cffc25a721e43ed1d32773d0f200bd',
        ],
        'url' => [
            'token' => 'https://api.cdek.ru/v2/oauth/token?parameters',
            'tariff' => 'https://api.cdek.ru/v2/calculator/tariff',
            'cities' => 'https://api.cdek.ru/v2/location/cities',
            'points' => 'https://api.cdek.ru/v2/deliverypoints',
            'orders' => 'https://api.cdek.ru/v2/orders',
        ],
        'fromPostalCode' => '630121'
    ];

    private array $token = [];

    // https://confluence.cdek.ru/pages/viewpage.action?pageId=63345540#id-Калькулятор.Расчетподоступнымтарифам-calc_tariff2
    private array $services = [
        'cdek-store-to-store' => [
            'provider' => 'cdekProvider',
            'method' => [
                'getPointsOfCity' => 'getPointsOfCity',
                'getDeliveryPrice' => 'getDeliveryPrice',
            ],
            'restriction' => [
                'maxWeight' => 30000,
                'country' => [
                    'RU' => [
                        'access' => false,
                    ],
                    'KZ' => [
                        'access' => true,
                    ],
                    'BY' => [
                        'access' => false,
                    ],
                    'UA' => [
                        'access' => false,
                    ],
                ]
            ],
        ],
        'cdek-store-to-door' => [
            'provider' => 'cdekProvider',
            'method' => [
                'getDeliveryPrice' => 'getDeliveryPrice'
            ],
            'restriction' => [
                'maxWeight' => 30000,
                'country' => [
                    'RU' => [
                        'access' => false,
                    ],
                    'KZ' => [
                        'access' => true,
                        'minPrice' => 0, // сумма заказа, от которой будет доступна служба доставки
                    ],
                    'BY' => [
                        'access' => false,
                    ],
                    'UA' => [
                        'access' => false,
                    ],
                ]
            ],
        ],
    ];

    // https://confluence.cdek.ru/pages/viewpage.action?pageId=63345540#id-Калькулятор.Расчетподоступнымтарифам-calc_tariff2
    private array $tariffId = [
        7 => 'cdek-international-express-document-door-to-door', // Международный экспресс документы дверь-дверь - дверь-дверь (Д-Д) - до 5 кг
        8 => 'cdek-international-express-door-to-door', // Международный экспресс грузы дверь-дверь - дверь-дверь (Д-Д) - до 30 кг
        136 => 'cdek-store-to-store', // Посылка склад-склад - склад-склад (С-С) - до 30 кг
        137 => 'cdek-store-to-door', // Посылка склад-дверь - склад-дверь (С-Д) - до 30 кг
        138 => 'cdek-door-to-store', // Посылка дверь-склад - дверь-склад (Д-С) - до 30 кг
        139 => 'cdek-door-to-door', // Посылка дверь-дверь - дверь-дверь (Д-Д) - до 30 кг
        233 => 'cdek-economical-store-to-door', // Экономичная посылка склад-дверь - склад-дверь (С-Д) - до 50 кг
        234 => 'cdek-economical-store-to-store', // Экономичная посылка склад-склад - склад-склад (С-С) - до 50 кг
        291 => 'cdek-express-store-to-store', // CDEK Express склад-склад - склад-склад (С-С)
        293 => 'cdek-express-door-to-door', // CDEK Express дверь-дверь - дверь-дверь (Д-Д)
        294 => 'cdek-express-store-to-door', // CDEK Express склад-дверь - склад-дверь (С-Д)
        295 => 'cdek-express-door-to-store', // CDEK Express дверь-склад - дверь-склад (Д-С)
        366 => 'cdek-door-to-postamat', // Посылка дверь-постамат - дверь-постамат (Д-П) - до 30 кг
        368 => 'cdek-store-to-postamat', // Посылка склад-постамат - склад-постамат (С-П) - до 30 кг
        378 => 'cdek-economical-store-to-postamat', // Экономичная посылка склад-постамат - склад-постамат (С-П) - до 50 кг
    ];

    private array $tariffCode = [
        'cdek-international-express-document-door-to-door' => 7, // Международный экспресс документы дверь-дверь - дверь-дверь (Д-Д) - до 5 кг
        'cdek-international-express-door-to-door' => 8, // Международный экспресс грузы дверь-дверь - дверь-дверь (Д-Д) - до 30 кг
        'cdek-store-to-store' => 136, // Посылка склад-склад - склад-склад (С-С) - до 30 кг
        'cdek-store-to-door' => 137, // Посылка склад-дверь - склад-дверь (С-Д) - до 30 кг
        'cdek-door-to-store' => 138, // Посылка дверь-склад - дверь-склад (Д-С) - до 30 кг
        'cdek-door-to-door' => 139, // Посылка дверь-дверь - дверь-дверь (Д-Д) - до 30 кг
        'cdek-economical-store-to-door' => 233, // Экономичная посылка склад-дверь - склад-дверь (С-Д) - до 50 кг
        'cdek-economical-store-to-store' => 234, // Экономичная посылка склад-склад - склад-склад (С-С) - до 50 кг
        'cdek-express-store-to-store' => 291, // CDEK Express склад-склад - склад-склад (С-С)
        'cdek-express-door-to-door' => 293, // CDEK Express дверь-дверь - дверь-дверь (Д-Д)
        'cdek-express-store-to-door' => 294, // CDEK Express склад-дверь - склад-дверь (С-Д)
        'cdek-express-door-to-store' => 295, // CDEK Express дверь-склад - дверь-склад (Д-С)
        'cdek-door-to-postamat' => 366, // Посылка дверь-постамат - дверь-постамат (Д-П) - до 30 кг
        'cdek-store-to-postamat' => 368, // Посылка склад-постамат - склад-постамат (С-П) - до 30 кг
        'cdek-economical-store-to-postamat' => 378, // Экономичная посылка склад-постамат - склад-постамат (С-П) - до 50 кг
    ];

    private array $deliveryModeId = [
        1 => 'cdek-door-to-door', // дверь-дверь
        2 => 'cdek-door-to-store', // дверь-склад
        3 => 'cdek-store-to-door', // склад-дверь
        4 => 'cdek-store-to-store', // склад-склад
        6 => 'cdek-door-to-postamat', // дверь-постамат
        7 => 'cdek-store-to-postamat', // склад-постамат
    ];

    private array $deliveryModeCode = [
        'cdek-door-to-door' => 1, // дверь-дверь
        'cdek-door-to-store' => 2, // дверь-склад
        'cdek-store-to-door' => 3, // склад-дверь
        'cdek-store-to-store' => 4, // склад-склад
        'cdek-door-to-postamat' => 6, // дверь-постамат
        'cdek-store-to-postamat' => 7, // склад-постамат
    ];

    // https://confluence.cdek.ru/pages/viewpage.action?pageId=63345519
    public function getDeliveryPrice(array $request): array
    {
        $weight = $this->getWeight($request['basket']['weight']);

        if (!$request['deliveryMethod']) {
            $request['deliveryMethod'] = self::SURFACE;
        }

        $request['location']['city'] = mb_strtolower($request['location']['city']);

        if ($request['point']) {
            $request['location']['city'] = $request['point']['CITY_NAME'];
            $request['location']['zip'] = $request['point']['ZIP'];
            $request['location']['country']['code'] = $request['point']['COUNTRY_CODE'];
        }

        // Попробуем получить данные из базы
        $response = PriceTable::getList([
            'select' => [
                'PRICE',
                'PRICE_VAT',
                'PERIOD_MIN',
                'PERIOD_MAX',
            ],
            'filter' => [
                '=DATE_CALCULATE' => date('Ymd'),
                '=DELIVERY_METHOD' => $request['deliveryMethod'],
                '=DELIVERY_CODE' => $request['delivery'],
                '=FROM_ZIP' => $this->getSettings('fromPostalCode'),
                '=TO_ZIP' => $request['location']['zip'],
                '=TO_CITY' => $request['location']['city'],
                '=COUNTRY_CODE' => $request['location']['country']['code'],
                '=COUNTRY_ID' => DeliverySystem::getInstance()->getCountryIdByCode($request['location']['country']['code']),
                '=WEIGHT' => $weight,
            ],
            'order' => [
                'ID' => 'desc'
            ],
            'limit' => 1
        ])->fetchRaw();

        if ($response && $response['PRICE']) {

            $response = [
                'price' => $response['PRICE'] + $response['PRICE_VAT'],
                'period' => [
                    'min' => $response['PERIOD_MIN'],
                    'max' => $response['PERIOD_MAX'],
                ],
            ];

        } else {

            // Запросим стоимость доставки от сервера
            $data = [
                'type' => 1, // интернет-магазин
                'from_location' => [
                    'code' => $this->getLocationCodeByPostalCode($this->getSettings('fromPostalCode')),
                ],
                'tariff_code' => $this->getTariffIdByCode($request['delivery']),
            ];

            if ($request['point']) {
                $data['to_location']['code'] = $request['point']['CITY_CODE'];
            } else {
                $data['to_location']['code'] = $this->getLocationCodeByPostalCode($request['location']['zip'], $request['location']['country']['code']);
            }

            if (!$data['to_location']['code']) {
                return [
                    'price' => 0,
                    'period' => 0,
                ];
            }

            // Товары
            if ($request['basket']['products']) {
                foreach ($request['basket']['products'] as $product) {
                    for ($i = 0; $i < $product['QUANTITY']; $i++) {
                        $data['packages'][] = [
                            'weight' => round($product['WEIGHT']),
                            /*'length' => $product['LENGTH'] ? $product['LENGTH'] / 10 : 1,
                            'width' => $product['WIDTH'] ? $product['WIDTH'] / 10 : 1,
                            'height' => $product['HEIGHT'] ? $product['HEIGHT'] / 10 : 1,*/
                        ];
                    }
                }
            } else if ($request['basket']['weight']) {
                // Общий вес корзины
                $data['packages'][] = [
                    'weight' => $request['basket']['weight'],
                    /*'length' => 1,
                    'width' => 1,
                    'height' => 1,*/
                ];
            }

            // Коробка
            $data['packages'][] = [
                'weight' => $this->getWeightSimpleBox(),
                /*'length' => 1,
                'width' => 1,
                'height' => 1,*/
            ];

            $response = Request::getInstance()->curl($this->getSettings('url')['tariff'], 'POST', $this->getHeaders(), json_encode($data));
            $response = json_decode($response, true);

            if ($response['errors']) {
                return [
                    'price' => 0,
                    'period' => 0,
                ];
            }

            if ($response['delivery_sum'] && $response['total_sum'] > 0) {
                PriceTable::add([
                    'DATE_CREATE' => new DateTime(),
                    'DATE_CALCULATE' => date('Ymd'),
                    'DELIVERY_METHOD' => $request['deliveryMethod'],
                    'DELIVERY_CODE' => $request['delivery'],
                    'FROM_ZIP' => $this->getSettings('fromPostalCode'),
                    'TO_ZIP' => $request['location']['zip'],
                    'TO_CITY' => $request['location']['city'],
                    'COUNTRY_CODE' => $request['location']['country']['code'],
                    'COUNTRY_ID' => DeliverySystem::getInstance()->getCountryIdByCode($request['location']['country']['code']),
                    'WEIGHT' => $weight,
                    'PRICE' => $response['total_sum'],
                    'PRICE_VAT' => 0,
                    'PERIOD_MIN' => $response['period_min'],
                    'PERIOD_MAX' => $response['period_max'],
                ]);

                $response = [
                    'price' => $response['total_sum'],
                    'period' => [
                        'min' => $response['period_min'],
                        'max' => $response['period_min'],
                    ],
                ];
            }
        }

        if ((!$response['period']['min'] && $response['period']['max']) || ($response['period']['min'] === $response['period']['max'])) {
            $response['period'] = $response['period']['max'];
        }

        return $response;
    }

    /**
     * Метод получения ПВЗ в указанном городе
     * Используется для вывода ПВЗ на карту
     * @param $request
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getPointsOfCity($request): array
    {
        $city = trim(mb_strtolower($request['location']['city']['value']));
        if (!$city) {
            return [];
        }
        $result = [];
        $points = PointTable::getList([
            'select' => [
                'POINT_ID' => 'CODE',
                'CITY_NAME' => 'CITY',
                'ADDRESS_REDUCE' => 'ADDRESS',
                'WORK_SCHEDULE' => 'SCHEDULE',
                'ZIP' => 'POSTAL_CODE',
                'PHONE' => 'PHONES',
                'COUNTRY_CODE',
                //'ADDRESS_FULL',
                'CITY_CODE',
                'CITY_SEARCH',
                'LONGITUDE',
                'LATITUDE',
            ],
            'filter' => [
                '%CITY_SEARCH' => $city,
            ],
            'order' => [
                'ADDRESS' => 'asc',
            ],
        ]);
        if ($points->getSelectedRowsCount() > 0) {
            while ($point = $points->fetchRaw()) {
                if ($point['PHONE']) {
                    $point['PHONE'] = unserialize($point['PHONE']);
                    if (count($point['PHONE']) > 0) {
                        $phones = [];
                        foreach ($point['PHONE'] as $phone) {
                            $phones[] = $phone['number'];
                        }
                        $point['PHONE'] = implode('<br>', $phones);
                    }
                }
                if ($point['LONGITUDE'] && $point['LATITUDE']) {
                    $LONGITUDE = $point['LONGITUDE'];
                    $LATITUDE = $point['LATITUDE'];
                    $point['LONGITUDE'] = $LATITUDE;
                    $point['LATITUDE'] = $LONGITUDE;
                }
                $result[$point['POINT_ID']] = $point;
            }
        }
        return $result ? $result : [];
    }

    /**
     * Метод для создания документа посылки для заказа при оплате заказа
     * Используется для события при оплате заказа
     *
     * @param $params
     * @throws SystemException
     */
    public function createParcel($params)
    {
        $order =& $params['order'];
        $fields =& $params['fields'];
        $old =& $params['old'];

        if (
            $fields['STATUS_ID'] !== 'P'
            ||
            (
                isset($old['STATUS_ID']) &&
                $old['STATUS_ID'] === 'P'
            )
            ||
            $fields['PAYED'] !== 'Y'
            ||
            isset($old['PAYED']) === false
            ||
            (
                isset($fields['PAYED']) &&
                $fields['PAYED'] === $old['PAYED']
            )
        ) return;

        $deliveryId =& $fields['DELIVERY_ID'];
        $deliveryCode = DeliveryServices::getList(['select' => ['XML_ID'], 'filter' => ['=ID' => $deliveryId], 'limit' => 1])->fetchRaw()['XML_ID'];

        if (!$this->getServiceByCode($deliveryCode)) {
            return;
        }

        $customer = [];
        $personTypeId = $order->getPersonTypeId();
        $properties = $order->getPropertyCollection()->getArray()['properties'];
        $need = [
            // Физическое лицо
            'EMAIL' => true,
            'PHONE' => true,
            'FIRST_NAME' => true,
            'NAME' => true,
            'SECOND_NAME' => true,
            'ZIP' => true,
            'COUNTRY_CODE' => true,
            'CITY' => true,
            'STREET' => true,
            'HOUSE' => true,
            'APARTMENT' => true,
            // Юридическое лицо
            'COMPANY_NAME' => true,
            //'COMPANY_ADR' => true,
            'INN' => true,
            //'KPP' => true,
            'CDEK_POINT_ID' => true,
        ];
        foreach ($properties as $property) {
            if (!$need[$property['CODE']] || $property['PERSON_TYPE_ID'] != $personTypeId) continue;
            $propertyCode = trim($property['CODE']);
            $value = trim($property['VALUE'][0]);
            $customer[$propertyCode] = $value;
        }

        $fields = [];
        $fields['type'] = 1;
        $fields['shipper_name'] = 'ООО "Звенящие Кедры"';
        $fields['shipper_address'] = 'Россия, г. Новосибирск, ул. Станционная, 80 к1';
        $fields['sender'] = [
            'company' => 'ООО "Звенящие Кедры"',
            'email' => 'admin@megre.ru',
            'phones' => [
                ['number' => '+79137713517'],
                ['number' => '+78003500270'],
            ],
        ];
        $fields['number'] = $order->getField('ACCOUNT_NUMBER');
        $fields['date_invoice'] = $order->getField('DATE_INSERT')->format('Y-m-d');
        $fields['tariff_code'] = $this->getTariffIdByCode($deliveryCode);

        $fields['recipient'] = [
            'name' => trim($customer['FIRST_NAME'] . ' ' . $customer['NAME'] . ' ' . $customer['SECOND_NAME']),
            'company' => $customer['COMPANY_NAME'],
            'tin' => $customer['INN'],
            'email' => $customer['EMAIL'],
            'phones' => [
                ['number' => $customer['PHONE']]
            ],
        ];

        if ($fields['recipient']['phones'][0]['number']) {
            $parse = PhoneParser::getInstance()->parse($fields['recipient']['phones'][0]['number'], LANGUAGE_ID);
            if ($parse->isValid()) {
                $fields['recipient']['phones'][0]['number'] = $parse->format(PhoneFormat::NATIONAL);
            }
        }

        $fields['from_location'] = [
            'postal_code' => $this->getSettings('fromPostalCode')
        ];

        if ($customer['CDEK_POINT_ID']) {
            $fields['delivery_point'] = $customer['CDEK_POINT_ID'];
        } else {
            $fields['to_location'] = [
                'code' => $this->getLocationCodeByPostalCode($customer['ZIP'], $customer['COUNTRY_CODE']),
                'postal_code' => $customer['ZIP'],
                'country_code' => $customer['COUNTRY_CODE'],
                'city' => $customer['CITY'],
                'address' => trim($customer['STREET'] . ', ' . $customer['HOUSE'] . ', ' . $customer['APARTMENT']),
            ];
        }
        $fields['packages']['number'] = $order->getField('ACCOUNT_NUMBER');
        $fields['packages']['weight'] = round($this->getWeight($order->getBasket()->getWeight()));
        $fields['packages']['items'] = [];
        $basket = $order->getBasket();
        foreach ($basket as $item) {
            if (!$item->getField('WEIGHT')) {
                continue;
            }
            $fields['packages']['items'][] = [
                'name' => $item->getField('NAME'),
                'cost' => $item->getPrice(),
                'country_code' => 'RU',
                'amount' => $item->getQuantity(),
                'ware_key' => 'ZK-' . $item->getField('ID'),
                'weight' => round($item->getField('WEIGHT')),
                'payment' => [ // в случае предоплаты значение = 0
                    'value' => 0,
                    'vat_sum' => 0
                ],
            ];
        }

        $response = Request::getInstance()->curl($this->getSettings('url')['orders'], 'POST', $this->getHeaders(), json_encode($fields));
        $response = json_decode($response, true);

        // Получим информацию по зарегистрированному заказу
        if ($response['entity']['uuid']) {
            $response = Request::getInstance()->curl($this->getSettings('url')['orders'] . '/' . $response['entity']['uuid'], 'GET', $this->getHeaders(), json_encode($fields));
            $response = json_decode($response, true);

            //pr('ORDER');
            //pr($response);
        }

        /*global $USER;
        if ($USER->GetID() == 14) {
            pr($fields);
            pr($response);
        }*/

        $log = [];
        $trackingNumber = false;
        $comment = '';
        if ($response['requests'][0]['state'] === 'INVALID' && $response['requests'][0]['errors']) {
            $errors = [];
            foreach ($response['requests'][0]['errors'] as $key => $error) {
                if ($error['message']) {
                    $errors[] = ($key + 1) . '. ' . $error['message'];
                }
            }
            if (count($errors) > 0) {
                $comment = implode("\n", $errors);
                $log = $errors;
            }
        } else if ($response['requests'][0]['state'] === 'SUCCESSFUL' && $response['entity']['cdek_number']) {
            $comment = 'Идентификатор накладной: ' . $response['entity']['cdek_number'];
            $shipmentData ['STATUS_ID'] = 'DS'; // Передан в службу доставки
            //$shipmentData ['TRACKING_NUMBER'] = $response['entity']['cdek_number'];
            $trackingNumber = $response['entity']['cdek_number'];
            $log[] = 'Идентификатор отправления: '. $trackingNumber;
        }

        \CEventLog::Add([
            'AUDIT_TYPE_ID' => 'CDEK_EXPORT',
            'MODULE_ID' => 'sale',
            'ITEM_ID' => 'Заказ: ' . $order->getField('ACCOUNT_NUMBER'),
            'DESCRIPTION' => implode('<br>', $log)
        ]);

        // Запишем информацию в комментарий по отгрузке
        if (!empty($comment)) {
            $shipmentData['COMMENTS'] = $comment;
            $shipmentCollection = $order->getShipmentCollection();
            foreach ($shipmentCollection as $shipment) {
                if ($shipment->getPrice() == $order->getField('PRICE_DELIVERY')) {
                    $shipment->setFields($shipmentData);
                    // Сохраним трек-номер в базу данных
                    if ($trackingNumber !== false) {
                        TrackNumberTable::add([
                            'DATE_CREATE' => new DateTime(),
                            'ORDER_ID' => $order->getId(),
                            'SHIPMENT_ID' => $shipment->getId(),
                            'TRACK_NUMBER' => $trackingNumber,
                        ]);
                    }
                    $shipment->save();
                }
            }
        }
    }

    /**
     * Метод для получения информации по посылкам заказа из сервиса Почты России
     * И сохранения информации в заказ
     *
     * @param $params
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getParcel($params)
    {
        $order =& $params['order'];
        $fields =& $params['fields'];
        $old =& $params['old'];
        if (
            $fields['STATUS_ID'] !== 'F'
            ||
            empty($fields['TRACKING_NUMBER']) === false
            ||
            (
                isset($old['STATUS_ID']) &&
                $old['STATUS_ID'] === 'F'
            )
        ) return;
        $deliveryId =& $fields['DELIVERY_ID'];
        $deliveryCode = DeliveryServices::getList(['select' => ['XML_ID'], 'filter' => ['=ID' => $deliveryId], 'limit' => 1])->fetchRaw()['XML_ID'];
        if (!$this->getServiceByCode($deliveryCode)) {
            return;
        }
        $log = [];
        $shipmentCollection = $order->getShipmentCollection();
        foreach ($shipmentCollection as $shipment) {
            if ($shipment->getPrice() == $order->getField('PRICE_DELIVERY')) {

                // Получаем трек-номер из комментария менеджера
                // Который был получен ранее, при создании посылки в сервисе
                $trackingNumber = TrackNumberTable::getList([
                    'select' => [
                        'TRACK_NUMBER'
                    ],
                    'filter' => [
                        '=SHIPMENT_ID' => $shipment->getId(),
                        '=ORDER_ID' => $order->getId(),
                    ],
                    'order' => [
                        'ID' => 'desc'
                    ],
                    'limit' => 1
                ])->fetchRaw()['TRACK_NUMBER'];

                if ($trackingNumber) {
                    $shipmentData = [
                        'STATUS_ID' => 'DF', // Отгружен
                        'TRACKING_NUMBER' => $trackingNumber,
                        'COMMENTS' => 'Данные из СДЭК успешно загружены'
                    ];
                    $log[] = 'Данные из СДЭК успешно загружены';
                    $log[] = 'Идентификатор отправления: ' . $trackingNumber;
                } else {
                    $shipmentData = [
                        'COMMENTS' => 'Не удалось получить данные из СДЭК'
                    ];
                    $log[] = 'Не удалось получить данные из СДЭК';
                }

                \CEventLog::Add([
                    'AUDIT_TYPE_ID' => 'CDEK_IMPORT',
                    'MODULE_ID' => 'sale',
                    'ITEM_ID' => 'Заказ: ' . $order->getField('ACCOUNT_NUMBER'),
                    'DESCRIPTION' => implode('<br>', $log)
                ]);

                $shipment->setFields($shipmentData);
                $shipment->save();
                break;
            }
        }
    }

    private function getTariffIdByCode(string $code): ?int
    {
        return $this->tariffCode[$code];
    }

    private function getTariffCodeById(string $id): ?string
    {
        return $this->tariffId[$id];
    }

    private function getDeliveryModeIdByCode(string $code): ?int
    {
        return $this->deliveryModeCode[$code];
    }

    private function getDeliveryModeCodeById(int $id): ?string
    {
        return $this->deliveryModeId[$id];
    }

    /**
     * Возвращает ограничения сервиса по коду доставки
     * @param $code
     * @return false|array
     *
     * @deprecated 2021-02-22
     */
    public function getServicesRestrictionByCode(string $code)
    {
        return isset($this->getServiceByCode($code)['restriction']) ? $this->getServiceByCode($code)['restriction'] : false;
    }

    public function getServiceByCode(string $code)
    {
        return isset($this->services[$code]) ? $this->services[$code] : false;
    }

    /**
     * Получиение заголовко для работы в сервисе
     * @return string[]
     */
    private function getHeaders(): array
    {
        $token = $this->getToken();
        return [
            'Authorization: ' . $token['TOKEN_TYPE'] . ' ' . $token['ACCESS_TOKEN'],
            'Content-Type: application/json;charset=UTF-8',
        ];
    }

    // https://confluence.cdek.ru/pages/viewpage.action?pageId=29923918
    private function getToken()
    {
        if (count($this->token) === 0) {
            $date = new DateTime();
            $token = ApiTokenTable::getList([
                'select' => [
                    'ACCESS_TOKEN',
                    'TOKEN_TYPE',
                ],
                'filter' => [
                    '>EXPIRE_AT' => $date
                ],
                'order' => [
                    'ID' => 'desc'
                ],
                'limit' => 1,
            ])->fetchRaw();
            if (!$token) {
                $data = [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->getSettings('connect')['account'],
                    'client_secret' => $this->getSettings('connect')['password'],
                ];
                $response = Request::getInstance()->curl($this->getSettings('url')['token'], 'POST', [], $data);
                $response = json_decode($response, true);
                if (!$response['error']) {
                    $dateCreate = new DateTime();
                    $dateExpire = new DateTime();
                    ApiTokenTable::add([
                        'DATE_CREATE' => $dateCreate,
                        'EXPIRE_AT' => $dateExpire->add($response['expires_in'] . ' seconds'),
                        'JTI' => $response['jti'],
                        'SCOPE' => $response['scope'],
                        'TOKEN_TYPE' => $response['token_type'],
                        'ACCESS_TOKEN' => $response['access_token'],
                    ]);
                    $token = [
                        'ACCESS_TOKEN' => $response['access_token'],
                        'TOKEN_TYPE' => $response['token_type'],
                    ];
                } else {
                    return [];
                }
            }
            if ($token) {
                if (isset($token['TOKEN_TYPE'])) {
                    $token['TOKEN_TYPE'] = ucfirst($token['TOKEN_TYPE']);
                }
                $this->token = $token;
            }
        }
        return $this->token;
    }

    // https://confluence.cdek.ru/pages/viewpage.action?pageId=33829437
    public function getLocationCodeByPostalCode(string $postalCode, string $countryCode = 'RU'): ?int
    {
        $code = null;
        $countryCode = mb_strtoupper($countryCode);
        $code = PostalCodeTable::getList([
            'select' => [
                'CODE' => 'LOCATION.CODE',
            ],
            'filter' => [
                '=POSTAL_CODE' => $postalCode,
                '=COUNTRY_CODE' => $countryCode,
            ],
            'runtime' => [
                'LOCATION' => [
                    'data_type' => LocationTable::getEntity(),
                    'reference' => [
                        '=this.LOCATION_ID' => 'ref.CODE'
                    ],
                ],
            ],
            'limit' => 1
        ])->fetchRaw()['CODE'];
        if (!$code) {
            $request = [
                'country_codes' => $countryCode,
                'postal_code' => $postalCode,
                'page' => 0,
                'size' => 1,
            ];
            $response = Request::getInstance()->curl($this->getSettings('url')['cities'], 'GET', $this->getHeaders(), $request);
            $response = json_decode($response, true);
            if ($response[0]) {
                try {
                    $this->saveLocation($response);
                } catch (ObjectPropertyException | SystemException $e) {
                }
                $code = $response[0]['code'];
            }
        }
        return $code;
    }

    /**
     * Метод получения списка населенных пунктов
     * @param string $countryCode
     * @param bool $reloadData
     * @return int
     * @throws SqlQueryException
     */
    private function getLocationList(string $countryCode = 'RU', $reloadData = false): int
    {
        $total = 0;
        $countryCode = mb_strtoupper($countryCode);
        if (!Application::getConnection()->isTableExists(PostalCodeTable::getTableName())) {
            $this->migrationUp(PostalCodeTable::getTableName());
        }
        if (!Application::getConnection()->isTableExists(LocationTable::getTableName())) {
            $this->migrationUp(LocationTable::getTableName());
        }
        if ($reloadData === true) {
            Application::getConnection()->queryExecute('truncate table ' . PostalCodeTable::getTableName());
            Application::getConnection()->queryExecute('truncate table ' . LocationTable::getTableName());
        }
        $request = [
            'country_codes' => $countryCode,
            'page' => 0,
            'size' => 300
        ];
        do {
            $response = Request::getInstance()->curl($this->getSettings('url')['cities'], 'GET', $this->getHeaders(), $request);
            $response = json_decode($response, true);
            if (count($response) > 0) {
                try {
                    $total += $this->saveLocation($response);
                } catch (ObjectPropertyException | SystemException $e) {
                }
            }
            $request['page']++;
        } while ($response);
        return $total;
    }

    // https://confluence.cdek.ru/pages/viewpage.action?pageId=36982648

    /**
     * Метод получения списка офисов
     * @param string $countryCode
     * @param false $reloadData
     * @return int
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SqlQueryException
     * @throws SystemException
     */
    public function getPointList(string $countryCode = 'RU', $reloadData = false): int
    {
        Application::getConnection()->dropTable(PointTable::getTableName());
        $total = 0;
        $countryCode = mb_strtoupper($countryCode);
        if (!Application::getConnection()->isTableExists(PointTable::getTableName())) {
            $this->migrationUp(PointTable::getTableName());
        }
        if ($reloadData === true) {
            Application::getConnection()->queryExecute('truncate table ' . PointTable::getTableName());
        }
        $request = [
            'country_code' => $countryCode,
            //'postal_code1' => $countryCode,
        ];
        $response = Request::getInstance()->curl($this->getSettings('url')['points'], 'GET', $this->getHeaders(), $request);
        $response = json_decode($response, true);
        if ($response && count($response) > 0) {
            foreach ($response as $point) {
                if ($reloadData === false && PointTable::getList([
                        'select' => ['ID'],
                        'filter' => [
                            '=CODE' => $point['code'],
                            '=COUNTRY_CODE' => $point['location']['country_code']
                        ],
                        'limit' => 1
                    ])->getSelectedRowsCount() > 0) {
                    continue;
                }
                $data = [
                    'CODE' => $point['code'],
                    'NAME' => $point['name'],
                    'POSTAL_CODE' => $point['location']['postal_code'],
                    'COUNTRY_CODE' => $point['location']['country_code'],
                    'CITY_CODE' => $point['location']['city_code'],
                    'CITY' => $point['location']['city'],
                    'CITY_SEARCH' => mb_strtolower($point['location']['city']),
                    'ADDRESS' => $point['location']['address'],
                    'ADDRESS_FULL' => $point['location']['address_full'],
                    'LONGITUDE' => $point['location']['longitude'],
                    'LATITUDE' => $point['location']['latitude'],
                    'TYPE' => $point['type'],
                    'OWNER_CODE' => $point['owner_code'],
                    'SCHEDULE' => $point['work_time'],
                    'NEAREST_STATION' => $point['nearest_station'],
                    'WEIGHT_MAX' => $point['weight_max'],
                    'EMAIL' => $point['email'],
                    'NOTE' => $point[''],
                    'ADDRESS_COMMENT' => $point['address_comment'],
                ];
                if ($point['phones']) {
                    $data['PHONES'] = serialize($point['phones']);
                }
                if ($point['office_image_list']) {
                    $data['IMAGES'] = serialize($point['office_image_list']);
                }
                if ($point['work_time_list']) {
                    $data['WORK_TIME'] = serialize($point['work_time_list']);
                }
                if ($point['take_only']) {
                    $data['TAKE_ONLY'] = 'Y';
                }
                if ($point['is_handout']) {
                    $data['IS_HANDOUT'] = 'Y';
                }
                if ($point['is_dressing_room']) {
                    $data['IS_DRESSING_ROOM'] = 'Y';
                }
                if ($point['have_cashless']) {
                    $data['HAVE_CASHLESS'] = 'Y';
                }
                if ($point['have_cash']) {
                    $data['HAVE_CASH'] = 'Y';
                }
                if ($point['allowed_cod']) {
                    $data['ALLOWED_COD'] = 'Y';
                }
                if ($point['dimensions']) {
                    $data['DIMENSION_WIDTH'] = $point['dimensions'][0]['width'];
                    $data['DIMENSION_HEIGHT'] = $point['dimensions'][0]['height'];
                    $data['DIMENSION_DEPTH'] = $point['dimensions'][0]['depth'];
                }
                if (PointTable::add($data)->isSuccess()) {
                    $total++;
                }
            }
        }
        return $total;
    }

    /**
     * @param array $locationList
     * @return int - Количество сохраненных локаций
     * @throws SystemException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     */
    private function saveLocation(array $locationList): int
    {
        $total = 0;
        if (count($locationList) === 0) {
            return $total;
        }
        foreach ($locationList as $key => $location) {
            if (
                LocationTable::getList(['select' => ['ID'], 'filter' => ['=CODE' => $location['code']], 'limit' => 1])->getSelectedRowsCount() > 0 ||
                count($location['postal_codes']) === 0
            ) {
                return $total;
            }
            $result = LocationTable::add([
                'CODE' => $location['code'],
                'CITY' => $location['city'],
                'FIAS_CITY_GUID' => $location['fias_guid'],
                'COUNTRY_CODE' => $location['country_code'],
                'COUNTRY_NAME' => $location['country'],
                'REGION_CODE' => $location['region_code'],
                'REGION_NAME' => $location['region'],
                'FIAS_REGION_GUID' => $location['fias_region_guid'],
                'SUB_REGION_NAME' => $location['sub_region'],
                'LONGITUDE' => $location['longitude'],
                'LATITUDE' => $location['latitude'],
                'TIME_ZONE' => $location['time_zone'],
                'PAYMENT_LIMIT' => $location['payment_limit'],
            ]);
            if ($result->isSuccess()) {
                foreach ($location['postal_codes'] as $postalCode) {
                    PostalCodeTable::add([
                        'POSTAL_CODE' => $postalCode,
                        'COUNTRY_CODE' => $location['country_code'],
                        'LOCATION_ID' => $location['code'],
                    ]);
                }
                $total++;
            }
        }
        return $total;
    }

    public function migration(string $type)
    {
        $type = mb_strtolower($type);
        $type = ucfirst($type);
        $type = 'migration' . $type;
        $this->$type();
    }

    private function migrationUp(string $tableName = ''): bool
    {
        $arSql[ApiTokenTable::getTableName()] = '
            CREATE TABLE IF NOT EXISTS ' . ApiTokenTable::getTableName() . '
            (
            `ID` INT(11) NOT NULL AUTO_INCREMENT,
            `DATE_CREATE` DATETIME NULL,
            `EXPIRE_AT` DATETIME NULL,
            `JTI` VARCHAR(100),
            `SCOPE` VARCHAR(100),
            `TOKEN_TYPE` VARCHAR(6),
            `ACCESS_TOKEN` TEXT NOT NULL,
            PRIMARY KEY(`ID`),
            INDEX (`EXPIRE_AT`)
            );';

        $arSql[PostalCodeTable::getTableName()] = '
            CREATE TABLE IF NOT EXISTS ' . PostalCodeTable::getTableName() . '
            (
            `ID` INT(11) NOT NULL AUTO_INCREMENT,
            `POSTAL_CODE` VARCHAR(15),
            `COUNTRY_CODE` CHAR(2),
            `LOCATION_ID` INT(11),
            PRIMARY KEY(`ID`),
            INDEX (`POSTAL_CODE`, `COUNTRY_CODE`)
            );';

        $arSql[LocationTable::getTableName()] = '
            CREATE TABLE IF NOT EXISTS ' . LocationTable::getTableName() . '
            (
            `ID` INT(11) NOT NULL AUTO_INCREMENT,
            `CODE` INT(11),
            `CITY` VARCHAR(255),
            `FIAS_CITY_GUID` VARCHAR(255),
            `COUNTRY_CODE` CHAR(2),
            `COUNTRY_NAME` VARCHAR(255),
            `REGION_CODE` INT(11),
            `REGION_NAME` VARCHAR(255),
            `FIAS_REGION_GUID` VARCHAR(255),
            `SUB_REGION_NAME` VARCHAR(255),
            `LONGITUDE` FLOAT,
            `LATITUDE` FLOAT,
            `TIME_ZONE` VARCHAR(255),
            `PAYMENT_LIMIT` FLOAT,
            PRIMARY KEY(`ID`),
            INDEX (`CODE`)
            );';

        $arSql[PointTable::getTableName()] = '
            CREATE TABLE IF NOT EXISTS ' . PointTable::getTableName() . '
            (
            `ID` INT(11) NOT NULL AUTO_INCREMENT,
            `CODE` VARCHAR(15),
            `NAME` VARCHAR(255),
            `COUNTRY_CODE` CHAR(2),
            `POSTAL_CODE` VARCHAR(15),
            `CITY_CODE` VARCHAR(50),
            `CITY` VARCHAR(255),
            `CITY_SEARCH` VARCHAR(255),
            `ADDRESS` VARCHAR(255),
            `ADDRESS_FULL` VARCHAR(255),
            `LONGITUDE` FLOAT,
            `LATITUDE` FLOAT,
            `TYPE` VARCHAR(255),
            `OWNER_CODE` VARCHAR(255),
            `SCHEDULE` VARCHAR(255),
            `NEAREST_STATION` VARCHAR(255),
            `TAKE_ONLY` CHAR(1) DEFAULT "N",
            `IS_HANDOUT` CHAR(1) DEFAULT "N",
            `IS_DRESSING_ROOM` CHAR(1) DEFAULT "N",
            `HAVE_CASHLESS` CHAR(1) DEFAULT "N",
            `HAVE_CASH` CHAR(1) DEFAULT "N",
            `ALLOWED_COD` CHAR(1) DEFAULT "N",
            `EMAIL` VARCHAR(100),
            `PHONES` TEXT,
            `WORK_TIME` TEXT,
            `IMAGES` TEXT,
            `NOTE` TEXT,
            `ADDRESS_COMMENT` TEXT,
            `WEIGHT_MAX` INT(5),
            `DIMENSION_WIDTH` INT(5),
            `DIMENSION_HEIGHT` INT(5),
            `DIMENSION_DEPTH` INT(5),
            PRIMARY KEY(`ID`),
            INDEX (`CITY_SEARCH`, `COUNTRY_CODE`, `TYPE`)
            );';

        $arSql[TrackNumberTable::getTableName()] = '
            CREATE TABLE IF NOT EXISTS ' . TrackNumberTable::getTableName() . '
            (
            `ID` INT(11) NOT NULL AUTO_INCREMENT,
            `DATE_CREATE` DATETIME NULL,
            `ORDER_ID` VARCHAR(15),
            `SHIPMENT_ID` VARCHAR(15),
            `TRACK_NUMBER` VARCHAR(20),
            PRIMARY KEY(`ID`),
            INDEX (`ORDER_ID`, `SHIPMENT_ID`)
            );';

        if (!empty($tableName)) {
            $sql = $arSql[$tableName];
            try {
                Application::getConnection()->queryExecute($sql);
            } catch (SqlQueryException $e) {
            }
            return true;
        }
        foreach ($arSql as $tableName => $sql) {
            try {
                Application::getConnection()->queryExecute($sql);
            } catch (SqlQueryException $e) {
            }
        }
        return true;
    }

    private function migrationDown(string $tableName = ''): bool
    {
        if (!empty($tableName)) {
            if (Application::getConnection()->isTableExists($tableName)) {
                try {
                    Application::getConnection()->dropTable($tableName);
                } catch (SqlQueryException $e) {
                }
            }
            return true;
        }

        if (Application::getConnection()->isTableExists(ApiTokenTable::getTableName())) {
            try {
                Application::getConnection()->dropTable(ApiTokenTable::getTableName());
            } catch (SqlQueryException $e) {
            }
        }
        if (Application::getConnection()->isTableExists(LocationTable::getTableName())) {
            try {
                Application::getConnection()->dropTable(LocationTable::getTableName());
            } catch (SqlQueryException $e) {
            }
        }
        if (Application::getConnection()->isTableExists(PointTable::getTableName())) {
            try {
                Application::getConnection()->dropTable(PointTable::getTableName());
            } catch (SqlQueryException $e) {
            }
        }
        if (Application::getConnection()->isTableExists(PostalCodeTable::getTableName())) {
            try {
                Application::getConnection()->dropTable(PostalCodeTable::getTableName());
            } catch (SqlQueryException $e) {
            }
        }
        if (Application::getConnection()->isTableExists(TrackNumberTable::getTableName())) {
            try {
                Application::getConnection()->dropTable(TrackNumberTable::getTableName());
            } catch (SqlQueryException $e) {
            }
        }
        return true;
    }

    public function migrationReload(string $tableName = '')
    {
        $this->migrationDown($tableName);
        $this->migrationUp($tableName);
    }

    private function getSettings(string $code = '')
    {
        if ($code) {
            return $this->settings[$code];
        }
        return $this->settings;
    }

    public static function getInstance(): Cdek
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct()
    {
    }

    public function __call($name, $arguments)
    {
        die('Method \'' . $name . '\' is not defined');
    }

    private function __clone()
    {
    }
}
