<?php
/*
 * Изменено: 06 декабря 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @global CMain $APPLICATION
 * @var array    $arParams
 * @var array    $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
$GLOBALS['SHOWCASE_PRODUCTS_BESTSELLERS_COUNT'] = null;
$GLOBALS['SHOWCASE_PRODUCTS_NEW_COUNT'] = null;
$GLOBALS['productsBestsellersComponentFilter'] = ['!PROPERTY_SALELEADER' => false];
$GLOBALS['productsNewComponentFilter'] = ['!PROPERTY_NEWPRODUCT' => false];
?>
	<div class="showcase-wrapper">
		<div class="showcase-wrapper-header my-5">
			<h3 class="showcase-switcher" data-code="bestsellers">Бестселлеры</h3>
			<h3 class="showcase-switcher" data-code="new">Новинки</h3>
		</div>
		<div class="showcase-products-wrapper">
			<div id="showcase-bestsellers" class="showcase-slider-wrapper">
				<?php $APPLICATION->IncludeComponent(
						"bitrix:catalog.section",
						"products.bestsellers",
						[
								"COMPONENT_HEADER_CONTENT"        => $arResult["COMPONENT_HEADER_CONTENT"],
								"ACTION_VARIABLE"                 => "action",
								"ADD_PICT_PROP"                   => "-",
								"ADD_PROPERTIES_TO_BASKET"        => "Y",
								"ADD_SECTIONS_CHAIN"              => "N",
								"ADD_TO_BASKET_ACTION"            => "ADD",
								"AJAX_MODE"                       => "N",
								"AJAX_OPTION_ADDITIONAL"          => "",
								"AJAX_OPTION_HISTORY"             => "N",
								"AJAX_OPTION_JUMP"                => "N",
								"AJAX_OPTION_STYLE"               => "Y",
								"BACKGROUND_IMAGE"                => "-",
								"BASKET_URL"                      => "/cart/",
								"BRAND_PROPERTY"                  => "-",
								"BROWSER_TITLE"                   => "-",
								"CACHE_FILTER"                    => "Y",
								"CACHE_GROUPS"                    => "N",
								"CACHE_TIME"                      => "36000000",
								"CACHE_TYPE"                      => "A",
								"COMPATIBLE_MODE"                 => "N",
								"COMPOSITE_FRAME_MODE"            => "A",
								"COMPOSITE_FRAME_TYPE"            => "AUTO",
								"CONVERT_CURRENCY"                => "N",
								"CUSTOM_FILTER"                   => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
								"DATA_LAYER_NAME"                 => "dataLayer",
								"DETAIL_URL"                      => "/catalog/#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
								"DISABLE_INIT_JS_IN_COMPONENT"    => "N",
								"DISCOUNT_PERCENT_POSITION"       => "bottom-right",
								"DISPLAY_BOTTOM_PAGER"            => "N",
								"DISPLAY_COMPARE"                 => "N",
								"DISPLAY_TOP_PAGER"               => "N",
								"ELEMENT_SORT_FIELD"              => "sort",
								"ELEMENT_SORT_FIELD2"             => "timestamp_x",
								"ELEMENT_SORT_ORDER"              => "asc",
								"ELEMENT_SORT_ORDER2"             => "desc",
								"ENLARGE_PRODUCT"                 => "STRICT",
								"FILTER_NAME"                     => 'productsBestsellersComponentFilter',
								"HIDE_NOT_AVAILABLE"              => "N",
								"HIDE_NOT_AVAILABLE_OFFERS"       => "N",
								"IBLOCK_ID"                       => "37",
								"IBLOCK_TYPE"                     => "1c_catalog",
								"INCLUDE_SUBSECTIONS"             => "Y",
								"LABEL_PROP"                      => "",
								"LABEL_PROP_MOBILE"               => "",
								"LABEL_PROP_POSITION"             => "top-left",
								"LAZY_LOAD"                       => "N",
								"LINE_ELEMENT_COUNT"              => "4",
								"LOAD_ON_SCROLL"                  => "N",
								"MESSAGE_404"                     => "",
								"MESS_BTN_ADD_TO_BASKET"          => "В корзину",
								"MESS_BTN_BUY"                    => "Купить",
								"MESS_BTN_DETAIL"                 => "Подробнее",
								"MESS_BTN_LAZY_LOAD"              => "Показать ещё",
								"MESS_BTN_SUBSCRIBE"              => "Подписаться",
								"MESS_NOT_AVAILABLE"              => "Нет в наличии",
								"META_DESCRIPTION"                => "-",
								"META_KEYWORDS"                   => "-",
								"OFFERS_FIELD_CODE"               => [
										0 => "",
										1 => "",
								],
								"OFFERS_LIMIT"                    => "5",
								"OFFERS_SORT_FIELD"               => "sort",
								"OFFERS_SORT_FIELD2"              => "id",
								"OFFERS_SORT_ORDER"               => "asc",
								"OFFERS_SORT_ORDER2"              => "desc",
								"PAGER_BASE_LINK_ENABLE"          => "N",
								"PAGER_DESC_NUMBERING"            => "N",
								"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
								"PAGER_SHOW_ALL"                  => "N",
								"PAGER_SHOW_ALWAYS"               => "N",
								"PAGER_TEMPLATE"                  => ".default",
								"PAGER_TITLE"                     => "Товары",
								"PAGE_ELEMENT_COUNT"              => "166",
								"PARTIAL_PRODUCT_PROPERTIES"      => "N",
								"PRICE_CODE"                      => [
										0 => "Розница",
								],
								"PRICE_VAT_INCLUDE"               => "Y",
								"PRODUCT_BLOCKS_ORDER"            => "price,props,sku,quantityLimit,quantity,buttons",
								"PRODUCT_DISPLAY_MODE"            => "N",
								"PRODUCT_ID_VARIABLE"             => "id",
								"PRODUCT_PROPS_VARIABLE"          => "prop",
								"PRODUCT_QUANTITY_VARIABLE"       => "quantity",
								"PRODUCT_ROW_VARIANTS"            => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
								"PRODUCT_SUBSCRIPTION"            => "Y",
								"PROPERTY_CODE_MOBILE"            => "",
								"RCM_PROD_ID"                     => $_REQUEST["PRODUCT_ID"],
								"RCM_TYPE"                        => "personal",
								"SECTION_CODE"                    => "",
								"SECTION_CODE_PATH"               => "",
								"SECTION_ID"                      => $_REQUEST["SECTION_ID"],
								"SECTION_ID_VARIABLE"             => "",
								"SECTION_URL"                     => "/catalog/#SECTION_CODE_PATH#/",
								"SECTION_USER_FIELDS"             => [
										0 => "",
										1 => "",
								],
								"SEF_MODE"                        => "N",
								"SEF_RULE"                        => "",
								"SET_BROWSER_TITLE"               => "N",
								"SET_LAST_MODIFIED"               => "N",
								"SET_META_DESCRIPTION"            => "N",
								"SET_META_KEYWORDS"               => "N",
								"SET_STATUS_404"                  => "N",
								"SET_TITLE"                       => "N",
								"SHOW_404"                        => "N",
								"SHOW_ALL_WO_SECTION"             => "N",
								"SHOW_CLOSE_POPUP"                => "N",
								"SHOW_DISCOUNT_PERCENT"           => "Y",
								"SHOW_FROM_SECTION"               => "N",
								"SHOW_MAX_QUANTITY"               => "N",
								"SHOW_OLD_PRICE"                  => "Y",
								"SHOW_PRICE_COUNT"                => "1",
								"SHOW_SLIDER"                     => "Y",
								"SLIDER_INTERVAL"                 => "3000",
								"SLIDER_PROGRESS"                 => "N",
								"TEMPLATE_THEME"                  => "blue",
								"USE_ENHANCED_ECOMMERCE"          => "N",
								"USE_MAIN_ELEMENT_SECTION"        => "N",
								"USE_PRICE_COUNT"                 => "N",
								"USE_PRODUCT_QUANTITY"            => "N",
								"COMPONENT_TEMPLATE"              => "products.bestsellers",
						],
						false
				) ?>
			</div>
			<div id="showcase-new" class="showcase-slider-wrapper">
				<?php $APPLICATION->IncludeComponent(
						"bitrix:catalog.section",
						"products.new",
						[
								"COMPONENT_HEADER_CONTENT"        => $arResult['COMPONENT_HEADER_CONTENT'],
								"ACTION_VARIABLE"                 => "action",
								"ADD_PICT_PROP"                   => "-",
								"ADD_PROPERTIES_TO_BASKET"        => "Y",
								"ADD_SECTIONS_CHAIN"              => "N",
								"ADD_TO_BASKET_ACTION"            => "ADD",
								"AJAX_MODE"                       => "N",
								"AJAX_OPTION_ADDITIONAL"          => "",
								"AJAX_OPTION_HISTORY"             => "N",
								"AJAX_OPTION_JUMP"                => "N",
								"AJAX_OPTION_STYLE"               => "Y",
								"BACKGROUND_IMAGE"                => "-",
								"BASKET_URL"                      => "/cart/",
								"BRAND_PROPERTY"                  => "-",
								"BROWSER_TITLE"                   => "-",
								"CACHE_FILTER"                    => "Y",
								"CACHE_GROUPS"                    => "N",
								"CACHE_TIME"                      => "36000000",
								"CACHE_TYPE"                      => "A",
								"COMPATIBLE_MODE"                 => "N",
								"COMPOSITE_FRAME_MODE"            => "A",
								"COMPOSITE_FRAME_TYPE"            => "AUTO",
								"CONVERT_CURRENCY"                => "N",
								"CUSTOM_FILTER"                   => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
								"DATA_LAYER_NAME"                 => "dataLayer",
								"DETAIL_URL"                      => "/catalog/#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
								"DISABLE_INIT_JS_IN_COMPONENT"    => "N",
								"DISCOUNT_PERCENT_POSITION"       => "bottom-right",
								"DISPLAY_BOTTOM_PAGER"            => "N",
								"DISPLAY_COMPARE"                 => "N",
								"DISPLAY_TOP_PAGER"               => "N",
								"ELEMENT_SORT_FIELD"              => "sort",
								"ELEMENT_SORT_FIELD2"             => "timestamp_x",
								"ELEMENT_SORT_ORDER"              => "asc",
								"ELEMENT_SORT_ORDER2"             => "desc",
								"ENLARGE_PRODUCT"                 => "STRICT",
								"FILTER_NAME"                     => 'productsNewComponentFilter',
								"HIDE_NOT_AVAILABLE"              => "N",
								"HIDE_NOT_AVAILABLE_OFFERS"       => "N",
								"IBLOCK_ID"                       => "37",
								"IBLOCK_TYPE"                     => "1c_catalog",
								"INCLUDE_SUBSECTIONS"             => "Y",
								"LABEL_PROP"                      => [
								],
								"LABEL_PROP_MOBILE"               => "",
								"LABEL_PROP_POSITION"             => "top-left",
								"LAZY_LOAD"                       => "N",
								"LINE_ELEMENT_COUNT"              => "4",
								"LOAD_ON_SCROLL"                  => "N",
								"MESSAGE_404"                     => "",
								"MESS_BTN_ADD_TO_BASKET"          => "В корзину",
								"MESS_BTN_BUY"                    => "Купить",
								"MESS_BTN_DETAIL"                 => "Подробнее",
								"MESS_BTN_LAZY_LOAD"              => "Показать ещё",
								"MESS_BTN_SUBSCRIBE"              => "Подписаться",
								"MESS_NOT_AVAILABLE"              => "Нет в наличии",
								"META_DESCRIPTION"                => "-",
								"META_KEYWORDS"                   => "-",
								"OFFERS_FIELD_CODE"               => [],
								"OFFERS_LIMIT"                    => "5",
								"OFFERS_SORT_FIELD"               => "sort",
								"OFFERS_SORT_FIELD2"              => "id",
								"OFFERS_SORT_ORDER"               => "asc",
								"OFFERS_SORT_ORDER2"              => "desc",
								"PAGER_BASE_LINK_ENABLE"          => "N",
								"PAGER_DESC_NUMBERING"            => "N",
								"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
								"PAGER_SHOW_ALL"                  => "N",
								"PAGER_SHOW_ALWAYS"               => "N",
								"PAGER_TEMPLATE"                  => ".default",
								"PAGER_TITLE"                     => "Товары",
								"PAGE_ELEMENT_COUNT"              => "16",
								"PARTIAL_PRODUCT_PROPERTIES"      => "N",
								"PRICE_CODE"                      => [
										0 => "Розница",
								],
								"PRICE_VAT_INCLUDE"               => "Y",
								"PRODUCT_BLOCKS_ORDER"            => "price,props,sku,quantityLimit,quantity,buttons",
								"PRODUCT_DISPLAY_MODE"            => "N",
								"PRODUCT_ID_VARIABLE"             => "id",
								"PRODUCT_PROPS_VARIABLE"          => "prop",
								"PRODUCT_QUANTITY_VARIABLE"       => "quantity",
								"PRODUCT_ROW_VARIANTS"            => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
								"PRODUCT_SUBSCRIPTION"            => "Y",
								"PROPERTY_CODE_MOBILE"            => [
								],
								"RCM_PROD_ID"                     => $_REQUEST["PRODUCT_ID"],
								"RCM_TYPE"                        => "personal",
								"SECTION_CODE"                    => "",
								"SECTION_CODE_PATH"               => "",
								"SECTION_ID"                      => $_REQUEST["SECTION_ID"],
								"SECTION_ID_VARIABLE"             => "",
								"SECTION_URL"                     => "/catalog/#SECTION_CODE_PATH#/",
								"SECTION_USER_FIELDS"             => [],
								"SEF_MODE"                        => "N",
								"SEF_RULE"                        => "",
								"SET_BROWSER_TITLE"               => "N",
								"SET_LAST_MODIFIED"               => "N",
								"SET_META_DESCRIPTION"            => "N",
								"SET_META_KEYWORDS"               => "N",
								"SET_STATUS_404"                  => "N",
								"SET_TITLE"                       => "N",
								"SHOW_404"                        => "N",
								"SHOW_ALL_WO_SECTION"             => "N",
								"SHOW_CLOSE_POPUP"                => "N",
								"SHOW_DISCOUNT_PERCENT"           => "Y",
								"SHOW_FROM_SECTION"               => "N",
								"SHOW_MAX_QUANTITY"               => "N",
								"SHOW_OLD_PRICE"                  => "Y",
								"SHOW_PRICE_COUNT"                => "1",
								"SHOW_SLIDER"                     => "Y",
								"SLIDER_INTERVAL"                 => "3000",
								"SLIDER_PROGRESS"                 => "N",
								"TEMPLATE_THEME"                  => "blue",
								"USE_ENHANCED_ECOMMERCE"          => "N",
								"USE_MAIN_ELEMENT_SECTION"        => "N",
								"USE_PRICE_COUNT"                 => "N",
								"USE_PRODUCT_QUANTITY"            => "N",
								"COMPONENT_TEMPLATE"              => "",
						],
						false
				) ?>
			</div>
		</div>
	</div>
<?php
if (
		!$GLOBALS['SHOWCASE_PRODUCTS_BESTSELLERS_COUNT']
		&& !$GLOBALS['SHOWCASE_PRODUCTS_NEW_COUNT']
) {
	?>
	<style>
		.showcase-wrapper {
			display : none;
		}
	</style>
	<?php
} else {
	if (!$GLOBALS['SHOWCASE_PRODUCTS_BESTSELLERS_COUNT']) {
		?>
		<style>
			.showcase-switcher[data-code="bestsellers"],
			#showcase-bestsellers {
				display : none;
			}
		</style>
		<script>
			document.querySelector('.showcase-switcher[data-code="bestsellers"]').remove()
			document.getElementById('showcase-bestsellers').remove()
		</script>
		<?php
	}
	if (!$GLOBALS['SHOWCASE_PRODUCTS_NEW_COUNT']) {
		?>
		<style>
			.showcase-switcher[data-code="new"],
			#showcase-new {
				display : none;
			}
		</style>
		<script>
			document.querySelector('.showcase-switcher[data-code="new"]').remove()
			document.getElementById('showcase-new').remove()
		</script>
		<?php
	}
	?>
	<script>
		ShowcaseComponent.run(<?= CUtil::PhpToJSObject([
				'bestsellers' => [
						'count'           => $GLOBALS['SHOWCASE_PRODUCTS_BESTSELLERS_COUNT'],
						'autoplay'        => false,
						'autoplayTimeout' => 2300,
				],
				'new'         => [
						'count'           => $GLOBALS['SHOWCASE_PRODUCTS_NEW_COUNT'],
						'autoplay'        => false,
						'autoplayTimeout' => 2200,
				],
		])?>)
	</script>
	<?php
}
unset(
		$GLOBALS['SHOWCASE_PRODUCTS_BESTSELLERS_COUNT'],
		$GLOBALS['SHOWCASE_PRODUCTS_NEW_COUNT'],
);