<?
/*
 * Изменено: 24 January 2022, Monday
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

//Navigation chain template
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arChainBody = [];
foreach ($arCHAIN as $item) {
    if (mb_strlen($item["LINK"]) < mb_strlen(SITE_DIR))
        continue;
    if ($item["LINK"] <> "")
        $arChainBody[] = '<a href="' . $item["LINK"] . '">' . htmlspecialcharsex($item["TITLE"]) . '</a>';
    else
        $arChainBody[] = htmlspecialcharsex($item["TITLE"]);
}
return implode('&nbsp;/&nbsp;', $arChainBody);
?>