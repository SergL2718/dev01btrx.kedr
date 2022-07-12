<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$name = ($arElement["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] : $arElement["NAME"];
 ?><div class="bxr-element-name" id="bxr-element-name-<?=$arElement["ID"]?>">
         <a href="<?=$arElement["DETAIL_PAGE_URL"]?>" id="<?=$arItemIDs["NAME"]?>" class="bxr-font-color bxr-font-color-hover" title="<?=$name?>"><?
             echo (strlen($arElement["SHORT_NAME"])>0) ? $arElement["SHORT_NAME"] : $name;
?></a></div>