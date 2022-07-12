<?php
/*
 * Изменено: 22 февраля 2022, вторник
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

/**
 * @var $APPLICATION
 */
?>

<div style="margin: 20px 0;">
	<? $APPLICATION->IncludeComponent(
			"alexkova.market:catalog.markers",
			".default",
			[
					"ACTION_VARIABLE"                             => "action",
					"ADD_PROPERTIES_TO_BASKET"                    => "Y",
					"BASKET_URL"                                  => "/personal/basket.php",
					"BESTSELLER_IBLOCK_ID"                        => "37",
					"BESTSELLER_IBLOCK_TYPE"                      => "1c_catalog",
					"BXREADY_ELEMENT_DRAW"                        => "system#ecommerce.v2.lite",
					"BXREADY_LIST_BOOTSTRAP_GRID_STYLE"           => "10",
					"BXREADY_LIST_HIDE_MOBILE_SLIDER_ARROWS"      => "N",
					"BXREADY_LIST_HIDE_MOBILE_SLIDER_AUTOSCROLL"  => "N",
					"BXREADY_LIST_HIDE_MOBILE_SLIDER_SCROLLSPEED" => "2000",
					"BXREADY_LIST_HIDE_SLIDER_ARROWS"             => "Y",
					"BXREADY_LIST_LG_CNT"                         => "2",
					"BXREADY_LIST_MD_CNT"                         => "2",
					"BXREADY_LIST_PAGE_BLOCK_TITLE"               => "",
					"BXREADY_LIST_PAGE_BLOCK_TITLE_GLYPHICON"     => "",
					"BXREADY_LIST_SLIDER"                         => "Y",
					"BXREADY_LIST_SLIDER_MARKERS"                 => "Y",
					"BXREADY_LIST_SM_CNT"                         => "5",
					"BXREADY_LIST_VERTICAL_SLIDER_MODE"           => "N",
					"BXREADY_LIST_XS_CNT"                         => "5",
					"CACHE_FILTER"                                => "N",
					"CACHE_GROUPS"                                => "N",
					"CACHE_TIME"                                  => "36000000",
					"CACHE_TYPE"                                  => "A",
					"COMPONENT_TEMPLATE"                          => ".default",
					"CONVERT_CURRENCY"                            => "N",
					"DISPLAY_BOTTOM_PAGER"                        => "N",
					"DISPLAY_TOP_PAGER"                           => "N",
					"ELEMENT_SORT_FIELD"                          => "sort",
					"ELEMENT_SORT_FIELD2"                         => "id",
					"ELEMENT_SORT_ORDER"                          => "asc",
					"ELEMENT_SORT_ORDER2"                         => "desc",
					"FILTER_NAME"                                 => "arrFilter",
					"HIDE_NOT_AVAILABLE"                          => "Y",
					"IBLOCK_ID"                                   => "37",
					"IBLOCK_TYPE"                                 => "1c_catalog",
					"INCLUDE_SUBSECTIONS"                         => "Y",
					"OFFERS_CART_PROPERTIES"                      => "",
					"OFFERS_LIMIT"                                => "5",
					"OFFERS_PROPERTY_CODE"                        => [
					],
					"OFFERS_SORT_FIELD"                           => "sort",
					"OFFERS_SORT_FIELD2"                          => "id",
					"OFFERS_SORT_ORDER"                           => "asc",
					"OFFERS_SORT_ORDER2"                          => "desc",
					"PAGER_DESC_NUMBERING"                        => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME"             => "3600000",
					"PAGER_SHOW_ALL"                              => "N",
					"PAGER_SHOW_ALWAYS"                           => "N",
					"PAGER_TEMPLATE"                              => ".default",
					"PAGER_TITLE"                                 => "Товары",
					"PAGE_ELEMENT_COUNT"                          => "15",
					"PARTIAL_PRODUCT_PROPERTIES"                  => "N",
					"PRICE_CODE"                                  => [
							0 => "Розница",
					],
					"PRICE_VAT_INCLUDE"                           => "Y",
					"PRODUCT_ID_VARIABLE"                         => "id",
					"PRODUCT_PROPERTIES"                          => [
					],
					"PRODUCT_PROPS_VARIABLE"                      => "prop",
					"PRODUCT_QUANTITY_VARIABLE"                   => "",
					"SHOW_ALL_WO_SECTION"                         => "Y",
					"SHOW_PRICE_COUNT"                            => "1",
					"TAB_ACTION_SETTING"                          => "Y",
					"TAB_ACTION_SORT"                             => "100",
					"TAB_HIT_SETTING"                             => "Y",
					"TAB_HIT_SORT"                                => "400",
					"TAB_NEW_SETTING"                             => "Y",
					"TAB_NEW_SORT"                                => "300",
					"TAB_RECCOMEND_SETTING"                       => "Y",
					"TAB_RECCOMEND_SORT"                          => "200",
					"USE_PRICE_COUNT"                             => "N",
					"USE_PRODUCT_QUANTITY"                        => "N",
					"COMPOSITE_FRAME_MODE"                        => "A",
					"COMPOSITE_FRAME_TYPE"                        => "AUTO",
			],
			false,
			[
					"ACTIVE_COMPONENT" => "Y",
			]
	); ?>
