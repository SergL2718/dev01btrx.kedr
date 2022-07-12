<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$hasOffers = (empty($arElement["OFFERS"])) ? false : true;
$subscribe = ( $arElement["CATALOG_QUANTITY"] <= 0 && $arElement["CATALOG_CAN_BUY_ZERO"] == "N"  || empty($arElement["MIN_PRICE"]) ) ? true : false;
$useSubscribeBtn = ($arElement['CATALOG_SUBSCRIBE'] == 'Y') ? true : false;

if (!$hasOffers && (is_array($arElement["BASKET_PROPS"]["REQUIRED_CHECK"]) || is_array($arElement["BASKET_PROPS"]["OPTIONAL_CHECK"]))
    || $hasOffers) {
    ?><a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="bxr-more-info bxr-hover-btn" id="<?=$arItemIDs["BUY_LINK"]?>" title="<?=($arElementParams["MESS_BTN_DETAIL"]) ?: GetMessage("MORE_INFO_TITLE")?>">
        <i class="fa fa-external-link"></i>
    </a><?
} elseif (!$subscribe) {
    ?><form class="bxr-basket-action bxr-basket-group bxr-currnet-torg">
        <button class="bxr-basket-add bxr-hover-btn" title="<?=($arElementParams["MESS_BTN_ADD_TO_BASKET"]) ?: GetMessage("BXR_BASKET")?>">
            <i class="fa fa-shopping-basket"></i>
        </button>
        <input class="bxr-basket-item-id" type="hidden" name="item" value="<?=$arElement["ID"]?>">
        <input type="hidden" name="action" value="add">
    </form><?
} else {
    ?><button class="bxr-detail-product-request bxr-hover-btn" value="<?=$arElement["ID"]?>" title="<?=($arElementParams["MESS_BTN_SUBSCRIBE"]) ?: GetMessage("BXR_REQUEST_BTN")?>"
              data-pid="<?=$arElement["ID"]?>"
              data-oid=""
              data-toggle="modal"
              data-target="#bxr-request-product-popup">
        <i class="fa fa-pencil" aria-hidden="true"></i>
    </button>
    <script>
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
    </script><?
}?>
