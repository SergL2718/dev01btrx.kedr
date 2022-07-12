<?
/*
foreach($arResult["ITEMS"] as $key=>$arItem){
if($arItem["IBLOCK_SECTION_ID"]){
$res = CIBlockSection::GetByID($arItem["IBLOCK_SECTION_ID"]);
if($ar_res = $res->GetNext()){
  $arResult["ITEMS"][$key]["IBLOCK_SECTION_NAME"] = $ar_res['NAME'];
  $arResult["ITEMS"][$key]["IBLOCK_SECTION_PICTURE"] = CFile::GetPath($ar_res['PICTURE']);
//echo "<pre>"; print_r($ar_res); echo "</pre>";
}
}
}