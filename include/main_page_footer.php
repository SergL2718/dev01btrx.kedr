<?php
/*
 * Изменено: 22 февраля 2022, вторник
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

/**
 * @var $APPLICATION
 * @var $USER
 */

use Alexkova\Market\Core;
use Bitrix\Main\LoaderException;

$APPLICATION->IncludeComponent(
    "bxready:block.list",
    "main_page_articles",
    [
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "BXREADY_ELEMENT_DRAW" => "system#classic.image.v1",
        "BXREADY_LIST_BOOTSTRAP_GRID_STYLE" => "12",
        "BXREADY_LIST_HIDE_MOBILE_SLIDER_ARROWS" => "N",
        "BXREADY_LIST_HIDE_MOBILE_SLIDER_AUTOSCROLL" => "N",
        "BXREADY_LIST_HIDE_SLIDER_ARROWS" => "Y",
        "BXREADY_LIST_LG_CNT" => "4",
        "BXREADY_LIST_MD_CNT" => "4",
        "BXREADY_LIST_PAGE_BLOCK_TITLE" => "Статьи и обзоры",
        "BXREADY_LIST_PAGE_BLOCK_TITLE_GLYPHICON" => "",
        "BXREADY_LIST_SLIDER" => "N",
        "BXREADY_LIST_SLIDER_MARKERS" => "Y",
        "BXREADY_LIST_SM_CNT" => "4",
        "BXREADY_LIST_VERTICAL_SLIDER_MODE" => "N",
        "BXREADY_LIST_XS_CNT" => "12",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "N",
        "CHECK_DATES" => "Y",
        "COMPONENT_TEMPLATE" => "main_page_articles",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => [
            0 => "NAME",
            1 => "PREVIEW_TEXT",
            2 => "PREVIEW_PICTURE",
            3 => "",
        ],
        "FILTER_NAME" => "",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => "16",
        "IBLOCK_TYPE" => "content",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "Y",
        "NEWS_COUNT" => "3",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => "Обзоры",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "PROPERTY_CODE" => [
            0 => "",
            1 => "",
        ],
        "SET_BROWSER_TITLE" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_BY2" => "SORT",
        "SORT_ORDER1" => "DESC",
        "SORT_ORDER2" => "ASC",
        "SHOW_LINK_MAIN_PAGE_IBLOCK" => "N",
    ],
    false
);

try {
    if (Bitrix\Main\Loader::includeModule('sender')) {
        ?>
        <div class="my-5">
            <?php $APPLICATION->IncludeComponent(
                "bitrix:subscribe.form",
                'native',
                [
                    "COMPONENT_TEMPLATE" => 'native',
                    "USE_PERSONALIZATION" => "Y",
                    "SHOW_HIDDEN" => "N",
                    "PAGE" => "/personal/subscribe/",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "3600000",
                    "SHOW_RUBRICS" => "N",
                ],
                false,
                [
                    "ACTIVE_COMPONENT" => "Y",
                ]
            ) ?>
        </div>
        <?php
    }
} catch (LoaderException $e) {
}

$APPLICATION->IncludeComponent(
    "bxready:block.list",
    ".default",
    [
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "BXREADY_ELEMENT_DRAW" => "system#classic.image.v1",
        "BXREADY_LIST_BOOTSTRAP_GRID_STYLE" => "12",
        "BXREADY_LIST_HIDE_MOBILE_SLIDER_ARROWS" => "N",
        "BXREADY_LIST_HIDE_MOBILE_SLIDER_AUTOSCROLL" => "N",
        "BXREADY_LIST_HIDE_SLIDER_ARROWS" => "Y",
        "BXREADY_LIST_LG_CNT" => "4",
        "BXREADY_LIST_MD_CNT" => "4",
        "BXREADY_LIST_PAGE_BLOCK_TITLE" => "Акции",
        "BXREADY_LIST_PAGE_BLOCK_TITLE_GLYPHICON" => "",
        "BXREADY_LIST_SLIDER" => "N",
        "BXREADY_LIST_SLIDER_MARKERS" => "Y",
        "BXREADY_LIST_SM_CNT" => "4",
        "BXREADY_LIST_VERTICAL_SLIDER_MODE" => "N",
        "BXREADY_LIST_XS_CNT" => "12",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "N",
        "CHECK_DATES" => "Y",
        "COMPONENT_TEMPLATE" => ".default",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => [0 => "", 1 => "",],
        "FILTER_NAME" => "",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => "17",
        "IBLOCK_TYPE" => "content",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "Y",
        "NEWS_COUNT" => "4",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => "Обзоры",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "PROPERTY_CODE" => [0 => "", 1 => "",],
        "SET_BROWSER_TITLE" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_LINK_MAIN_PAGE_IBLOCK" => "N",
        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_BY2" => "SORT",
        "SORT_ORDER1" => "DESC",
        "SORT_ORDER2" => "ASC",
    ]
);


$APPLICATION->IncludeComponent(
    "bxready:block.list",
    ".default",
    [
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "BXREADY_ELEMENT_DRAW" => "system#news.short.list.v1",
        "BXREADY_LIST_BOOTSTRAP_GRID_STYLE" => "12",
        "BXREADY_LIST_HIDE_MOBILE_SLIDER_ARROWS" => "N",
        "BXREADY_LIST_HIDE_MOBILE_SLIDER_AUTOSCROLL" => "N",
        "BXREADY_LIST_HIDE_SLIDER_ARROWS" => "Y",
        "BXREADY_LIST_LG_CNT" => "6",
        "BXREADY_LIST_MD_CNT" => "6",
        "BXREADY_LIST_PAGE_BLOCK_TITLE" => "Новости",
        "BXREADY_LIST_PAGE_BLOCK_TITLE_GLYPHICON" => "",
        "BXREADY_LIST_SLIDER" => "N",
        "BXREADY_LIST_SLIDER_MARKERS" => "Y",
        "BXREADY_LIST_SM_CNT" => "12",
        "BXREADY_LIST_VERTICAL_SLIDER_MODE" => "N",
        "BXREADY_LIST_XS_CNT" => "18",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "N",
        "CHECK_DATES" => "Y",
        "COMPONENT_TEMPLATE" => ".default",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => [
            0 => "NAME",
            1 => "DATE_ACTIVE_FROM",
            2 => "",
        ],
        "FILTER_NAME" => "",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => "15",
        "IBLOCK_TYPE" => "content",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "Y",
        "NEWS_COUNT" => "6",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => "Новости",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "PROPERTY_CODE" => [
            0 => "",
            1 => "",
        ],
        "SET_BROWSER_TITLE" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_BY2" => "SORT",
        "SORT_ORDER1" => "DESC",
        "SORT_ORDER2" => "ASC",
    ],
    false,
    [
        "ACTIVE_COMPONENT" => "Y",
    ]
);
?>
<br>
<br>
