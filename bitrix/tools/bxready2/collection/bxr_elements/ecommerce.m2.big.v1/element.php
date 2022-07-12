<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Alexkova\Bxready2\Draw;
use Alexkova\Market2\Core;
IncludeModuleLangFile(__FILE__);

$addClass = '';
$picture = false;

global $APPLICATION;
$elementDraw = \Alexkova\Bxready2\Draw::getInstance($this);

if (isset($arElementParams["BXR_PRESENT_SETTINGS"]["BXREADY_LIST_MARKER_TYPE"]) && strlen($arElementParams["BXR_PRESENT_SETTINGS"]["BXREADY_LIST_MARKER_TYPE"])>0)
    $markerCollection = $arElementParams["BXR_PRESENT_SETTINGS"]["BXREADY_LIST_MARKER_TYPE"];
if (isset($arElementParams["BXR_PRESENT_SETTINGS"]["BXREADY_LIST_OWN_MARKER_USE"]) && $arElementParams["BXR_PRESENT_SETTINGS"]["BXREADY_LIST_OWN_MARKER_USE"] == "Y"
    && isset($arElementParams["BXR_PRESENT_SETTINGS"]["BXREADY_LIST_OWN_MARKER_TYPE"]) && strlen($arElementParams["BXR_PRESENT_SETTINGS"]["BXREADY_LIST_OWN_MARKER_TYPE"])>0)
    $markerCollection = $arElementParams["BXR_PRESENT_SETTINGS"]["BXREADY_LIST_OWN_MARKER_TYPE"];
if (strlen($markerCollection) > 0)
    $elementDraw->setMarkerCollection($markerCollection);

$markerGroup = array(
    "NEW" => ($arElement["PROPERTIES"]["NEWPRODUCT"]["VALUE_XML_ID"] == "Y") ? true : false,
    "SALE" => ($arElement["PROPERTIES"]["SPECIALOFFER"]["VALUE_XML_ID"] == "Y") ? true : false,
    "DISCOUNT" => $arElement["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"],
    "HIT" => ($arElement["PROPERTIES"]["SALELEADER"]["VALUE_XML_ID"] == "Y") ? true : false,
    "REC" => ($arElement["PROPERTIES"]["RECOMMENDED"]["VALUE_XML_ID"] == "Y") ? true : false,
);

$markerParams = array(
    "BXREADY_USER_TYPES" => $arElementParams["BXR_PRESENT_SETTINGS"]["BXREADY_LIST_OWN_MARKER_USE"],
    "BXREADY_USER_TYPE_VARIANT" => $markerCollection
);

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

