<?php
/*
 * Изменено: 26 октября 2021, вторник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */


namespace Native\App\Provider;


use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Db\SqlQueryException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use Bitrix\Sale\Delivery\Services\Table as DeliveryServices;
use Native\App\Delivery\Base;
use Native\App\Provider\Boxberry\PointsTable;
use Native\App\Provider\Boxberry\TrackNumberTable;
use Native\App\Request;
use Native\App\Sale\DeliverySystem;
use Native\App\Sale\Location;

class Boxberry extends Base
{
    private static ?Boxberry $instance = null;

    // https://account.boxberry.ru/client/infoblock/index?tab=api&api=methods

    const URL = 'https://api.boxberry.ru/json.php';
    const TOKEN = '157d7bda66b201a7055b59fb8b8edadc';

    const COURIER = 'boxberry-courier';
    const POINT = 'boxberry-point';
    const POINT_FREE = 'boxberry-point-free';

    private static array $services = [
        self::COURIER => [
            'provider' => 'boxberryProvider',
            'method' => [
                'getDeliveryPrice' => 'getDeliveryPriceByCourier'
            ],
            'restriction' => [
                'country' => [
                    'RU' => [
                        'access' => true,
                    ],
                    'KZ' => [
                        'access' => false,
                    ],
                ]
            ]
        ],
        self::POINT => [
            'provider' => 'boxberryProvider',
            'method' => [
                'getPointsOfCity' => 'getPointsOfCity',
                'getDeliveryPrice' => 'getDeliveryPriceToPoint',
            ],
            'restriction' => [
                'country' => [
                    'RU' => [
                        'access' => true,
                        'maxPrice' => DeliverySystem::MIN_PRICE_FOR_FREE_DELIVERY, // сумма заказа, до которой будет доступна служба доставки
                        'minPrice' => 20000, // сумма заказа, от которой будет доступна служба доставки
                    ],
                    'KZ' => [
                        'access' => true,
                    ],
                ],
            ]
        ],
        self::POINT_FREE => [
            'provider' => 'boxberryProvider',
            'method' => [
                'getPointsOfCity' => 'getPointsOfCity',
                'getDeliveryPrice' => 'getDeliveryPriceToPoint',
            ],
            //'price' => 'free',
            'restriction' => [
                'country' => [
                    'RU' => [
                        'access' => true,
                        'minPrice' => DeliverySystem::MIN_PRICE_FOR_FREE_DELIVERY, // сумма заказа, от которой будет доступна служба доставки
                    ],
                ]
            ]
        ],
    ];

    private function getTargetFrom(string $locationCode = ''): string
    {
        if (!$locationCode) {
            $locationCode = Location::getCurrentCityCode() === Location::NSK ? Location::OTHER : Location::getCurrentCityCode();
        }
        $list = [
			Location::MSK   => '77714', // 127006, Москва г, Весковский пер, д.2, строение 1
			//Location::MSK   => '01712', // 127055, Москва г, Новослободская ул, д.31/1
			Location::OTHER => '54931', // Новосибирск
		];
        return $list[$locationCode] ?? $list[Location::OTHER];
    }

    private function getZipFrom(string $locationCode = ''): string
    {
        if (!$locationCode) {
            $locationCode = Location::getCurrentCityCode() === Location::NSK ? Location::OTHER : Location::getCurrentCityCode();
        }
        $list = [
			Location::MSK   => '127006', // 127006, Москва г, Весковский пер, д.2, строение 1
			//Location::MSK   => '127055', // 127055, Москва г, Новослободская ул, д.31/1
			Location::OTHER => '630121', // Новосибирск
		];
        return $list[$locationCode] ?? $list[Location::OTHER];
    }

