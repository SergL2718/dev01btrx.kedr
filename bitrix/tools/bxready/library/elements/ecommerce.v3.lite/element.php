<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
use Alexkova\Bxready\Draw;
IncludeModuleLangFile(__FILE__);
$addClass = ''; $UID = 0;
$picture = false;

global $APPLICATION;

$elementDraw = \Alexkova\Bxready\Draw::getInstance($this);
if (isset($arElementParams["BXREADY_LIST_MARKER_TYPE"]) && strlen($arElementParams["BXREADY_LIST_MARKER_TYPE"])>0)
	$elementDraw->setMarkerCollection($arElementParams["BXREADY_LIST_MARKER_TYPE"]);
$arMatrix = array('width' => 160, 'height' => 160);

if (is_array($arElement["PREVIEW_PICTURE"])){
	$picture = $elementDraw->prepareImage($arElement["PREVIEW_PICTURE"]["ID"], $arMatrix);
}else{
	if (is_array($arElement["DETAIL_PICTURE"])){
		$picture = $elementDraw->prepareImage($arElement["DETAIL_PICTURE"]["ID"], $arMatrix);
	}
}

if (!is_array($picture) || strlen($picture["src"])<=0){
	$picture = array("src" => $elementDraw->getDefaultImage());
}


if(intval($arElementParams["UNICUM_ID"])>0){
	$UID = $arElementParams["UNICUM_ID"];
}

$markerGroup = array(
	"NEW" => ($arElement["PROPERTIES"]["NEWPRODUCT"]["VALUE"]) ? true : false,
	"SALE" => ($arElement["PROPERTIES"]["SPECIALOFFER"]["VALUE"]) ? true : false,
	"DISCOUNT" => $arElement["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"],
	"HIT" => ($arElement["PROPERTIES"]["SALELEADER"]["VALUE"]) ? true : false,
	"REC" => ($arElement["PROPERTIES"]["RECOMMENDED"]["VALUE"]) ? true : false,
);

$strMainID = $arElementParams["AREA_ID"];
$arItemIDs = array(
		'ID' => $strMainID,
                'NAME' => $strMainID.'_name',
		'PICT' => $strMainID.'_pict',
		'SECOND_PICT' => $strMainID.'_secondpict',
		'STICKER_ID' => $strMainID.'_sticker',
		'SECOND_STICKER_ID' => $strMainID.'_secondsticker',
                'AVAIL' => $strMainID.'_avail',
		'QUANTITY' => $strMainID.'_quantity',
		'QUANTITY_DOWN' => $strMainID.'_quant_down',
		'QUANTITY_UP' => $strMainID.'_quant_up',
		'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
		'BUY_LINK' => $strMainID.'_buy_link',
		'BASKET_ACTIONS' => $strMainID.'_basket_actions',
		'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
		'SUBSCRIBE_LINK' => $strMainID.'_subscribe',
		'COMPARE_LINK' => $strMainID.'_compare_link',

		'PRICE' => $strMainID.'_price',
		'DSC_PERC' => $strMainID.'_dsc_perc',
		'SECOND_DSC_PERC' => $strMainID.'_second_dsc_perc',
		'PROP_DIV' => $strMainID.'_sku_tree',
		'PROP' => $strMainID.'_prop_',
		'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
		'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
	);
$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);


$voteDisplayAsRating = $arElementParams['VOTE_DISPLAY_AS_RATING'];
$useVoteRating = ('Y' == $arElementParams['USE_VOTE_RATING']);
$rating = 0;
$useCompare = ('Y' == $arElementParams['DISPLAY_COMPARE']);

