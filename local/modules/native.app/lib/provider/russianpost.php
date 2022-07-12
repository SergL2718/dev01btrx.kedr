<?php
/*
 * Изменено: 16 марта 2022, среда
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */


namespace Native\App\Provider;


use Bitrix\Catalog\PriceTable;
use Bitrix\Catalog\ProductTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\PhoneNumber\Format as PhoneNumberFormat;
use Bitrix\Main\PhoneNumber\Parser as PhoneParser;
use Bitrix\Main\SystemException;
use Bitrix\Sale\Delivery\Services\Table as DeliveryServices;
use Native\App\Delivery\Base;
use Native\App\Request;
use Native\App\Sale\DeliverySystem;
use Native\App\Sale\Location;

class RussianPost extends Base
{
    private static ?RussianPost $instance = null;

    /**
     * @link https://otpravka.pochta.ru/specification#/orders-creating_order_v2
     */

    const TOKEN = 'dLOCxKr94FFxPX2G4ePyHB_TNfFac18g';
    const SECRET = 'b3B0QG1lZ3JlLnJ1OmFsa2ExMTcy';

    const URL_TARIFF = 'https://otpravka-api.pochta.ru/1.0/tariff';
    const URL_ORDER = 'https://otpravka-api.pochta.ru/2.0/user/backlog';
    const URL_ORDER_SEARCH = 'https://otpravka-api.pochta.ru/1.0/shipment/search';

    //const ZIP_FROM = '630108';
    const ZIP_FROM = '630121';

    const CLASSIC = 'russian-post';
    const CLASSIC_FREE = 'russian-post-free';
    const EMS = 'russian-post-ems';
    const AIR = 'russian-post-air';
    const SURFACE = 'russian-post-surface';
    //const EMS_FREE = 'russian-post-ems-free';

    private static array $services = [
        self::EMS => [
            'id' => 'EMS',
            'provider' => 'russianPostProvider',
            'method' => [
                'getDeliveryPrice' => 'getDeliveryPriceByEms'
            ],
            'restriction' => [
                'maxWeight' => 31500,
                'country' => [
                    'RU' => [
                        'access' => true,
                    ],
                    'KZ' => [
                        'access' => true,
                    ],
                    'BY' => [
                        'access' => true,
                    ],
                    'UA' => [
						'access' => false,
					],
                ],
                // города в которых служба запрещена
                // ключ является приоритетным перед city
                'city-deny' => [
                    Location::MOSCOW_CITY_TITLE_LOWER => true,
                    Location::NOVOSIBIRSK_CITY_TITLE_LOWER => true,
                    Location::BERDSK_CITY_TITLE_LOWER => true,
                ]
            ]
        ],
        self::CLASSIC => [
            'id' => 'POSTAL_PARCEL',
            'provider' => 'russianPostProvider',
            'method' => [
                'getDeliveryPrice' => 'getDeliveryPriceByDefault'
            ],
            'restriction' => [
                'maxWeight' => 20000,
                'country' => [
                    'RU' => [
                        'access' => true,
                        'maxPrice' => DeliverySystem::MIN_PRICE_FOR_FREE_DELIVERY, // сумма заказа, до которой будет доступна служба доставки
                        'minPrice' => 20000, // сумма заказа, от которой будет доступна служба доставки
                    ],
                    'KZ' => [
                        'access' => true,
                        'maxPrice' => DeliverySystem::MIN_PRICE_FOR_FREE_DELIVERY, // сумма заказа, до которой будет доступна служба доставки
                        'minPrice' => 20000, // сумма заказа, от которой будет доступна служба доставки
                    ],
                    'BY' => [
                        'access' => true,
                        'maxPrice' => DeliverySystem::MIN_PRICE_FOR_FREE_DELIVERY, // сумма заказа, до которой будет доступна служба доставки
                        'minPrice' => 20000, // сумма заказа, от которой будет доступна служба доставки
                    ],
                    'UA' => [
                        'access' => false,
                    ],
                ],
                // города в которых служба запрещена
                // ключ является приоритетным перед city
                'city-deny' => [
                    Location::MOSCOW_CITY_TITLE_LOWER => true,
                    Location::NOVOSIBIRSK_CITY_TITLE_LOWER => true,
                    Location::BERDSK_CITY_TITLE_LOWER => true,
                ]
            ],
        ],
        self::CLASSIC_FREE => [
            'id' => 'POSTAL_PARCEL',
            'provider' => 'russianPostProvider',
            'method' => [
                'getDeliveryPrice' => 'getDeliveryPriceFree'
            ],
            'restriction' => [
                'maxWeight' => 20000,
                'country' => [
                    'RU' => [
                        'access' => true,
                        'minPrice' => DeliverySystem::MIN_PRICE_FOR_FREE_DELIVERY, // сумма заказа, от которой будет доступна служба доставки
                    ],
                    'KZ' => [
                        'access' => true,
                        'minPrice' => DeliverySystem::MIN_PRICE_FOR_FREE_DELIVERY, // сумма заказа, от которой будет доступна служба доставки
                    ],
                    'BY' => [
                        'access' => true,
                        'minPrice' => DeliverySystem::MIN_PRICE_FOR_FREE_DELIVERY, // сумма заказа, от которой будет доступна служба доставки
                    ],
                ],
                // города в которых служба запрещена
                // ключ является приоритетным перед city
                'city-deny' => [
                    Location::MOSCOW_CITY_TITLE_LOWER => true,
                    Location::NOVOSIBIRSK_CITY_TITLE_LOWER => true,
                    Location::BERDSK_CITY_TITLE_LOWER => true,
                ]
            ],
        ],
        self::AIR => [
            'id' => 'POSTAL_PARCEL',
            'provider' => 'russianPostProvider',
            'method' => [
                'getDeliveryPrice' => 'getDeliveryPriceByDefault'
            ],
            'restriction' => [
                'maxWeight' => 20000,
                'country' => [
                    'UA' => [
						'access' => false,
					],
                ]
            ],
        ],
        self::SURFACE => [
            'id' => 'POSTAL_PARCEL',
            'provider' => 'russianPostProvider',
            'method' => [
                'getDeliveryPrice' => 'getDeliveryPriceByDefault'
            ],
            'restriction' => [
                'maxWeight' => 20000,
                'country' => [
                    'UA' => [
						'access' => false,
					],
                ]
            ],
        ],
    ];

    // массив для хранения посылок - когда упаковываем товар в коробки
    private ?array $parcels = null;

    // массив для хранения уже распределнных посылок
    private array $distributions = [];

