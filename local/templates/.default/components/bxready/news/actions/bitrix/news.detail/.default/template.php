<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);
//echo "<pre>";print_r($arResult);echo "</pre>";
$excludeProps = array('PRICE', 'OLD_PRICE', "MORE_URLS", "VIDEO");

$QueryTitle = COption::GetOptionString('alexkova.corporate', 'query_button_title', GetMessage('QUERY_BUTTON_TITLE'));
$SaleTitle = COption::GetOptionString('alexkova.corporate', 'query_button_title', GetMessage('SALE_BUTTON_TITLE'));

?>
<?if (in_array("DATE_ACTIVE_FROM",$arParams["DETAIL_FIELD_CODE"]) || in_array("DATE_ACTIVE_TO",$arParams["DETAIL_FIELD_CODE"])):?>
        <div class="date-news">
<?if (in_array("DATE_ACTIVE_FROM",$arParams["DETAIL_FIELD_CODE"])):?>

                <?=$arResult["ACTIVE_FROM"]?>

<?endif;?>
<?if (in_array("DATE_ACTIVE_TO",$arParams["DETAIL_FIELD_CODE"])):?>

                / <?=$arResult["DATE_ACTIVE_TO"]?>

<?endif;?>
        </div>
<?endif;?>

<?if (is_array($arResult["DETAIL_PICTURE"])):?>
        <div class="bxr-news-image">
                <img src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>">
        </div>
<?endif;?>


<?if (count($arResult["FILES"])>0
	|| count($arResult["LINKS"])>0
		|| count($arResult["VIDEO"])>0):?>


<ul class="nav nav-tabs" role="tablist" id="details">

	<li role="presentation" class="active"><a href="#description" aria-controls="description" role="tab" data-toggle="tab"><?=GetMessage("DETAIL_TEXT_DESC")?></a></li>

	<?if (count($arResult["VIDEO"])>0):?>
		<li role="presentation"><a href="#video" aria-controls="video" role="tab" data-toggle="tab"><?=GetMessage("VIDEO_TAB_DESC")?></a></li>
	<?endif;?>
	<?if (count($arResult["FILES"])>0):?>
		<li role="presentation" ><a href="#files" aria-controls="files" role="tab" data-toggle="tab"><?=GetMessage("CATALOG_FILES")?></a></li>
	<?endif;?>
	<?if (count($arResult["LINKS"])>0):?>
		<li role="presentation"><a href="#links" aria-controls="video" role="tab" data-toggle="tab"><?=GetMessage("LINKS_TAB_DESC")?></a></li>
	<?endif;?>
</ul>

<div class="tab-content">
	<div role="tabpanel" class="tab-pane fade in active" id="description">
		<hr /><?echo $arResult["DETAIL_TEXT"];?>
	</div>

	<?if (count($arResult["FILES"])>0):?>
		<div id="files" class="element-files tb20 tab-pane fade" role="tabpanel">
			<hr />
			<?foreach ($arResult["FILES"] as $val):?>

				<?$template = "file_element";
				$arElementDrawParams = array(
					"DISPLAY_VARIANT" => $template,
					"ELEMENT" => array(
						"NAME" => $val["ORIGINAL_NAME"],
						"LINK" => $val["SRC"],
						"CLASS_NAME"=>$val["EXTENTION"]
					)
				);
?>
				<?
				$APPLICATION->IncludeComponent(
					"alexkova.corporate:element.draw",
					".default",
					$arElementDrawParams,
					false
				)
				?>

			<?endforeach;?>

		</div><div class="clearfix"></div>
	<?endif;?>

	<?if (count($arResult["LINKS"])>0):?>
		<div id="links" class="element-files tb20 tab-pane fade" role="tabpanel">
			<hr />
			<?foreach ($arResult["LINKS"] as $val):?>

				<?$template = "glyph_links";
				$arElementDrawParams = array(
					"DISPLAY_VARIANT" => $template,
					"ELEMENT" => array(
						"NAME" => $val["TITLE"],
						"LINK" => $val["LINK"],
						"GLYPH"=>array("GLYPH_CLASS" => "glyphicon-chevron-right"),
						"TARGET"=>"_blank"
					)
				);
				?>
				<?
				$APPLICATION->IncludeComponent(
					"alexkova.corporate:element.draw",
					".default",
					$arElementDrawParams,
					false
				)
				?>

			<?endforeach;?>

		</div><div class="clearfix"></div>
	<?endif;?>

	<?if (count($arResult["VIDEO"])>0):?>
		<div id="video" class="element-files tb20 tab-pane fade" role="tabpanel">
			<hr />
			<?foreach ($arResult["VIDEO"] as $val):?>

				<?$template = "video_card";
				$arElementDrawParams = array(
					"DISPLAY_VARIANT" => $template,
					"ELEMENT" => array(
						"VIDEO" => $val["LINK"],                  //������ �� �����
						"VIDEO_IMG" => '',               //������ �� ��������
						"VIDEO_IMG_WIDTH" => '150',         //������ �������� ��� �����
						"NAME" => $val["TITLE"]
					)
				);




				?>
				<div class="col-lg-3">
				<?
				$APPLICATION->IncludeComponent(
					"alexkova.corporate:element.draw",
					".default",
					$arElementDrawParams,
					false
				)
				?>
				</div>

			<?endforeach;?>

		</div><div class="clearfix"></div>
	<?endif;?>