    /**
     * Метод получения стоимости доставки от склада продавца
     * До покупателя с помощью курьера
     * @param $request
     * @return array|mixed|null
     */
    public function getDeliveryPriceByCourier($request)
    {
        $params = [
            'DELIVERY_CODE' => $request['delivery'],
            'FROM_ZIP' => $this->getZipFrom($this->getLocationCode($request)),
            'TO_ZIP' => $request['location']['zip'],
            'TO_CITY' => mb_strtolower($request['location']['city']),
            'COUNTRY_CODE' => $request['location']['country']['code'],
            'COUNTRY_ID' => DeliverySystem::getInstance()->getCountryIdByCode($request['location']['country']['code']),
            'WEIGHT' => $this->getWeight($request['basket']['weight']),
        ];

        // Попробуем получить данные из базы
        $response = $this->getData($params);

        if ($response && $response['PRICE']) {
            $response = [
                'price' => $response['PRICE'] + $response['PRICE_VAT'],
                'period' => $response['PERIOD_MIN']
            ];
        } else {

            // Проверим возможность курьерской доставки по индексу
            // Если доставка курьером по индексу невозможна
            // Тогда прекращаем операцию
            $data = [
                'method' => 'ZipCheck',
                'Zip' => $request['location']['zip'],
                'CountryCode' => DeliverySystem::getInstance()->getCountryIdByCode($request['location']['country']['code']),
            ];
            $response = $this->request($data);

            /*global $USER;
            if ($USER->GetID() == 14) {
                return $response;
            }*/

            // Доставка курьером по указанному индексу - невозможна
            if ($response->err) return null;

            // Запросим стоимость доставки для доставки курьером
            $data = [
                'method' => 'DeliveryCosts',
                'targetstart' => $this->getTargetFrom($this->getLocationCode($request)),
                'zip' => $params['TO_ZIP'],
                'weight' => $params['WEIGHT'],
                'ordersum' => $request['basket']['amount']
            ];

            $response = $this->request($data);

            // Если стоимость получить не удалось
            // Тогда прекращаем операцию
            if ($response->err) return null;

            // Сохраним в базу
            $params['PRICE'] = $response->{'price_base'};
            $params['PRICE_VAT'] = $response->{'price_service'};
            $params['PERIOD_MIN'] = $response->{'delivery_period'};
            $params['PERIOD_MAX'] = $response->{'delivery_period'};

            if (($params['PRICE'] + $params['PRICE_VAT']) > 100) {
                $this->saveData($params);
            }

            $response = [
                'price' => $params['PRICE'] + $params['PRICE_VAT'],
                'period' => $params['PERIOD_MIN']
            ];
        }

        $response['price'] = round($response['price'], 2);

        if ($response['price'] <= 100) {
            return null;
        }

        return $response;
    }

    /**
     * Метод получения стоимости доставки от склада продавца
     * До пункта выдачи заказов, который указал покупатель
     *
     * Для рассчета стоимости доставки достаточно лишь одного ПВЗ из города
     * Так как стоимость для всех ПВЗ в городе одинаковая
     *
     * @param $request
     * @return mixed
     */
    public function getDeliveryPriceToPoint($request)
    {
        $params = [
            'DELIVERY_CODE' => $request['delivery'],
            'FROM_ZIP' => $this->getZipFrom($this->getLocationCode($request)),
            'TO_CITY' => $request['point']['CITY_SEARCH'],
            'COUNTRY_CODE' => $request['point']['COUNTRY_CODE'],
            'COUNTRY_ID' => $request['point']['COUNTRY_ID'],
            'WEIGHT' => $this->getWeight($request['basket']['weight']),
        ];

        // Попробуем получить данные из базы
        $response = $this->getData($params);

        if ($response && $response['PRICE']) {

            $response = [
                'price' => $response['PRICE'] + $response['PRICE_VAT'],
                'period' => $response['PERIOD_MIN']
            ];

        } else {

            $data = [
                'method' => 'DeliveryCosts',
                'targetstart' => $this->getTargetFrom($this->getLocationCode($request)),
                'target' => $request['point']['POINT_ID'],
                'weight' => $params['WEIGHT'],
                'ordersum' => $request['basket']['amount']
            ];

            $response = $this->request($data);


            // Если стоимость получить не удалось
            // Тогда прекращаем операцию
            if ($response->err) return null;

            // Сохраним в базу
            $params['TO_ZIP'] = $request['point']['ZIP'];
            $params['PRICE'] = $response->{'price_base'};
            $params['PRICE_VAT'] = $response->{'price_service'};
            $params['PERIOD_MIN'] = $response->{'delivery_period'};
            $params['PERIOD_MAX'] = $response->{'delivery_period'};

            if (($params['PRICE'] + $params['PRICE_VAT']) > 100) {
                $this->saveData($params);
            }

            $response = [
                'price' => $params['PRICE'] + $params['PRICE_VAT'],
                'period' => $params['PERIOD_MIN']
            ];
        }

        if ($response['price'] <= 100) {
            return null;
        }

        if ($request['delivery'] === self::POINT_FREE) {
            $response['price'] = 'free';
        }

        return $response;
    }