    // массив коробок для посылок - когда упаковываем товар в коробки
    private array $boxesParcels = [
        4180000 => [
            'name' => 'Ящик №1',
            'weight' => 118,
            'dimensions' => [
                'length' => 200,
                'width' => 190,
                'height' => 110,
            ],
            'volume' => 4180000,
        ],
        10080000 => [
            'name' => 'Ящик №2',
            'weight' => 226,
            'dimensions' => [
                'length' => 200,
                'width' => 280,
                'height' => 180,
            ],
            'volume' => 10080000,
        ],
        16200000 => [
            'name' => 'Ящик №3',
            'weight' => 276,
            'dimensions' => [
                'width' => 450,
                'height' => 180,
                'length' => 200,
            ],
            'volume' => 16200000,
        ],
        20000000 => [
            'name' => 'Ящик №4',
            'weight' => 382,
            'dimensions' => [
                'length' => 200,
                'width' => 400,
                'height' => 250,
            ],
            'volume' => 20000000,
        ],
        47385000 => [
            'name' => 'Ящик №5',
            'weight' => 642,
            'dimensions' => [
                'length' => 390,
                'width' => 450,
                'height' => 270,
            ],
            'volume' => 47385000,
        ],
    ];

    // массив ID подушек - для проверки наличия подушек в корзине
    private array $pillowIds = [
        7976 => true,
        8217 => true,
        8152 => true,
        8127 => true,
    ];

    // количество подушек в корзине
    private int $pillowQuantity = 0;

    /**
     * Метод для получения стоимости доставки для Стандратной бесплатной посылки
     * Узнаем только срок доставки
     * @param array $request
     * @return array
     */
    public function getDeliveryPriceFree(array $request): array
    {
        $service = $this->getServiceByCode($request['delivery']);
        $weight = $request['basket']['weight'] < $service['restriction']['maxWeight'] ? $request['basket']['weight'] : $service['restriction']['maxWeight'];

        $params = [
            'DELIVERY_CODE' => $request['delivery'],
            'FROM_ZIP' => self::ZIP_FROM,
            'TO_ZIP' => $request['location']['zip'],
            'TO_CITY' => mb_strtolower($request['location']['city']),
            'COUNTRY_CODE' => $request['location']['country']['code'],
            'COUNTRY_ID' => DeliverySystem::getInstance()->getCountryIdByCode($request['location']['country']['code']),
            'WEIGHT' => $weight,
        ];

        // Попробуем получить данные из базы
        $response = $this->getData($params);

        // Если данных в базе нет, тогда запросим данные с сервера сервиса
        if ($response && $response['PRICE']) {

            $response = [
                'price' => 'free',
                'period' => [
                    'min' => $response['PERIOD_MIN'],
                    'max' => $response['PERIOD_MAX'],
                ],
            ];

        } else {

            // Запросим стоимость у сервера
            $data = [
                'transport-type' => 'SURFACE', // https://otpravka.pochta.ru/specification#/enums-base-transport-type
                'index-from' => $params['FROM_ZIP'],
                'index-to' => $params['TO_ZIP'],
                'mass' => $params['WEIGHT'], // вес в граммах
                'mail-direct' => $params['COUNTRY_ID'], // https://otpravka.pochta.ru/specification#/dictionary-countries
                'mail-type' => $this->getServicesIdByCode($request['delivery']), // https://otpravka.pochta.ru/specification#/enums-base-mail-type,
                'mail-category' => 'ORDINARY', // https://otpravka.pochta.ru/specification#/enums-base-mail-category
            ];

            $data = json_encode($data);
            $response = Request::getInstance()->curl(self::URL_TARIFF, 'POST', $this->getHeaders(), $data);
            $response = json_decode($response);

            $response = [
                'price' => [
                    'value' => $response->{'total-rate'},
                    'vat' => $response->{'total-vat'},
                ],
                'period' => [
                    'min' => $response->{'delivery-time'}->{'min-days'},
                    'max' => $response->{'delivery-time'}->{'max-days'},
                ]
            ];

            if ((!$response['period']['min'] && $response['period']['max']) || ($response['period']['min'] === $response['period']['max'])) {
                $response['period'] = $response['period']['max'];
            }

            //return $response;

            // Сохраним в базу
            $params['PRICE'] = $response['price']['value'] > 0 ? $response['price']['value'] : 0;
            $params['PRICE_VAT'] = $response['price']['vat'] > 0 ? $response['price']['vat'] : 0;
            $params['PERIOD_MIN'] = $response['period']['min'] ? $response['period']['min'] : ($response['period'] > 1 ? $response['period'] : 1);
            $params['PERIOD_MAX'] = $response['period']['max'] ? $response['period']['max'] : ($response['period'] > 1 ? $response['period'] : 1);
            $this->saveData($params);

            // Ответ для клиента
            $response = [
                'price' => 'free',
                'period' => [
                    'min' => $params['PERIOD_MIN'],
                    'max' => $params['PERIOD_MAX'],
                ]
            ];
        }

        if ((!$response['period']['min'] && $response['period']['max']) || ($response['period']['min'] === $response['period']['max'])) {
            $response['period'] = $response['period']['max'];
        }

        return $response;
    }

    /**
     * Метод для получения стоимости доставки для Стандратной посылки
     *
     * @param array $request
     * @return array
     */
    public function getDeliveryPriceByDefault(array $request): array
    {
        return $this->getDeliveryPrice($request);
    }

    /**
     * Метод для получения стоимости доставки для EMS-посылки
     *
     * @param array $request
     * @return array
     */
    public function getDeliveryPriceByEms(array $request): array
    {
        return $this->getDeliveryPrice($request);
    }

