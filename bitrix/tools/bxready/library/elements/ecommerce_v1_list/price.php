<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$boolDiscountShow = ('Y' == $arElementParams['SHOW_OLD_PRICE']);
?>
<div class="bxr-product-price-wrap">
    <div class="bxr-market-item-price bxr-format-price">
        <!--old price-->
        <?
        $priceValue = $arElement['MIN_PRICE']['VALUE'];
        if ($arElement['MIN_PRICE']['VALUE_VAT']) {
            $priceValue = $arElement['MIN_PRICE']['VALUE_VAT'];
        }

        $discountValue = $arElement["MIN_PRICE"]['DISCOUNT_VALUE'];
        if ($arElement["MIN_PRICE"]['DISCOUNT_VALUE_VAT']) {
            $discountValue = $arElement["MIN_PRICE"]['DISCOUNT_VALUE_VAT'];
        }

        if ($arElement["MIN_PRICE"]['UNROUND_DISCOUNT_VALUE']) {
            $discountValue = $arElement["MIN_PRICE"]['UNROUND_DISCOUNT_VALUE'];
        }

        if ($boolDiscountShow && $priceValue && $discountValue && $priceValue != $discountValue) { ?>
            <span class="bxr-market-old-price"><?=$arElement["MIN_PRICE"]['PRINT_VALUE']?></span><br>
        <?}?>
        <!--current price with all discounts-->
        <span class="bxr-market-current-price bxr-market-format-price"><?=$arElement["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?></span>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
</div>
<?foreach($arElement["OFFERS"] as $offer):?>
    <div class="bxr-offer-price-wrap" id="bxr-offer-price-<?=$offer["ID"]?>" data-item="<?=$offer["ID"]?>" style="display: none;">
        <?$arPrice = $offer["MIN_PRICE"];?>
        <div class="bxr-market-item-price bxr-format-price">
            <!--old price-->
            <?
            $offerDiscountValue = $arPrice['DISCOUNT_VALUE'];
            if ($arPrice['DISCOUNT_VALUE_VAT']) {
                $offerDiscountValue = $arPrice['DISCOUNT_VALUE_VAT'];
            }
            if ($arPrice['UNROUND_DISCOUNT_VALUE']) {
                $offerDiscountValue = $arPrice['UNROUND_DISCOUNT_VALUE'];
            }

            if ($boolDiscountShow && $arPrice['VALUE'] != $offerDiscountValue) {?>
                <span class="bxr-market-old-price" id="<? echo $arItemIDs['OLD_PRICE'].'_'.$offer['ID']; ?>"><?=$arPrice['PRINT_VALUE']?></span><br>
            <?}?>
            <!--current price with all discounts-->
            <span class="bxr-market-current-price bxr-market-format-price" id="<? echo $arItemIDs['PRICE'].'_'.$offer['ID']; ?>"><?=CurrencyFormat($arPrice['DISCOUNT_VALUE'],$arPrice["CURRENCY"]) ?></span>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
<?endforeach;