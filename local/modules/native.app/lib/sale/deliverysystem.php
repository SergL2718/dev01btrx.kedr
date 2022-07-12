<?php
/*
 * Изменено: 06 сентября 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */


namespace Native\App\Sale;


use Bitrix\Main\Loader;
use Bitrix\Sale\Delivery\Services\Table;
use Native\App\Delivery\Base;


class DeliverySystem extends Base
{
    private static ?DeliverySystem $instance = null;

    const MIN_PRICE_FOR_FREE_DELIVERY = 5000;
    const WITHOUT_DELIVERY = 'without-delivery';
    const PICKUP_MSK_NOVOSLOBODSKAYA = 'pickup-msk-novoslobodskaya';
    const COURIER_MSK_OUTSIDE_MKAD = 'courier-msk-outside-mkad';
    const COURIER_MSK_INSIDE_MKAD = 'courier-msk-inside-mkad';
    const COURIER_MSK_INSIDE_MKAD_FREE = 'courier-msk-inside-mkad-free';
    const PICKUP_NSK = 'pickup-nsk';
    const COURIER_NSK = 'courier-nsk';
    const COURIER_NSK_FREE = 'courier-nsk-free';
    const COURIER_BERDSK = 'courier-berdsk';
    const COURIER_BERDSK_FREE = 'courier-berdsk-free';

    private static array $deliveryId = [];
    private static array $deliveryCode = [];
    private static array $deliveryName = [];

    private array $countriesId = [
        '643' => [
            'code' => 'RU',
            'name' => 'Россия',
        ],
        '112' => [
            'code' => 'BY',
            'name' => 'Беларусь',
        ],
        '398' => [
            'code' => 'KZ',
            'name' => 'Казахстан',
        ],
        '804' => [
            'code' => 'UA',
            'name' => 'Украина',
        ],
    ];

    private array $countriesCode = [
        'RU' => [
            'id' => 643,
            'name' => 'Россия',
        ],
        'BY' => [
            'id' => 112,
            'name' => 'Беларусь',
        ],
        'KZ' => [
            'id' => 398,
            'name' => 'Казахстан',
        ],
        'UA' => [
            'id' => 804,
            'name' => 'Украина',
        ],
    ];

