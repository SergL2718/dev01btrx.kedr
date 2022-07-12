<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Alexkova\Bxready2\Draw;
use Alexkova\Market2\Core;
IncludeModuleLangFile(__FILE__);

$addClass = '';
$picture = false;

global $APPLICATION;
$elementDraw = \Alexkova\Bxready2\Draw::getInstance($this);

$UID = (intval($arElementParams["UNICUM_ID"])>0) ? $arElementParams["UNICUM_ID"] : 1;

$strMainID = $arElementParams["AREA_IDS"][$arElement["ID"]];
$arItemIDs = array(
    'ID' => $strMainID,
    'NAME' => $strMainID.'_name',
    'PICT' => $strMainID.'_pict',
    'SECOND_PICT' => $strMainID.'_secondpict',
    'PICT_SLIDER' => $strMainID.'_pict_slider',
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
    'DISCOUNT_TIMER' => $strMainID.'_discount_timer',
    'PRICE' => $strMainID.'_price',
    'DSC_PERC' => $strMainID.'_dsc_perc',
    'SECOND_DSC_PERC' => $strMainID.'_second_dsc_perc',
    'PROP_DIV' => $strMainID.'_sku_tree',
    'PROP' => $strMainID.'_prop_',
    'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
    'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
    'FAST_VIEW' => $strMainID.'_fast_view',
);
$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

$useCompare = (!$arElementParams['DISPLAY_COMPARE']) ? false : true;
?><div class="bxr-m2-ecommerce-small-v1<?=' '.$arElementParams['BXR_PRESENT_SETTINGS']['BXREADY_ELEMENT_ADDCLASS']?>" data-uid="<?=$UID?>" data-resize="1" id="<?=$arItemIDs["ID"]?>">
        <div class="bxr-element-container">
            <?include('block_templates/basket_indicator.php');?>
            <?include('block_templates/picture.php');?>
            <?include('block_templates/name.php');?>
            <?include('block_templates/price.php');?>
            <?include('block_templates/hover_block.php');?>
        </div>
    </div><?
include 'epilog.php';
?>
<script>
    $(document).ready(function(){
        bxrM2EcommerceSmallV1.init('<?=($arElementParams['LAZY_LOAD']) ? 'true' : 'false'?>', '<?=$pictureContainerHeight?>');
    });
</script>