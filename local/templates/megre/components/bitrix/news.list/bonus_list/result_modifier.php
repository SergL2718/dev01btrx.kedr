<?php
$BONUS = 0;
global $USER;
if ($USER->IsAuthorized()) {
	$USER_ID = $USER->GetID();
	$rsUsers = CUser::GetList(($by = "personal_country"), ($order = "desc"), array("ID" => $USER_ID), Array("SELECT"=>Array("UF_*")));
	while ($ar_fields = $rsUsers->GetNext()) {
		//echo "<pre>"; print_r($ar_fields); echo "</pre>";
		$BONUS = $ar_fields["UF_BONUS"];
	}
}
$arResult["BONUS"]["VALUE"] = $BONUS;
$arResult["BONUS"]["MEASUREMENT"] = pluralForm($BONUS, "бонус", "бонуса", "бонусов");
