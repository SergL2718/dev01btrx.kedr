<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$bxr_use_links_sku = COption::GetOptionString("alexkova.market", "bxr_use_links_sku", "N");
$offerMask = "offer";
$boolDiscountShow = ('Y' == $arElementParams['SHOW_OLD_PRICE']);
$showSubscribeBtn = ($arElement['CATALOG_SUBSCRIBE'] == 'Y') ? true : false;
?>
<div class="bxr-detail-tab bxr-detail-offers" data-tab="offers">
    <table width="100%">
        <tbody>
            <?  foreach ($arElement["OFFERS"] as $key => $offer) {
                $arElement['OFFERS'][$key]["DETAIL_PAGE_URL"] = rtrim($arElement["DETAIL_PAGE_URL"], '/')."/".$offerMask."/".$offer["ID"]."/";
                $offer["DETAIL_PAGE_URL"] = rtrim($arElement["DETAIL_PAGE_URL"], '/')."/".$offerMask."/".$offer["ID"]."/";?>
            <tr data-offer-id="<?=$offer["ID"]?>" itemprop="offers" itemscope itemtype="http://schema.org/Offer">

                <?$propsStr = "";?>
                <?foreach($arElementParams["OFFERS_PROPERTY_CODE"] as $propCode):?>
                    <?$printValue = "";?>
                    <? if (array_key_exists($propCode, $offer["PROPERTIES"])):?>
                        <?
                            if ($offer["PROPERTIES"][$propCode]["PROPERTY_TYPE"] == "E") {
                                $printValue = $arProp["NAME"].": ".$arResult["SKU_PROPS"][$propCode]["VALUES"][$arProp["VALUE"]]["NAME"];
                            } else if ($offer["PROPERTIES"][$propCode]["PROPERTY_TYPE"] == "S") {
                                $printValue = $offer["PROPERTIES"][$propCode]["NAME"].": ".$offer["PROPERTIES"][$propCode]["DISPLAY_VALUE"];
                            } else if ($offer["PROPERTIES"][$propCode]["PROPERTY_TYPE"] == "L" 
                                        && $offer["PROPERTIES"][$propCode]["MULTIPLE"] == "Y" 
                                            && $offer["PROPERTIES"][$propCode]["VALUE"]) {
                                $printValue = $offer["PROPERTIES"][$propCode]["NAME"].": ";
                                $valueCount = count($offer["PROPERTIES"][$propCode]["VALUE"])-1;
                                foreach ($offer["PROPERTIES"][$propCode]["VALUE"] as $key => $value)
                                {
                                    $printValue .= $value;
                                    if ($key!=$valueCount) $printValue .= ',';
                                }
                            } else if (strlen($offer["PROPERTIES"][$propCode]["VALUE"]) > 0) {
                                    $printValue = $offer["PROPERTIES"][$propCode]["NAME"].": ".$offer["PROPERTIES"][$propCode]["VALUE"];
                            }
                        ?>
                        <?
                            if(!empty($printValue)) $propsStr .= $printValue.", ";
                        ?>
                    <? endif;?>
                <?endforeach;?>
                <?$propsStr = rtrim($propsStr, ", ");?>
                
                <td class="basket-name">
                    <a href="<?if ($bxr_use_links_sku == "Y") echo  $offer['DETAIL_PAGE_URL']; else echo $arElement["DETAIL_PAGE_URL"]; ?>" class="bxr-font-hover-light" itemprop="sku">
                        <?=$offer["NAME"]?>
                    </a>
                    <div class="offers-display-props"><?=$propsStr?></div>
                    <input type="hidden" value="<?=$propsStr?>" class="offers-props">
                </td>
                
                <!--prices-->
                <td class="basket-price bxr-format-price hidden-xs">
                    <div class="bxr-offer-price-wrap" data-item="<?=$offer["ID"]?>">
                        <?foreach($offer["PRICES"] as $priceCode => $arPrice):?>
                            <div class="bxr-market-item-price bxr-format-price <?if (count($offer["PRICES"]) == 1) echo 'bxr-market-price-without-name'?>">
                                <!--price name-->
                                <?if (count($offer["PRICES"]) > 1) {?>
                                    <span class="bxr-market-price-name"><?=$arResult["CATALOG_GROUP_NAME_".$arPrice['PRICE_ID']]?></span>
                                <?}?>
                                <!--next blocks has float right--> 
                                <!--current price with all discounts-->
                                <span itemprop="price" class="bxr-market-current-price bxr-market-format-price"><?=$arPrice['PRINT_DISCOUNT_VALUE']?></span>
                                <!--old price-->
                                <?if ($boolDiscountShow && $arPrice['VALUE'] != $arPrice['DISCOUNT_VALUE']) {?>
                                    <span class="bxr-market-old-price hidden-xs"><?=$arPrice['PRINT_VALUE']?></span><br>
                                <?}?>
                                <div class="clearfix"></div>
                            </div>
                            <?if (count($offer["PRICES"]) == 1) {?>
                                <div class="clearfix"></div>
                            <?}?>
                        <?endforeach;?>
                    </div>
                </td>
                <!---->
                
                <td class="basket-line-qty">
                    <span class="bxr-market-current-price bxr-market-format-price hidden-lg hidden-md hidden-sm" id="<? echo $arItemIDs['PRICE']; ?>"><?=CurrencyFormat($arPrice['DISCOUNT_VALUE'], $arPrice['CURRENCY'])?><br></span>
                    <div class="offers-btn-wrap" data-item="<?=$offer["ID"]?>">
                        <?if ($offer["CATALOG_QUANTITY"] <= 0 && $offer["CATALOG_CAN_BUY_ZERO"] == "N" || !$offer["PRICES"]) {?>
                            <?if($showSubscribeBtn) {?>
                                <div class="bxr-subscribe-wrap">
                                    <? include_once 'subscribe_script.php';?>
                                    <?$APPLICATION->includeComponent('alexkova.market:catalog.product.subscribe','',
                                        array(
                                            'PRODUCT_ID' => $offer['ID'],
                                            'BUTTON_ID' => 'bxr-ev2list-'.$UID.'-'.$offer['ID'].'-subscribe',
                                            'BUTTON_CLASS' => 'bxr-color-button bxr-subscribe',
                                        ),
                                        $component, array('HIDE_ICONS' => 'Y')
                                    );?>
                                </div>
                            <?} else {?>
                                <button class="bxr-color-button bxr-trade-request" value="<?=$offer["ID"]?>"
                                        data-offer-id="<?=$offer["ID"]?>" 
                                        data-trade-id="<?=$arElement["ID"]?>" 
                                        data-trade-name="<?=$arElement["NAME"]?>" 
                                        data-trade-link="<?=$arElement["DETAIL_PAGE_URL"]?>" 
                                        data-prop-name="<?=$propsStr?>" 
                                        data-msg="<?=str_replace('#TRADE_NAME#', $arElement["NAME"], GetMessage('OFFER_REQUEST_MSG'))?>">
                                    <?=GetMessage("REQUEST_BTN")?>
                                </button>
                            <?}?>
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
                            <div class="clearfix"></div>
                        <?}?>
                    </div>
                </td>
            </tr>
            <?}?>
        </tbody>
    </table>
</div>