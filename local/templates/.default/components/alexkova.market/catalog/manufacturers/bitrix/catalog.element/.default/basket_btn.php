<!--basket-btns block-->
<?if (count($arResult["OFFERS"]) > 0) {
    foreach ($arResult["OFFERS"] as $offer) {?>
        <div class="offers-btn-wrap" style="display: none" data-item="<?=$offer["ID"]?>">
            <?if ($offer["CATALOG_QUANTITY"] <= 0 && $offer["CATALOG_CAN_BUY_ZERO"] == "N") {?>
                <button class="bxr-color-button bxr-trade-request" value="<?=$offer["ID"]?>">
                    <?=GetMessage("REQUEST_BTN")?>
                </button>
            <?} else {
                $qtyMax = $offer["CATALOG_CAN_BUY_ZERO"] === 'Y' ? 0 : $offer["CATALOG_QUANTITY"];
                ?>
                <form class="bxr-basket-action bxr-basket-group bxr-currnet-torg" action="">
                    <input type="button" class="bxr-quantity-button-minus" value="-" data-item="<?=$offer["ID"]?>">
                    <input type="text" name="quantity" value="1" class="bxr-quantity-text" data-item="<?=$offer["ID"]?>">
                    <input type="button" class="bxr-quantity-button-plus" value="+" data-item="<?=$offer["ID"]?>" data-max="<?=$qtyMax?>">
                    <button class="bxr-color-button bxr-basket-add">
                            <!--<span class="fa fa-shopping-cart"></span>-->
                            <?=GetMessage("TO_BASKET")?>
                    </button>
                    <input class="bxr-basket-item-id" type="hidden" name="item" value="<?=$offer["ID"]?>">
                    <input type="hidden" name="action" value="add">
                </form>
                <!--one click buy block-->
                <div class="bxr-basket-action">
                    <button class="bxr-color-button bxr-one-click-buy" data-item="<?=$offer["ID"]?>">
                        <?=GetMessage("ONE_CLICK_BUY")?>
                    </button>
                </div>
                <div class="clearfix"></div>
            <?}?>
        </div>
<?  }?>
    <div class="bxr-detail-torg-btn">
        <!--share block-->
        <div class="bxr-share-group">
            <span class="fa fa-share-alt hidden-md"></span>
            <?=GetMessage("SHARE")?>
        </div>

        <!--compare block-->
        <?
        if ($useCompare)
        {
        ?>
        <div class="bxr-basket-group">
            <button class="bxr-indicator-item white bxr-indicator-item-compare bxr-compare-button" value="" data-item="<?=$arResult["ID"]?>">
                <span class="fa fa-bar-chart hidden-md" aria-hidden="true"></span>
                <?=GetMessage("COMPARE")?>
            </button>
        </div>
        <?}?>
        
        <!--favor block-->
        <form class="bxr-basket-action bxr-basket-group" action="">
            <button class="bxr-indicator-item white bxr-indicator-item-favor bxr-basket-favor" data-item="<?=$arResult["ID"]?>" tabindex="0">
                <span class="fa fa-heart-o hidden-md"></span>
                <?=GetMessage("FAVORITES")?>
            </button>
            <input type="hidden" name="item" value="<?=$arResult["ID"]?>" tabindex="0">
            <input type="hidden" name="action" value="favor" tabindex="0">
            <input type="hidden" name="favor" value="yes">
        </form>
        <div class="clearfix"></div>
    </div>
<?} else {?>
    <script>
        trade_name = "<?=$arResult['NAME']?>";
        trade_id = "<?=$arResult['ID']?>";
        trade_link = "<?=$arResult['DETAIL_PAGE_URL']?>";
        formRequestMsg = "<?=GetMessage('TRADE_REQUEST_MSG')?>";
        formRequestMsg = formRequestMsg.replace("#TRADE_NAME#",'<?=$arResult['NAME']?>');
    </script>
    <?if ($arResult["CATALOG_QUANTITY"] <= 0 && $arResult["CATALOG_CAN_BUY_ZERO"] == "N") {?>
        <button class="bxr-color-button bxr-trade-request" value="<?=$arResult["ID"]?>">
            <?=GetMessage("REQUEST_BTN")?>
        </button>
    <?} else {
        $qtyMax = $arResult["CATALOG_CAN_BUY_ZERO"] === 'Y' ? 0 : $arResult["CATALOG_QUANTITY"];
        if ($arResult['PROPERTIES']['DOWNLOAD_LINK']['VALUE']) {
            $qtyMax = 1;
        }
        ?>
        <form class="bxr-basket-action bxr-basket-group bxr-currnet-torg" action="">
            <input type="button" class="bxr-quantity-button-minus" value="-" data-item="<?=$arResult["ID"]?>">
            <input type="text" name="quantity" value="1" class="bxr-quantity-text" data-item="<?=$arResult["ID"]?>">
            <input type="button" class="bxr-quantity-button-plus" value="+" data-item="<?=$arResult["ID"]?>" data-max="<?=$qtyMax?>">
            <button class="bxr-color-button bxr-basket-add">
                    <!--<span class="fa fa-shopping-cart"></span>-->
                    <?=GetMessage("TO_BASKET")?>
            </button>
            <input class="bxr-basket-item-id" type="hidden" name="item" value="<?=$arResult["ID"]?>">
            <input type="hidden" name="action" value="add">
        </form>
        <!--one click buy block-->
        <div class="bxr-basket-action">
            <button class="bxr-color-button bxr-one-click-buy" data-item="<?=$arResult["ID"]?>">
                <?=GetMessage("ONE_CLICK_BUY")?>
            </button>
        </div>
        <div class="clearfix"></div>
    <?}?>
    <div class="bxr-detail-torg-btn">
        <!--share block-->
        <div class="bxr-share-group">
            <span class="fa fa-share-alt hidden-md"></span>
            <?=GetMessage("SHARE")?>
        </div>

        <!--compare block-->
        <?
        if ($useCompare)
        {
        ?>
        <div class="bxr-basket-group">
            <button class="bxr-indicator-item white bxr-indicator-item-compare bxr-compare-button" value="" data-item="<?=$arResult["ID"]?>">
                <span class="fa fa-bar-chart hidden-md" aria-hidden="true"></span>
                <?=GetMessage("COMPARE")?>
            </button>
        </div>
        <?}?>
        <!--favor block-->
        <form class="bxr-basket-action bxr-basket-group" action="">
            <button class="bxr-indicator-item white bxr-indicator-item-favor bxr-basket-favor" data-item="<?=$arResult["ID"]?>" tabindex="0">
                <span class="fa fa-heart-o hidden-md"></span>
                <?=GetMessage("FAVORITES")?>
            </button>
            <input type="hidden" name="item" value="<?=$arResult["ID"]?>" tabindex="0">
            <input type="hidden" name="action" value="favor" tabindex="0">
            <input type="hidden" name="favor" value="yes">
        </form>
        <div class="clearfix"></div>
    </div>
<?}?>

<div class="bxr-share-icon-wrap">
    <?$APPLICATION->IncludeComponent(
            "bitrix:main.share",
            "element_detail",
            Array(
                    "COMPONENT_TEMPLATE" => ".default",
                    "HANDLERS" => $arParams["HANDLERS"],
                    "HIDE" => "N",
                    "PAGE_TITLE" => $arResult["NAME"],
                    "PAGE_URL" => $arResult["DETAIL_PAGE_URL"],
                    "SHORTEN_URL_KEY" => "",
                    "SHORTEN_URL_LOGIN" => ""
            ),
            false,
            array("HIDE_ICONS" => "Y")
    );?>        
</div>