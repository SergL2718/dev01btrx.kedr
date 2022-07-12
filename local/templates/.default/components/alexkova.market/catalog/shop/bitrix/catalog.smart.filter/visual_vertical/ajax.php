<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
/**
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @updated 28.05.2020, 10:02
 * @copyright 2011-2020
 */

unset($arResult["COMBO"]);
$APPLICATION->RestartBuffer();
echo CUtil::PHPToJSObject($arResult, true);
?>
