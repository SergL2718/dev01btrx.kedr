<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\PhoneNumber\Format;
use Bitrix\Main\PhoneNumber\Parser;
foreach($_POST as $KEY=>$VAL){
	$_POST[$KEY] = trim(htmlspecialcharsEx($VAL));
}
if($_POST["AUTH_START_IN"] && $_POST["AUTH_CODE"]){
	$USER_ID = false;
	$filter = Array();
	$AUTH_START_IN = $_POST["AUTH_START_IN"];
	$ITS_ALL_OK = false;
	if(check_email($AUTH_START_IN)){
		$ITS_ALL_OK = true;
		$EMAIL = $AUTH_START_IN;
		$filter = Array(
			"EMAIL" => $EMAIL
		);
	}
	else{
		$parsedPhone = Parser::getInstance()->parse($AUTH_START_IN);
		if($parsedPhone->getNumberType()){
			$ITS_ALL_OK = true;
			$PHONE = $parsedPhone->format(Format::NATIONAL);
			$filter = Array(
				"PERSONAL_PHONE" => $PHONE
			);
		}
	}
	if($ITS_ALL_OK){
		$rsUsers = CUser::GetList(($by="personal_country"), ($order="desc"), $filter);
		while($ar_fields = $rsUsers->GetNext()){
			$USER_ID = $ar_fields["ID"];

			$WAIT = false;
			$FIND_CODE = false;
			$where = "";
			global $DB;
			if($filter["EMAIL"]) $where = $filter["EMAIL"];
			else $where = $filter["PERSONAL_PHONE"];
			$query = "SELECT * FROM b_registration_code WHERE email=\"".$where."\"  AND code=\"".$_POST["AUTH_CODE"]."\"";
			//echo $query;
			$results = $DB->Query($query);
			//echo "<pre>"; print_r($results); echo "</pre>";
			while ($row = $results->Fetch()) {
				$dateExp = MakeTimeStamp($row["expire_at"],"YYYY-MM-DD HH:MI:SS");
				$dateNow = getmicrotime();
				$FIND_CODE = true;
				//echo "<pre>"; print_r($row); echo "</pre>";
				if($dateExp>$dateNow){
					global $USER;
					if($USER->Authorize($USER_ID)){
						echo "success";
					}
					else
						echo "error auth";
				}
				else
					echo "error expired";
			}
			if(!$FIND_CODE)echo "error no code";
		}
	}
	else echo "error data";
}
else echo "error empty";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
