<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\PhoneNumber\Format;
use Bitrix\Main\PhoneNumber\Parser;
global $USER;
if ($USER->IsAuthorized()){
	$USER_ID = $USER->GetID();
	$filter = Array(
		"ID"                  => $USER_ID
	);
	$rsUsers = CUser::GetList(($by="personal_country"), ($order="desc"), $filter);
	while($ar_fields_user = $rsUsers->GetNext()){
		$PHONE = $ar_fields_user["PERSONAL_PHONE"];
		if($PHONE){
			$el = new CIBlockElement;
			$PROP = $_POST;       // свойству с кодом 3 присваиваем значение 38
			$arLoadProductArray = Array(
				"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
				"IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
				"IBLOCK_ID"      => 71,
				"NAME"           => $PHONE,
				"ACTIVE"         => "Y",            // активен
			);
			if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
				echo $PRODUCT_ID;
			}
		}
	}
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
