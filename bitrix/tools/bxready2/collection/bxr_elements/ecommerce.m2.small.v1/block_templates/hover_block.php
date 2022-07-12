<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$useFastView = ($arElementParams['BXR_PRESENT_SETTINGS']["BXR_USE_FAST_VIEW"] == "Y" && intval($arElement["IBLOCK_ID"]) > 0 && intval($arElement["ID"]) > 0) ? true : false;
$topClass = ($useFastView || $useCompare) ? ' bxr-hover-top-50' : ' bxr-hover-top-24'?>
<div class="bxr-element-hover">
    <div class="bxr-element-hover-content<?=$topClass?>">
        <?include('indicators.php');?>
        <?if ($useFastView || $useCompare) {?><div class="bxr-delimeter"></div><?}?>
        <?include('buttons.php');?>
        <?include('fast_view.php');?>
    </div>
</div>