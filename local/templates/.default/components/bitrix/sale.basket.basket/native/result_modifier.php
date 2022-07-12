<?php
/*
 * Изменено: 30 июня 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var $arParams
 * @var $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

global $APPLICATION, $USER, $ratio_settings, $bxr_ratio_prop_code;

// https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/2021/
// Проверим наличие товаров исключенных из скидок корзины
// Если такие товары имеются, тогда добавим их в корзину лишь для визуального представления
$fUserId = \Bitrix\Sale\Fuser::getId();

// Если корзина пустая, тогда не обрабатываем логику
if (count($arResult['GRID']['ROWS']) === 0 /*&& $arResult['HAS_EXCLUDED_PRODUCTS'] === 'N'*/) {
    $arResult['EMPTY_BASKET'] = true;
    return;
}

$reloadBasket = false; // перезагрузить страницу (или нет) после предварительной обработки данных
$giftListAmount = 0;
$dateObject = new \Bitrix\Main\Type\DateTime();

$arResult['HAS_OFFLINE_PRODUCT'] = 'N';

// Обработка введенного купона
if (is_array($arResult['COUPON_LIST'][0])) {
    $arResult['COUPON'] = $arResult['COUPON_LIST'][0];
    unset($arResult['COUPON_LIST'][0]);

    if ($arResult['COUPON']['JS_STATUS'] === 'BAD') {
        $arResult['COUPON']['STATUS'] = 'FAIL';
        $arResult['COUPON']['JS_STATUS'] = 'FAIL';
    } else {
        $arResult['COUPON']['STATUS'] = 'SUCCESS';
        $arResult['COUPON']['JS_STATUS'] = 'SUCCESS';
    }
}

