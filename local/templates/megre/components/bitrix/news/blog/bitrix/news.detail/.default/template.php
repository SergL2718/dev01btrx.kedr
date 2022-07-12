<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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

//echo "<pre>"; print_r($arResult); echo "</pre>";
?>


<div class="article">
    <div class="container">
        <div class="blog-filter">
            <div class="blog-filter__search">
                <form class="input-search" action="/blog?">
                    <input placeholder="Поиск в блоге..." name="q"/>
                    <button type="submit"></button>
                </form>
            </div>
            <div class="blog-filter__type">
                <div class="filter-select">
                    <div class="filter-select__selected">КАТЕГОРИЯ <i></i></div>
                    <div class="filter-select__options">
                        <div class="filter-select__options-wrap">

							<?php foreach ($arResult['SECTIONS'] as $item): ?>
                                <a href="/blog<?= $item['DETAIL_PAGE_URL'] ?>" class="filter-select__option blog-type">

                                    <div class="blog-type__image"><img src="<?= $item['PICTURE'] ?>"
                                                                       alt="<?= $item['NAME'] ?>"></div>
                                    <div class="blog-type__name"><?= $item['NAME'] ?></div>
                                </a>

							<?php endforeach ?>
                            <a href="/blog/" class="filter-select__option blog-type">

                                <div class="blog-type__image"><img
                                            src="<?= SITE_TEMPLATE_PATH ?>/images/icons/note-sheet.svg" alt=""></div>
                                <div class="blog-type__name">Все статьи</div>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="article__header">
            <div class="article__title">
                <div class="page-title"><?= $arResult["NAME"] ?></div>
				<? if ($arResult["PROPERTIES"]["AUTHOR_ID"]["VALUE"]) { ?>
                    <div class="article__user">
                        <div class="article__user-avatar">
							<? if ($arResult["PROPERTIES"]["AUTHOR_ID"]["VALUE_ARR"]["PERSONAL_PHOTO"]) { ?>
                                <img src="<?= $arResult["PROPERTIES"]["AUTHOR_ID"]["VALUE_ARR"]["PERSONAL_PHOTO"] ?>"
                                     alt="">
							<? } ?>
                        </div>
                        <div class="article__user-info">
                            <b><?= $arResult["PROPERTIES"]["AUTHOR_ID"]["VALUE_ARR"]["NAME"] ?></b><span><?= $arResult["PROPERTIES"]["AUTHOR_ID"]["VALUE_ARR"]["PERSONAL_PROFESSION"] ?></span>
                        </div>
                    </div>
				<? } ?>
            </div>
            <div class="article__sub">
                <div class="blog-type">
                    <div class="blog-type__image"><img
                                src="<? echo ($arResult["SECTIONS"][$arResult["IBLOCK_SECTION_ID"]]["PICTURE"])?$arResult["SECTIONS"][$arResult["IBLOCK_SECTION_ID"]]["PICTURE"]:"/local/templates/megre/components/bitrix/news/blog/bitrix/news.list/.default/images/list.svg"; ?>"
                                alt=""></div>
                    <div class="blog-type__name"><? echo $arResult["SECTIONS"][$arResult["IBLOCK_SECTION_ID"]]["NAME"] ?></div>
                </div>
                <div class="article__sub-hr"></div>
                <div class="article__sub-date"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></div>
            </div>
        </div>
        <div class="article__content"><?= $arResult["DETAIL_TEXT"] ?></div>
        <div class="block-share">
            <div class="block-share__label">Поделиться</div>
            <script src="https://yastatic.net/share2/share.js"></script>
            <div class="ya-share2" data-curtain data-shape="round"
                 data-services="vkontakte,facebook,odnoklassniki,twitter,pinterest"></div>
        </div>
    </div>
</div>

<?php

