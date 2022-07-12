<?
/*
 * Изменено: 29 сентября 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
global $APPLICATION;
echo json_encode($APPLICATION->CaptchaGetCode());
?>