    /**
     * Метод для получения стоимости доставки
     *
     * @param array $request
     * @return array
     */
    public function getDeliveryPrice(array $request): array
    {
        // Товары разложенные программно по коробкам
        $parcels = $this->getParcels($request['delivery'], $request['basket']);

        // Если вдруг посылки не расчитаны, тогда не будем считать общую стоиомсть доставки
        if (isset($parcels['errors']) || count($parcels) === 0) {
            return [
                'price' => 0,
                'period' => 0,
                'errors' => $parcels['errors'] ?? []
            ];
        }

        $params = [
            'DELIVERY_CODE' => $request['delivery'],
            'DELIVERY_METHOD' => 'SURFACE',
            'FROM_ZIP' => self::ZIP_FROM,
            'TO_ZIP' => $request['location']['zip'],
            'TO_CITY' => mb_strtolower($request['location']['city']),
            'COUNTRY_CODE' => $request['location']['country']['code'],
            'COUNTRY_ID' => DeliverySystem::getInstance()->getCountryIdByCode($request['location']['country']['code']),
            'WEIGHT' => 0,
        ];

        if ($request['delivery'] === self::AIR) {
            $params['DELIVERY_METHOD'] = 'AVIA';
        }

        $totalPrice = 0; // общая стоимость доставки всех посылок
        $totalPeriod = []; // общая срок доставки всех посылок

        foreach ($parcels as $parcel) {

            $params['WEIGHT'] = $parcel['weight'] + $parcel['products']['weight']; // общий вес посылки

            // Попробуем получить данные из базы
            $response = $this->getData($params);

            if ($response && $response['PRICE']) {

                $price = $response['PRICE'] + $response['PRICE_VAT'];

                // Запишем стоимость доставки текущей посылки в общую стоимость пересылки
                $totalPrice += $price;

                // Запишем период доставки для текущей посылки в общий срок пересылки
                $totalPeriod['min'][] = $response['PERIOD_MIN'];
                $totalPeriod['max'][] = $response['PERIOD_MAX'];

            } else {

                // Запросим стоимость у сервера
                $data = [
                    'transport-type' => $params['DELIVERY_METHOD'], // https://otpravka.pochta.ru/specification#/enums-base-transport-type
                    'index-from' => $params['FROM_ZIP'],
                    'index-to' => $params['TO_ZIP'],
                    'mass' => $params['WEIGHT'], // вес в граммах
                    'mail-direct' => $params['COUNTRY_ID'], // https://otpravka.pochta.ru/specification#/dictionary-countries
                    'mail-type' => $this->getServicesIdByCode($request['delivery']), // https://otpravka.pochta.ru/specification#/enums-base-mail-type,
                    'mail-category' => 'ORDINARY', // https://otpravka.pochta.ru/specification#/enums-base-mail-category
                ];
                $response = Request::getInstance()->curl(self::URL_TARIFF, 'POST', $this->getHeaders(), json_encode($data));
                $response = json_decode($response);

                if (!$response->{'total-rate'} || !$response->{'total-vat'} || ($response->{'sub-code'} && $response->{'sub-code'} === 'INTERNAL_ERROR')) continue;

                // Сохраним в базу
                $params['PRICE'] = $response->{'total-rate'} > 0 ? $response->{'total-rate'} : 0;
                $params['PRICE_VAT'] = $response->{'total-vat'} > 0 ? $response->{'total-vat'} : 0;
                //$params['PERIOD_MIN'] = $response->{'delivery-time'}->{'min-days'};
                //$params['PERIOD_MAX'] = $response->{'delivery-time'}->{'max-days'};

                if ($params['COUNTRY_CODE'] === 'UA') {
                    $params['PERIOD_MIN'] = $response->{'delivery-time'}->{'min-days'} ?: 14;
                    $params['PERIOD_MAX'] = $response->{'delivery-time'}->{'max-days'} ?: 30;
                } else {
                    $params['PERIOD_MIN'] = $response->{'delivery-time'}->{'min-days'} ?: 1;
                    $params['PERIOD_MAX'] = $response->{'delivery-time'}->{'max-days'} ?: 1;
                }

                $this->saveData($params);

                $price = $params['PRICE'] + $params['PRICE_VAT'];

                // Запишем стоимость доставки текущей посылки в общую стоимость пересылки
                $totalPrice += $price;

                $totalPeriod['min'][] = $params['PERIOD_MIN'];
                $totalPeriod['max'][] = $params['PERIOD_MAX'];
            }
        }

        if ($totalPrice > 0) {
            $totalPrice = $totalPrice / 100;
            // http://task.anastasia.ru/issues/3811
            // Минимальная сумма доставки - 300 руб
            $totalPrice = $totalPrice < $this->getMinPrice() ? $this->getMinPrice() : $totalPrice;
        }

        $totalPeriod['min'] = max($totalPeriod['min']);
        $totalPeriod['max'] = max($totalPeriod['max']);

        $response = [
            'price' => $totalPrice,
            'period' => $totalPeriod,
            'parcels' => $parcels,
        ];

        if ((!$response['period']['min'] && $response['period']['max']) || ($response['period']['min'] === $response['period']['max'])) {
            $response['period'] = $response['period']['max'];
        }

        return $response;
    }

    /**
     * Метод для раскладки товаров корзины по коробкам
     * Для каждой службы доставки раскладываем по своим коробкам
     * @param $deliveryCode
     * @param $basket
     * @return null []
     */
    private function getParcels($deliveryCode, $basket)
    {
        if ($this->parcels[$deliveryCode] !== null) return $this->parcels[$deliveryCode];

        // Проверим наличие подушек в корзине
        // В случае наличия подушек, добавляется дополнительный ящик
        $this->checkPillows($basket);

        $service = $this->getServiceByCode($deliveryCode);

        // Проверим, если для службы доставки имеются ограничения по весу и объему посылки
        // Тогда распределим товар по соответствующим коробкам
        if (!$service['restriction']['maxWeight']) {
            $this->parcels[$deliveryCode] = [];
            return $this->parcels[$deliveryCode];
        }

        $distribution = []; // распредленные товары по коробкам - посылки

        // Если товары были распределены хотя бы для одной службы доставки
        // Тогда проверим
        // Быть может какая-то раскладка товаров по коробкам подойдет и для текущей службы доставки
        if (count($this->distributions) > 0) {
            foreach ($this->distributions as $distributedMaxWeight => $distributed) {
                // Если текущий максимальный вес посылки меньше максимального веса уже распределенной посылки
                // И если текущий максимальный вес посылки больше веса корзины
                // Делаем вывод, что текущая раскладка товара по коробкам подойдет и для текущей службы доставки
                if ($service['restriction']['maxWeight'] < $distributedMaxWeight && $service['restriction']['maxWeight'] > $basket['weight']) {
                    $distribution = $distributed;
                    break;
                }
            }
        }

        // Если не удалось подгрузить распределенные посылки из предыдущих служб доставок
        // Тогда произведем раскладку товаров по коробкам
        if (count($distribution) === 0) {
            $distribution = $this->distributeBoxes($service['restriction']['maxWeight'], $basket);
        }

        $this->distributions[$service['restriction']['maxWeight']] = $distribution;
        $this->parcels[$deliveryCode] = $distribution;

        return $this->parcels[$deliveryCode];
    }

