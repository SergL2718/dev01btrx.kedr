<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if ($arElementParams['BXR_PRESENT_SETTINGS']["BXR_SHOW_PREVIEW_TEXT"]=="Y" && strlen($arElement["PREVIEW_TEXT"]) > 0) :
    ?><div class="bxr-previewtext"><?=$arElement["PREVIEW_TEXT"]?></div><?
endif;