<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*
 * Изменено: 05 March 2018, Monday
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

/*echo '<pre>';
//print_r($_REQUEST);
//print_r($arResult);
echo '</pre>';*/

if(!empty($arResult["RUBRICS"]) && is_array($arResult["RUBRICS"]) && !empty($_REQUEST["SENDER_SUBSCRIBE_RUB_ID"])) {
    foreach ($arResult["RUBRICS"] as $k => $v) {
        if(in_array($v["ID"], $_REQUEST["SENDER_SUBSCRIBE_RUB_ID"])) {
             $arResult["RUBRICS"][$k]["CHECKED"] = "true";    
        }
    }
}

$arResult["REQUEST"]["RUBRICS_PARAM"] = $_REQUEST["SENDER_SUBSCRIBE_RUB_ID"];
if(isset($_REQUEST["SENDER_SUBSCRIBE_EMAIL"]) && !empty($_REQUEST["SENDER_SUBSCRIBE_EMAIL"]))
    $arResult["REQUEST"]["EMAIL"] = $_REQUEST["SENDER_SUBSCRIBE_EMAIL"];
?>
