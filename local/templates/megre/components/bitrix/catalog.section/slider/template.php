<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 *
 *  _________________________________________________________________________
 * |    Attention!
 * |    The following comments are for system use
 * |    and are required for the component to work correctly in ajax mode:
 * |    <!-- items-container -->
 * |    <!-- pagination-container -->
 * |    <!-- component-end -->
 */

$this->setFrameMode(true);


//echo "<pre>"; print_r($arResult["ITEMS"]); echo "</pre>";
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
	"MESS_BTN_BUY" => "Купить",
	"MESS_BTN_DETAIL" => "Подробнее",
	"MESS_BTN_COMPARE" => "",
	"MESS_BTN_SUBSCRIBE" => "Подписаться",
	"MESS_BTN_ADD_TO_BASKET" => "В корзину",
	"MESS_NOT_AVAILABLE" => "Нет в наличии"
];
?>
<section class="product-more">
    <div class="container">
        <div class="block-title"><? if ($arParams["TITLE"]) echo $arParams["TITLE"]; else "Товары из статьи"; ?></div>
        <div class="swiper">
            <div class="product-more__nav">
                <div class="swiper-arrow-prev"></div>
                <div class="swiper-arrow-next"></div>
            </div>
            <div class="swiper-wrapper">
				<? foreach ($arResult["ITEMS"] as $ITEM) { ?>

                    <div class="swiper-slide">
						<? $APPLICATION->IncludeComponent(
							'bitrix:catalog.item',
							'product.preview',
							[
								'RESULT' => [
									'ITEM' => $ITEM,
									'AREA_ID' => $this->GetEditAreaId($ITEM['ID']),
								],
								'PARAMS' => $generalParams + ['SKU_PROPS' => $arResult['SKU_PROPS'][$ITEM['IBLOCK_ID']]],
							],
							$component,
							['HIDE_ICONS' => 'N']
						);
						?>

						<? /*?><article data-id="<?=$ITEM["ID"]?>" class="product-preview-wrapper">
							<a href="<?=$ITEM["DETAIL_PAGE_URL"]?>">
								<div class="product-image">
									<img src="<?=$ITEM["DETAIL_PICTURE"]["SRC"]?>"
										 alt="<?=$ITEM["DETAIL_PICTURE"]["ALT"]?>">
								</div>
								<div class="product-title"><?=$ITEM["NAME"]?></div>
								<div class="product-price-wrapper">
									<div class="product-current-price"><?=$ITEM["ITEM_PRICES"][0]["PRINT_RATIO_PRICE"]?></div>
									<?if($ITEM["ITEM_PRICES"][0]["PERCENT"]){?>
										<div class="product-old-price"><?=$ITEM["ITEM_PRICES"][0]["PRINT_RATIO_BASE_PRICE"]?></div>
									<?}?>
								</div>

							</a>
							<div class="product-button-wrapper">
								<a class="product-button" data-controller="addToBasket" data-id="<?=$ITEM["ID"]?>" href="javascript:void(0)">В
									корзину</a>
							</div>
							<div class="product-added-wrapper">
								<div>Добавлено</div>
								<div>
									<a data-controller="changeQuantity" data-direction="reduce" data-id="<?=$ITEM["ID"]?>" href="javascript:void(0)"><i
											class="fas fa-angle-left"></i></a>
									<span class="quantity" data-id="<?=$ITEM["ID"]?>">1</span>
									<a data-controller="changeQuantity" data-direction="increase" data-id="<?=$ITEM["ID"]?>" href="javascript:void(0)"><i
											class="fas fa-angle-right"></i></a>
								</div>
							</div>
							<div class="product-add-to-favorites-button-wrapper">
								<a data-controller="addToFavorites" data-id="<?=$ITEM["ID"]?>" href="javascript:void(0)"><i class="heart"></i><i
										class="heart-filled"></i></a>
							</div>
							<?if($ITEM["PROPERTIES"]["SALELEADER"]["VALUE"]){?>
								<div class="product-badge-list-wrapper">
									<div class="product-badge" data-code="HIT">Бестселлеры</div>
								</div>
							<?}?>
						</article><?*/ ?>
                    </div>
				<? } ?>

            </div>
        </div>
    </div>
</section>
