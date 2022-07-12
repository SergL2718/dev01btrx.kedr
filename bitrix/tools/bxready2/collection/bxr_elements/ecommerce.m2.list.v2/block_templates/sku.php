<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if (count($arElement["OFFERS"]) > 0) {?>
    <tr class="bxr-element-offers-tr">
        <td colspan="3">
            <div class="bxr-element-offers">
                <?
                $module_id = "alexkova.market2";

                $bxr_use_links_sku = COption::GetOptionString($module_id, "bxr_use_links_sku", "N");

                $bxr_use_links_sku_sef_section = COption::GetOptionString($module_id, "bxr_use_links_sku_sef_section", "");
                $offerMask = ($bxr_use_links_sku_sef_section != "") ? $bxr_use_links_sku_sef_section : 'offer';

                $bxr_use_links_sku_sef_request = COption::GetOptionString($module_id, "bxr_use_links_sku_sef_request", "");
                $offerRequestMask = ($bxr_use_links_sku_sef_request != "") ? $bxr_use_links_sku_sef_request : 'offer_id';

                $bxr_use_links_sku_sef = COption::GetOptionString($module_id, "bxr_use_links_sku_sef", "N");
                $bxr_use_links_sku_sef_code = COption::GetOptionString($module_id, "bxr_use_links_sku_sef_code", "N");

                $boolDiscountShow = ('Y' == $arElementParams['SHOW_OLD_PRICE']);
                $showSubscribeBtn = ($arElement['CATALOG_SUBSCRIBE'] == 'Y') ? true : false;
                ?>
                <div class="bxr-detail-tab bxr-detail-offers" data-tab="offers">
                    <table width="100%">
                        <tbody>
                            <?  foreach ($arElement["OFFERS"] as $key => $offer) {
                                $arElement['OFFERS'][$key]["DETAIL_PAGE_URL"] = rtrim($arElement["DETAIL_PAGE_URL"], '/')."/".$offerMask."/".$offer["ID"]."/";
                                $offer["DETAIL_PAGE_URL"] = ($bxr_use_links_sku_sef == "Y") ? (($bxr_use_links_sku_sef_code == "Y") ? $arElement["DETAIL_PAGE_URL"].$offerMask."/".$offer["CODE"]."/" : $arElement["DETAIL_PAGE_URL"].$offerMask."/".$offer["ID"]."/") : $arElement["DETAIL_PAGE_URL"]."?".$offerRequestMask."=".$offer["ID"];?>
                                <tr data-offer-id="<?=$offer["ID"]?>" itemprop="offers" itemscope itemtype="http://schema.org/Offer">

                                <?
                                $propsStr = "";
                                foreach($offer["PROPERTIES"] as $propCode => $arProp):
                                    $printValue = "";
                                    if (array_key_exists($propCode, $arElement["OFFERS_PROP"]) || in_array($arProp["CODE"], $arElementParams["~OFFERS_PROPERTY_CODE"])):
                                        $sPropId = $arElementParams["SKU_PROPS"][$propCode]["XML_MAP"][$arProp["VALUE"]];
                                        if ($arProp["PROPERTY_TYPE"] == "E" && strlen($arElementParams["SKU_PROPS"][$propCode]["VALUES"][$arProp["VALUE"]]["NAME"]) > 0) {
                                            $printValue = $arProp["NAME"].": ".$arElementParams["SKU_PROPS"][$propCode]["VALUES"][$arProp["VALUE"]]["NAME"];
                                        } else if ($arProp["PROPERTY_TYPE"] == "S" && strlen($arElementParams["SKU_PROPS"][$propCode]["VALUES"][$sPropId]["NAME"]) > 0) {
                                            $printValue = $arProp["NAME"].": ".$arElementParams["SKU_PROPS"][$propCode]["VALUES"][$sPropId]["NAME"];
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

                                            if(!empty($printValue))
                                                $propsStr .= $printValue.", ";

                                    endif;
                                endforeach;
                                $propsStr = rtrim($propsStr, ", ");

                                $offer["OFFER_PROPS_TEXT"] = $propsStr;
                                $offer["MSG"] = str_replace("#TRADE_NAME#", htmlspecialchars($offer["NAME"],ENT_QUOTES, SITE_CHARSET), GetMessage('OFFER_REQUEST_MSG'));
                                $offer["MSG"] = str_replace("#PARAMS#", htmlspecialchars($propsStr,ENT_QUOTES, SITE_CHARSET), $offer["MSG"]);

                                $offer["BASKET_VALUES"] = $arElement['OFFERS'][$key]["BASKET_VALUES"] = array(
                                    "ID" => $arElement["ID"],
                                    "OFFER_ID" => $offer["ID"],
                                    "NAME" => $offer["NAME"],
                                    "LINK" => $offer["DETAIL_PAGE_URL"],
                                    "IMG" => $offer["PREVIEW_PICTURE"]["SRC"],
                                    "MSG" => $offer['MSG'],
                                    "HAS_PRICE" => (!empty($offer['MIN_PRICE'])) ? 'Y' : 'N',
                                    "CATALOG_QUANTITY" => $offer['CATALOG_QUANTITY'],
                                    "CATALOG_CAN_BUY_ZERO" => $offer['CATALOG_CAN_BUY_ZERO'],
                                    "MAX_QTY" => ($offer['CATALOG_CAN_BUY_ZERO'] == 'Y') ? 0 : $offer['CATALOG_QUANTITY'],
                                    "CATALOG_SUBSCRIBE" => $offer['CATALOG_SUBSCRIBE'],
                                    "QTY_MAX" => $offer['QTY_MAX'],
                                    "RATIO" => $offer['RATIO'],
                                    "START_QTY" => $offer['START_QTY']
                                );
                                ?>

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
                                                    <?$APPLICATION->includeComponent('bxready.market2:catalog.product.subscribe','',
                                                        array(
                                                            'PRODUCT_ID' => $offer['ID'],
                                                            'BUTTON_ID' => 'bxr-ev2list-'.$UID.'-'.$offer['ID'].'-subscribe',
                                                            'BUTTON_CLASS' => 'bxr-color-button bxr-subscribe',
                                                        ),
                                                        $component, array('HIDE_ICONS' => 'Y')
                                                    );?>
                                                </div>
                                            <?} else {?>
                                                <button class="bxr-color-button bxr-trade-request"
                                                    value="<?=$offer["ID"]?>"
                                                    data-pid="<?=$arElement["ID"]?>"
                                                    data-oid="<?=$offer["ID"]?>"
                                                    data-toggle="modal"
                                                    data-target="#bxr-request-product-popup">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                    <span><?=GetMessage("REQUEST_BTN")?></span>
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
                            <script>
                                if (!BXReady.Market.basketValues[<?=$offer['ID']?>])
                                    BXReady.Market.basketValues[<?=$offer['ID']?>] = {
                                        ID: '<?=$arElement['ID']?>',
                                        OFFER_ID: '<?=$offer['ID']?>',
                                        NAME: '<?=$offer["BASKET_VALUES"]["NAME"]?>',
                                        LINK: '<?=$offer["BASKET_VALUES"]["LINK"]?>',
                                        IMG: '<?=$offer["BASKET_VALUES"]["IMG"]?>',
                                        MSG: '<?=$offer["BASKET_VALUES"]["MSG"]?>',
                                        HAS_PRICE: '<?=$offer["BASKET_VALUES"]["HAS_PRICE"]?>',
                                        CATALOG_QUANTITY: '<?=$offer["BASKET_VALUES"]['CATALOG_QUANTITY']?>',
                                        CATALOG_CAN_BUY_ZERO: '<?=$offer["BASKET_VALUES"]['CATALOG_CAN_BUY_ZERO']?>',
                                        CATALOG_SUBSCRIBE: '<?=$offer["BASKET_VALUES"]['CATALOG_SUBSCRIBE']?>',
                                        QTY_MAX: '<?=$offer["BASKET_VALUES"]['QTY_MAX']?>',
                                        RATIO: '<?=$offer["BASKET_VALUES"]['RATIO']?>',
                                        START_QTY: '<?=$offer["BASKET_VALUES"]['START_QTY']?>'
                                    };
                            </script>

                            <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
        </td>
    </tr>
<?}?>