    private static array $services = [
        self::WITHOUT_DELIVERY => [
            'price' => 'free',
        ],
        self::PICKUP_NSK => [
            'price' => 'free',
            'location' => [
				'country'     => [
					'code' => 'RU',
					'name' => 'Россия',
				],
				'zip'         => '630007',
				'city'        => 'Новосибирск',
				'street'      => 'ул. Коммунистическая',
				'building'    => 'ст. 2',
				'room'        => 'офис 516',
				'description' => 'Заберите заказ с 9:00 до 17:30 на',
			],
            'restriction' => [
                'country' => [
                    'RU' => [
                        'access' => true,
                    ],
                ],
                // Города, в которых служба доступна
                'city' => [
					Location::NOVOSIBIRSK_CITY_TITLE_LOWER                        => true,
					Location::NOVOSIBIRSK_EUROPEAN_COTTAGE_SETTLEMENT_TITLE_LOWER => true,
					Location::BERDSK_CITY_TITLE_LOWER                             => true,
				]
            ]
        ],
        self::COURIER_NSK => [
            'restriction' => [
                'country' => [
                    'RU' => [
                        'access' => true,
                        'maxPrice' => DeliverySystem::MIN_PRICE_FOR_FREE_DELIVERY, // сумма заказа, до которой будет доступна служба доставки
                    ],
                ],
                // Города, в которых служба доступна
                'city' => [
					Location::NOVOSIBIRSK_CITY_TITLE_LOWER                        => true,
					Location::NOVOSIBIRSK_EUROPEAN_COTTAGE_SETTLEMENT_TITLE_LOWER => true,
				]
            ]
        ],
        self::COURIER_NSK_FREE => [
            'price' => 'free',
            'restriction' => [
                'country' => [
                    'RU' => [
                        'access' => true,
                        'minPrice' => self::MIN_PRICE_FOR_FREE_DELIVERY, // сумма заказа, от которой будет доступна служба доставки
                    ],
                ],
                // Города, в которых служба доступна
                'city' => [
					Location::NOVOSIBIRSK_CITY_TITLE_LOWER                        => true,
					Location::NOVOSIBIRSK_EUROPEAN_COTTAGE_SETTLEMENT_TITLE_LOWER => true,
				]
            ]
        ],
        self::COURIER_BERDSK => [
            'provider' => 'deliverySystemProvider',
            'method' => [
                'getDeliveryPrice' => 'getDeliveryPrice'
            ],
            'restriction' => [
                'country' => [
                    'RU' => [
                        'access' => true,
                        'maxPrice' => self::MIN_PRICE_FOR_FREE_DELIVERY, // сумма заказа, до которой будет доступна служба доставки
                    ],
                ],
                // Города, в которых служба доступна
                'city' => [
                    Location::BERDSK_CITY_TITLE_LOWER => true,
                ]
            ]
        ],
        self::COURIER_BERDSK_FREE => [
            'price' => 'free',
            'restriction' => [
                'country' => [
                    'RU' => [
                        'access' => true,
                        'minPrice' => self::MIN_PRICE_FOR_FREE_DELIVERY, // сумма заказа, от которой будет доступна служба доставки
                    ],
                ],
                // Города, в которых служба доступна
                'city' => [
                    Location::BERDSK_CITY_TITLE_LOWER => true,
                ]
            ]
        ],
        self::PICKUP_MSK_NOVOSLOBODSKAYA => [
            'price' => 'free',
            'location' => [
                'country' => [
                    'code' => 'RU',
                    'name' => 'Россия',
                ],
                'zip' => '127055',
                'city' => 'Москва',
                'street' => 'ул. Новослободская',
                'building' => '18',
                'room' => '',
                'description' => 'Заберите заказ с 12:00 до 20:00 на',
            ],
            'restriction' => [
                'country' => [
                    'RU' => [
                        'access' => true,
                    ],
                ],
                // Города, в которых служба доступна
                'city' => [
                    Location::MOSCOW_CITY_TITLE_LOWER => true,
                ]
            ]
        ],
        self::COURIER_MSK_INSIDE_MKAD => [
            'provider' => 'deliverySystemProvider',
            'method' => [
                'getDeliveryPrice' => 'getDeliveryPrice'
            ],
            'restriction' => [
                'country' => [
                    'RU' => [
                        'access' => true,
                        'maxPrice' => self::MIN_PRICE_FOR_FREE_DELIVERY, // сумма заказа, от которой будет доступна служба доставки
                    ],
                ],
                // Города, в которых служба доступна
                'city' => [
                    Location::MOSCOW_CITY_TITLE_LOWER => true,
                ]
            ]
        ],
        self::COURIER_MSK_INSIDE_MKAD_FREE => [
            'provider' => 'deliverySystemProvider',
            'method' => [
                'getDeliveryPrice' => 'getDeliveryPrice'
            ],
            'price' => 'free',
            'restriction' => [
                'country' => [
                    'RU' => [
                        'access' => true,
                        'minPrice' => self::MIN_PRICE_FOR_FREE_DELIVERY, // сумма заказа, от которой будет доступна служба доставки
                    ],
                ],
                // Города, в которых служба доступна
                'city' => [
                    Location::MOSCOW_CITY_TITLE_LOWER => true,
                ]
            ]
        ],
        self::COURIER_MSK_OUTSIDE_MKAD => [
            'provider' => 'deliverySystemProvider',
            'method' => [
                'getDeliveryPrice' => 'getDeliveryPrice'
            ],
            'price' => 'refine',
            'restriction' => [
                'country' => [
                    'RU' => [
                        'access' => true,
                    ],
                ],
                // Служба доступна только в этих городах
                'city' => [
                    Location::MOSCOW_CITY_TITLE_LOWER => true,
                ]
            ]
        ],
    ];

