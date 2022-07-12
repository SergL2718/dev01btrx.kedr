<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/*
 * Изменено: 13 сентября 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

$arTemplateParameters = [
	'POSITION_FIXED' => [
		'PARENT'  => 'VISUAL',
		'NAME'    => GetMessage('CP_BCCL_TPL_PARAM_TITLE_POSITION_FIXED'),
		'TYPE'    => 'CHECKBOX',
		'DEFAULT' => 'Y',
		'REFRESH' => 'Y',
	],
];

if (!isset($arCurrentValues['POSITION_FIXED']) || $arCurrentValues['POSITION_FIXED'] == 'Y') {
	$positionList = [
		'top left'     => GetMessage('CP_BCCL_TPL_PARAM_POSITION_TOP_LEFT'),
		'top right'    => GetMessage('CP_BCCL_TPL_PARAM_POSITION_TOP_RIGHT'),
		'bottom left'  => GetMessage('CP_BCCL_TPL_PARAM_POSITION_BOTTOM_LEFT'),
		'bottom right' => GetMessage('CP_BCCL_TPL_PARAM_POSITION_BOTTOM_RIGHT'),
	];
	$arTemplateParameters['POSITION'] = [
		'PARENT'  => 'VISUAL',
		'NAME'    => GetMessage('CP_BCCL_TPL_PARAM_TITLE_POSITION'),
		'TYPE'    => 'LIST',
		'VALUES'  => $positionList,
		'DEFAULT' => 'top left',
	];
	unset($positionList);
}
?>