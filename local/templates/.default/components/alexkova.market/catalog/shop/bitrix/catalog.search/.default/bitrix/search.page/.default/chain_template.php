<?
/**
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @updated 28.05.2020, 10:02
 * @copyright 2011-2020
 */

//Navigation chain template
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arChainBody = array();
foreach($arCHAIN as $item)
{
	if(strlen($item["LINK"])<strlen(SITE_DIR))
		continue;
	if($item["LINK"] <> "")
		$arChainBody[] = '<a href="'.$item["LINK"].'">'.htmlspecialcharsex($item["TITLE"]).'</a>';
	else
		$arChainBody[] = htmlspecialcharsex($item["TITLE"]);
}
return implode('&nbsp;/&nbsp;', $arChainBody);
?>
