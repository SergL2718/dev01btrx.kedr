<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if(CModule::IncludeModule("subscribe")){
	//echo "<pre>"; print_r($_POST); echo "</pre>";
	if($_POST["EMAIL"] && check_email($_POST["EMAIL"])){
		$RUB_ID = 4;
		$FORMAT = "html";
		$EMAIL = $_POST["EMAIL"];
		$arFields = Array(
			"USER_ID" => ($USER->IsAuthorized()? $USER->GetID():false),
			"FORMAT" => ($FORMAT <> "html"? "text":"html"),
			"EMAIL" => $EMAIL,
			"ACTIVE" => "Y",
			"RUB_ID" => $RUB_ID
		);
		$subscr = new CSubscription;

		//can add without authorization
		$ID = $subscr->Add($arFields);
		if($ID>0) {
			CSubscription::Authorize($ID);
			echo "success";
		}
		else {
			$strWarning .= "Error adding subscription: " . $subscr->LAST_ERROR . "<br>";
			echo "error";
		}

	}
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
