<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
foreach($_POST as $KEY=>$VAL){
	$_POST[$KEY] = trim(htmlspecialcharsEx($VAL));
}
if(count($_POST)>=6){
	//echo "<pre>"; print_r($_POST); echo "</pre>";

	$el = new CIBlockElement;

	$PROP = $_POST;       // свойству с кодом 3 присваиваем значение 38

	$arLoadProductArray = Array(
		"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
		"IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
		"IBLOCK_ID"      => 70,
		"PROPERTY_VALUES"=> $PROP,
		"NAME"           => "Возврат ".$_POST["ORDER_NUMBER"],
		"ACTIVE"         => "Y",            // активен
	);

	if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
		echo $PRODUCT_ID;
	}
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
