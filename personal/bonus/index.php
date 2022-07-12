<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Бонусы");
?>


<section class="cabinet cabinet_inner">
    <div class="container">
        <div class="page-title">Личный кабинет</div>
		<?
		$APPLICATION->IncludeFile("/local/include/personal/menu.php", array("ACTIVE" => 2), array(
			"MODE" => "html",
			"NAME" => "",
			"TEMPLATE" => ""
		));
		?>
        <div class="cabinet-container">
            <div class="page-title"><a class="link-more" href="cabinet.html">НАЗАД</a>БОНУСЫ</div>
            <p class="s-none">Возвращаем баллы с каждой покупки</p>
            <div class="cabinet-bonus">
                <div class="cabinet-bonus__title">НА ВАШЕМ СЧЁТЕ:</div>
                <div class="cabinet-bonus__sub"><span><?echo ($GLOBALS["BONUS"]["VALUE"])?$GLOBALS["BONUS"]["VALUE"]:0;?></span><?=$GLOBALS["BONUS"]["MEASUREMENT"]?>
                    <div class="icon icon-pine-cone"></div>
                </div>
                <p>Возвращаем баллы с каждой покупки</p>
            </div>
            <?$GLOBALS["arrFilter"] = Array("PROPERTY_USER_ID" => $USER->GetID());?>
			<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"bonus_list", 
	array(
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"AJAX_MODE" => "Y",
		"IBLOCK_TYPE" => "service",
		"IBLOCK_ID" => "68",
		"NEWS_COUNT" => "999",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "arrFilter",
		"FIELD_CODE" => array(
			0 => "ID",
			1 => "NAME",
			2 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "BONUS",
			1 => "USER_ID",
			2 => "SUMM",
			3 => "TYPE",
			4 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y H:i",
		"SET_TITLE" => "Y",
		"SET_BROWSER_TITLE" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_META_DESCRIPTION" => "Y",
		"SET_LAST_MODIFIED" => "Y",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"HIDE_LINK_WHEN_NO_DETAIL" => "Y",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"DISPLAY_TOP_PAGER" => "Y",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "Y",
		"PAGER_TEMPLATE" => "",
		"PAGER_DESC_NUMBERING" => "Y",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "Y",
		"PAGER_BASE_LINK_ENABLE" => "Y",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"PAGER_BASE_LINK" => "",
		"PAGER_PARAMS_NAME" => "arrPager",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPONENT_TEMPLATE" => "bonus_list",
		"STRICT_SECTION_CHECK" => "N"
	),
	false
);?>

            <div class="cabinet-bonus-help">
                <div class="cabinet-bonus-help__title">КАК НАКОПИТЬ БОНУСЫ? НА ЧТО ИХ ТРАТИТЬ?</div>
                <a class="link-more" href="#">ПОДРОБНЕЕ О БОНУСНОЙ СИСТЕМЕ</a>
            </div>
        </div>
    </div>
</section>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