    /**
     * Проверим наличие подушек в корзине
     * Если имеется хотя бы одна подушка
     * Тогда будем использовать дополнительную коробку для раскладки
     * @link http://task.anastasia.ru/issues/4805
     * @param $basket
     */
    private function checkPillows($basket)
    {
        if ($this->pillowQuantity === false) return;

        foreach ($basket['products'] as $product) {
            if (!isset($this->pillowIds[$product['PRODUCT_ID']])) continue;
            $this->pillowQuantity += $product['QUANTITY'];
        }

        // Если подушек в корзине нет
        // Тогда выходим
        if ($this->pillowQuantity === 0) {
            $this->pillowQuantity = false;
            return;
        }

        // Если имеется хотя бы одна подушка
        // Тогда добавим дополнительный тип коробки для отправки посылки
        $this->boxesParcels[50323100] = [
            'name' => 'Ящик №6',
            'weight' => 450,
            'dimensions' => [
                'length' => 265,
                'width' => 525,
                'height' => 395,
            ],
            //'volume' => 54954375, // реальный объем коробки
            'volume' => 50323100, // объем только для 2 подушек и бус с кулоном
        ];
    }

    /**
     * Метод распределяет товары по коробкам учитывая максимальный вес посылки
     * @param $maxWeightLimit
     * @param $basket
     * @return array
     */
    public function distributeBoxes($maxWeightLimit, $basket): array
    {
        $parcels = []; // массив коробок (посылок), в которые распределяем товар

        $boxesParcels = $this->boxesParcels; // массив коробок, которые имеются для упаковки товара
        ksort($boxesParcels); // отсортируем коробки по объему, от меньшего к большему
        $maxVolumeBox = array_key_last($boxesParcels); // максимальный объем коробки
        $currentBox = $boxesParcels[$maxVolumeBox]; // по умолчанию берем самую большую коробку
        //arsort($currentBox['dimensions']); // отсортируем габариты коробки для последующего сравнения
        $maxBoxLength = max($currentBox['dimensions']); // максимальная длина любой из сторон коробки

        $basket = $basket['products']; // корзина заказа
        $basketVolume = 0; // общий объем товара в корзине
        $basketWeight = 0; // общий вес товара в корзине
        $maxProductLength = 0; // максимальная длина любого из габаритов товара

        // =============================== Обработаем товары корзины ==========================================
        $products = []; // массив для хранения товаров

        foreach ($basket as $item) {
            if ($item['WEIGHT'] <= 0) {
                return [
                    'errors' => [
                        'message' => 'Неверные габариты товара: ' . $item['NAME'],
                        'list' => ['WEIGHT']
                    ],
                ];
            }
            $product = [
                'id' => $item['PRODUCT_ID'],
                'name' => $item['NAME'],
                'weight' => $item['WEIGHT'],
                'dimensions' => [
                    'length' => $item['LENGTH'],
                    'width' => $item['WIDTH'],
                    'height' => $item['HEIGHT'],
                ],
                'volume' => $item['VOLUME'],
                'type' => $item['TYPE'],
            ];

            // Получим максимальную длину стороны товара
            $product['maxLength'] = max($product['dimensions']);

            // Не у всех товаров заполнены габариты
            // Фиксим данный баг
            // http://task.anastasia.ru/issues/4881
            if ($product['volume'] === 0) continue;
            // Проверим, может ли товар уместиться в самый большой ящик, который имеется для посылок
            // Если товар не может уместиться, тогда исключаем его из раскладки
            // http://task.anastasia.ru/issues/4881
            if ($product['maxLength'] > $maxBoxLength) {
                continue;
            }

            // Зафиксируем масильную длину стороны товаров
            if ($product['maxLength'] > $maxProductLength) {
                $maxProductLength = $product['maxLength'];
            }

            // Зафиксируем объем товара
            $basketVolume += $product['volume'] * $item['QUANTITY'];

            // Зафиксируем вес товара
            $basketWeight += $product['weight'] * $item['QUANTITY'];

            // Отсортируем габариты текущего товара
            // Чтобы потом сравнить габариты коробки и товара
            arsort($product['dimensions']);

            // Каждую единицу товара заполним отдельной позицией
            // Чтобы потом было проще укладывать все в коробку
            for ($i = 0; $i < $item['QUANTITY']; $i++) {
                // Добавим в массив товаров для последующей обработки
                $products[$product['volume']][] = $product;
            }
        }

        krsort($products); // Отсортируем товары по объему
        // =============================== Обработаем товары корзины - Конец ==================================

        // =============================== Уложим товары в коробки ============================================
        $numberBox = 1; // номер коробки
        $boxHasSmallDimensions = false; // переменная для хранения габаритов, в случае, если товар не может быть уложен по своим размерам
        $pillowQuantity = $this->pillowQuantity; // количество подушек для отправки

        // Проверим, быть может, весь текущий, общий объем товара можно уместить в одну коробку
        $oneBox = false;
        foreach ($boxesParcels as $box) {
            if ($basketVolume < $box['volume'] && $maxProductLength <= max($box['dimensions']) && $basketWeight < $maxWeightLimit) {
                $oneBox = $box;
                break;
            }
        }

        // Если потенциально сможем уместить весь товар в один ящик, тогда проверим самую длинную сторону товара и ящика
        if ($oneBox) {
            foreach ($products as $productVolume => $productList) {
                foreach ($productList as $product) {
                    $oneBox['products']['weight'] += $product['weight'];
                    $oneBox['products']['volume'] += $product['volume'];
                    $oneBox['products']['quantity'] += 1;
                    if (!$oneBox['products']['list'][$product['id']]) {
                        $oneBox['products']['list'][$product['id']] = $product;
                    } else {
                        $oneBox['products']['list'][$product['id']]['weight'] += $product['weight'];
                        $oneBox['products']['list'][$product['id']]['volume'] += $product['volume'];
                    }
                    $oneBox['products']['list'][$product['id']]['quantity'] += 1;
                }
            }
            $parcels[$numberBox] = $oneBox;
            return $parcels;
        }

        // Если не удается уместить товары в одну коробку
        // Тогда разложим товары по нескольким коробкам
        do {
            // Подберём наиболее подходящую коробку
            // Для начала возьмём самую большую по объему коробку
            $currentBox = $boxesParcels[$maxVolumeBox];
            // Отсортируем габариты текущей коробки
            // Чтобы потом сравнить габариты укладываемого в нее товара
            arsort($currentBox['dimensions']);

            // Если в заказе имеются подушки
            // Тогда производим укладку товара с самой большой коробки
            if ($pillowQuantity !== false && $pillowQuantity > 0 && $pillowQuantity % 2) {
                krsort($boxesParcels);
            }

            // Если при раскладке товара в коробку - при очередной итерации
            // Какой-либо товар не уместился в коробку из-за своих габаритов
            // То есть, габариты выбранной коробки были малы
            // Тогда, подберем коробку в зависимости от габаритов, а не объема корзины
            if ($boxHasSmallDimensions !== false) {
                // Габариты не уместившегося товара в коробку на прошлой итерации
                $productDimensions =& $boxHasSmallDimensions;
                // Проверяем коробки по габаритам
                // Начинаем с самой маленькой коробки, чтобы не пытаться уместить товар сразу в самую большую коробку
                foreach ($boxesParcels as $boxVolume => $box) {
                    // Отсортируем габариты текущей коробки
                    // Чтобы потом сравнить габариты укладываемого в нее товара
                    arsort($box['dimensions']);
                    // Проверим габариты
                    $checkDimensions = $this->compareDimensionsProductAndBox($productDimensions, $box['dimensions']);
                    // Если габариты подходят, тогда выбираем коробку
                    if ($checkDimensions === true) {
                        $currentBox = $box;
                        break;
                    }
                }

            } // Иначе, подберём коробку исходя из объема товаров в корзине
            else {
                // Проверим, быть может для текущего объема корзины можно взять иную коробку
                foreach ($boxesParcels as $boxVolume => $box) {
                    // Если объем подходит, тогда выбираем коробку
                    if ($basketVolume <= $boxVolume) {
                        // Отсортируем габариты текущей коробки
                        // Чтобы потом сравнить габариты укладываемого в нее товара
                        arsort($box['dimensions']);
                        $currentBox = $box;
                        break;
                    }
                }
            }

            $maxWeightParcel = $maxWeightLimit - $currentBox['weight']; // максимальный вес посылки с учетом текущей коробки
            $maxVolumeParcel = $currentBox['volume']; // максимальный объем посылки исходя из текущей коробки

            // Складываем товары из корзины в текущую коробку
            // Постараемся максимально распределить товар по коробке
            // То есть, например, если в коробку положили объемный товар, но в коробке еще остался объем или вес
            // Тогда пройдемся по остальным товарам и посмотрим, какой товар можно еще добавить в текущую коробку
            foreach ($products as $volume => $list) {

                // Если все единицы товара по текущему объему уже были распределены
                // Тогда переходим к следующему объему товаров
                if (count($list) === 0) continue;

                // Если после добавления объема текущего товара в текущую коробку
                // Объем коробки будет превышен, тогда переходим к следующему объему товаров
                if ($maxVolumeParcel - $volume < 0) continue;

                foreach ($list as $index => $product) {

                    // Если габариты текущего товара не могут уместиться в текущеуй коробке
                    // Тогда переходим к заполнению следующей коробки
                    $checkDimensions = $this->compareDimensionsProductAndBox($product['dimensions'], $currentBox['dimensions']);
                    if ($checkDimensions === false) {
                        // Запомним габариты товара
                        // Чтобы при следующей итерации подорбрать коробку удовлетворяющую габаритам товара
                        $boxHasSmallDimensions = $product['dimensions'];
                        break;
                    } else {
                        $boxHasSmallDimensions = false;
                    }

                    $weight = $product['weight'];
                    $volume = $product['volume'];

                    // Если после добавления текущей единицы товара в текущей коробке не остается необходимого объема или веса
                    // Тогда переходим к заполнению следующей коробки
                    if ($maxVolumeParcel - $volume < 0 || $maxWeightParcel - $weight < 0) break;

                    // Добавим текущую единицу товара в текущую коробку
                    // То есть заполним объем и вес текущей коробки
                    // Тем самым уменьшив общий объем и вес посылки
                    $maxVolumeParcel -= $volume;
                    $maxWeightParcel -= $weight;

                    // Так как единица товара успешно уместилась в текущей коробке
                    // Значит можем уменьшить общий объем корзины
                    $basketVolume -= $volume;

                    // А также удалим текущую единицу товара из корзины
                    // Чтобы при следующей укладке товара в коробку
                    // Данная единица уже не участвовала
                    unset($products[$volume][$index]);

                    // Добавим текущую единицу товара в список товаров текущей коробки
                    // А также обновим данные по текущей коробке
                    $id = $product['id'];
                    $name = $product['name'];
                    $length = $product['dimensions']['length'];
                    $width = $product['dimensions']['width'];
                    $height = $product['dimensions']['height'];

                    // Ссылки на характеристики коробки (для удобства)
                    $currentBoxWeight =& $currentBox['products']['weight'];
                    $currentBoxVolume =& $currentBox['products']['volume'];
                    $currentBoxQuantity =& $currentBox['products']['quantity'];
                    $currentBoxItem =& $currentBox['products']['list'][$id];

                    // Обработаем добавленную единицу товара
                    if (!isset($currentBoxItem)) {
                        $currentBoxItem = [
                            'id' => $id,
                            'name' => $name,
                            'dimensions' => [
                                'length' => $length,
                                'width' => $width,
                                'height' => $height
                            ],
                            'type' => $product['type'],
                        ];
                    }

                    // Добавим вес, объем и количество текущей единицы товара в коробке
                    $currentBoxItem['weight'] += $weight;
                    $currentBoxItem['volume'] += $volume;
                    $currentBoxItem['quantity']++;

                    // Обновим данные по текущей коробке
                    // Добавим текущую единицу товара в текущую коробку
                    // Увеличим общий вес и объем товара в текущей коробке
                    $currentBoxWeight += $weight;
                    $currentBoxVolume += $volume;
                    $currentBoxQuantity++;

                    // Если текущий товар является подушкой
                    // Тогда уменьшим количество подушек для текущей отправки
                    if (isset($this->pillowIds[$id])) {
                        $pillowQuantity--;
                    }
                }
            }

            // Добавим текущую укомплектованную коробку в список посылок
            if (isset($currentBox['products'])) {
                $parcels[$numberBox] = $currentBox;
                // Берем следующую коробку
                $numberBox++;
            }

            // Если все подушки разложили по коробкам
            // Тогда можем убрать коробку для подушек из списка коробок
            if (!$pillowQuantity && isset($boxesParcels[50323100])) {
                unset($boxesParcels[50323100]);
                // Установим новый максимальный объем коробки
                $maxVolumeBox = array_key_last($boxesParcels);
            }

            // Прерывание процесса на случай, если вдруг что-то пойдет не так при расчете коробок
            if ($numberBox === 70) {
                die('<div style="color: red; text-align: center">Не удалось распределить товар. Пожалуйста, напишите нам об этом.</div>');
            }

            // Если в заказе имеются подушки
            // И если все подушки уже были расзложены по коробкам
            // Тогда производим укладку товара с самой маленькой коробки
            if ($pillowQuantity !== false && $pillowQuantity == 0) {
                ksort($boxesParcels);
            }

        } while ($basketVolume > 0);
        // =============================== Уложим товары в коробки - Конец ====================================


        return $parcels;
    }