</div>

<? $APPLICATION->IncludeComponent(
		"alexkova.market:promo",
		"ribbon",
		[
				"CACHE_TIME"          => "0",
				"CACHE_TYPE"          => "A",
				"COMPONENT_TEMPLATE"  => "ribbon",
				"DISPLAY_TYPE"        => "block",
				"FIELD_CODE"          => [0 => "NAME", 1 => "PREVIEW_TEXT", 2 => "DETAIL_PICTURE", 3 => "",],
				"HOVER_EFFECT"        => "goliath",
				"IBLOCK_ID"           => "20",
				"IBLOCK_TYPE"         => "content",
				"INCLUDE_SUBSECTIONS" => "Y",
				"NEWS_COUNT"          => "10",
				"PARENT_SECTION"      => "0",
				"PROPERTY_CODE"       => [0 => "PROMO_HIDE_NAME", 1 => "",],
		]
); ?>

<style>
	#prirodapteka-banner img {
		width : 100%;
	}

	#prirodapteka-banner img[src*='ajax_loader.gif'] {
		width : auto;
	}
</style>
<div style="margin: 50px 0;text-align: center;" id="prirodapteka-banner">
	<a href="/catalog/prirodnaya_apteka/">
		<img v-bx-lazyload
             data-lazyload-dont-hide
             data-lazyload-src="/images/pa1150-410_new.png"
             src="<?= SITE_TEMPLATE_PATH ?>/images/ajax_loader.gif"
        >
    </a>
    <script>
        BX.Vue.create({
            el: '#prirodapteka-banner'
        });
    </script>