    /**
     * Возвращает стоимость и сроки доставки из настроек службы доставки
     * @param array $request
     * @return array
     */
    public function getDeliveryPrice(array $request): array
    {
        $delivery = $this->getServiceByCode($request['delivery']);
        $location = $request['location'];
        $city = mb_strtolower($request['location']['city']);
        if (
            (
                isset($delivery['restriction']['country']) &&
                (
                    !isset($delivery['restriction']['country'][$location['country']['code']]) ||
                    $delivery['restriction']['country'][$location['country']['code']]['access'] !== true
                )
            )
            ||
            (
                isset($delivery['restriction']['city-deny'][$city]) &&
                $delivery['restriction']['city-deny'][$city] === true
            )
            ||
            (
                isset($delivery['restriction']['city'][$city]) &&
                $delivery['restriction']['city'][$city] !== true
            )
        ) {
            return [];
        }
        $response = [];
        if ($delivery['price'] > 0 || $delivery['price'] === 'free' || $delivery['price'] === 'refine') {
            $response['price'] = $delivery['price'] > 0 ? round($delivery['price'], 2) : $delivery['price'];
        } else {
            $response['price'] = 0;
        }
        if ($delivery['period']) {
            $response['period'] = $delivery['period'];
            if ((!$response['period']['min'] && $response['period']['max']) || ($response['period']['min'] === $response['period']['max'])) {
                $response['period'] = $response['period']['max'];
            }
        }
        return $response;
    }

    /**
     * Возвращает список стран
     * @return array[]
     */
    public function getCountryList(): array
    {
        return $this->countriesCode;
    }

    /**
     * Возвращает ID страны по коду
     * @param $code
     * @return false|int
     */
    public function getCountryIdByCode($code)
    {
        return $this->countriesCode[$code]['id'] ?? false;
    }

    /**
     * Возвращает код страны по ID
     * @param $id
     * @return false|string
     */
    public function getCountryCodeById($id)
    {
        return $this->countriesId[$id]['code'] ?? false;
    }

    /**
     * Возвращает название страны по коду
     * @param $code
     * @return false|int
     */
    public function getCountryNameByCode($code)
    {
        return $this->countriesCode[$code]['name'] ?? false;
    }

    public function getServiceByCode(string $code): array
    {
        return self::$services[$code] ?? [];
    }

    /**
     * Возвращает ID службы доставки по ее коду
     * @param $code
     * @return integer|null
     */
    public function getIdByCode($code): ?int
    {
        return self::$deliveryId[$code] ?? null;
    }

    /**
     * Возвращает код службы доставки по ее ID
     * @param $id
     * @return string|bool
     */
    public function getCodeById($id)
    {
        return self::$deliveryCode[$id] ?? false;
    }

    /**
     * Возвращает название службы доставки по ее ID
     * @param $id
     * @return string|bool
     */
    public function getNameById($id)
    {
        return self::$deliveryName[$id] ?? false;
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

    public static function getInstance(): ?DeliverySystem
    {
        if (self::$instance === null) {
            self::$instance = new self;
            Loader::includeModule('sale');
            $rows = Table::getList([
                'select' => [
                    'ID',
                    'NAME',
                    'XML_ID',
                    'CONFIG',
                ],
                'filter' => [
                    '!XML_ID' => false
                ],
                'order' => [
                    'SORT' => 'ASC'
                ]
            ]);
            while ($ar = $rows->fetch()) {
                self::$deliveryId[$ar['XML_ID']] = $ar['ID'];
                self::$deliveryCode[$ar['ID']] = $ar['XML_ID'];
                self::$deliveryName[$ar['ID']] = $ar['NAME'];
                if (!self::$services[$ar['XML_ID']]) {
                    continue;
                }
                $delivery =& self::$services[$ar['XML_ID']];
                $delivery['ID'] = $ar['ID'];
                $delivery['XML_ID'] = $ar['XML_ID'];
                $delivery['NAME'] = $ar['NAME'];
                if ($ar['CONFIG']['MAIN']['PRICE'] > 0 && !$delivery['price']) {
                    $delivery['price'] = $ar['CONFIG']['MAIN']['PRICE'];
                }
                if ($ar['CONFIG']['MAIN']['PERIOD']) {
                    if ($ar['CONFIG']['MAIN']['PERIOD']['FROM'] > 0) {
                        $delivery['period']['min'] = $ar['CONFIG']['MAIN']['PERIOD']['FROM'];
                    }
                    if ($ar['CONFIG']['MAIN']['PERIOD']['TO'] > 0) {
                        $delivery['period']['max'] = $ar['CONFIG']['MAIN']['PERIOD']['TO'];
                    }
                }
            }
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