if ($arResult["PROPERTIES"]["PRODUCT_LIST"]["VALUE"]) {
	$GLOBALS["arrFilter"] = array("ID" => $arResult["PROPERTIES"]["PRODUCT_LIST"]["VALUE"]);
	?>
	<?
	$intSectionID = $APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		"slider",
		array(
			"IBLOCK_TYPE" => "1c_catalog",
			"IBLOCK_ID" => "37",
			"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
			"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
			"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
			"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
			"PROPERTY_CODE" => (isset($arParams["LIST_PROPERTY_CODE"]) ? $arParams["LIST_PROPERTY_CODE"] : []),
			"PROPERTY_CODE_MOBILE" => array(),
			"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
			"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
			"BROWSER_TITLE" => "-",
			"SET_LAST_MODIFIED" => "N",
			"INCLUDE_SUBSECTIONS" => "Y",
			"BASKET_URL" => $arParams["BASKET_URL"],
			"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
			"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
			"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
			"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
			"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
			"FILTER_NAME" => "arrFilter",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "N",
			"SET_TITLE" => "N",
			"MESSAGE_404" => $arParams["~MESSAGE_404"],
			"SET_STATUS_404" => "N",
			"SHOW_404" => "N",
			"FILE_404" => $arParams["FILE_404"],
			"DISPLAY_COMPARE" => "N",
			"PAGE_ELEMENT_COUNT" => "24",
			"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
			"PRICE_CODE" => array(
				0 => "BASE",
			),
			"USE_PRICE_COUNT" => "N",
			"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
			"PRICE_VAT_INCLUDE" => "N",
			"USE_PRODUCT_QUANTITY" => "N",
			"ADD_PROPERTIES_TO_BASKET" => "N",
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"PRODUCT_PROPERTIES" => (isset($arParams["PRODUCT_PROPERTIES"]) ? $arParams["PRODUCT_PROPERTIES"] : []),
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "N",
			"PAGER_TITLE" => $arParams["PAGER_TITLE"],
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
			"PAGER_SHOW_ALL" => "N",
			"PAGER_BASE_LINK_ENABLE" => "N",
			"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
			"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
			"LAZY_LOAD" => "N",
			"MESS_BTN_LAZY_LOAD" => $arParams["~MESS_BTN_LAZY_LOAD"],
			"LOAD_ON_SCROLL" => "N",
			"OFFERS_CART_PROPERTIES" => (isset($arParams["OFFERS_CART_PROPERTIES"]) ? $arParams["OFFERS_CART_PROPERTIES"] : []),
			"OFFERS_FIELD_CODE" => array(
				0 => "",
				1 => $arParams["LIST_OFFERS_FIELD_CODE"],
				2 => "",
			),
			"OFFERS_PROPERTY_CODE" => (isset($arParams["LIST_OFFERS_PROPERTY_CODE"]) ? $arParams["LIST_OFFERS_PROPERTY_CODE"] : []),
			"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
			"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
			"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
			"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
			"OFFERS_LIMIT" => (isset($arParams["LIST_OFFERS_LIMIT"]) ? $arParams["LIST_OFFERS_LIMIT"] : 0),
			"SECTION_ID" => "",
			"SECTION_CODE" => "",
			"SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
			"DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
			"USE_MAIN_ELEMENT_SECTION" => "N",
			"CONVERT_CURRENCY" => "N",
			"CURRENCY_ID" => $arParams["CURRENCY_ID"],
			"HIDE_NOT_AVAILABLE" => "N",
			"HIDE_NOT_AVAILABLE_OFFERS" => "N",
			"LABEL_PROP" => array(),
			"LABEL_PROP_MOBILE" => $arParams["LABEL_PROP_MOBILE"],
			"LABEL_PROP_POSITION" => $arParams["LABEL_PROP_POSITION"],
			"ADD_PICT_PROP" => "-",
			"PRODUCT_DISPLAY_MODE" => "N",
			"PRODUCT_BLOCKS_ORDER" => $arParams["LIST_PRODUCT_BLOCKS_ORDER"],
			"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false},{'VARIANT':'6','BIG_DATA':false}]",
			"ENLARGE_PRODUCT" => "STRICT",
			"ENLARGE_PROP" => isset($arParams["LIST_ENLARGE_PROP"]) ? $arParams["LIST_ENLARGE_PROP"] : "",
			"SHOW_SLIDER" => "N",
			"SLIDER_INTERVAL" => isset($arParams["LIST_SLIDER_INTERVAL"]) ? $arParams["LIST_SLIDER_INTERVAL"] : "",
			"SLIDER_PROGRESS" => isset($arParams["LIST_SLIDER_PROGRESS"]) ? $arParams["LIST_SLIDER_PROGRESS"] : "",
			"OFFER_ADD_PICT_PROP" => $arParams["OFFER_ADD_PICT_PROP"],
			"OFFER_TREE_PROPS" => (isset($arParams["OFFER_TREE_PROPS"]) ? $arParams["OFFER_TREE_PROPS"] : []),
			"PRODUCT_SUBSCRIPTION" => "N",
			"SHOW_DISCOUNT_PERCENT" => "N",
			"DISCOUNT_PERCENT_POSITION" => $arParams["DISCOUNT_PERCENT_POSITION"],
			"SHOW_OLD_PRICE" => "N",
			"SHOW_MAX_QUANTITY" => "N",
			"MESS_SHOW_MAX_QUANTITY" => (isset($arParams["~MESS_SHOW_MAX_QUANTITY"]) ? $arParams["~MESS_SHOW_MAX_QUANTITY"] : ""),
			"RELATIVE_QUANTITY_FACTOR" => (isset($arParams["RELATIVE_QUANTITY_FACTOR"]) ? $arParams["RELATIVE_QUANTITY_FACTOR"] : ""),
			"MESS_RELATIVE_QUANTITY_MANY" => (isset($arParams["~MESS_RELATIVE_QUANTITY_MANY"]) ? $arParams["~MESS_RELATIVE_QUANTITY_MANY"] : ""),
			"MESS_RELATIVE_QUANTITY_FEW" => (isset($arParams["~MESS_RELATIVE_QUANTITY_FEW"]) ? $arParams["~MESS_RELATIVE_QUANTITY_FEW"] : ""),
			"MESS_BTN_BUY" => (isset($arParams["~MESS_BTN_BUY"]) ? $arParams["~MESS_BTN_BUY"] : ""),
			"MESS_BTN_ADD_TO_BASKET" => (isset($arParams["~MESS_BTN_ADD_TO_BASKET"]) ? $arParams["~MESS_BTN_ADD_TO_BASKET"] : ""),
			"MESS_BTN_SUBSCRIBE" => (isset($arParams["~MESS_BTN_SUBSCRIBE"]) ? $arParams["~MESS_BTN_SUBSCRIBE"] : ""),
			"MESS_BTN_DETAIL" => (isset($arParams["~MESS_BTN_DETAIL"]) ? $arParams["~MESS_BTN_DETAIL"] : ""),
			"MESS_NOT_AVAILABLE" => (isset($arParams["~MESS_NOT_AVAILABLE"]) ? $arParams["~MESS_NOT_AVAILABLE"] : ""),
			"MESS_BTN_COMPARE" => (isset($arParams["~MESS_BTN_COMPARE"]) ? $arParams["~MESS_BTN_COMPARE"] : ""),
			"USE_ENHANCED_ECOMMERCE" => "N",
			"DATA_LAYER_NAME" => (isset($arParams["DATA_LAYER_NAME"]) ? $arParams["DATA_LAYER_NAME"] : ""),
			"BRAND_PROPERTY" => (isset($arParams["BRAND_PROPERTY"]) ? $arParams["BRAND_PROPERTY"] : ""),
			"TEMPLATE_THEME" => (isset($arParams["TEMPLATE_THEME"]) ? $arParams["TEMPLATE_THEME"] : ""),
			"ADD_SECTIONS_CHAIN" => "N",
			"ADD_TO_BASKET_ACTION" => "ADD",
			"SHOW_CLOSE_POPUP" => "N",
			"COMPARE_PATH" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["compare"],
			"COMPARE_NAME" => $arParams["COMPARE_NAME"],
			"USE_COMPARE_LIST" => "Y",
			"BACKGROUND_IMAGE" => (isset($arParams["SECTION_BACKGROUND_IMAGE"]) ? $arParams["SECTION_BACKGROUND_IMAGE"] : ""),
			"COMPATIBLE_MODE" => "N",
			"DISABLE_INIT_JS_IN_COMPONENT" => "N",
			"COMPONENT_TEMPLATE" => "slider",
			"SECTION_USER_FIELDS" => array(
				0 => "",
				1 => "",
			),
			"SHOW_ALL_WO_SECTION" => "Y",
			"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
			"RCM_TYPE" => "personal",
			"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
			"SHOW_FROM_SECTION" => "N",
			"SEF_MODE" => "N",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "N",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"SET_BROWSER_TITLE" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_META_DESCRIPTION" => "N"
		),
		false
	);
	?>
	<?php
}
?>


