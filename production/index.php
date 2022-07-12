<?
/*
 * Изменено: 09 декабря 2021, четверг
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Вся продукция");
?><? $APPLICATION->IncludeComponent(
	"alexkova.market:catalog",
	"production",
	[
		"ACTION_VARIABLE"                  => "action",
		"ADDITIONAL_DETAIL_INFO"           => "/include/additional_detail_info.php",
		"ADDITIONAL_SKU_PIC_2_SLIDER"      => "N",
		"ADDITIONAL_TAB_SHOW"              => "N",
		"ADD_ELEMENT_CHAIN"                => "N",
		"ADD_PICT_PROP"                    => "-",
		"ADD_PROPERTIES_TO_BASKET"         => "N",
		"ADD_SECTIONS_CHAIN"               => "Y",
		"AJAX_MODE"                        => "N",
		"AJAX_OPTION_ADDITIONAL"           => "",
		"AJAX_OPTION_HISTORY"              => "N",
		"AJAX_OPTION_JUMP"                 => "N",
		"AJAX_OPTION_STYLE"                => "Y",
		"ALSO_BUY_ELEMENT_COUNT"           => "4",
		"ALSO_BUY_MIN_BUYES"               => "1",
		"ALSO_BUY_TITLE"                   => "С этим товаром покупают",
		"ANOUNCE_TRUNCATE_LEN"             => "",
		"ARTICLE_POSITION"                 => "none",
		"BASKET_URL"                       => "/personal/basket/",
		"BESTSALLERS_CNT"                  => "4",
		"BESTSALLERS_SORT"                 => "200",
		"BESTSALLERS_TITLE"                => "Лидеры продаж",
		"BESTSALLERS_WERE_SHOW"            => "bottom",
		"BIG_DATA_CNT"                     => "4",
		"BIG_DATA_RCM_TYPE"                => "bestsell",
		"BIG_DATA_TITLE"                   => "Персональные рекомендации",
		"CACHE_FILTER"                     => "N",
		"CACHE_GROUPS"                     => "Y",
		"CACHE_TIME"                       => "3600000",
		"CACHE_TYPE"                       => "A",
		"CATALOG_DEFAULT_SORT"             => "",
		"CATALOG_DEFAULT_SORT_ORDER"       => "desc",
		"CATALOG_VIEW_SHOW"                => "Y",
		"CHANGE_TITLE_SKU"                 => "N",
		"CHECK_DATES"                      => "N",
		"CHILD_MENU_TYPE"                  => "left",
		"COMMON_ADD_TO_BASKET_ACTION"      => "",
		"COMMON_SHOW_CLOSE_POPUP"          => "N",
		"COMPARE_ELEMENT_SORT_FIELD"       => "shows",
		"COMPARE_ELEMENT_SORT_ORDER"       => "asc",
		"COMPARE_FIELD_CODE"               => [
			0 => "NAME",
			1 => "DETAIL_PICTURE",
			2 => "",
		],
		"COMPARE_NAME"                     => "CATALOG_COMPARE_LIST",
		"COMPARE_OFFERS_FIELD_CODE"        => [
			0 => "",
			1 => "",
		],
		"COMPARE_OFFERS_PROPERTY_CODE"     => [
			0 => "",
			1 => "",
		],
		"COMPARE_POSITION"                 => "top left",
		"COMPARE_POSITION_FIXED"           => "Y",
		"COMPARE_PROPERTY_CODE"            => [
			0 => "PRISUTSTVIE_V_INTERNET_MAGAZINE",
			1 => "",
		],
		"COMPARE_SCROLL_UP"                => "Y",
		"CONVERT_CURRENCY"                 => "N",
		"DEFAULT_CATALOG_VIEW"             => "TITLE",
		"DELAY"                            => "N",
		"DETAIL_ADD_DETAIL_TO_SLIDER"      => "Y",
		"DETAIL_ADD_DETAIL_TO_SLIDER_SKU"  => "Y",
		"DETAIL_ADD_TO_BASKET_ACTION"      => [
		],
		"DETAIL_BACKGROUND_IMAGE"          => "-",
		"DETAIL_BLOG_EMAIL_NOTIFY"         => "N",
		"DETAIL_BLOG_URL"                  => "catalog_comments",
		"DETAIL_BLOG_USE"                  => "Y",
		"DETAIL_BRAND_PROP_CODE"           => [
			0 => "",
			1 => "MANUFACTURER",
			2 => "",
		],
		"DETAIL_BRAND_USE"                 => "N",
		"DETAIL_BROWSER_TITLE"             => "-",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"DETAIL_DETAIL_PICTURE_MODE"       => "POPUP",
		"DETAIL_DISPLAY_NAME"              => "Y",
		"DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "H",
		"DETAIL_DISPLAY_SHOW_FILES"        => "Y",
		"DETAIL_DISPLAY_SHOW_VIDEO"        => "Y",
		"DETAIL_FB_USE"                    => "N",
		"DETAIL_META_DESCRIPTION"          => "-",
		"DETAIL_META_KEYWORDS"             => "-",
		"DETAIL_OFFERS_FIELD_CODE"         => [
			0 => "NAME",
			1 => "PREVIEW_TEXT",
			2 => "PREVIEW_PICTURE",
			3 => "DETAIL_TEXT",
			4 => "DETAIL_PICTURE",
			5 => "",
		],
		"DETAIL_OFFERS_PROPERTY_CODE"      => [
			0 => "VES",
			1 => "",
		],
		"DETAIL_PROPERTY_CODE"             => [
			0 => "",
			1 => "",
		],
		"DETAIL_SET_CANONICAL_URL"         => "Y",
		"DETAIL_SHOW_BASIS_PRICE"          => "Y",
		"DETAIL_SHOW_MAX_QUANTITY"         => "N",
		"DETAIL_STRICT_SECTION_CHECK"      => "N",
		"DETAIL_USE_COMMENTS"              => "N",
		"DETAIL_USE_VOTE_RATING"           => "N",
		"DETAIL_VK_API_ID"                 => "API_ID",
		"DETAIL_VK_USE"                    => "N",
		"DETAIL_VOTE_DISPLAY_AS_RATING"    => "rating",
		"DISPLAY_BOTTOM_PAGER"             => "Y",
		"DISPLAY_ELEMENT_COUNT"            => "Y",
		"DISPLAY_ELEMENT_SELECT_BOX"       => "N",
		"DISPLAY_SHOW_FILES_TYPE"          => "load",
		"DISPLAY_TOP_PAGER"                => "N",
		"ELEMENT_SORT_FIELD"               => "sort",
		"ELEMENT_SORT_FIELD2"              => "timestamp_x",
		"ELEMENT_SORT_ORDER"               => "asc",
		"ELEMENT_SORT_ORDER2"              => "desc",
		"FIELDS"                           => [
			0 => "",
			1 => "",
		],
		"FILTER_FIELD_CODE"                => [
			0 => "",
			1 => "",
		],
		"FILTER_NAME"                      => "",
		"FILTER_OFFERS_FIELD_CODE"         => [
			0 => "",
			1 => "",
		],
		"FILTER_OFFERS_PROPERTY_CODE"      => [
			0 => "",
			1 => "",
		],
		"FILTER_PRICE_CODE"                => [
		],
		"FILTER_PROPERTY_CODE"             => [
			0 => "",
			1 => "",
		],
		"FILTER_SKU_PHOTO"                 => "N",
		"FILTER_VIEW_MODE"                 => "VERTICAL",
		"FORUM_ID"                         => "",
		"GIFTS_HIDE_NOT_AVAILABLE"         => "N",
		"GROUP_PRICE_COUNT"                => "count",
		"HANDLERS"                         => [
			0 => "lj",
			1 => "twitter",
			2 => "vk",
			3 => "facebook",
			4 => "mailru",
			5 => "delicious",
		],
		"HIDE_FILTER_MOBILE"               => "Y",
		"HIDE_NOT_AVAILABLE"               => "N",
		"HIDE_OFFERS_LIST"                 => "N",
		"HIDE_PREVIEW_PROPS_INLIST"        => "Y",
		"HOVER_MENU_COL_LG"                => isset($arLeftMenu["HOVER_MENU_COL_LG"]) ? $arLeftMenu["HOVER_MENU_COL_LG"] : "2",
		"HOVER_MENU_COL_MD"                => isset($arLeftMenu["HOVER_MENU_COL_MD"]) ? $arLeftMenu["HOVER_MENU_COL_MD"] : "2",
		"HOVER_MENU_COL_SM"                => "1",
		"HOVER_MENU_COL_XS"                => "1",
		"HOVER_TEMPLATE"                   => "classic",
		"IBLOCK_ID"                        => "39",
		"IBLOCK_TYPE"                      => "1c_catalog",
		"INCLUDE_SUBSECTIONS"              => "Y",
		"IN_STOCK"                         => "В наличии",
		"LABEL_PROP"                       => "-",
		"LEFT_MENU_TEMPLATE"               => "left_hover",
		"LINE_ELEMENT_COUNT"               => "3",
		"LINK_ELEMENTS_URL"                => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"LINK_IBLOCK_ID"                   => "",
		"LINK_IBLOCK_TYPE"                 => "",
		"LINK_PROPERTY_SID"                => "CML2_LINK",
		"LIST_BROWSER_TITLE"               => "-",
		"LIST_META_DESCRIPTION"            => "-",
		"LIST_META_KEYWORDS"               => "-",
		"LIST_OFFERS_FIELD_CODE"           => [
			0 => "",
			1 => "",
		],
		"LIST_OFFERS_LIMIT"                => "5",
		"LIST_OFFERS_PROPERTY_CODE"        => [
			0 => "",
			1 => "",
		],
		"LIST_PROPERTY_CODE"               => [
			0 => "",
			1 => "METAL_DETECTOR",
			2 => "INSULATION",
			3 => "SUVENIR_NAZNACH",
			4 => "WIDTH",
			5 => "LENGHT",
			6 => "SIZE",
			7 => "HEIGHT",
			8 => "RECOMMEND",
			9 => "",
		],
		"MAIN_TITLE"                       => "Наличие на складах",
		"MAX_LEVEL"                        => "3",
		"MESSAGES_PER_PAGE"                => "10",
		"MESSAGE_404"                      => "",
		"MESS_BTN_ADD_TO_BASKET"           => "В корзину",
		"MESS_BTN_BUY"                     => "Купить",
		"MESS_BTN_COMPARE"                 => "Сравнение",
		"MESS_BTN_DETAIL"                  => "Подробнее",
		"MESS_BTN_REQUEST"                 => "Оставить заявку",
		"MESS_NOT_AVAILABLE"               => "Нет в наличии",
		"MIN_AMOUNT"                       => "10",
		"NOT_IN_STOCK"                     => "Нет в наличии",
		"NO_TABS"                          => "N",
		"NO_WORD_LOGIC"                    => "N",
		"OFFERS_CART_PROPERTIES"           => [
		],
		"OFFERS_SORT_FIELD"                => "shows",
		"OFFERS_SORT_FIELD2"               => "shows",
		"OFFERS_SORT_ORDER"                => "asc",
		"OFFERS_SORT_ORDER2"               => "asc",
		"OFFERS_VIEW"                      => "SELECT",
		"OFFER_ADD_PICT_PROP"              => "-",
		"OFFER_PRICE_SHOW_FROM"            => "N",
		"OFFER_TREE_PROPS"                 => [
		],
		"PAGER_BASE_LINK_ENABLE"           => "N",
		"PAGER_DESC_NUMBERING"             => "Y",
		"PAGER_DESC_NUMBERING_CACHE_TIME"  => "3600000",
		"PAGER_SHOW_ALL"                   => "Y",
		"PAGER_SHOW_ALWAYS"                => "N",
		"PAGER_TEMPLATE"                   => "round",
		"PAGER_TITLE"                      => "Товары",
		"PAGE_ELEMENT_COUNT"               => "16",
		"PAGE_ELEMENT_COUNT_LIST"          => [
			0 => "",
			1 => "",
		],
		"PAGE_ELEMENT_COUNT_SHOW"          => "Y",
		"PARTIAL_PRODUCT_PROPERTIES"       => "N",
		"PATH_TO_SMILE"                    => "/bitrix/images/forum/smile/",
		"PICTURE_CATEGARIES"               => isset($arLeftMenu["PICTURE_CATEGARIES"]) ? $arLeftMenu["PICTURE_CATEGARIES"] : "N",
		"PICTURE_SECTION"                  => "N",
		"PICTURE_SECTION_HOVER"            => "N",
		"PREVIEW_DETAIL_PROPERTY_CODE"     => [
			0 => "",
			1 => "COLOR",
			2 => "",
		],
		"PREVIEW_TRUNCATE_LEN"             => "",
		"PRICE_CODE"                       => [
		],
		"PRICE_VAT_INCLUDE"                => "N",
		"PRICE_VAT_SHOW_VALUE"             => "N",
		"PRODUCT_DISPLAY_MODE"             => "N",
		"PRODUCT_ID_VARIABLE"              => "id",
		"PRODUCT_PROPERTIES"               => [
		],
		"PRODUCT_PROPS_VARIABLE"           => "prop",
		"PRODUCT_QUANTITY_VARIABLE"        => "quantity",
		"PROPS_TAB_VIEW"                   => "TABLE",
		"QTY_LESS_GOODS_TEXT"              => "мало",
		"QTY_MANY_GOODS_INT"               => "3",
		"QTY_MANY_GOODS_TEXT"              => "много",
		"QTY_SHOW_TYPE"                    => "NUM",
		"RCM_COUNT_DETAIL"                 => "",
		"RCM_NAME_DETAIL"                  => "",
		"RCM_TYPE_DETAIL"                  => "",
		"RESTART"                          => "N",
		"REVIEW_AJAX_POST"                 => "N",
		"ROOT_MENU_TYPE"                   => "left",
		"SECTIONS_MAIN_VIEW_MODE"          => "LIST",
		"SECTIONS_SHOW_DESCRIPTION"        => "N",
		"SECTIONS_SHOW_PARENT_NAME"        => "Y",
		"SECTIONS_VIEW_MODE"               => "LIST",
		"SECTION_ADD_TO_BASKET_ACTION"     => "BUY",
		"SECTION_BACKGROUND_IMAGE"         => "-",
		"SECTION_COUNT_ELEMENTS"           => "Y",
		"SECTION_ID_VARIABLE"              => "SECTION_CODE",
		"SECTION_TOP_DEPTH"                => "2",
		"SEF_FOLDER"                       => "/production/",
		"SEF_MODE"                         => "Y",
		"SET_LAST_MODIFIED"                => "N",
		"SET_STATUS_404"                   => "N",
		"SET_TITLE"                        => "Y",
		"SHORTEN_URL_KEY"                  => "",
		"SHORTEN_URL_LOGIN"                => "",
		"SHOWS_BIGDATA_DETAIL"             => "N",
		"SHOW_404"                         => "N",
		"SHOW_CATALOG_QUANTITY"            => "N",
		"SHOW_CATALOG_QUANTITY_CNT"        => "N",
		"SHOW_DEACTIVATED"                 => "N",
		"SHOW_DESCRIPTION_AFTER_SECTION"   => "Y",
		"SHOW_DISCOUNT_PERCENT"            => "N",
		"SHOW_EMPTY_STORE"                 => "Y",
		"SHOW_GENERAL_STORE_INFORMATION"   => "Y",
		"SHOW_LEFT_CATALOG_MENU"           => "Y",
		"SHOW_LEFT_MENU"                   => "Y",
		"SHOW_LEFT_MENU_SETTINGS"          => "Y",
		"SHOW_LINK_TO_FORUM"               => "N",
		"SHOW_MAIN_INSTEAD_NF_SKU"         => "N",
		"SHOW_MEASURE"                     => "N",
		"SHOW_OFFER_PIC_BYCLICK"           => "Y",
		"SHOW_OLD_PRICE"                   => "N",
		"SHOW_PRICE_COUNT"                 => "0",
		"SHOW_PRICE_NAME"                  => "N",
		"SHOW_SECTION_DESC"                => "top",
		"SHOW_SECTION_SEO"                 => "N",
		"SHOW_TOP_ELEMENTS"                => "Y",
		"SIDEBAR_DETAIL_SHOW"              => "Y",
		"SIDEBAR_PATH"                     => "",
		"SIDEBAR_SECTION_SHOW"             => "Y",
		"SKU_PROPS_SHOW_TYPE"              => "square",
		"SKU_SORT_PARAMS"                  => "N",
		"STORES"                           => [
			0 => "1",
		],
		"STORE_PATH"                       => "/company/#store_id#",
		"STYLE_MENU"                       => "colored_light",
		"STYLE_MENU_HOVER"                 => "colored_light",
		"SUBMENU"                          => "ACTIVE_SHOW",
		"TEMPLATE_THEME"                   => "site",
		"THEME"                            => "default",
		"TILE_SHOW_PROPERTIES"             => "Y",
		"TITLE_MENU"                       => "Вся продукция",
		"TOP_ADD_TO_BASKET_ACTION"         => "BUY",
		"TOP_ELEMENT_COUNT"                => "2",
		"TOP_ELEMENT_SORT_FIELD"           => "shows",
		"TOP_ELEMENT_SORT_FIELD2"          => "shows",
		"TOP_ELEMENT_SORT_ORDER"           => "asc",
		"TOP_ELEMENT_SORT_ORDER2"          => "asc",
		"TOP_LINE_ELEMENT_COUNT"           => "3",
		"TOP_OFFERS_FIELD_CODE"            => [
			0 => "",
			1 => "",
		],
		"TOP_OFFERS_LIMIT"                 => "5",
		"TOP_OFFERS_PROPERTY_CODE"         => [
			0 => "",
			1 => "",
		],
		"TOP_PROPERTY_CODE"                => [
			0 => "",
			1 => "",
		],
		"TOP_ROTATE_TIMER"                 => "30",
		"TOP_TITLE"                        => "Лидеры продаж",
		"TOP_VIEW_MODE"                    => "BANNER",
		"URL_TEMPLATES_READ"               => "",
		"USER_FIELDS"                      => [
			0 => "",
			1 => "",
		],
		"USE_ALSO_BUY"                     => "N",
		"USE_BIG_DATA"                     => "Y",
		"USE_CAPTCHA"                      => "Y",
		"USE_COMMON_SETTINGS_BASKET_POPUP" => "N",
		"USE_COMPARE"                      => "N",
		"USE_ELEMENT_COUNTER"              => "N",
		"USE_EXT"                          => "Y",
		"USE_FAVORITES"                    => "Y",
		"USE_FAVORITES_TEXT"               => "Избранное",
		"USE_FILTER"                       => "Y",
		"USE_GIFTS_DETAIL"                 => "N",
		"USE_GIFTS_SECTION"                => "N",
		"USE_LANGUAGE_GUESS"               => "Y",
		"USE_MAIN_ELEMENT_SECTION"         => "N",
		"USE_MIN_AMOUNT"                   => "N",
		"USE_ONE_CLICK"                    => "Y",
		"USE_ONE_CLICK_TEXT"               => "Купить в 1 клик",
		"USE_PRICE_COUNT"                  => "N",
		"USE_PRODUCT_QUANTITY"             => "N",
		"USE_REVIEW"                       => "Y",
		"USE_SALE_BESTSELLERS"             => "N",
		"USE_SHARE"                        => "Y",
		"USE_SHARE_TEXT"                   => "Поделиться",
		"USE_STORE"                        => "N",
		"USE_STORE_PHONE"                  => "Y",
		"USE_STORE_SCHEDULE"               => "Y",
		"VIDEO_PLAYER"                     => "MEJ",
		"VIDEO_PLAYER_FULLSCREEN"          => "N",
		"VIDEO_TYPE"                       => "GRID",
		"VIEWED_PRODUCTS_BLOCK_TITLE"      => "Просмотренные товары",
		"VIEWED_PRODUCTS_CNT"              => "3",
		"VIEWED_PRODUCTS_SHOW"             => "N",
		"VIEWED_PRODUCTS_SORT"             => "100",
		"VIEWED_PRODUCTS_WERE_SHOW"        => "left",
		"ZOOM_ON"                          => "N",
		"COMPONENT_TEMPLATE"               => "production",
		"COMPOSITE_FRAME_MODE"             => "A",
		"COMPOSITE_FRAME_TYPE"             => "AUTO",
		"SEF_URL_TEMPLATES"                => [
			"sections"     => "",
			"section"      => "#SECTION_CODE#/",
			"element"      => "#SECTION_CODE#/#ELEMENT_CODE#/",
			"compare"      => "compare.php?action=#ACTION_CODE#",
			"smart_filter" => "#SECTION_CODE#/filter/#SMART_FILTER_PATH#/apply/",
		],
		"VARIABLE_ALIASES"                 => [
			"compare" => [
				"ACTION_CODE" => "action",
			],
		],
	],
	false
);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>