    private function getLocationCode($params): string
    {
        $locationCode = Location::OTHER;
        if ($params['location']['code']) {
            $locationCode = $params['location']['code'];
        } else if (
            $params['location']['country']['code'] === 'RU' &&
            mb_strpos(mb_strtolower($params['location']['city']), Location::MOSCOW_CITY_TITLE_LOWER) !== false
        ) {
            $locationCode = Location::MSK;
        }
        return $locationCode;
    }

    /**
     * Метод для создания документа посылки для заказа при оплате заказа
     * Используется для события при оплате заказа
     *
     * @link https://account.boxberry.ru/client/infoblock/index?tab=api&api=methods&single=22
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

        $personTypeId = $order->getPersonTypeId();

        $fields = [];
        $fields['order_id'] = $order->getField('ACCOUNT_NUMBER');
        $fields['payment_sum'] = 0; // С покупателя при получении заказа деньги не берем

        $fields['price'] = $order->getPrice();
        $fields['delivery_sum'] = $order->getField('PRICE_DELIVERY');

        // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/21385/
        $fields['vid'] = $deliveryCode === self::COURIER ? 2 : 1;

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
            if (!$need[$property['CODE']] || $property['PERSON_TYPE_ID'] != $personTypeId) continue;
            $propertyCode = trim($property['CODE']);
            $value = trim($property['VALUE'][0]);
            $customer[$propertyCode] = $value;
        }
        $locationCode = Location::OTHER;
        if (
            mb_strpos(mb_strtolower($customer['CITY']), Location::MOSCOW_CITY_TITLE_LOWER) !== false ||
            mb_strpos($order->getField('ACCOUNT_NUMBER'), Location::MSK) !== false
        ) {
            $locationCode = Location::MSK;
        }
        $fields['shop']['name1'] = $this->getTargetFrom($locationCode);

        if ($deliveryCode !== self::COURIER) {
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

        if ($deliveryCode === self::COURIER) {
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
                'id' => is_string($item->getProductId()) ? $item->getProductId() : $item->getProductId() . ':' . time(),
                'name' => $item->getField('NAME'),
                'price' => $item->getPrice(),
                'quantity' => $item->getQuantity()
            ];
        }

        $fields['weights'] = [
            'weight' => $this->getWeight($order->getBasket()->getWeight())
        ];

        // Создадим Посылку в сервисе Боксбери
        $data = [
            'token' => self::TOKEN,
            'method' => 'ParselCreate',
            'sdata' => json_encode($fields)
        ];

        $response = Request::getInstance()->curl(self::URL, 'POST', [], $data);
        $response = json_decode($response, true);

        /*global $USER;
        if ($USER->GetID() == 14) {
            pr('$fields');
            pr($fields);
            pr('$response');
            pr($response);
        }*/

        $log = [];
        $trackingNumber = false;
        // Запишем информацию в комментарий по отгрузке
        if (!$response['err']) {
            $trackingNumber = $response['track'];
            $shipmentData = [
                'STATUS_ID' => 'DS', // Передан в службу доставки
                //'TRACKING_NUMBER' => $response['track'],
                'COMMENTS' => $trackingNumber,
            ];
            $log[] = 'Идентификатор отправления: ' . $trackingNumber;
        } else {
            $shipmentData = [
                'COMMENTS' => $response['err']
            ];
            $log[] = $response['err'];
        }