// Обработка списка товаров
foreach ($arResult['GRID']['ROWS'] as $key => &$row) {
    $row['GIFT'] = 'N';
    // Если скидка 100%, тогда расцениваем товар, как подарок
    if (mb_strpos(strtolower($row['NAME']), 'подарок') !== false || $row['DISCOUNT_PRICE_PERCENT'] == 100) {
        $row['GIFT'] = 'Y';
    }
    if ($row['GIFT'] === 'Y' && ($row['DISCOUNT_PRICE_PERCENT'] == 0 || $arResult['COUPON']['STATUS'] !== 'SUCCESS')) {
        unset($arResult['GRID']['ROWS'][$key]);
        CSaleBasket::Delete($row['ID']);
        $reloadBasket = true;
        break;
    }
    $canBuy = true;
    $product = CCatalogProduct::GetByIDEx($row['PRODUCT_ID']);
    if ($row['CAN_BUY'] === 'Y' && $row['AVAILABLE_QUANTITY'] <= 0 && $product['PRODUCT']['CAN_BUY_ZERO'] !== 'Y') {
        $canBuy = false;
    }
    if ($canBuy === true && $product['PRODUCT']['CAN_BUY_ZERO'] === 'Y') {
        // Разрешаем много покупать - даже если в минус загоним
        $row['AVAILABLE_QUANTITY'] = 10000000;
    } else if (($canBuy === false && $product['PRODUCT']['CAN_BUY_ZERO'] !== 'Y') || $row['AVAILABLE_QUANTITY'] < 0) {
        $row['AVAILABLE_QUANTITY'] = 0;
    }
    // Если товар является электронным и доступен только по ссылке для скачивания
    if ($product['PRODUCT']['TYPE'] == \Bitrix\Catalog\ProductTable::TYPE_OFFER && !isset($product['PROPERTIES']['DOWNLOAD_LINK'])) {
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
    // Не разрешаем покупать цифровые товары больше, чем 1 шт
    if ($product['PROPERTIES']['DOWNLOAD_LINK']['VALUE']) {
        if ($row['QUANTITY'] > 1) {
            CSaleBasket::Update($row['ID'], ['QUANTITY' => 1]);
            $reloadBasket = true;
        }
        $canBuy = true;
        $row['AVAILABLE_QUANTITY'] = 1;
    }
    if (($canBuy === false || $row['AVAILABLE_QUANTITY'] == 0)) {
        CSaleBasket::Delete($row['ID']);
        $reloadBasket = true;
    }
    // Если уже понятно, что надо будет обновить страницу
    // Тогда дальше товары не обрабатываем
    // Но и цикл не завершаем, что удалить все ненужные товары из коризны
    if ($reloadBasket === true) {
        continue;
    }
    // Свойства товаров
    $row['PROPERTIES'] = $product['PROPERTIES'];
    if ($arResult['HAS_OFFLINE_PRODUCT'] === 'N') {
        $arResult['HAS_OFFLINE_PRODUCT'] = $row['PROPERTIES']['CHANNEL_SALE']['VALUE_XML_ID'] === \Native\App\Catalog\Product::TYPE_RETAIL ? 'Y' : 'N';
    }
    // Если подарок, обнулим все цены и суммы, чтобы они визуально не учитывались
    if ($row['GIFT'] === 'Y') {
        $giftListAmount += $row['DISCOUNT_PRICE'];
        $row['PRICE'] = 0;
        $row['FULL_PRICE'] = 0;
        $row['SUM_VALUE'] = 0;
        $row['SUM_FULL_PRICE'] = 0;
    }
    $row['PRICE'] = [
        'VALUE' => $row['PRICE'],
        'WITHOUT_CURRENCY' => CCurrencyLang::CurrencyFormat($row['PRICE'], $row['CURRENCY'], false),
        //'WITH_CURRENCY' => $row['PRICE_FORMATED'],
        'BASE' => [
            'VALUE' => $row['FULL_PRICE'],
            'WITHOUT_CURRENCY' => CCurrencyLang::CurrencyFormat($row['FULL_PRICE'], $row['CURRENCY'], false),
            //'WITH_CURRENCY' => $row['FULL_PRICE_FORMATED'],
        ],
        'DISCOUNT' => [
            'VALUE' => $row['DISCOUNT_PRICE'],
            'WITHOUT_CURRENCY' => CCurrencyLang::CurrencyFormat($row['DISCOUNT_PRICE'], $row['CURRENCY'], false),
            //'WITH_CURRENCY' => $row['DISCOUNT_PRICE_FORMATED'],
            'PERCENT' => [
                'VALUE' => $row['DISCOUNT_PRICE_PERCENT'],
                'FORMATTED' => $row['DISCOUNT_PRICE_PERCENT_FORMATED'],
            ]
        ],
    ];
    $row['AMOUNT'] = [
        'VALUE' => $row['SUM_VALUE'],
        'WITHOUT_CURRENCY' => CCurrencyLang::CurrencyFormat($row['SUM_VALUE'], $row['CURRENCY'], false),
        //'WITH_CURRENCY' => $row['SUM'],
        'BASE' => [
            'VALUE' => $row['SUM_FULL_PRICE'],
            'WITHOUT_CURRENCY' => CCurrencyLang::CurrencyFormat($row['SUM_FULL_PRICE'], $row['CURRENCY'], false),
            //'WITH_CURRENCY' => $row['SUM_FULL_PRICE_FORMATED'],
        ]
    ];
    unset(
        $row['PRICE_FORMATED'],
        $row['DISCOUNT_PRICE'],
        $row['DISCOUNT_PRICE_FORMATED'],
        $row['DISCOUNT_PRICE_PERCENT'],
        $row['DISCOUNT_PRICE_PERCENT_FORMATED'],
        $row['FULL_PRICE'],
        $row['FULL_PRICE_FORMATED'],
        $row['SUM'],
        $row['SUM_VALUE'],
        $row['SUM_FULL_PRICE'],
        $row['SUM_FULL_PRICE_FORMATED']
    );
    // Для js-обработки
    $arResult['JS']['PRODUCT']['LIST'][$row['ID']] = [
        'QUANTITY' => [
            'VALUE' => $row['QUANTITY'],
            'MAX' => $row['AVAILABLE_QUANTITY'],
            'MIN' => 0,
            'RATIO' => $row['MEASURE_RATIO'],
        ],
        'PRICE' => [
            'VALUE' => $row['PRICE']['VALUE'],
            'BASE' => [
                'VALUE' => $row['PRICE']['BASE']['VALUE'],
            ],
        ],
        'AMOUNT' => [
            'VALUE' => $row['AMOUNT']['VALUE'],
            'BASE' => [
                'VALUE' => $row['AMOUNT']['BASE']['VALUE'],
            ]
        ],
        'GIFT' => $row['GIFT'],
        //'EXCLUDE_FROM_DISCOUNT' => $row['PROPERTIES']['EXCLUDE_FROM_DISCOUNT']['VALUE_XML_ID'],
        'IS_OFFLINE' => $row['PROPERTIES']['CHANNEL_SALE']['VALUE_XML_ID'] === \Native\App\Catalog\Product::TYPE_RETAIL ? 'Y' : 'N',
    ];
}

if ($reloadBasket) LocalRedirect($_SERVER['REQUEST_URI']);

\Bitrix\Main\UI\Extension::load('ui.notification'); // для нотификаций

// Аторизация через социальные сервисы
if (!$USER->IsAuthorized() && Bitrix\Main\Loader::IncludeModule('socialservices')) {
    $arResult['LOGIN_BY_SOCIAL']['AUTH_URL'] = '/login/';
    $arResult['LOGIN_BY_SOCIAL']['SERVICES'] = false;
    $arResult['LOGIN_BY_SOCIAL']['CURRENT_SERVICE'] = false;
    $oAuthManager = new CSocServAuthManager();
    $arServices = $oAuthManager->GetActiveAuthServices([]);
    if (!empty($arServices)) {
        $arResult['LOGIN_BY_SOCIAL']['SERVICES'] = $arServices;
        if (
            isset($_REQUEST['auth_service_id']) &&
            $_REQUEST['auth_service_id'] <> '' &&
            isset($arResult['LOGIN_BY_SOCIAL']['SERVICES'][$_REQUEST['auth_service_id']])
        ) {
            $arResult['LOGIN_BY_SOCIAL']['CURRENT_SERVICE'] = $_REQUEST['auth_service_id'];
            if (isset($_REQUEST['auth_service_error']) && $_REQUEST['auth_service_error'] <> '') {
                $arResult['LOGIN_BY_SOCIAL']['ERROR_MESSAGE'] = $oAuthManager->GetError($arResult['LOGIN_BY_SOCIAL']['CURRENT_SERVICE'], $_REQUEST['auth_service_error']);
            } elseif (!$oAuthManager->Authorize($_REQUEST['auth_service_id'])) {
                if ($ex = $APPLICATION->GetException()) {
                    $arResult['LOGIN_BY_SOCIAL']['ERROR_MESSAGE'] = $ex->GetString();
                }
            }
        }
    }
}

$signer = new \Bitrix\Main\Security\Sign\Signer;
$arResult['signedParamsString'] = $signer->sign(base64_encode(serialize($arParams)), 'sale.basket.basket');

// Преобразование данных

$arResult['AMOUNT'] = [
    'VALUE' => $arResult['allSum'],
    //'WITHOUT_CURRENCY' => CCurrencyLang::CurrencyFormat($arResult['allSum'], $arResult['CURRENCY'], false),
    'WITH_CURRENCY' => $arResult['allSum_FORMATED'],
    'BASE' => [
        'VALUE' => str_replace(' ', '', CCurrencyLang::CurrencyFormat(str_replace(' ', '', $arResult['PRICE_WITHOUT_DISCOUNT']), $arResult['CURRENCY'], false)),
        //'WITHOUT_CURRENCY' => CCurrencyLang::CurrencyFormat(str_replace(' ', '', $arResult['PRICE_WITHOUT_DISCOUNT']), $arResult['CURRENCY'], false),
        'WITH_CURRENCY' => $arResult['PRICE_WITHOUT_DISCOUNT'],
    ]
];

// Если имеются товары, которые были исключены из стандартных скидок - 3%, 5%, 7%, 20%
// Тогда вычтем их сумму из базовой суммы корзины - так как именно от нее рассчитываются скидки
// Добавляем исключенные товары в список товаров
/*if (count($excludedProducts) > 0) {
    foreach ($excludedProducts as $product) {
        if (!isset($arResult['GRID']['ROWS'][$product['ID']])) {
            $arResult['GRID']['ROWS'][$product['ID']] = $product;
        } else {
            $arResult['GRID']['ROWS'][] = $product;
        }

        // Добавим сумму товара в общую {визуальную} сумму корзины
        $arResult['AMOUNT']['VALUE'] += $product['AMOUNT']['VALUE'];
    }

    // Обновим общую {визуальную} сумму корзины
    $arResult['AMOUNT']['WITH_CURRENCY'] = CCurrencyLang::CurrencyFormat($arResult['AMOUNT']['VALUE'], $arResult['CURRENCY'], true);

    ksort($arResult['GRID']['ROWS']);
    unset($excludedProducts, $product);
}*/

if (count($arResult['GRID']['ROWS']) > 0) {
    $arResult['EMPTY_BASKET'] = false;
}

// В корзине имеются подарки, тогда вычтем их стоимость из общей суммы корзины
if ($giftListAmount > 0) {
    $arResult['AMOUNT']['BASE']['VALUE'] -= $giftListAmount;
    //$arResult['AMOUNT']['BASE']['WITHOUT_CURRENCY'] = CCurrencyLang::CurrencyFormat($arResult['AMOUNT']['BASE']['VALUE'], $arResult['CURRENCY'], false);
    $arResult['AMOUNT']['BASE']['WITH_CURRENCY'] = CCurrencyLang::CurrencyFormat($arResult['AMOUNT']['BASE']['VALUE'], $arResult['CURRENCY'], true);
}

$arResult['CURRENCY'] = [
    'VALUE' => $arResult['CURRENCY'],
    'FORMATTED' => 'руб.'
];

unset(
    $arResult['CURRENCIES'],
    $arResult['allSum'],
    $arResult['allSum_FORMATED'],
    $arResult['PRICE_WITHOUT_DISCOUNT']
);

// Скидки для информера
$discountList = [
    1000 => [
        'PERCENT' => [
            'VALUE' => '3',
            'FORMATTED' => '3%',
        ]
    ],
    3000 => [
        'PERCENT' => [
            'VALUE' => '5',
            'FORMATTED' => '5%',
        ]
    ],
    10000 => [
        'PERCENT' => [
            'VALUE' => '7',
            'FORMATTED' => '7%',
        ]
    ],
    20000 => [
        'PERCENT' => [
            'VALUE' => '20',
            'FORMATTED' => '20%',
        ]
    ],
];
$basketAmountBase =& $arResult['AMOUNT']['BASE']['VALUE'];
$discounts = [];

$lastDiscountAmount = array_key_last($discountList);
$arResult['DISCOUNT']['LAST']['VALUE'] = $lastDiscountAmount;

if ($basketAmountBase >= $lastDiscountAmount) {
    $arResult['DISCOUNT']['NEXT']['VALUE'] = 0;

    $arResult['DISCOUNT']['CURRENT'] = $discountList[$lastDiscountAmount];
    $arResult['DISCOUNT']['CURRENT']['VALUE'] = $lastDiscountAmount;

    unset($discountList[$lastDiscountAmount]);

    $lastDiscountAmount = array_key_last($discountList);
    $arResult['DISCOUNT']['PREVIOUS'] = $discountList[$lastDiscountAmount];
    $arResult['DISCOUNT']['PREVIOUS']['VALUE'] = $lastDiscountAmount;

} else {
    $arResult['DISCOUNT']['PREVIOUS']['VALUE'] = 0;
    $arResult['DISCOUNT']['CURRENT']['VALUE'] = 0;
    $arResult['DISCOUNT']['NEXT']['VALUE'] = 0;

    foreach ($discountList as $amount => $ar) {
        $discounts[$amount] = $ar;
        if ($amount > $basketAmountBase) {
            break;
        }
    }
    krsort($discounts);
    $counter = 1;
    foreach ($discounts as $amount => $ar) {
        switch ($counter) {
            case 1:
                $code = 'NEXT';
                break;
            case 2:
                $code = 'CURRENT';
                break;
            case 3:
                $code = 'PREVIOUS';
                break;
        }
        $arResult['DISCOUNT'][$code] = $ar;
        $arResult['DISCOUNT'][$code]['VALUE'] = $amount;
        if ($counter === 3) break;
        $counter++;
    }
}

// Для js-обработки
$arResult['JS']['PRODUCT']['LAST_UPDATED'] = [
    'ROW' => false,
    'QUANTITY' => false,
    //'EXCLUDE_FROM_DISCOUNTNTITY' => false,
];
$arResult['JS']['PRODUCT']['LAST_DELETED'] = [
    'ROW' => false,
    //'EXCLUDE_FROM_DISCOUNT' => false,
];

$arResult['JS']['BASKET'] = [
    'AMOUNT' => $arResult['AMOUNT'],
    'CURRENCY' => $arResult['CURRENCY'],
    'DISCOUNT' => $arResult['DISCOUNT'],
];

$arResult['JS']['PARAM'] = [
    'signedParamsString' => $arResult['signedParamsString'],
    'url' => [
        'ajax' => $this->__folder . '/ajax.php',
        'order' => $arParams['PATH_TO_ORDER'],
    ],
    'hasOfflineProduct' => $arResult['HAS_OFFLINE_PRODUCT'],
];

$arResult['JS']['COUPON']['VALUE'] = isset($arResult['COUPON']['COUPON']) ? $arResult['COUPON']['COUPON'] : '';

// Доработка корзины
// Что-то пока данный функционал не используется - отключил его - 28.05.20
/*if ($USER->GetID() == 14 || $USER->GetID() == 91523) {
    require 'basketClass.php';
    $basket = new BasketResultModifier($arResult);
    $basket->checkRules();
    $arResult = $basket->getResult();
}*/

unset(
    $arResult['GRID']['HEADERS'],
    $arResult['ITEMS'],
    $arResult['ShowReady'],
    $arResult['ShowDelay'],
    $arResult['ShowSubscribe'],
    $arResult['ShowNotAvail']
);