$useVoteRating = ($arElementParams['BXR_PRESENT_SETTINGS']['BXR_SHOW_RATING'] != "N") ? true : false;
$rating = 0;
if ($useVoteRating)
{
    if ($arElementParams['BXR_PRESENT_SETTINGS']['BXR_SHOW_RATING'] == 'vote_avg')
        $rating = (($arElement['PROPERTIES']['vote_sum']['VALUE']/$arElement['PROPERTIES']['vote_count']['VALUE'])/5)*100;
    else
        $rating = ($arElement['PROPERTIES']['rating']['VALUE']/5)*100;

    $rating = (int)$rating;
}
$useCompare = (!$arElementParams['DISPLAY_COMPARE']) ? false : true;
$showCatalogQty = ($arElementParams["SHOW_MAX_QUANTITY"] != "N") ? true : false;
?><div class="bxr-big-ecommerce-v1<?=' '.$arElementParams['BXR_PRESENT_SETTINGS']['BXREADY_ELEMENT_ADDCLASS']?>" data-uid="<?=$UID?>" data-resize="1" id="<?=$arItemIDs["ID"]?>">
    <?include('block_templates/fast_view.php');?>
    <div class="bxr-element-container">
        <div class="bxr-element-inner-container">
            <?$elementDraw->showMarkerGroup($markerGroup, false, $markerParams);?>
            <?include('block_templates/picture.php');?>
            <?include('block_templates/name.php');?>
            <div class="bxr-element-avail-wrap">
                <?include('block_templates/avail.php');?>
                <?include('block_templates/article.php');?>
            </div>
            <?include('block_templates/buy_block.php');?>
            <?include('block_templates/preview_text.php');?>
            <?include('block_templates/action_timer.php');?>
            <?include('block_templates/sku.php');?>
            <?include('block_templates/indicators.php');?>
            <?$arJSParams = array(
                'PRODUCT_TYPE' => $arElement['CATALOG_TYPE'],
                'SHOW_QUANTITY' => ($arElementParams['USE_PRODUCT_QUANTITY'] == 'Y'),
                'SHOW_ADD_BASKET_BTN' => false,
                'SHOW_BUY_BTN' => true,
                'SHOW_ABSENT' => true,
                'SHOW_SKU_PROPS' => $arElement['OFFERS_PROPS_DISPLAY'],
                'SECOND_PICT' => $arElement['SECOND_PICT'],
                'SHOW_OLD_PRICE' => ('Y' == $arElementParams['SHOW_OLD_PRICE']),
                'SHOW_DISCOUNT_PERCENT' => ('Y' == $arElementParams['SHOW_DISCOUNT_PERCENT']),
                'ADD_TO_BASKET_ACTION' => $arElementParams['ADD_TO_BASKET_ACTION'],
                'SHOW_CLOSE_POPUP' => ($arElementParams['SHOW_CLOSE_POPUP'] == 'Y'),
                'DISPLAY_COMPARE' => $arElementParams['DISPLAY_COMPARE'],
                'DEFAULT_PICTURE' => array(
                    'PICTURE' => $arElement['PRODUCT_PREVIEW'],
                    'PICTURE_SECOND' => $arElement['PRODUCT_PREVIEW_SECOND']
                ),
                'VISUAL' => array(
                    'ID' => $arItemIDs['ID'],
                    'NAME' => $arItemIDs['NAME'],
                    'PICT_ID' => $arItemIDs['PICT'],
                    'AVAIL_ID' => $arItemIDs['AVAIL'],
                    'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
                    'PICT_SLIDER_ID' => $arItemIDs['PICT_SLIDER'],
                    'QUANTITY_ID' => $arItemIDs['QUANTITY'],
                    'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
                    'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
                    'QUANTITY_MEASURE' => $arItemIDs['QUANTITY_MEASURE'],
                    'PRICE_ID' => $arItemIDs['PRICE'],
                    'TREE_ID' => $arItemIDs['PROP_DIV'],
                    'TREE_ITEM_ID' => $arItemIDs['PROP'],
                    'BUY_ID' => $arItemIDs['BUY_LINK'],
                    'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
                    'DSC_PERC' => $arItemIDs['DSC_PERC'],
                    'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
                    'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
                    'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
                    'NOT_AVAILABLE_MESS' => $arItemIDs['NOT_AVAILABLE_MESS'],
                    'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK']
                ),
                'BASKET' => array(
                    'QUANTITY' => $arElementParams['PRODUCT_QUANTITY_VARIABLE'],
                    'PROPS' => $arElementParams['PRODUCT_PROPS_VARIABLE'],
                    'SKU_PROPS' => $arElement['OFFERS_PROP_CODES'],
                    'ADD_URL_TEMPLATE' => $arElementParams['~ADD_URL_TEMPLATE'],
                    'BUY_URL_TEMPLATE' => $arElementParams['~BUY_URL_TEMPLATE']
                ),
                'PRODUCT' => array(
                    'ID' => $arElement['ID'],
                    'NAME' => $arElement['NAME'],
                    'DETAIL_PAGE_URL' => $arElement['DETAIL_PAGE_URL'],
                    'MORE_PHOTO' => $arElement['MORE_PHOTO'],
                    'MORE_PHOTO_COUNT' => $arElement['MORE_PHOTO_COUNT']
                ),
                'OFFERS' => $arElement['JS_OFFERS'],
                'OFFER_SELECTED' => $arElement['OFFERS_SELECTED'],
                'TREE_PROPS' => $arSkuProps,
                'LAST_ELEMENT' => $arElement['LAST_ELEMENT']
            );
            if ($arElementParams['DISPLAY_COMPARE'])
            {
                $arJSParams['COMPARE'] = array(
                    'COMPARE_URL_TEMPLATE' => $arElementParams['~COMPARE_URL_TEMPLATE'],
                    'COMPARE_PATH' => $arElementParams['COMPARE_PATH']
                );
            }
            $arJSParams['USE_ENHANCED_ECOMMERCE'] = $arElementParams['USE_ENHANCED_ECOMMERCE'];
            $arJSParams['DATA_LAYER_NAME'] = $arElementParams['DATA_LAYER_NAME'];
            $arJSParams['BRAND_PROPERTY'] = !empty($arElement['DISPLAY_PROPERTIES'][$arElementParams['BRAND_PROPERTY']])
                    ? $arElement['DISPLAY_PROPERTIES'][$arElementParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
                    : null;
            
            ?><script type="text/javascript">
                var useSkuLinks = '<?=$bxr_use_links_sku?>';
                var <? echo $strObName; ?> = new JCCatalogECommerceBig(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
            </script></div></div></div><?
include 'epilog.php';
?>
<script>
    $(document).ready(function(){
        bxrM2EcommerceBigV1.init('<?=($arElementParams['LAZY_LOAD']) ? 'true' : 'false'?>');
    });
</script>