</div>

<script>
	$(function () {
		$('#details a').click(function (e) {
			e.preventDefault();
			$(this).tab('show')
		})
	})
</script>
<?else:?>
	<?echo $arResult["DETAIL_TEXT"];?>

    <? if (count($arResult['PROPERTIES']['PRODUCT_LIST']['VALUE']) > 0): ?>
        <?
        global $arrFilter;
        $arrFilter = [
            "ID" => $arResult['PROPERTIES']['PRODUCT_LIST']['VALUE']
        ];
        $arParams['PRODUCT_DISPLAY_MODE'] = 'Y';

        $APPLICATION->IncludeComponent(
            "bitrix:catalog.section",
            "article",
            array(
                "ACTION_VARIABLE" => "action",
                "ADD_PROPERTIES_TO_BASKET" => "Y",
                "BASKET_URL" => "/personal/basket.php",

                "BXREADY_ELEMENT_DRAW" => "system#ecommerce.v2.lite",

                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "Y",
                "CACHE_TIME" => "36000000",
                "CACHE_TYPE" => "A",
                "COMPONENT_TEMPLATE" => "article",
                "CONVERT_CURRENCY" => "N",
                "DISPLAY_BOTTOM_PAGER" => "N",
                "DISPLAY_TOP_PAGER" => "N",
                "ELEMENT_SORT_FIELD" => "sort",
                "ELEMENT_SORT_FIELD2" => "id",
                "ELEMENT_SORT_ORDER" => "asc",
                "ELEMENT_SORT_ORDER2" => "desc",
                "FILTER_NAME" => "arrFilter",
                "HIDE_NOT_AVAILABLE" => "Y",
                "IBLOCK_ID" => "37",
                "IBLOCK_TYPE" => "1c_catalog",
                "INCLUDE_SUBSECTIONS" => "Y",
                "OFFERS_CART_PROPERTIES" => "",
                "OFFERS_LIMIT" => "0",
                "OFFERS_PROPERTY_CODE" => "",
                "OFFERS_SORT_FIELD" => "sort",
                "OFFERS_SORT_FIELD2" => "id",
                "OFFERS_SORT_ORDER" => "asc",
                "OFFERS_SORT_ORDER2" => "desc",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
                "PAGER_SHOW_ALL" => "N",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_TEMPLATE" => ".default",
                "PAGER_TITLE" => "Товары",
                "PAGE_ELEMENT_COUNT" => "20",
                "PARTIAL_PRODUCT_PROPERTIES" => "N",
                "PRICE_CODE" => array(
                    0 => "Розница",
                ),
                "PRICE_VAT_INCLUDE" => "Y",
                "PRODUCT_ID_VARIABLE" => "id",
                "PRODUCT_PROPERTIES" => "",
                "PRODUCT_PROPS_VARIABLE" => "prop",
                "PRODUCT_QUANTITY_VARIABLE" => "",
                "SHOW_ALL_WO_SECTION" => "Y",
                "SHOW_PRICE_COUNT" => "1",
                "TAB_ACTION_SETTING" => "Y",
                "TAB_ACTION_SORT" => "100",
                "TAB_HIT_SETTING" => "Y",
                "TAB_HIT_SORT" => "400",
                "TAB_NEW_SETTING" => "Y",
                "TAB_NEW_SORT" => "300",
                "TAB_RECCOMEND_SETTING" => "Y",
                "TAB_RECCOMEND_SORT" => "200",
                "USE_PRICE_COUNT" => "N",
                "USE_PRODUCT_QUANTITY" => "N",
                "COMPOSITE_FRAME_MODE" => "A",
                "COMPOSITE_FRAME_TYPE" => "AUTO",
                "SECTION_CODE" => "",
                "SECTION_USER_FIELDS" => array(
                    0 => "",
                    1 => "",
                ),
                "CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
                "HIDE_NOT_AVAILABLE_OFFERS" => "N",
                "OFFERS_FIELD_CODE" => array(
                    0 => "",
                    1 => "",
                ),
                "BACKGROUND_IMAGE" => "-",
                "SECTION_URL" => "",
                "DETAIL_URL" => "",
                "SECTION_ID_VARIABLE" => "SECTION_ID",
                "SEF_MODE" => "N",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "SET_TITLE" => "N",
                "SET_BROWSER_TITLE" => "N",
                "BROWSER_TITLE" => "-",
                "SET_META_KEYWORDS" => "N",
                "META_KEYWORDS" => "-",
                "SET_META_DESCRIPTION" => "N",
                "META_DESCRIPTION" => "-",
                "SET_LAST_MODIFIED" => "N",
                "USE_MAIN_ELEMENT_SECTION" => "N",
                "ADD_SECTIONS_CHAIN" => "N",
                "DISPLAY_COMPARE" => "N",
                "PAGER_BASE_LINK_ENABLE" => "N",
                "SET_STATUS_404" => "N",
                "SHOW_404" => "N",
                "MESSAGE_404" => "",
                "COMPATIBLE_MODE" => "Y",
                "DISABLE_INIT_JS_IN_COMPONENT" => "N"
            ),
            false
        );
        ?>
    <? endif ?>
<?endif;?>
