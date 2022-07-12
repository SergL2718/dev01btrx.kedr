<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

//echo "<pre>"; print_r($arResult); echo "</pre>";
if($arResult["ORIGINAL_PARAMETERS"]["IBLOCK_ID"] == 41) {
	$arResult["CODE_ARR"] = array();
	foreach ($arResult["ITEMS"] as $ITEM) {
		$arResult["CODE_ARR"][] = $ITEM["CODE"];
	}
	$arResult["ITEMS"] = Array();
	CModule::IncludeModule("iblock");
	$IBLOCK_ID = 37;
	$arSelect = Array();
	$arFilter = Array(
		"IBLOCK_ID"=>$IBLOCK_ID,
		"ACTIVE"=>"Y",
		"CODE"=>$arResult["CODE_ARR"]
	);
	$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);
	while($ar_fields = $res->GetNext()){
		$rsFile = CFile::GetByID($ar_fields["DETAIL_PICTURE"]);
		$arFile = $rsFile->Fetch();
		$arFile["SRC"] = CFile::GetPath($ar_fields["DETAIL_PICTURE"]);
		$DETAIL_PICTURE =  $arFile;
		$ar_fields["PREVIEW_PICTURE"] = $DETAIL_PICTURE;
		$ar_fields["DETAIL_PICTURE"] = $DETAIL_PICTURE;
		$arResult["ITEMS"][] = $ar_fields;
	}
}

//echo "<pre>"; print_r($arResult["ITEMS"]); echo "</pre>";