<?php
$GLOBALS["arrFilter"] = Array("!ID" => $arResult["ID"]);
?>
<?$APPLICATION->IncludeComponent("bitrix:news.list", "slider", Array(
	"DISPLAY_DATE" => "N",	// Выводить дату элемента
	"DISPLAY_NAME" => "N",	// Выводить название элемента
	"DISPLAY_PICTURE" => "N",	// Выводить изображение для анонса
	"DISPLAY_PREVIEW_TEXT" => "N",	// Выводить текст анонса
	"AJAX_MODE" => "N",	// Включить режим AJAX
	"IBLOCK_TYPE" => "content",	// Тип информационного блока (используется только для проверки)
	"IBLOCK_ID" => "16",	// Код информационного блока
	"NEWS_COUNT" => "10",	// Количество новостей на странице
	"SORT_BY1" => "ACTIVE_FROM",	// Поле для первой сортировки новостей
	"SORT_ORDER1" => "DESC",	// Направление для первой сортировки новостей
	"SORT_BY2" => "SORT",	// Поле для второй сортировки новостей
	"SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
	"FILTER_NAME" => "arrFilter",	// Фильтр
	"FIELD_CODE" => array(	// Поля
		0 => "",
		1 => "",
	),
	"PROPERTY_CODE" => array(	// Свойства
		0 => "AUTHOR_ID",
		1 => "HIDE",
		2 => "",
	),
	"CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
	"DETAIL_URL" => "",	// URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
	"PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода (только для типа текст)
	"ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
	"SET_TITLE" => "N",	// Устанавливать заголовок страницы
	"SET_BROWSER_TITLE" => "N",	// Устанавливать заголовок окна браузера
	"SET_META_KEYWORDS" => "N",	// Устанавливать ключевые слова страницы
	"SET_META_DESCRIPTION" => "N",	// Устанавливать описание страницы
	"SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",	// Включать инфоблок в цепочку навигации
	"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
	"HIDE_LINK_WHEN_NO_DETAIL" => "N",	// Скрывать ссылку, если нет детального описания
	"PARENT_SECTION" => "",	// ID раздела
	"PARENT_SECTION_CODE" => "",	// Код раздела
	"INCLUDE_SUBSECTIONS" => "N",	// Показывать элементы подразделов раздела
	"CACHE_TYPE" => "A",	// Тип кеширования
	"CACHE_TIME" => "3600",	// Время кеширования (сек.)
	"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
	"CACHE_GROUPS" => "N",	// Учитывать права доступа
	"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
	"DISPLAY_BOTTOM_PAGER" => "N",	// Выводить под списком
	"PAGER_TITLE" => "Новости",	// Название категорий
	"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
	"PAGER_TEMPLATE" => "",	// Шаблон постраничной навигации
	"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
	"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
	"PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
	"SET_STATUS_404" => "N",	// Устанавливать статус 404
	"SHOW_404" => "N",	// Показ специальной страницы
	"MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
	"PAGER_BASE_LINK" => "",
	"PAGER_PARAMS_NAME" => "arrPager",
	"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
	"AJAX_OPTION_STYLE" => "N",	// Включить подгрузку стилей
	"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
	"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
	"COMPONENT_TEMPLATE" => ".default",
	"STRICT_SECTION_CHECK" => "N",	// Строгая проверка раздела для показа списка
"SECTIONS_ARR" => $arResult['SECTIONS']
),
	false
);?>





