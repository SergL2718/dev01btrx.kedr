<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?><div class="bxr-element-action"  id="<?=$arItemIDs["BASKET_ACTIONS"]?>"><div class="bxr-buy-btn-wrap"><?
    $hasOffers = (empty($arElement["OFFERS"])) ? false : true;
    $subscribe = ( $arElement["CATALOG_QUANTITY"] <= 0 && $arElement["CATALOG_CAN_BUY_ZERO"] == "N"  || empty($arElement["MIN_PRICE"]) ) ? true : false;
    $useSubscribeBtn = ($arElement['CATALOG_SUBSCRIBE'] == 'Y') ? true : false;
    if($hasOffers || $subscribe && $useSubscribeBtn) {
        ?><div class="bxr-subscribe-wrap<?=($hasOffers)?' bxr-hidden':''?>">
        <a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="bxr-color-button" id="<?=$arItemIDs["BUY_LINK"]?>"><?
            echo (strlen($arElementParams["MESS_BTN_DETAIL"]) > 0) ? $arElementParams["MESS_BTN_DETAIL"] : GetMessage("MORE_INFO_TITLE");
            ?></a>
        </div><?
    }
    if($hasOffers || $subscribe && !$useSubscribeBtn) {
        ?><button class="bxr-color-button bxr-detail-product-request<?=($hasOffers)?' bxr-hidden':''?>" value="<?=$arElement["ID"]?>"                         
            data-pid="<?=$arElement["ID"]?>" 
            data-oid=""
            data-toggle="modal" 
            data-target="#bxr-request-product-popup">
            <i class="fa fa-pencil" aria-hidden="true"></i><?
            echo (strlen($arElementParams["MESS_BTN_SUBSCRIBE"]) > 0) ? $arElementParams["MESS_BTN_SUBSCRIBE"] : GetMessage("BXR_REQUEST_BTN");
        ?></button><?
    }
    if($hasOffers || !$subscribe) {   
        if (!$hasOffers && (is_array($arElement["BASKET_PROPS"]["REQUIRED_CHECK"]) || is_array($arElement["BASKET_PROPS"]["OPTIONAL_CHECK"])) ) {
            ?><a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="bxr-color-button" id="<?=$arItemIDs["BUY_LINK"]?>"><?
                echo (strlen($arElementParams["MESS_BTN_DETAIL"]) > 0) ? $arElementParams["MESS_BTN_DETAIL"] : GetMessage("MORE_INFO_TITLE");
            ?></a><?
        } else {
            $qtyMax = ($arElement["CATALOG_CAN_BUY_ZERO"] == "Y") ? 0 : $arElement["CATALOG_QUANTITY"];?>
            <form class="bxr-basket-action bxr-basket-group bxr-currnet-torg<?=($hasOffers)?' bxr-hidden':''?>">                
                <input type="button" class="bxr-quantity-button-minus" value="-" data-item="<?=$arElement["ID"]?>" data-ratio="<?=$arElement["RATIO"];?>"><!--
                --><input type="text" name="quantity" value="<?=$arElement['START_QTY'];?>" class="bxr-quantity-text" data-item="<?=$arElement["ID"]?>"><!--
                --><input type="button" class="bxr-quantity-button-plus" value="+" data-item="<?=$arElement["ID"]?>" data-ratio="<?=$arElement["RATIO"];?>" data-max="<?=$arElement["QTY_MAX"]?>">
                <button class="bxr-color-button bxr-color-button-small-only-icon bxr-basket-add">
                    <span class="fa fa-shopping-basket"></span><?
                    echo (strlen($arElementParams["MESS_BTN_ADD_TO_BASKET"]) > 0) ? $arElementParams["MESS_BTN_ADD_TO_BASKET"] : GetMessage("BXR_BASKET");
                ?></button>
                <input class="bxr-basket-item-id" type="hidden" name="item" value="<?=$arElement["ID"]?>">
                <input type="hidden" name="action" value="add">
            </form><?
        }
    }
    ?><script>
        if (!BXReady.Market.basketValues[<?=$arElement['ID']?>])
            BXReady.Market.basketValues[<?=$arElement['ID']?>] = {
                ID: '<?=$arElement['ID']?>',
                OFFER_ID: '',
                NAME: '<?=htmlspecialchars($arElement['NAME'],ENT_QUOTES, SITE_CHARSET)?>',
                LINK: '<?=$arElement['DETAIL_PAGE_URL']?>',
                IMG: '<?=$arElement['DETAIL_PICTURE']['SRC']?>',
                MSG: '<?=str_replace('#TRADE_NAME#', htmlspecialchars($arElement['NAME'],ENT_QUOTES, SITE_CHARSET), GetMessage('TRADE_REQUEST_MSG'))?>',
                HAS_PRICE: '<?=(empty($arElement["MIN_PRICE"])) ? 'N' : 'Y'?>',
                CATALOG_QUANTITY: '<?=$arElement['CATALOG_QUANTITY']?>',
                CATALOG_CAN_BUY_ZERO: '<?=$arElement['CATALOG_CAN_BUY_ZERO']?>',
                CATALOG_SUBSCRIBE: '<?=$arElement['CATALOG_SUBSCRIBE']?>',
                QTY_MAX: '<?=$arElement['QTY_MAX']?>',
                RATIO: '<?=$arElement['RATIO']?>',
                START_QTY: '<?=$arElement['START_QTY']?>'
            };
    </script>
</div></div>