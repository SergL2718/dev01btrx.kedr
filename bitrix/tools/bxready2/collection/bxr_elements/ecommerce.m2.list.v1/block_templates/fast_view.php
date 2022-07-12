<? if ($arElementParams['BXR_PRESENT_SETTINGS']["BXR_USE_FAST_VIEW"] == "Y" && intval($arElement["IBLOCK_ID"]) > 0 && intval($arElement["ID"]) > 0) {?>
    <div id="<?=$arItemIDs["FAST_VIEW"]?>" class="bxr-fast-view-btn" 
         data-state="" 
         data-toggle="modal" 
         data-target="#fv_fastview"
         data-uid="<?=$strMainID?>" 
         data-form-id="ajaxFormContainer_fastview"
         data-element-id="<?=$arElement["ID"]?>" 
         data-offer-id="" 
         data-section-id="<?=$arElement["IBLOCK_SECTION_ID"]?>" 
         data-element-url="<?=$arElement["DETAIL_PAGE_URL"]?>"
     ><?=($arElementParams['BXR_PRESENT_SETTINGS']['MESS_BTN_FAST_VIEW'])?:GetMessage('FAST_VIEW_BTN_TEXT')?></div>

<?}?>