    /**
     * Метод для проверки того, войдет ли товар в коробку по габаритам, или нет
     * @param $box
     * @param $product
     * @return bool
     */
    private function compareDimensionsProductAndBox($product, $box): bool
    {
        $success = true;
        $product = array_values($product);
        $box = array_values($box);
        rsort($product);
        rsort($box);
        foreach ($product as $key => $value) {
            if ($value > $box[$key]) {
                $success = false;
                break;
            }
        }
        return $success;
    }

    /**
     * Возвращает ID сервиса по коду доставки
     * @param $code
     * @return false|int
     */
    public function getServicesIdByCode($code)
    {
        return self::$services[$code]['id'] ?? false;
    }

    /**
     * Возвращает ограничения сервиса по коду доставки
     * @param $code
     * @return false|array
     *
     * @deprecated 2021-02-22
     */
    public function getServicesRestrictionByCode($code)
    {
        return self::$services[$code]['restriction'] ?? false;
    }

    /**
     * Метод для создания документа посылки для заказа при переводе заказа в статус Оплачен, формируется к отправке
     * Используется для события при изменении статуса заказа
     *
     * @url https://otpravka.pochta.ru/specification#/orders-creating_order
     *
     * @param array $params
     * @return bool
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function createParcel(array $params): bool
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
        ) return false;

        $deliveryId =& $fields['DELIVERY_ID'];
        $deliveryCode = DeliveryServices::getList(['select' => ['XML_ID'], 'filter' => ['=ID' => $deliveryId], 'limit' => 1])->fetchRaw()['XML_ID'];

        if (!$this->getServiceByCode($deliveryCode)) {
            return false;
        }

        $basket = $order->getBasket();
        $personTypeId = $order->getPersonTypeId();

        $property = [];
        $properties = $order->getPropertyCollection()->getArray()['properties'];
        $need = [
            'EMAIL' => true,
            'PHONE' => true,
            'FIRST_NAME' => true,
            'NAME' => true,
            'SECOND_NAME' => true,
            'COUNTRY_CODE' => true,
            'COUNTRY_NAME' => true,
            'ZIP' => true,
            'CITY' => true,
            'STREET' => true,
            'HOUSE' => true,
            'APARTMENT' => true,
            'COMPANY_NAME' => true,
            'COMPANY_ADR' => true,
            //'SYS_DELIVERY_TOTAL_WEIGHT' => true,
        ];
        foreach ($properties as $item) {
            if (!$need[$item['CODE']] || $item['PERSON_TYPE_ID'] != $personTypeId) continue;
            $code = trim($item['CODE']);
            $value = trim($item['VALUE'][0]);
            $property[$code] = $value;
        }

        $transliterate = ['change_case' => false, 'replace_space' => ' ', 'replace_other' => false];

        $countryCode = $property['COUNTRY_CODE'] ? $property['COUNTRY_CODE'] : 'RU';
        $countryId = DeliverySystem::getInstance()->getCountryIdByCode($countryCode);
        $fio = trim($property['FIRST_NAME'] . ' ' . $property['NAME'] . ' ' . $property['SECOND_NAME']);
        $fullAddress = $property['COMPANY_ADR'] ? $property['COMPANY_ADR'] : trim($property['COUNTRY_NAME'] . ' ' . $property['ZIP'] . ' ' . $property['CITY'] . ' ' . $property['STREET'] . ' ' . $property['HOUSE'] . ' ' . $property['APARTMENT']);

        $data['postoffice-code'] = self::ZIP_FROM;
        $data['order-num'] = $fields['ACCOUNT_NUMBER'];

        //$data['mass'] = $property['SYS_DELIVERY_TOTAL_WEIGHT'] ? $property['SYS_DELIVERY_TOTAL_WEIGHT'] : $order->getBasket()->getWeight();
        $data['mass'] = $basket->getWeight();

        $data['insr-value'] = 0;

        $data['mail-type'] = $deliveryCode === self::EMS ? 'EMS' : 'POSTAL_PARCEL';
        $data['mail-direct'] = $countryId;
        $data['mail-category'] = 'ORDINARY';
        $data['payment-method'] = 'CASHLESS';

        $data['transport-type'] = $deliveryCode === self::AIR ? 'AVIA' : 'SURFACE';

        $data['recipient-name'] = $property['COMPANY_NAME'] ? $property['COMPANY_NAME'] : $fio;
        $data['surname'] = $property['FIRST_NAME'];
        $data['given-name'] = $property['NAME'];
        $data['middle-name'] = $property['SECOND_NAME'];

        if ($property['PHONE']) {
            $parsedPhone = PhoneParser::getInstance()->parse($property['PHONE']);
            $data['tel-address'] = $parsedPhone->format(PhoneNumberFormat::E164);
        }

        $data['address-type-to'] = 'DEFAULT';
        $data['raw-address'] = $fullAddress;
        $data['index-to'] = $property['ZIP'];
        $data['str-index-to'] = $property['ZIP'];

        if ($property['CITY']) {
            $property['CITY'] = trim(str_replace(['г.', 'г '], '', $property['CITY']));
            $data['place-to'] = $property['CITY'];
            $data['region-to'] = $property['CITY'];
        }

        if ($countryCode === 'RU') {

            if ($property['STREET']) {
                $data['street-to'] = trim(str_replace(['ул.', 'ул '], '', $property['STREET']));
            }
            if ($property['HOUSE']) {
                $data['house-to'] = trim(str_replace(['д.', 'д '], '', $property['HOUSE']));
            }
            if ($property['APARTMENT']) {
                $data['room-to'] = trim(str_replace(['кв.', 'кв '], '', $property['APARTMENT']));
            }

        } else {

            if ($property['STREET']) {
                $data['street-to'] = 'ул. ' . trim(str_replace(['ул.', 'ул '], '', $property['STREET']));
            }
            if ($property['HOUSE']) {
                $data['house-to'] = 'д. ' . trim(str_replace(['д.', 'д '], '', $property['HOUSE']));
            }
            if ($property['APARTMENT']) {
                $data['room-to'] = 'кв. ' . trim(str_replace(['кв.', 'кв '], '', $property['APARTMENT']));
            }

            $data['recipient-name'] = \Cutil::translit($data['recipient-name'], 'ru', $transliterate);
            $data['surname'] = \Cutil::translit($data['surname'], 'ru', $transliterate);
            $data['given-name'] = \Cutil::translit($data['given-name'], 'ru', $transliterate);
            $data['middle-name'] = \Cutil::translit($data['middle-name'], 'ru', $transliterate);
            $data['raw-address'] = \Cutil::translit($data['raw-address'], 'ru', $transliterate);
            $data['region-to'] = \Cutil::translit($data['region-to'], 'ru', $transliterate);
            $data['place-to'] = \Cutil::translit($data['place-to'], 'ru', $transliterate);
            $data['street-to'] = \Cutil::translit($data['street-to'], 'ru', $transliterate);
            $data['house-to'] = \Cutil::translit($data['house-to'], 'ru', $transliterate);
            $data['room-to'] = \Cutil::translit($data['room-to'], 'ru', $transliterate);
            $data['street-to'] = trim($data['street-to'] . ' ' . $data['house-to'] . ' ' . $data['room-to']);
        }

        // Данные для таможенной декларации - форма CN23
        // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/12535/
        if (
            $countryCode === 'UA' &&
            Loader::includeModule('iblock') &&
            Loader::includeModule('sale') &&
            Loader::includeModule('catalog')
        ) {
            // Простые товары
            $elementList = [];
            foreach ($basket as $item) {
                $elementList[$item->getProductId()] = [
                    'NAME' => $item->getField('NAME'),
                    'QUANTITY' => $item->getQuantity(),
                    'PRICE' => $item->getPrice(),
                    'WEIGHT' => ceil($item->getWeight()),
                ];
            }
            // Обработка Комплектов
            $setList = [];
            $childrenList = [];
            $productList = [];
            $quantityList = [];
            $priceList = [];
            $sets = \CCatalogProductSet::GetList(
                [], [
                'TYPE' => ProductTable::TYPE_PRODUCT,
                'OWNER_ID' => array_keys($elementList),
                '!ITEM_ID' => array_keys($elementList)
            ], false, false, ['OWNER_ID', 'ITEM_ID', 'QUANTITY']);
            while ($element = $sets->Fetch()) {
                $childrenList[$element['ITEM_ID']] = true;
                $setList[$element['OWNER_ID']][$element['ITEM_ID']]['QUANTITY'] = $element['QUANTITY'];
            }
            if (count($childrenList) > 0) {
                $products = ProductTable::getList([
                    'select' => [
                        'ID',
                        'NAME' => 'IBLOCK_ELEMENT.NAME',
                        'PRICE' => 'PRICE_LIST.PRICE',
                        'WEIGHT',
                    ],
                    'filter' => [
                        '=ID' => array_keys($childrenList),
                    ],
                    'runtime' => [
                        'PRICE_LIST' => [
                            'data_type' => PriceTable::class,
                            'reference' => [
                                '=this.ID' => 'ref.PRODUCT_ID',
                            ],
                            'join_type' => 'left'
                        ],
                    ],
                ]);
                while ($element = $products->fetch()) {
                    $childrenList[$element['ID']] = $element;
                }
                foreach ($setList as $setId => &$children) {
                    $parent = $elementList[$setId];
                    $parent['BASE_PRICE'] = 0;
                    $parent['TOTAL_PRICE'] = 0;
                    foreach ($children as $id => &$element) {
                        $element = array_merge($element, $childrenList[$id]);
                        if ($element['PRICE'] <= 0) {
                            $element['PRICE'] = 0.01;
                        }
                        if ($element['WEIGHT'] <= 0) {
                            $element['WEIGHT'] = 0.01;
                        }
                        $element['WEIGHT'] = ceil($element['WEIGHT']);
                        $parent['BASE_PRICE'] += $element['PRICE'] * $element['QUANTITY'];
                    }
                    if ($parent['BASE_PRICE'] !== $parent['PRICE']) {
                        $parent['DISCOUNT'] = ($parent['BASE_PRICE'] - $parent['PRICE']) / $parent['BASE_PRICE'];
                        if ($parent['DISCOUNT'] > 0) {
                            foreach ($children as $id => &$element) {
                                $element['PRICE'] = $element['PRICE'] - ($element['PRICE'] * $parent['DISCOUNT']);
                                $parent['TOTAL_PRICE'] += $element['PRICE'] * $element['QUANTITY'];
                            }
                        }
                    }
                    foreach ($children as $id => $element) {
                        $productList[$element['ID']] = $element;
                        $quantityList[$id] += $element['QUANTITY'] * $parent['QUANTITY'];
                        $priceList[$id][] = $element['PRICE'];
                    }
                    unset($elementList[$setId]);
                }

                foreach ($priceList as &$price) {
                    $price = round(array_sum($price) / count($price), 2);
                }

                foreach ($productList as $id => $element) {
                    $element['PRICE'] = $priceList[$id];
                    $element['QUANTITY'] = $quantityList[$id];
                    $elementList[$id] = $element;
                }
            }
            unset($setList, $childrenList, $productList, $quantityList, $priceList);
            // Конец - Обработка Комплектов

            $elements = \CIBlockElement::GetList([], ['ID' => array_keys($elementList), '!PROPERTY_VED' => false], false, false, ['ID', 'PROPERTY_VED', 'PROPERTY_SHORT_NAME']);
            while ($element = $elements->fetch()) {
                $ved = $element['PROPERTY_VED_VALUE'];
                $shortName = $element['PROPERTY_SHORT_NAME_VALUE'];
                $element = $elementList[$element['ID']];
                if (!$shortName) {
                    $shortName = $element['NAME'];
                }
                $shortName = \Cutil::translit($shortName, 'ru', $transliterate);
                if (mb_strlen($shortName) > 59) {
                    $shortName = mb_strimwidth($shortName, 0, 57, '...');
                }
                $data['customs-declaration']['customs-entries'][] = [
                    'description' => $shortName, // Наименование товара;
                    'amount' => $element['QUANTITY'], // Количество
                    'country-code' => DeliverySystem::getInstance()->getCountryIdByCode('RU'),
                    'value' => $element['QUANTITY'] * $element['PRICE'] * 100, // Цена за единицу товара в копейках (вкл. НДС)
                    'weight' => $element['QUANTITY'] * $element['WEIGHT'], // Вес товара (в граммах)
                    'tnved-code' => $ved, // Код ТНВЭД
                ];
            }
            $data['customs-declaration']['currency'] = $order->getCurrency();
            $data['customs-declaration']['entries-type'] = 'SALE_OF_GOODS';
        }

        $response = Request::getInstance()->curl(self::URL_ORDER, 'PUT', $this->getHeaders(), json_encode([$data]));
        $response = json_decode($response, true);

        $log = [];
        $comment = '';
        // После перехода на АПИ версии 2 в ответе стали возвращать трек-номер сразу же, после создания посылки
        // Поэтому, сохраним трек-номер в Комментарий к Отгрузке, чтобы менеджер мог подглядеть его при необходимости
        // До момента, пока еще заказ не был передан в службу
        if ($response['orders'] && count($response['orders']) > 0) {
            $package = $response['orders'][0];
            if ($package) {
                $comment = '[' . date('d.m.Y H:i') . '] Идентификатор отправления: ' . $package['barcode'];
                $log[] = 'Идентификатор отправления: ' . $package['barcode'];
            }
        } // Также, в обновленном апи имеются и пояснения по ошибкам.
        else if ($response['errors'] && count($response['errors']) > 0) {
            $errors = [];
            foreach ($response['errors'] as $key => $error) {
                if ($error['error-codes']) {
                    foreach ($error['error-codes'] as $errorCode) {
                        $errors[] = ($key + 1) . '. ' . $errorCode['description'];
                    }
                }
            }
            if (count($errors) > 0) {
                $comment = implode("\n", $errors);
                $log = $errors;
            }
        }

        \CEventLog::Add([
            'AUDIT_TYPE_ID' => 'RUSSIAN_POST_EXPORT',
            'MODULE_ID' => 'sale',
            'ITEM_ID' => 'Заказ: ' . $order->getField('ACCOUNT_NUMBER'),
            'DESCRIPTION' => implode('<br>', $log)
        ]);

        if (!empty($comment)) {
            $shipmentCollection = $order->getShipmentCollection();
            foreach ($shipmentCollection as $shipment) {
                if ($shipment->getPrice() == $order->getField('PRICE_DELIVERY')) {
                    $shipment->setFields(['COMMENTS' => $comment]);
                    $shipment->save();
                    break;
                }
            }
        }
        return true;
    }

    /**
     * Метод для получения информации по посылкам заказа из сервиса
     * И сохранения информации в заказ
     *
     * @param array $params
     * @return false
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getParcel(array $params): bool
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
        ) return false;

        $deliveryId =& $fields['DELIVERY_ID'];
        $deliveryCode = DeliveryServices::getList(['select' => ['XML_ID'], 'filter' => ['=ID' => $deliveryId], 'limit' => 1])->fetchRaw()['XML_ID'];

        if (!$this->getServiceByCode($deliveryCode)) {
            return false;
        }

        $orderNumber =& $fields['ACCOUNT_NUMBER'];
        $response = Request::getInstance()->curl(self::URL_ORDER_SEARCH, 'GET', $this->getHeaders(), ['query' => $orderNumber]);
        $response = json_decode($response);
        if (empty($response)) return false;
        if (isset($response->{'status'}) && $response->{'status'} === 'ERROR') {
            \CEventLog::Add([
                'AUDIT_TYPE_ID' => 'RUSSIAN_POST_IMPORT',
                'MODULE_ID' => 'sale',
                'ITEM_ID' => 'Заказ: ' . $order->getField('ACCOUNT_NUMBER'),
                'DESCRIPTION' => implode('<br>', [
                    'Не удалось получить данные из Почты России',
                    $response->{'message'}
                ])
            ]);
            return false;
        }
        $trackingNumber = '';
        $barcodes = [];
        $totalWeight = 0;
        $totalAmount = 0;
        foreach ($response as $parcel) {
            $barcodes[] = $parcel->{'barcode'};
            $totalWeight += $parcel->{'mass'};
            $totalAmount += $parcel->{'mass-rate-with-vat'};
        }
        if (!empty($barcodes)) {
            $trackingNumber = implode(', ', $barcodes);
        }
        $log = [];
        if ($trackingNumber) {
            $totalAmount = $totalAmount / 100;
            //$personTypeId = $order->getPersonTypeId();
            $propertyCollection = $order->getPropertyCollection();
            foreach ($propertyCollection as $property) {
                $ar = $property->getProperty();
                //if ($ar['PERSON_TYPE_ID'] != $personTypeId) continue;
                if ($ar['CODE'] === 'SYS_DELIVERY_TOTAL_WEIGHT') {
                    $property->setField('VALUE', $totalWeight);
                } else if (
                    $ar['CODE'] === 'SYS_DELIVERY_TOTAL_PRICE'
                ) {
					//$property->setField('VALUE', $deliveryCode === self::CLASSIC_FREE ? 0 : $totalAmount);
					$property->setField('VALUE', $totalAmount);
				}
            }
            $propertyCollection->save();
            $shipmentData = [
                'STATUS_ID' => 'DF', // Отгружен
                'TRACKING_NUMBER' => $trackingNumber,
                'COMMENTS' => 'Данные из Почты России успешно загружены'
            ];
            $log[] = 'Данные из Почты России успешно загружены';
            $log[] = 'Идентификатор отправления: ' . $trackingNumber;
        } else {
            $shipmentData = [
                'COMMENTS' => 'Не удалось получить данные из Почты России'
            ];
            $log[] = 'Не удалось получить данные из Почты России';
        }
        \CEventLog::Add([
            'AUDIT_TYPE_ID' => 'RUSSIAN_POST_IMPORT',
            'MODULE_ID' => 'sale',
            'ITEM_ID' => 'Заказ: ' . $order->getField('ACCOUNT_NUMBER'),
            'DESCRIPTION' => implode('<br>', $log)
        ]);
        $shipmentCollection = $order->getShipmentCollection();
        foreach ($shipmentCollection as $shipment) {
            if ($shipment->getPrice() == $order->getField('PRICE_DELIVERY')) {
                $shipment->setFields($shipmentData);
                $shipment->save();
                break;
            }
        }
        //$order->save();
        return true;
    }

    public function getListStatuses(string $tackingCode): array
    {
        $response = Request::getInstance()->curl(self::URL_ORDER_SEARCH, 'GET', $this->getHeaders(), ['query' => $tackingCode]);
        $response = json_decode($response, true);
		//pr($response);
        return !empty($response) && isset($response[0]) && isset($response[0]['human-operation-name']) ? $response[0] : [];
    }

    public function getServiceByCode(string $code)
    {
        return self::$services[$code] ?? false;
    }

    /**
     * Заголовки для выполнения запроса к сервису
     * @return string[]
     */
    public function getHeaders(): array
    {
        return [
            'Authorization: AccessToken ' . self::TOKEN,
            'X-User-Authorization: Basic ' . self::SECRET,
            'Content-Type: application/json',
            'Accept: application/json;charset=UTF-8',
        ];
    }

    public static function getInstance(): ?RussianPost
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
