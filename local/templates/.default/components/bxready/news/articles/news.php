<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*
 * Изменено: 24 January 2022, Monday
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<p>
	Статьи на интересные и полезные темы. В этом разделе найдется, что почитать.
</p><br>
<h2><a href="/articles/blog/">Блог</a></h2>
<p>
    Неформально о самом интересном. Живые заметки о том, что происходит в компании и вокруг, размышления о важном,
    события и люди. Всё то, что выходит за рамки официальных новостей. Здесь мы заглядываем в разные уголки нашей
    компании, делимся секретами и настроением.
</p>
<p>
    <a href="/articles/blog/">Читать далее...</a>
</p><br>
<h2><a href="/articles/retsepty/">Рецепты</a></h2>
<p>
    Подборка рецептов от нашего диетолога Елены Гарагуля. Сытные и легкие, разнообразные блюда и напитки, созданные с
    учетом сезонности и климатических особенностей России. Еда – это наше главное лечение и профилактика при любых
    состояниях организма, именно правильные рецепты помогают поддерживать здоровье и высокий уровень энергии.
</p>
<p><a href="/articles/retsepty/">Смотреть рецепты...</a></p><br>
<h2><a href="/articles/zdorove/">Здоровье</a></h2>
<p>
    Как вылечить простуду при помощи питания? Какие продукты спасают от депрессии? Как менять свой рацион в зависимости
    от сезона и всегда быть на максимуме энергии? И конечно, тонкости кедровой продукции с медицинской точки зрения.
    Очень простые и жизненные советы от врача-диетолога.
</p>
<p><a href="/articles/zdorove/">Читать далее...</a></p>
<?/*$APPLICATION->IncludeComponent(
	"bxready:block.list",
	".default",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"NEWS_COUNT" => $arParams["NEWS_COUNT"],
		"SORT_BY1" => $arParams["SORT_BY1"],
		"SORT_ORDER1" => $arParams["SORT_ORDER1"],
		"SORT_BY2" => $arParams["SORT_BY2"],
		"SORT_ORDER2" => $arParams["SORT_ORDER2"],
		"FIELD_CODE" => $arParams["LIST_FIELD_CODE"],
		"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
		"MESSAGE_404" => $arParams["MESSAGE_404"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"SHOW_404" => $arParams["SHOW_404"],
		"FILE_404" => $arParams["FILE_404"],
		"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_FILTER" => $arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
		"PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
		"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
		"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
		"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
		"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
		"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
		"ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],
		"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
		"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
		"FILTER_NAME" => $arParams["FILTER_NAME"],
		"HIDE_LINK_WHEN_NO_DETAIL" => $arParams["HIDE_LINK_WHEN_NO_DETAIL"],
		"CHECK_DATES" => $arParams["CHECK_DATES"],

		"BXREADY_ELEMENT_DRAW" => $arParams["BXREADY_ELEMENT_DRAW_MAIN"],
		"BXREADY_LIST_BOOTSTRAP_GRID_STYLE" => $arParams["BXREADY_LIST_BOOTSTRAP_GRID_STYLE"],
		"BXREADY_LIST_HIDE_MOBILE_SLIDER_ARROWS" => $arParams["BXREADY_LIST_HIDE_MOBILE_SLIDER_ARROWS_MAIN"],
		"BXREADY_LIST_HIDE_MOBILE_SLIDER_AUTOSCROLL" => $arParams["BXREADY_LIST_HIDE_MOBILE_SLIDER_AUTOSCROLL_MAIN"],
		"BXREADY_LIST_HIDE_SLIDER_ARROWS" => $arParams["BXREADY_LIST_HIDE_SLIDER_ARROWS_MAIN"],
		"BXREADY_LIST_LG_CNT" => $arParams["BXREADY_LIST_LG_CNT_MAIN"],
		"BXREADY_LIST_MD_CNT" => $arParams["BXREADY_LIST_MD_CNT_MAIN"],
		"BXREADY_LIST_PAGE_BLOCK_TITLE" => '',
		"BXREADY_LIST_PAGE_BLOCK_TITLE_GLYPHICON" => '',
		"BXREADY_LIST_SLIDER" => $arParams["BXREADY_LIST_SLIDER_MAIN"],
		"BXREADY_LIST_SLIDER_MARKERS" => $arParams["BXREADY_LIST_SLIDER_MARKERS_MAIN"],
		"BXREADY_LIST_SM_CNT" => $arParams["BXREADY_LIST_SM_CNT_MAIN"],
		"BXREADY_LIST_VERTICAL_SLIDER_MODE" => $arParams["BXREADY_LIST_VERTICAL_SLIDER_MODE_MAIN"],
		"BXREADY_LIST_XS_CNT" => $arParams["BXREADY_LIST_XS_CNT_MAIN"],
		"DETAIL_PAGE_TITLE" => $arParams["DETAIL_PAGE_TITLE"],

	),
	$component,
	array("HIDE_ICONS"=>"Y")
);*/?>
