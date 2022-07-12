<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="bxr-element-price" id="<?=$arItemIDs["PRICE"]?>">
    <?$boolDiscountShow = ('Y' == $arElementParams['SHOW_OLD_PRICE']);?>
    <div class="bxr-product-price-wrap">
        <div class="bxr-market-item-price bxr-format-price">
            <!--old price-->
            <?
            $priceValue = ($arElement['MIN_PRICE']['VALUE_VAT']) ? $arElement['MIN_PRICE']['VALUE_VAT'] : $arElement['MIN_PRICE']['VALUE'];
            $discountValue = ($arElement["MIN_PRICE"]['UNROUND_DISCOUNT_VALUE']) ? $arElement["MIN_PRICE"]['UNROUND_DISCOUNT_VALUE'] : (($arElement["MIN_PRICE"]['DISCOUNT_VALUE_VAT']) ? $arElement["MIN_PRICE"]['DISCOUNT_VALUE_VAT'] : $arElement["MIN_PRICE"]['DISCOUNT_VALUE']);

            if ($boolDiscountShow && $priceValue && $discountValue && $priceValue != $discountValue) { ?>
                <span class="bxr-market-old-price"><?=Alexkova\Market2\Core::bxrFormatPrice($arElement["MIN_PRICE"]['PRINT_VALUE'], false, true, true)?></span>
            <?}?>
            <!--current price with all discounts-->
            <? if (!empty($arElement["OFFERS"])){?>
                <span class="bxr-detail-from"><?=GetMessage("BXR_FROM");?></span>
            <?}?>
            <span class="bxr-market-current-price bxr-market-format-price">
                <?= Alexkova\Market2\Core::bxrFormatPrice($arElement["MIN_PRICE"]['PRINT_DISCOUNT_VALUE'], false, true)?>
            </span>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>