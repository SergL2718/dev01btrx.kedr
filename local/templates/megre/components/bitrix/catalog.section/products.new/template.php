<?php
    /*
     * Изменено: 23 ноября 2021, вторник
     * Автор: Артамонов Денис <software.engineer@internet.ru>
     * copyright (c) 2021
     */

    /**
     * @global CMain         $APPLICATION
     * @var CBitrixComponent $component
     * @var array            $arParams
     * @var array            $arResult
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
        'SHOW_DISCOUNT_PERCENT'        => $arParams['SHOW_DISCOUNT_PERCENT'],
        'PRODUCT_DISPLAY_MODE'         => $arParams['PRODUCT_DISPLAY_MODE'],
        'SHOW_MAX_QUANTITY'            => $arParams['SHOW_MAX_QUANTITY'],
        'RELATIVE_QUANTITY_FACTOR'     => $arParams['RELATIVE_QUANTITY_FACTOR'],
        'MESS_SHOW_MAX_QUANTITY'       => $arParams['~MESS_SHOW_MAX_QUANTITY'],
        'MESS_RELATIVE_QUANTITY_MANY'  => $arParams['~MESS_RELATIVE_QUANTITY_MANY'],
        'MESS_RELATIVE_QUANTITY_FEW'   => $arParams['~MESS_RELATIVE_QUANTITY_FEW'],
        'SHOW_OLD_PRICE'               => $arParams['SHOW_OLD_PRICE'],
        'USE_PRODUCT_QUANTITY'         => $arParams['USE_PRODUCT_QUANTITY'],
        'PRODUCT_QUANTITY_VARIABLE'    => $arParams['PRODUCT_QUANTITY_VARIABLE'],
        'ADD_TO_BASKET_ACTION'         => $arParams['ADD_TO_BASKET_ACTION'],
        'ADD_PROPERTIES_TO_BASKET'     => $arParams['ADD_PROPERTIES_TO_BASKET'],
        'PRODUCT_PROPS_VARIABLE'       => $arParams['PRODUCT_PROPS_VARIABLE'],
        'SHOW_CLOSE_POPUP'             => $arParams['SHOW_CLOSE_POPUP'],
        'DISPLAY_COMPARE'              => $arParams['DISPLAY_COMPARE'],
        'COMPARE_PATH'                 => $arParams['COMPARE_PATH'],
        'COMPARE_NAME'                 => $arParams['COMPARE_NAME'],
        'PRODUCT_SUBSCRIPTION'         => $arParams['PRODUCT_SUBSCRIPTION'],
        'PRODUCT_BLOCKS_ORDER'         => $arParams['PRODUCT_BLOCKS_ORDER'],
        'LABEL_POSITION_CLASS'         => $labelPositionClass,
        'DISCOUNT_POSITION_CLASS'      => $discountPositionClass,
        'SLIDER_INTERVAL'              => $arParams['SLIDER_INTERVAL'],
        'SLIDER_PROGRESS'              => $arParams['SLIDER_PROGRESS'],
        '~BASKET_URL'                  => $arParams['~BASKET_URL'],
        '~ADD_URL_TEMPLATE'            => $arResult['~ADD_URL_TEMPLATE'],
        '~BUY_URL_TEMPLATE'            => $arResult['~BUY_URL_TEMPLATE'],
        '~COMPARE_URL_TEMPLATE'        => $arResult['~COMPARE_URL_TEMPLATE'],
        '~COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
        'TEMPLATE_THEME'               => $arParams['TEMPLATE_THEME'],
        'USE_ENHANCED_ECOMMERCE'       => $arParams['USE_ENHANCED_ECOMMERCE'],
        'DATA_LAYER_NAME'              => $arParams['DATA_LAYER_NAME'],
        'MESS_BTN_BUY'                 => $arParams['~MESS_BTN_BUY'],
        'MESS_BTN_DETAIL'              => $arParams['~MESS_BTN_DETAIL'],
        'MESS_BTN_COMPARE'             => $arParams['~MESS_BTN_COMPARE'],
        'MESS_BTN_SUBSCRIBE'           => $arParams['~MESS_BTN_SUBSCRIBE'],
        'MESS_BTN_ADD_TO_BASKET'       => $arParams['~MESS_BTN_ADD_TO_BASKET'],
        'MESS_NOT_AVAILABLE'           => $arParams['~MESS_NOT_AVAILABLE'],
        'LAZY_LOAD'                    => 'N',
    ];
?>
<div class="mt-5 mb-4">
    <div class="swiper slider-newest swiper-arrow-center swiper-arrow-center_up">
        <div class="swiper-wrapper">
            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <div class="swiper-slide">
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:catalog.item',
                        'product.preview',
                        [
                            'RESULT' => [
                                'ITEM'    => $item,
                                'AREA_ID' => $this->GetEditAreaId($item['ID']),
                            ],
                            'PARAMS' => $generalParams + ['SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']]],
                        ],
                        $component,
                        ['HIDE_ICONS' => 'Y']
                    ) ?>
                </div>
            <?php endforeach ?>
        </div>
        <div class="swiper-arrow-prev"></div>
        <div class="swiper-arrow-next"></div>
        <div class="swiper-pagination"></div>
        <?php if ($arResult['ITEMS_COUNT'] > 8): ?>
            <div class="swiper-arrow-prev swiper-arrow-prev_2"></div>
            <div class="swiper-arrow-next swiper-arrow-next_2"></div>
        <?php endif ?>
        <?php if ($arResult['ITEMS_COUNT'] > 4): ?>
            <div class="swiper-pagination swiper-pagination_2"></div>
        <?php endif ?>
    </div>
    <script>
		const swiperNewest = new Swiper('.slider-newest', {
			loop: false,
			navigation: {
				nextEl: '.slider-newest .swiper-arrow-next',
				prevEl: '.slider-newest .swiper-arrow-prev',
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
					grid: {
						rows: 2,
					},
				},
				767: {
					slidesPerView: '3',
					spaceBetween: 20,
					grid: {
						rows: 2,
					},
				},
				999: {
					slidesPerView: '4',
					spaceBetween: 30,
					grid: {
						rows: 2,
					}
				}
			}
		});
    </script>
</div>
