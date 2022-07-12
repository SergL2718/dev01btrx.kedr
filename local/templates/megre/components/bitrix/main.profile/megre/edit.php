<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//echo "<pre>"; print_r($_POST); echo "</pre>";
global $USER;
if ($USER->IsAuthorized()){
	$arFields = Array();
	if($_POST["FULL_NAME"]){
		$FULL_NAME = explode(" ", $_POST["FULL_NAME"]);
		$arFields = Array("NAME" => $FULL_NAME[0],"LAST_NAME" => $FULL_NAME[1],"SECOND_NAME" => $FULL_NAME[2]);
	}
	$arFields["EMAIL"] = $_POST["EMAIL"];
	$arFields["PHONE_NUMBER"] = $_POST["PHONE_NUMBER"];
	$arFields["PERSONAL_PHONE"] = $_POST["PHONE_NUMBER"];
	$arFields["PERSONAL_CITY"] = $_POST["PERSONAL_CITY"];
	$arFields["PERSONAL_BIRTHDAY"] = $_POST["PERSONAL_BIRTHDAY"];
	if($_POST["PASSWORD"]) {
		$arFields["PASSWORD"] = $_POST["PASSWORD"];
		$arFields["CONFIRM_PASSWORD"] = $_POST["PASSWORD"];
	}
	$user = new CUser;
	if($user->Update($USER->GetID(), $arFields)){
		echo "success";
	}
	else
		echo $user->LAST_ERROR;
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