</div>
<? /*$APPLICATION->IncludeComponent(
		"alexkova.market:catalog.bestsellers",
		".default",
		[
				"ACTION_VARIABLE"                             => "action",
				"ADD_PROPERTIES_TO_BASKET"                    => "Y",
				"BASKET_URL"                                  => "/personal/basket.php",
				"BESTSELLER_IBLOCK_ID"                        => "28",
				"BESTSELLER_IBLOCK_TYPE"                      => "content",
				"BXREADY_ELEMENT_DRAW"                        => "system#ecommerce.v2.lite",
				"BXREADY_LIST_BOOTSTRAP_GRID_STYLE"           => "10",
				"BXREADY_LIST_HIDE_MOBILE_SLIDER_ARROWS"      => "Y",
				"BXREADY_LIST_HIDE_MOBILE_SLIDER_AUTOSCROLL"  => "N",
				"BXREADY_LIST_HIDE_MOBILE_SLIDER_SCROLLSPEED" => "2000",
				"BXREADY_LIST_HIDE_SLIDER_ARROWS"             => "N",
				"BXREADY_LIST_LG_CNT"                         => "2",
				"BXREADY_LIST_MD_CNT"                         => "2",
				"BXREADY_LIST_PAGE_BLOCK_TITLE"               => "Рекомендуем",
				"BXREADY_LIST_PAGE_BLOCK_TITLE_GLYPHICON"     => "",
				"BXREADY_LIST_SLIDER"                         => "Y",
				"BXREADY_LIST_SLIDER_MARKERS"                 => "Y",
				"BXREADY_LIST_SM_CNT"                         => "5",
				"BXREADY_LIST_VERTICAL_SLIDER_MODE"           => "Y",
				"BXREADY_LIST_XS_CNT"                         => "5",
				"CACHE_FILTER"                                => "N",
				"CACHE_GROUPS"                                => "Y",
				"CACHE_TIME"                                  => "36000000",
				"CACHE_TYPE"                                  => "N",
				"COMPONENT_TEMPLATE"                          => ".default",
				"CONVERT_CURRENCY"                            => "N",
				"DISPLAY_BOTTOM_PAGER"                        => "Y",
				"DISPLAY_TOP_PAGER"                           => "N",
				"ELEMENT_SORT_FIELD"                          => "sort",
				"ELEMENT_SORT_FIELD2"                         => "id",
				"ELEMENT_SORT_ORDER"                          => "asc",
				"ELEMENT_SORT_ORDER2"                         => "desc",
				"FILTER_NAME"                                 => "arrFilter",
				"HIDE_NOT_AVAILABLE"                          => "N",
				"IBLOCK_ID"                                   => "33",
				"IBLOCK_TYPE"                                 => "1c_catalog",
				"INCLUDE_SUBSECTIONS"                         => "Y",
				"OFFERS_CART_PROPERTIES"                      => "",
				"OFFERS_LIMIT"                                => "5",
				"OFFERS_PROPERTY_CODE"                        => [0 => "VES_TOVARNOGO_PREDLOZHENIYA", 1 => "OBEM_", 2 => "SOSTAV", 3 => "VES", 4 => "CML2_ARTICLE", 5 => "CML2_BASE_UNIT", 6 => "PEREPLET", 7 => "CML2_MANUFACTURER", 8 => "CML2_TRAITS", 9 => "CML2_TAXES", 10 => "CML2_ATTRIBUTES", 11 => "CML2_BAR_CODE", 12 => "RAZMER", 13 => "SODERZHANIE_ZHIVITSY", 14 => "SEMYA", 15 => "RAZMER_NOGI", 16 => "DEYSTVIE", 17 => "NAPOLNITEL", 18 => "VID", 19 => "TIP", 20 => "UPAKOVKA", 21 => "MATERIAL", 22 => "SOSTAV_1", 23 => "NA_KAKOM_MASLE_OSNOVAN", 24 => "NAZNACHENIE", 25 => "ZNACHOK_AKTSIYA", 26 => "ZNACHOK_NOVINKA", 27 => "ZNACHOK_RASPRODAZHA", 28 => "ZNACHOK_KHIT_PRODAZH", 29 => "PRISUTSTVIE_V_INTERNET_MAGAZINE",],
				"OFFERS_SORT_FIELD"                           => "sort",
				"OFFERS_SORT_FIELD2"                          => "id",
				"OFFERS_SORT_ORDER"                           => "asc",
				"OFFERS_SORT_ORDER2"                          => "desc",
				"PAGER_DESC_NUMBERING"                        => "N",
				"PAGER_DESC_NUMBERING_CACHE_TIME"             => "36000",
				"PAGER_SHOW_ALL"                              => "N",
				"PAGER_SHOW_ALWAYS"                           => "N",
				"PAGER_TEMPLATE"                              => ".default",
				"PAGER_TITLE"                                 => "Товары",
				"PAGE_ELEMENT_COUNT"                          => "30",
				"PARTIAL_PRODUCT_PROPERTIES"                  => "N",
				"PRICE_CODE"                                  => [0 => "Розница",],
				"PRICE_VAT_INCLUDE"                           => "Y",
				"PRODUCT_ID_VARIABLE"                         => "id",
				"PRODUCT_PROPERTIES"                          => [0 => "VED", 1 => "ZNACHENIE_KATEGORIY", 2 => "PROIZVODITEL", 3 => "CML2_MANUFACTURER", 4 => "CML2_TRAITS", 5 => "SOSTAV", 6 => "CML2_TAXES", 7 => "CML2_ATTRIBUTES", 8 => "_DLYA_SAYTA_NEISPOLZOVAT", 9 => "MEGRELLC_COM", 10 => "MEGRELLC_COM_1", 11 => "MEGRELLC_COM_2", 12 => "MEGRELLC_COM_3", 13 => "MEGRELLC_COM_4", 14 => "MEGRELLC_COM_5", 15 => "MEGRELLC_COM_6", 16 => "MEGRELLC_COM_7", 17 => "DEYSTVIE", 18 => "NAPOLNITEL", 19 => "VID", 20 => "TIP", 21 => "UPAKOVKA", 22 => "MATERIAL", 23 => "SOSTAV_1", 24 => "NA_KAKOM_MASLE_OSNOVAN", 25 => "NAZNACHENIE", 26 => "ZNACHOK_AKTSIYA", 27 => "ZNACHOK_NOVINKA", 28 => "ZNACHOK_EST", 29 => "ZNACHOK_RASPRODAZHA", 30 => "ZNACHOK_KHIT_PRODAZH", 31 => "PRISUTSTVIE_V_INTERNET_MAGAZINE",],
				"PRODUCT_PROPS_VARIABLE"                      => "prop",
				"PRODUCT_QUANTITY_VARIABLE"                   => "",
				"SHOW_ALL_WO_SECTION"                         => "Y",
				"SHOW_PRICE_COUNT"                            => "1",
				"USE_PRICE_COUNT"                             => "N",
				"USE_PRODUCT_QUANTITY"                        => "N",
		],
		false,
		[
				'ACTIVE_COMPONENT' => 'Y',
		]
);*/ ?>