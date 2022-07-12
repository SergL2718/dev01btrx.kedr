<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<ul class="bxr-detail-top-tabs hidden-xs">
    <?if (count($arResult["OFFERS"]) > 0 && $arParams["HIDE_OFFERS_LIST"] != "Y") {?>
        <li class="bxr-font-hover-light" data-tab="offers">
            <?=GetMessage("OFFERS_TEXT")?>
        </li>
    <?}?>
    <?if ($arResult["DETAIL_TEXT"]) {?>
        <li class="bxr-font-hover-light" data-tab="detail">
            <?=GetMessage("DETAIL_TEXT")?>
        </li>
    <?}?>
    <?if (count($arResult["DISPLAY_PROPERTIES"]) > 0) {?>
        <li class="bxr-font-hover-light" data-tab="props">
            <?=GetMessage("PROPS_TEXT")?>
        </li>
    <?}?>
    <?if ($show_files) {?>
        <li class="bxr-font-hover-light" data-tab="element-files">
            <?=$arResult["PROPERTIES"]["FILES"]["NAME"]?>
        </li>
    <?}?>
    <?if ($show_video) {?>
        <li class="bxr-font-hover-light" data-tab="element-video">
            <?=$arResult["PROPERTIES"]["VIDEO"]["NAME"]?>
        </li>
    <?}?>
    <?if ($useReview) {?>
        <li class="bxr-font-hover-light" data-tab="review">
            <?=GetMessage("REVIEWS_TEXT")?>
        </li>
    <?}?>
    <?if ($storeAmount) {?>
        <li class="bxr-font-hover-light" data-tab="storeamount">
            <?=GetMessage("STORE_AMOUNT_TEXT")?>
        </li>
    <?}?>
    <div class="clearfix"></div>
</ul>