        /*global $USER;
        if ($USER->GetID() == 14) {
            pr('$fields');
            pr($fields);
            pr('$response');
            pr($response);
            pr('$log');
            pr($log);
        }*/

        \CEventLog::Add([
            'AUDIT_TYPE_ID' => 'BOXBERRY_EXPORT',
            'MODULE_ID' => 'sale',
            'ITEM_ID' => 'Заказ: ' . $order->getField('ACCOUNT_NUMBER'),
            'DESCRIPTION' => implode('<br>', $log)
        ]);

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

    /**
     * Метод для получения информации по посылкам заказа из сервиса
     * И сохранения информации в заказ
     *
     * @param $params
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException
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
                // Получаем трек-номер из базы данныз
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
                        'COMMENTS' => 'Данные из Boxberry успешно загружены'
                    ];
                    $log[] = 'Данные из Boxberry успешно загружены';
                    $log[] = 'Идентификатор отправления: ' . $trackingNumber;
                } else {
                    $shipmentData = [
                        'COMMENTS' => 'Не удалось получить данные из Boxberry'
                    ];
                    $log[] = 'Не удалось получить данные из Boxberry';
                }

                \CEventLog::Add([
                    'AUDIT_TYPE_ID' => 'BOXBERRY_IMPORT',
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

    /**
     * Позволяет получить информацию о статусах посылки
     * @url https://account.boxberry.ru/client/infoblock/index?tab=api&api=methods&single=6
     * @param string $tackingCode
     * @return array
     */
    public function getListStatuses(string $tackingCode): array
    {
        $response = $this->request([
            'method' => 'ListStatuses',
            'ImId' => $tackingCode
        ]);
        return !empty($response) && is_array($response) ? $response : [];
    }

    /**
     * Метод получения ПВЗ в указанном городе
     * Используется для вывода ПВЗ на карту
     * @param $request
     * @return array
     */
    public function getPointsOfCity($request): array
    {
        $result = [];
        $city = trim(mb_strtolower($request['location']['city']['value']));
        $weight = $this->getWeight($request['basket']['weight']);
        $volume = $request['basket']['volume'];
        $points = false;
        try {
            $points = PointsTable::getList([
                'select' => [
                    'POINT_ID',
                    'ZIP',
                    'COUNTRY_CODE',
                    'COUNTRY_ID',
                    'CITY_CODE',
                    'CITY_NAME',
                    'CITY_SEARCH',
                    'ADDRESS_REDUCE',
                    'PHONE',
                    'WORK_SCHEDULE',
                    'LONGITUDE',
                    'LATITUDE',
                ],
                'filter' => [
                    '%CITY_SEARCH' => $city,
                    '>WEIGHT_LIMIT' => $weight,
                    '>VOLUME_LIMIT' => $volume,
                ],
                'order' => [
                    'ADDRESS_REDUCE' => 'asc',
                ],
                'cache' => ['ttl' => 8640000]
            ]);
        } catch (ObjectPropertyException | SystemException $e) {
        }
        if ($points) {
            while ($point = $points->fetchRaw()) {
                $result[$point['POINT_ID']] = $point;
            }
        }
        return $result ? $result : [];
    }

    public function checkTables()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS ' . TrackNumberTable::getTableName() . '
        (
        `ID` INT(11) NOT NULL AUTO_INCREMENT,
        `DATE_CREATE` DATETIME NULL,
        `ORDER_ID` VARCHAR(15),
        `SHIPMENT_ID` VARCHAR(15),
        `TRACK_NUMBER` VARCHAR(20),
        
        PRIMARY KEY(`ID`),
        INDEX (`ORDER_ID`, `SHIPMENT_ID`)
        )';

