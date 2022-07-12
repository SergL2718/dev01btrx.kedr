<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\PhoneNumber\Format;
use Bitrix\Main\PhoneNumber\Parser;
foreach($_POST as $KEY=>$VAL){
	$_POST[$KEY] = trim(htmlspecialcharsEx($VAL));
}
if($_POST["AUTH_START_IN"]){
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
				//"LOGIN" => $PHONE
			);
		}
	}
	if($ITS_ALL_OK){
		//echo "<pre>"; print_r ($filter);echo "</pre>";
		$rsUsers = CUser::GetList(($by="personal_country"), ($order="desc"), $filter);
		while($ar_fields = $rsUsers->GetNext()){
			//echo "<pre>"; print_r ($ar_fields);echo "</pre>";
			$USER_ID = $ar_fields["ID"];
		}
		if(!$USER_ID){
			$user = new CUser;
			$arFields = Array();
			if($filter["EMAIL"]){
				$arFields = Array(
					"EMAIL" => $filter["EMAIL"],
					"LOGIN" => $filter["EMAIL"]
				);
			}
			else{
				$arFields = Array(
					"PERSONAL_PHONE" => $filter["PERSONAL_PHONE"],
					"MOBILE_PHONE" => $filter["PERSONAL_PHONE"],
					"PHONE_NUMBER" => $filter["PERSONAL_PHONE"],
					"LOGIN" => $filter["PERSONAL_PHONE"]
				);
				$arFields["EMAIL"] = str_replace(Array(" ", "(", ")", "-", "+", "_"),"",$filter["PERSONAL_PHONE"])."@temp-megre.ru";
			}
			$PASSWORD = randString(12);
			$arFields["PASSWORD"] = $PASSWORD;
			$arFields["CONFIRM_PASSWORD"] = $PASSWORD;
			//echo "<pre>"; print_r($arFields); echo "</pre>";
			$USER_ID = $user->Add($arFields);
			if(!$USER_ID)echo $user->LAST_ERROR;
		}
		if($USER_ID){
			$CODE = randString(6, array(
				"ABCDEFGHIJKLNMOPQRSTUVWXYZ",
				"0123456789"
			));

			// check expire code in b_registration_code
			$WAIT = false;
			$where = "";
			global $DB;
			if($filter["EMAIL"]) $where = $filter["EMAIL"];
			else $where = $filter["PERSONAL_PHONE"];
			$query = "SELECT * FROM b_registration_code WHERE email=\"".$where."\"";
			//echo $query;
			$results = $DB->Query($query);
			//echo "<pre>"; print_r($results); echo "</pre>";
			while ($row = $results->Fetch()) {
				$dateExp = MakeTimeStamp($row["expire_at"],"YYYY-MM-DD HH:MI:SS");
				$dateNow = getmicrotime();
				if($dateExp>$dateNow){
					$WAIT = true;
				}
			}

			if(!$WAIT) {
				//echo "<pre>"; print_r($filter); echo "</pre>";
				if ($filter["EMAIL"]) {
					$arEventFields = array(
						"CHECKWORD" => $CODE,
						"EMAIL" => $filter["EMAIL"],
						"LOGIN" => $filter["EMAIL"],
						"USER_ID" => $USER_ID,
					);
					CEvent::Send("USER_CODE_REQUEST", SITE_ID, $arEventFields);
				} else {
					// send sms
					$fields = array(
						'USER_PHONE' => $filter["PERSONAL_PHONE"],
						'CODE' => $CODE,
					);
					//echo "<pre>"; print_r($fields); echo "</pre>";
					$sms = new \Bitrix\Main\Sms\Event('SMS_USER_CONFIRM_NUMBER', $fields);
					$sms->setSite('zg');
					$sms->setLanguage('ru');
					$res_sms = $sms->send();
					//echo "<pre>"; print_r($res_sms); echo "</pre>";
				}

				$arFieldsSql = Array(
					"email" =>$where,
					"created_at" => ConvertTimeStamp(time(), "FULL"),
					"expire_at" => ConvertTimeStamp(time()+60, "FULL"),
					"code" => $CODE,
				);
				//echo "<pre>"; print_r($arFieldsSql); echo "</pre>";
				$arInsert = $DB->PrepareInsert("b_registration_code", $arFieldsSql);
				$strSql = "INSERT INTO b_registration_code (".$arInsert[0].") VALUES (".$arInsert[1].")";
				//echo $strSql;
				$DB->Query($strSql, false, $err_mess.__LINE__);
				//echo $err_mess;
				if ($filter["EMAIL"]) {
					echo "Мы отправили код подтверждения на e-mail: ".$filter["EMAIL"];
				}
				else{
					echo "Мы отправили код подтверждения на номер: ".$filter["PERSONAL_PHONE"];
				}
			}
		}
	}
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
