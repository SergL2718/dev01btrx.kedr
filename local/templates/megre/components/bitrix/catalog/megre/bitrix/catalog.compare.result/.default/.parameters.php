<?
/*
 * Изменено: 13 сентября 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

use Bitrix\Main\ModuleManager;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arThemes = [];
if (ModuleManager::isModuleInstalled('bitrix.eshop')) {
	$arThemes['site'] = GetMessage('CP_BCC_TPL_THEME_SITE');
}

$arThemesList = [
	'blue'   => GetMessage('CP_BCC_TPL_THEME_BLUE'),
	'green'  => GetMessage('CP_BCC_TPL_THEME_GREEN'),
	'red'    => GetMessage('CP_BCC_TPL_THEME_RED'),
	'wood'   => GetMessage('CP_BCC_TPL_THEME_WOOD'),
	'yellow' => GetMessage('CP_BCC_TPL_THEME_YELLOW'),
	'black'  => GetMessage('CP_BCC_TPL_THEME_BLACK'),
];
$dir = trim(preg_replace("'[\\\\/]+'", "/", dirname(__FILE__) . "/themes/"));
if (is_dir($dir)) {
	foreach ($arThemesList as $themeID => $themeName) {
		if (!is_file($dir . $themeID . '/style.css'))
			continue;
		$arThemes[$themeID] = $themeName;
	}
}

$arTemplateParameters['TEMPLATE_THEME'] = [
	'PARENT'            => 'VISUAL',
	'NAME'              => GetMessage("CP_BCC_TPL_TEMPLATE_THEME"),
	'TYPE'              => 'LIST',
	'VALUES'            => $arThemes,
	'DEFAULT'           => 'blue',
	'ADDITIONAL_VALUES' => 'Y',
];
?>