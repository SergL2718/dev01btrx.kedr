<?
//--basket-btns block--
$showSubscribeBtn = ($arElement['CATALOG_SUBSCRIBE'] == 'Y') ? true : false;
global $ratio_settings,$bxr_ratio_prop_code;
if (count($arElement["OFFERS"]) > 0) {?>
    <a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="bxr-color-button" id="<?=$arItemIDs["BUY_LINK"]?>">
	<?=GetMessage("MORE_INFO_TITLE")?>
    </a>
<?} else {
	if ($arElement["CATALOG_QUANTITY"] <= 0 && $arElement["CATALOG_CAN_BUY_ZERO"] == "N" || !$arElement["MIN_PRICE"]['VALUE']) {
            if($showSubscribeBtn) {?>
                <div class="bxr-subscribe-wrap">
                    <? include_once 'subscribe_script.php';
                    $APPLICATION->includeComponent('alexkova.market:catalog.product.subscribe','',
                        array(
                            'PRODUCT_ID' => $arElement['ID'],
                            'BUTTON_ID' => 'bxr-ev3lc-'.$UID.'-'.$arElement['ID'].'-subscribe',
                            'BUTTON_CLASS' => 'bxr-color-button bxr-subscribe',
                        ),
                        $component, array('HIDE_ICONS' => 'Y')
                    );?>
                </div>
            <?} else {?>
                <button class="bxr-color-button bxr-trade-request" value="<?=$arElement["ID"]?>"
                                data-trade-id="<?=$arElement["ID"]?>"
                                data-trade-name="<?=$arElement["NAME"]?>"
                                data-trade-link="<?=$arElement["DETAIL_PAGE_URL"]?>"
                                data-msg="<?=str_replace('#TRADE_NAME#', $arElement["NAME"], GetMessage('TRADE_REQUEST_MSG'))?>">
                        <?=GetMessage("REQUEST_BTN")?>
                </button>
            <?}
	} else {
            if (is_array($arElement["BASKET_PROPS"]["REQUIRED_CHECK"]) || is_array($arElement["BASKET_PROPS"]["OPTIONAL_CHECK"])) {?>
                <a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="bxr-color-button" id="<?=$arItemIDs["BUY_LINK"]?>">
                    <?=GetMessage("MORE_INFO_TITLE")?>
                </a>
            <?} else {
            $qtyMax = ($arElement["CATALOG_CAN_BUY_ZERO"] == "Y") ? 0 : $arElement["CATALOG_QUANTITY"];
            
            $ratio = 1;
            
            if($ratio_settings == "own_prop" && strlen($bxr_ratio_prop_code)>0 
                    && isset($arElement["PROPERTIES"][$bxr_ratio_prop_code]["VALUE"]) && is_numeric($arElement["PROPERTIES"][$bxr_ratio_prop_code]["VALUE"]))
                $ratio = $arElement["PROPERTIES"][$bxr_ratio_prop_code]["VALUE"];
            elseif($ratio_settings == "base")
                $ratio = $arElement["CATALOG_MEASURE_RATIO"];
            
            $quantity = ($ratio > $arElement["CATALOG_QUANTITY"] && $arElement["CATALOG_QUANTITY"]>0) ? $arElement["CATALOG_QUANTITY"] : $ratio;?>
		<form class="bxr-basket-action bxr-basket-group bxr-currnet-torg" action="">
			<input type="button" class="bxr-quantity-button-minus" value="-" data-item="<?=$arElement["ID"]?>" data-ratio="<?=$ratio;?>">
                        <input type="text" name="quantity" value="<?=$quantity;?>" class="bxr-quantity-text" data-item="<?=$arElement["ID"]?>">
                        <input type="button" class="bxr-quantity-button-plus" value="+" data-item="<?=$arElement["ID"]?>" data-ratio="<?=$ratio;?>" data-max="<?=$qtyMax?>">
			<button class="bxr-color-button bxr-color-button-small-only-icon bxr-basket-add">
				<span class="fa fa-shopping-cart"></span>
			</button>
			<input class="bxr-basket-item-id" type="hidden" name="item" value="<?=$arElement["ID"]?>">
			<input type="hidden" name="action" value="add">
		</form>
		<div class="clearfix"></div>
            <?}?>
	<?}
}