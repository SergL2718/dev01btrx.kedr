<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>
<?$denomination = 10000;?>
<h3 class="bxr-detail-tab-mobile-title  hidden-lg hidden-md hidden-sm"><?=GetMessage("OFFERS_TEXT")?></h3>
<div class="bxr-detail-tab bxr-detail-offers" data-tab="offers">
    <table width="100%">
        <tbody>
            <?  foreach ($arResult["OFFERS"] as $key => $offer) {?>
                <?$propsStr = "";
                foreach($offer["PROPERTIES"] as $propCode => $arProp):
                    $printValue = "";?>
                    <? if (array_key_exists($propCode, $arResult["OFFERS_PROP"]) || in_array($arProp["CODE"], $arParams["~OFFERS_PROPERTY_CODE"])): 
                        $sPropId = $arResult["SKU_PROPS"][$propCode]["XML_MAP"][$arProp["VALUE"]];
                        if ($arProp["PROPERTY_TYPE"] == "E" && strlen($arResult["SKU_PROPS"][$propCode]["VALUES"][$arProp["VALUE"]]["NAME"]) > 0) {
                            $printValue = $arProp["NAME"].": ".$arResult["SKU_PROPS"][$propCode]["VALUES"][$arProp["VALUE"]]["NAME"];
                        } else if ($arProp["PROPERTY_TYPE"] == "S" && strlen($arResult["SKU_PROPS"][$propCode]["VALUES"][$sPropId]["NAME"]) > 0) {
                            $printValue = $arProp["NAME"].": ".$arResult["SKU_PROPS"][$propCode]["VALUES"][$sPropId]["NAME"];
                        } else if ($arProp["PROPERTY_TYPE"] == "L" && $arProp["MULTIPLE"] == "Y" && $arProp["VALUE"]) {
                                $printValue = $arProp["NAME"].": ";
                                $valueCount = count($arProp["VALUE"])-1;
                                foreach ($arProp["VALUE"] as $key => $value)
                                {
                                    $printValue .= $value;
                                    if ($key!=$valueCount) $printValue .= ',';
                                }
                        } else if (strlen($arProp["VALUE"]) > 0) {
                                $printValue = $arProp["NAME"].": ".$arProp["VALUE"];
                        }
                        ?>
                        <?
                            if(!empty($printValue))
                                $propsStr .= $printValue.", ";
                        ?>
                    <? endif;?>
                <?endforeach;?>
                <?$propsStr = rtrim($propsStr, ", ");?>
            <tr data-offer-id="<?=$offer["ID"]?>" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <td class="basket-image first hidden-xs">
                    <a href="<?=$offer["DETAIL_PAGE_URL"]?>" class="bxr-offer-img-in-list"<?=$offer["DETAIL_PAGE_URL"]?' itemprop="url"':''?>>
                        <?
                        if (is_array($offer["PREVIEW_PICTURE"])) {
                            $src = $offer["PREVIEW_PICTURE"]["SRC"];
                        } elseif (intval($offer["PREVIEW_PICTURE"]) > 0) {
                            $src = CFile::GetPath($offer["PREVIEW_PICTURE"]);
                        } elseif (is_array($offer["DETAIL_PICTURE"])) {
                            $src = $offer["DETAIL_PICTURE"]["SRC"];
                        } elseif (intval($offer["DETAIL_PICTURE"]) > 0) {
                            $src = CFile::GetPath($offer["DETAIL_PICTURE"]);
                        } elseif ($arResult["MORE_PHOTO"][0]["SRC"]) {
                            $src = $arResult["MORE_PHOTO"][0]["SRC"];
                        } else {
                            $src = SITE_TEMPLATE_PATH."/images/no-photo.png";
                        }
                        ?>
                        <img src="<?=$src?>" itemprop="image" alt="<?=$offer["NAME"]?>">
                    </a>    
                </td>
                <td class="basket-name">
                    <a href="<?=$offer["DETAIL_PAGE_URL"]?>" class="bxr-font-hover-light" itemprop="sku">
                        <?=$offer["NAME"]?>
                    </a>
                    <div class="offers-display-props"><?=$propsStr?></div>
                    <input type="hidden" value="<?=$propsStr?>" class="offers-props">
                </td>
                <td class="basket-price bxr-format-price hidden-xs">
                    <div class="bxr-offer-price-wrap" data-item="<?=$offer["ID"]?>">
                        <?foreach($offer["PRICES"] as $priceCode => $arPrice):?>
                            <?if (in_array($arResult["CAT_PRICES"][$priceCode]["ID"], $arResult["PRICES_ALLOW"])):?>
                                <div class="bxr-market-item-price bxr-format-price <?if (!$priceNameShow || count($offer["PRICES"]) == 1) echo 'bxr-market-price-without-name'?>">
                                    <!--price name-->
                                    <?if ($priceNameShow && count($offer["PRICES"]) > 1) {?>
                                        <span class="bxr-market-price-name"><?=$arResult["CAT_PRICES"][$priceCode]["TITLE"]?></span>
                                    <?}?>
                                    <!--next blocks has float right--> 
                                    <!--current price with all discounts-->
                                    <span itemprop="price" class="bxr-market-current-price bxr-market-format-price" id="<? echo $arItemIDs['PRICE']; ?>"><?=CurrencyFormat($arPrice['DISCOUNT_VALUE'], $arPrice['CURRENCY'])?></span>
                                    <br><span class="bxr-market-current-price-denom bxr-market-format-price" id="<? echo $arItemIDs['PRICE']; ?>_denom"><?=CurrencyFormat($arPrice['DISCOUNT_VALUE']*$denomination, $arPrice['CURRENCY'])?></span>
                                    <!--old price-->
                                    <?if ($boolDiscountShow && $arPrice['VALUE'] != $arPrice['DISCOUNT_VALUE']) {?>
                                        <span class="bxr-market-old-price hidden-xs" id="<? echo $arItemIDs['OLD_PRICE']; ?>"><?=$arPrice['PRINT_VALUE']?></span>
                                        <br><span class="bxr-market-old-price-denom hidden-xs" id="<? echo $arItemIDs['OLD_PRICE']; ?>_denom"><?=$arPrice['VALUE']*$denomination?></span>
                                    <?}?>
                                    <div class="clearfix"></div>
                                </div>
                                <?if (!$priceNameShow || count($offer["PRICES"]) == 1) {?>
                                    <div class="clearfix"></div>
                                <?}?>
                            <?endif;?>
                        <?endforeach;?>
                    </div>
                </td>
                <td class="basket-line-qty">
                     <span class="bxr-market-current-price bxr-market-format-price hidden-lg hidden-md hidden-sm" id="<? echo $arItemIDs['PRICE']; ?>"><?=CurrencyFormat($arPrice['DISCOUNT_VALUE'], $arPrice['CURRENCY'])?></span>
                    <div class="offers-btn-wrap" data-item="<?=$offer["ID"]?>">
                        <?if ($offer["CATALOG_QUANTITY"] <= 0 && $offer["CATALOG_CAN_BUY_ZERO"] == "N") {?>
                            <button class="bxr-color-button bxr-trade-request" value="<?=$offer["ID"]?>">
                                <?=GetMessage("REQUEST_BTN")?>
                            </button>
                        <?} else {?>
                            <form class="bxr-basket-action bxr-basket-group bxr-currnet-torg" action="">
                                <input type="button" class="bxr-quantity-button-minus hidden-xs" value="-" data-item="<?=$offer["ID"]?>">
                                <input type="text" name="quantity" value="1" class="bxr-quantity-text hidden-xs" data-item="<?=$offer["ID"]?>">
                                <input type="button" class="bxr-quantity-button-plus hidden-xs" value="+" data-item="<?=$offer["ID"]?>" data-max="<?=$offer["CATALOG_QUANTITY"]?>">
                                <button class="bxr-color-button bxr-color-button-small-only-icon bxr-basket-add">
                                    <span class="fa fa-shopping-cart"></span>
                                </button>
                                <input class="bxr-basket-item-id" type="hidden" name="item" value="<?=$offer["ID"]?>">
                                <input type="hidden" name="action" value="add">
                            </form>
                            <!--one click buy block-->
                            <div class="bxr-basket-action hidden-xs">
                                <button class="bxr-color-button bxr-one-click-buy" data-item="<?=$offer["ID"]?>">
                                    <?=GetMessage("ONE_CLICK_BUY")?>
                                </button>
                            </div>
                            <div class="clearfix"></div>
                        <?}?>
                    </div>
                </td>
            </tr>
            <?}?>
        </tbody>
    </table>
</div>

<script>
$(document).on("mouseover", ".bxr-detail-offers tr", function() {
    trade_id = '<?=$arResult["ID"]?>';
    trade_name = '<?=$arResult["NAME"]?>';
    trade_link = '<?=$arResult["DETAIL_PAGE_URL"]?>';
    selectParams = $(this).find('input.offers-props').val();
    current_offer_id = $(this).data('offer-id');
    formRequestMsg = "<?=GetMessage('OFFER_REQUEST_MSG')?>";
    formRequestMsg = formRequestMsg.replace("#PARAMS#",selectParams);
    formRequestMsg = formRequestMsg.replace("#TRADE_NAME#",'<?=$arResult["NAME"]?>'); 
});
</script>