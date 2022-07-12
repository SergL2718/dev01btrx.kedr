<?
global $APPLICATION;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Туры");
//$APPLICATION->AddChainItem('Клуб путешественников Звенящие Кедры', '/tourism/');
//$APPLICATION->AddChainItem('Туры', '/tourism/tours');
?>

<? /*
    <p>
        Мы выложим программу и дату туров по факту готовности.<br>
        Пока ждем ваших предложений и пожеланий.<br>
        По всем вопросам и предложениям звоните на нашу бесплатную линию <a href="tel:88003500270">8-800-350-02-70</a>, пишите на
        <a href="mailto:admin@megre.ru">admin@megre.ru</a>.
    </p>
*/ ?>
<? $APPLICATION->IncludeComponent(
    "bxready:news",
    "tours",
    array(
        "ADD_ELEMENT_CHAIN" => "Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "BROWSER_TITLE" => "-",
        "BXREADY_ELEMENT_DRAW_MAIN" => "system#classic.image.v2",
        "BXREADY_LIST_BOOTSTRAP_GRID_STYLE" => "12",
        "BXREADY_LIST_LG_CNT_MAIN" => "12",
        "BXREADY_LIST_MD_CNT_MAIN" => "12",
        "BXREADY_LIST_SLIDER_MAIN" => "N",
        "BXREADY_LIST_SM_CNT_MAIN" => "12",
        "BXREADY_LIST_XS_CNT_MAIN" => "12",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "N",
        "CHECK_DATES" => "Y",
        "COMPONENT_TEMPLATE" => "tours",
        "DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
        "DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
        "DETAIL_DISPLAY_TOP_PAGER" => "N",
        "DETAIL_FIELD_CODE" => array(
            0 => "",
            1 => "",
        ),
        "DETAIL_PAGER_SHOW_ALL" => "Y",
        "DETAIL_PAGER_TEMPLATE" => "",
        "DETAIL_PAGER_TITLE" => "Страница",
        "DETAIL_PAGE_TITLE" => "Подробнее",
        "DETAIL_PAGE_TITLE_MAIN" => "Подробнее опс",
        "DETAIL_PROPERTY_CODE" => array(
            0 => "EMAIL",
            1 => "",
        ),
        "DETAIL_SET_CANONICAL_URL" => "Y",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => "60",
        "IBLOCK_TYPE" => "content",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
        "LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
        "LIST_FIELD_CODE" => array(
            0 => "PREVIEW_TEXT",
            1 => "PREVIEW_PICTURE",
            2 => "DETAIL_PICTURE",
            3 => "",
        ),
        "LIST_PROPERTY_CODE" => array(
            0 => "",
            1 => "",
        ),
        "MESSAGE_404" => "",
        "META_DESCRIPTION" => "-",
        "META_KEYWORDS" => "-",
        "NEWS_COUNT" => "5",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => "round",
        "PAGER_TITLE" => "Туры",
        "PREVIEW_TRUNCATE_LEN" => "",
        "SEF_FOLDER" => "/tourism/tours/",
        "SEF_MODE" => "Y",
        "SET_LAST_MODIFIED" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "Y",
        "SHOW_404" => "N",
        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_BY2" => "SORT",
        "SORT_ORDER1" => "DESC",
        "SORT_ORDER2" => "ASC",
        "USE_CATEGORIES" => "N",
        "USE_FILTER" => "N",
        "USE_PERMISSIONS" => "N",
        "USE_RATING" => "N",
        "USE_REVIEW" => "N",
        "USE_RSS" => "N",
        "USE_SEARCH" => "N",
        "USE_SHARE" => "N",
        "SEF_URL_TEMPLATES" => array(
            "news" => "",
            "section" => "#SECTION_CODE#/",
            "detail" => "#ELEMENT_CODE#/",
        )
    ),
    false
); ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