if ($useVoteRating)
{
	if ($voteDisplayAsRating=='vote_avg')
		$rating = (($arElement['PROPERTIES']['vote_sum']['VALUE']/$arElement['PROPERTIES']['vote_count']['VALUE'])/5)*100;
	else
		$rating = ($arElement['PROPERTIES']['rating']['VALUE']/5)*100;

	$rating = (int)$rating;
}
$showCatalogQty = ('Y' == $arElementParams["SHOW_CATALOG_QUANTITY"]);
?>

	<div class="bxr-ecommerce-v3-lite" data-uid="<?=$UID?>" data-resize="1" id="<?=$arItemIDs["ID"]?>">
		<div class="bxr-element-container">
                    <div class="bxr-element-image">
                    <?
                    $title = ($arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arElement["NAME"];
                    $alt = ($arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"]) ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arElement["NAME"];
                    ?>
                            <a href="<?=$arElement["DETAIL_PAGE_URL"]?>">
                                <img src="<?=$picture["src"]?>" id="<?=$arItemIDs["PICT"]?>" alt="<?=$alt?>" title="<?=$title?>">
                            </a>
                            <!--<button class="bxr-element-detail-button bxr-bg-hover-light hidden-sm hidden-xs">
                                    <i class="fa fa-search-plus"></i>
                            </button>-->
                    </div>

                    <?$elementDraw->showMarkerGroup($markerGroup);?>

                    <div class="bxr-cart-basket-indicator">
                            <div class="bxr-indicator-item bxr-indicator-item-basket" data-item="<?=$arElement["ID"]?>">
                                    <span class="fa fa-shopping-cart"></span>
                                    <span class="bxr-counter-item bxr-counter-item-basket" data-item="<?=$arElement["ID"]?>">0</span>
                            </div>
                    </div>

                    <div class="bxr-sale-indicator">
                            <div class="bxr-basket-group">
                                <form class="bxr-basket-action bxr-basket-group" action="">
                                    <button class="bxr-indicator-item bxr-indicator-item-favor bxr-basket-favor" data-item="<?=$arElement["ID"]?>" tabindex="0">
                                        <span class="fa fa-heart-o"></span>
                                    </button>
                                    <input type="hidden" name="item" value="<?=$arElement["ID"]?>" tabindex="0">
                                    <input type="hidden" name="action" value="favor" tabindex="0">
                                    <input type="hidden" name="favor" value="yes">
                                </form>
                            </div>
                            <?
                            //compare
                            if ($useCompare)
                            {
                            ?>
                            <div class="bxr-basket-group">
                                    <button class="bxr-indicator-item bxr-indicator-item-compare bxr-compare-button" value="" data-item="<?=$arElement["ID"]?>">
                                            <span class="fa fa-bar-chart " aria-hidden="true"></span>
                                    </button>
                            </div>
                            <?}?>
                    </div>
                    <div class="bxr-bottom-block">
			<div class="bxr-element-name" id="bxr-element-name-<?=$arElement["ID"]?>">
				<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" id="<?=$arItemIDs["ID"]?>" title="<?=$arElement["NAME"]?>">
                                    <? echo (strlen($arElement["SHORT_NAME"])>0) ? $arElement["SHORT_NAME"] : $arElement["NAME"];?>
                                </a>
                                <?if ($arElementParams["TILE_SHOW_PROPERTIES"]=="Y"){?>
                                    <table class="bxr-element-props-table">
                                        <tbody>
                                            <?foreach ($arElement["DISPLAY_PROPERTIES"] as $arProperty) {?>
                                                <?if (!is_array($arProperty["DISPLAY_VALUE"]) && $arProperty["DISPLAY_VALUE"]){?>
                                                    <tr>
                                                        <td class="bxr-props-table-name"><?=trim($arProperty["NAME"])?></td>
                                                        <td class="bxr-props-table-value"><?=trim($arProperty["DISPLAY_VALUE"])?></td>
                                                    </tr>
                                                <?}?>
                                            <?}?>
                                        </tbody>
                                    </table>
                                <?}?>
			</div>
                        <div class="bxr-ra-string">
                            <?
                            //rating block
                            if ($useVoteRating){
                            ?>
                            <div class="bxr-element-rating">
                                <div class="bxr-rating-fs" style="background-position: 0 -<?=round($rating/20)*12?>px"></div>
                            </div>
                            <?}?>
                            <?if ($arElement["CATALOG_QUANTITY"] > 0 && $showCatalogQty) {?>
                                <div class="bxr-element-avail" id="<?=$arItemIDs["AVAIL"]?>">
                                        <?include('avail.php');?>
                                </div>
                            <?}?>
                            <div class="clearfix"></div>
                        </div>
			<div class="bxr-element-price" id="<?=$arItemIDs["PRICE"]?>">
				<?include('price.php');?>
			</div>

			<div class="bxr-element-action"  id="<?=$arItemIDs["BASKET_ACTIONS"]?>">
                            <?include('basket_btn.php');?>
			</div>
                    </div>
		</div>
	</div>


<?
/*
 * Pri neobhodimosti podkluchaem nuzhnye biblioteki
 */

$dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));
$elementDraw->setAdditionalFile("JS", "/bitrix/tools/bxready/library/elements/ecommerce.v3.lite/include/script.js", true);
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready/library/elements/ecommerce.v3.lite/include/style.css", false);
?>
<?if ($_REQUEST["bxr_ajax"]):
	global $resizeIndicator;

	if ($resizeIndicator != true){
		$resizeIndicator = true;
		?>
		<script>

			catalogEcommerceV3Lite.resizeVerticalBlock();

		</script>

	<?}

endif;?>

<script>

        catalogEcommerceV3Lite.resizeVerticalBlock();

</script>