<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$imgWidth = ($arElementParams['BXR_PRESENT_SETTINGS']['BXR_IMG_MAX_WIDTH'] > 0 && $arElementParams['BXR_PRESENT_SETTINGS']['BXR_IMG_MAX_WIDTH'] < 250) ? $arElementParams['BXR_PRESENT_SETTINGS']['BXR_IMG_MAX_WIDTH'] : 250;
$imgHeight = ($arElementParams['BXR_PRESENT_SETTINGS']['BXR_IMG_MAX_HEIGHT'] > 0 && $arElementParams['BXR_PRESENT_SETTINGS']['BXR_IMG_MAX_HEIGHT'] < 250) ? $arElementParams['BXR_PRESENT_SETTINGS']['BXR_IMG_MAX_HEIGHT'] : 250;
$arMatrix = array('width' => $imgWidth, 'height' => $imgHeight);
if (is_array($arElement["PREVIEW_PICTURE"])){
    $mainPicture = $elementDraw->prepareImage($arElement["PREVIEW_PICTURE"]["ID"], $arMatrix);
} else {
    if (is_array($arElement["DETAIL_PICTURE"]))
        $mainPicture = $elementDraw->prepareImage($arElement["DETAIL_PICTURE"]["ID"], $arMatrix);
}
if (!is_array($mainPicture) || strlen($mainPicture["src"]) <= 0){
	$mainPicture = array("src" => $elementDraw->getDefaultImage());
}

$showSlider = $arElementParams["BXR_PRESENT_SETTINGS"]["BXR_SHOW_SLIDER"] === "Y";
$morePhoto[$arElement["ID"]] = ($arElement["MORE_PHOTO_SLIDER"]) ?: $arElement['MORE_PHOTO'];
foreach ($morePhoto[$arElement["ID"]] as &$photo) {
    $picture = $elementDraw->prepareImage($photo["ID"], $arMatrix);
    $picture['ITEM_ID'] = $photo['ITEM_ID'];
    $photo = $picture;
}
unset($photo);
$isImgSlider = 'false';
$title = ($arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arElement["NAME"];
$alt = ($arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"]) ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arElement["NAME"];
if ($showSlider && count($morePhoto[$arElement["ID"]]) > 1) {
    $isImgSlider = 'true';
?><div class="bxr-slider slide bxr-img-container" id="<?=$arItemIDs['PICT_SLIDER']?>" title="<?=$title?>"><?
    foreach ($morePhoto[$arElement["ID"]] as $key => $photo) {
        ?><a class="bxr-item-image-wrap" href="<?=$arElement['DETAIL_PAGE_URL']?>" data-item="<?=($photo['ITEM_ID'])?:$arElement['ID']?>">
            <img class="bxr-item-image item-image" data-lazy="<?=$photo['src']?>" title="<?=$title?>" alt="<?=$alt?>">
        </a><?
    }
    unset($morePhoto[$arElement["ID"]]);
    ?></div><?
} else {
    ?><div class="bxr-element-image bxr-img-container">
        <a class="bxr-item-image-wrap" href="<?=$arElement['DETAIL_PAGE_URL']?>">
            <img class="item-image lazy" src="<?=$mainPicture['src']?>" title="<?=$title?>" alt="<?=$alt?>">
        </a>
    </div><?
}

$pictureContainerHeight = (!$isImgSlider) ? $imgHeight : $imgHeight;