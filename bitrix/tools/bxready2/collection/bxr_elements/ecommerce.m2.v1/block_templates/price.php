<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$boolDiscountShow = ('Y' == $arElementParams['SHOW_OLD_PRICE']);

?><div class="bxr-element-price" id="<?=$arItemIDs["PRICE"]?>">
<div class="bxr-product-price-wrap">
    <div class="bxr-market-item-price bxr-format-price"><?
        //--old price-->
        
        $priceValue = ($arElement['MIN_PRICE']['VALUE_VAT']) ? $arElement['MIN_PRICE']['VALUE_VAT'] : $arElement['MIN_PRICE']['VALUE'];
        $discountValue = ($arElement["MIN_PRICE"]['UNROUND_DISCOUNT_VALUE']) ? $arElement["MIN_PRICE"]['UNROUND_DISCOUNT_VALUE'] : (($arElement["MIN_PRICE"]['DISCOUNT_VALUE_VAT']) ? $arElement["MIN_PRICE"]['DISCOUNT_VALUE_VAT'] : $arElement["MIN_PRICE"]['DISCOUNT_VALUE']);

        if ($boolDiscountShow && $priceValue && $discountValue && $priceValue != $discountValue) { 
            ?><span class="bxr-market-old-price"><?=Alexkova\Market2\Core::bxrFormatPrice($arElement["MIN_PRICE"]['PRINT_VALUE'], false, true, true)?></span><?
        }
        //--current price with all discounts-->
        if (!empty($arElement["OFFERS"])){
            ?><span class="bxr-detail-from"><?=GetMessage("BXR_FROM");?></span><?
        }
        ?><span class="bxr-market-current-price bxr-market-format-price"><?
            echo Alexkova\Market2\Core::bxrFormatPrice($arElement["MIN_PRICE"]['PRINT_DISCOUNT_VALUE'], false, true);
        ?></span>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
</div><?

if (count($arElement["OFFERS"])>0)
foreach($arElement["OFFERS"] as $offer):
    //echo "<pre>"; print_r($offer); echo "</pre>";?>
    <div class="bxr-offer-price-wrap" id="bxr-offer-price-<?=$offer["ID"]?>" data-item="<?=$offer["ID"]?>" style="display: none;"><?
        $arPrice = $offer["MIN_PRICE"];
        ?><div class="bxr-market-item-price bxr-format-price"><?
            //--old price-->
            
            $offerDiscountValue = $arPrice['DISCOUNT_VALUE'];
            if ($arPrice['DISCOUNT_VALUE_VAT']) {
                $offerDiscountValue = $arPrice['DISCOUNT_VALUE_VAT'];
            }
            if ($arPrice['UNROUND_DISCOUNT_VALUE']) {
                $offerDiscountValue = $arPrice['UNROUND_DISCOUNT_VALUE'];
            }

            if ($boolDiscountShow && $arPrice['VALUE'] != $offerDiscountValue) {
                ?><span class="bxr-market-old-price" id="<? echo $arItemIDs['OLD_PRICE']; ?>"><?=Alexkova\Market2\Core::bxrFormatPrice($arPrice['PRINT_VALUE'], false, true)?></span><?
            }
            //--current price with all discounts-->
            ?><span class="bxr-market-current-price bxr-market-format-price" id="<? echo $arItemIDs['PRICE']; ?>"><?
                echo Alexkova\Market2\Core::bxrFormatPrice(CurrencyFormat($arPrice['DISCOUNT_VALUE'],$arPrice["CURRENCY"]), false, true);
            ?></span>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div><?
endforeach;
?></div>