        if (Application::getConnection()->isTableExists(TrackNumberTable::getTableName())) {
            try {
                Application::getConnection()->dropTable(TrackNumberTable::getTableName());
            } catch (SqlQueryException $e) {
            }
        }
        try {
            Application::getConnection()->queryExecute($sql);
        } catch (SqlQueryException $e) {
        }
    }

    public function getServiceByCode(string $code)
    {
        return self::$services[$code] ?? false;
    }

    /**
     * Возвращает ограничения сервиса по коду доставки
     * @param string $code
     * @return false|array
     *
     * @deprecated 2021-02-22
     */
    public function getServicesRestrictionByCode(string $code)
    {
        return isset($this->getServiceByCode($code)['restriction']) ? $this->getServiceByCode($code)['restriction'] : false;
    }

    /**
     * Метод получения ПВЗ (пункт выдачи заказов)
     * Для метода доставки до ПВЗ
     * @param bool $reloadData
     * @return bool
     * @throws SqlQueryException
     */
    public function getListPoints(bool $reloadData = false): bool
    {
        // Проверим наличие таблицы для хранения списка ПВЗ
        $sql = 'CREATE TABLE IF NOT EXISTS ' . PointsTable::getTableName() . '
        (
        `ID` INT(11) NOT NULL AUTO_INCREMENT,
        `DATE_CREATE` DATETIME NULL,
        `POINT_ID` VARCHAR(15),
        `ZIP` VARCHAR(10),
        `COUNTRY_CODE` VARCHAR(2),
        `COUNTRY_ID` VARCHAR(3),
        `CITY_CODE` VARCHAR(15),
        `CITY_NAME` VARCHAR(40),
        `CITY_SEARCH` VARCHAR(40),
        `ADDRESS_REDUCE` VARCHAR(300),
        `PHONE` VARCHAR(40),
        `WORK_SCHEDULE` VARCHAR(150),
        `WEIGHT_LIMIT` INT(9),
        `VOLUME_LIMIT` INT(9),
        `LONGITUDE` VARCHAR(15),
        `LATITUDE` VARCHAR(15),
        
        PRIMARY KEY(`ID`),
        INDEX (`CITY_SEARCH`, `WEIGHT_LIMIT`, `VOLUME_LIMIT`)
        )';

        if (Application::getConnection()->isTableExists(PointsTable::getTableName())) {
            Application::getConnection()->dropTable(PointsTable::getTableName());
        }
        Application::getConnection()->queryExecute($sql);

        // Если необходимо обновить все данные таблицы
        // Тогда, предварительно, очищаем таблицу
        if ($reloadData === true) {
            Application::getConnection()->queryExecute('truncate table ' . PointsTable::getTableName());
        }

        // Получим ПВЗ и сохраним в нашу базу
        $data = [
            'method' => 'ListPoints',
            'prepaid' => true,
        ];
        $response = $this->request($data);
        $date = new DateTime();
        foreach ($response as $point) {
            $zip = explode(',', $point->Address)[0];
            $gps = explode(',', $point->GPS);
            if ($countryCode = DeliverySystem::getInstance()->getCountryCodeById($point->CountryCode)) {
                PointsTable::add([
                    'DATE_CREATE' => $date,
                    'POINT_ID' => $point->Code,
                    'ZIP' => $zip,
                    'COUNTRY_CODE' => $countryCode,
                    'COUNTRY_ID' => $point->CountryCode,
                    'CITY_CODE' => $point->CityCode,
                    'CITY_NAME' => $point->CityName,
                    'CITY_SEARCH' => mb_strtolower($point->CityName),
                    'ADDRESS_REDUCE' => $point->AddressReduce,
                    'PHONE' => $point->Phone,
                    'WORK_SCHEDULE' => $point->WorkShedule,
                    'WEIGHT_LIMIT' => $point->LoadLimit * 1000,
                    'VOLUME_LIMIT' => $point->VolumeLimit * 1000000,
                    'LONGITUDE' => $gps[0],
                    'LATITUDE' => $gps[1],
                ]);
            }
        }

        return true;
    }

    /**
     * Метод для выполнения запросов на сервер
     * @param $data
     * @return mixed
     */
    private function request($data)
    {
        $data['token'] = self::TOKEN;
        $data['cms'] = 'bitrix';
        $data['sucrh'] = true;
        $response = Request::getInstance()->fileGetContents(self::URL, $data);
        return json_decode($response);
    }

    public static function getInstance(): ?Boxberry
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
