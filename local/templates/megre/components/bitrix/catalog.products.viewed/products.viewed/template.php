<?php
/*
 * Изменено: 06 декабря 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @global CMain $APPLICATION
 * @var CBitrixComponent $component
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
if (empty($arResult['ITEMS'])) {
	return;
}
$this->setFrameMode(true);
$discountPositionClass = '';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION'])) {
	foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos) {
		$discountPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
	}
}
$labelPositionClass = '';
if (!empty($arParams['LABEL_PROP_POSITION'])) {
	foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos) {
		$labelPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
	}
}
$generalParams = [
	'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
	'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
	'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
	'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
	'MESS_SHOW_MAX_QUANTITY' => $arParams['~MESS_SHOW_MAX_QUANTITY'],
	'MESS_RELATIVE_QUANTITY_MANY' => $arParams['~MESS_RELATIVE_QUANTITY_MANY'],
	'MESS_RELATIVE_QUANTITY_FEW' => $arParams['~MESS_RELATIVE_QUANTITY_FEW'],
	'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
	'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
	'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
	'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
	'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
	'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
	'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'],
	'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
	'COMPARE_PATH' => $arParams['COMPARE_PATH'],
	'COMPARE_NAME' => $arParams['COMPARE_NAME'],
	'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
	'PRODUCT_BLOCKS_ORDER' => $arParams['PRODUCT_BLOCKS_ORDER'],
	'LABEL_POSITION_CLASS' => $labelPositionClass,
	'DISCOUNT_POSITION_CLASS' => $discountPositionClass,
	'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
	'SLIDER_PROGRESS' => $arParams['SLIDER_PROGRESS'],
	'~BASKET_URL' => $arParams['~BASKET_URL'],
	'~ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
	'~BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
	'~COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
	'~COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
	'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
	'MESS_BTN_BUY' => $arParams['~MESS_BTN_BUY'],
	'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
	'MESS_BTN_COMPARE' => $arParams['~MESS_BTN_COMPARE'],
	'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
	'MESS_BTN_ADD_TO_BASKET' => $arParams['~MESS_BTN_ADD_TO_BASKET'],
	'MESS_NOT_AVAILABLE' => $arParams['~MESS_NOT_AVAILABLE'],
];
//echo "<pre>"; print_r($generalParams); echo "</pre>";
//if ($USER->IsAdmin()){echo "<pre>"; print_r($arResult['ITEMS']); echo "</pre>";}
//$generalParams["CUT"] = true;
?>


<div class="product-more">
    <div class="container">
        <div class="block-title">ВЫ НЕДАВНО СМОТРЕЛИ</div>
        <div class="swiper">
            <div class="product-more__nav">
                <div class="swiper-arrow-prev"></div>
                <div class="swiper-arrow-next"></div>
            </div>
            <div class="swiper-wrapper">
				<?php foreach ($arResult['ITEMS'] as $item) {
				if (!$item["NAME"]) continue;
				?>
                <div class="swiper-slide">
					<?$APPLICATION->IncludeComponent(
						'bitrix:catalog.item',
						'product.preview',
						[
							'RESULT' => [
								'ITEM' => $item,
								'AREA_ID' => $this->GetEditAreaId($item['ID']),
							],
							'PARAMS' => $generalParams + ['SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']]],
						],
						$component,
						['HIDE_ICONS' => 'N']
					);
					?>
                </div>
                <?}?>

            </div>
        </div>
    </div>
</div>


<?php
/*
?>
<section class="slider-viewed">
    <h3 class="mb-5 text-center">ВЫ НЕДАВНО СМОТРЕЛИ</h3>
    <div class="swiper swiper-arrow-center swiper-pagination-bottom swiper-arrow-center_top">
        <div class="swiper-wrapper">
            <?php foreach ($arResult['ITEMS'] as $item) {
                if (!$item["NAME"]) continue;
                ?><div class="swiper-slide">
                <?$APPLICATION->IncludeComponent(
                    'bitrix:catalog.item',
                    'product.preview',
                    [
                        'RESULT' => [
                            'ITEM' => $item,
                            'AREA_ID' => $this->GetEditAreaId($item['ID']),
                        ],
                        'PARAMS' => $generalParams + ['SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']]],
                    ],
                    $component,
                    ['HIDE_ICONS' => 'N']
                );
                ?>
                </div>
            <? } ?>
        </div>
        <div class="swiper-arrow-prev"></div>
        <div class="swiper-arrow-next"></div>
        <div class="swiper-pagination"></div>
    </div>
    <script>
        const swiperBuyWith = new Swiper('.slider-viewed .swiper', {
            loop: false,
            lazy: true,
            navigation: {
                nextEl: '.swiper-arrow-next',
                prevEl: '.swiper-arrow-prev',
            },
	        pagination: {
		        el: ".swiper-pagination",
		        dynamicBullets: true,
	        },
            spaceBetween: 30,
            breakpoints: {
                300: {
                    slidesPerView: '2',
                    spaceBetween: 15,
                },
                767: {
                    slidesPerView: '3',
                    spaceBetween: 20,
                },
                999: {
                    slidesPerView: '4',
                    spaceBetween: 30,
                }
            }
        });
    </script>
</section>

