<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\PhoneNumber\Format;
use Bitrix\Main\PhoneNumber\Parser;
global $USER;
if ($USER->IsAuthorized() && $_POST["PHONE"]){
	$parsedPhone = Parser::getInstance()->parse($_POST["PHONE"]);
	if($parsedPhone->getNumberType()) {
		$PHONE = $parsedPhone->format(Format::NATIONAL);
		$USER_ID = $USER->GetID();
		$user = new CUser;
		$fields = array(
			"LOGIN" => $PHONE,
			"PERSONAL_PHONE" => $PHONE,
		);
		$user->Update($USER_ID, $fields);
		echo $PHONE;
	}
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
