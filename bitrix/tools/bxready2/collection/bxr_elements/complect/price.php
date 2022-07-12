<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$boolDiscountShow = ('Y' == $arElementParams['SHOW_OLD_PRICE']);
?>
<div class="bxr-product-price-wrap">
    <?  foreach ($arElement["PRICES"] as $price) {?>
        <div class="bxr-market-item-price bxr-format-price">
            <!--old price-->
            <?
            $priceValue = $price["VALUE_NOVAT"];
            $discountValue = $price["DISCOUNT_VALUE_NOVAT"];

            if ($boolDiscountShow && $priceValue && $discountValue && $priceValue != $discountValue) { ?>
                <span class="bxr-market-old-price"><?=Alexkova\Market2\Core::bxrFormatPrice($price["PRINT_VALUE_NOVAT"], false, true, true)?></span>
            <?}?>
            <!--current price with all discounts-->
            <span class="bxr-market-current-price bxr-market-format-price">
                <?= Alexkova\Market2\Core::bxrFormatPrice($price['PRINT_DISCOUNT_VALUE_VAT'], false, true)?>
            </span>
            <div class="clearfix"></div>
        </div>
    <?}?>
</div>