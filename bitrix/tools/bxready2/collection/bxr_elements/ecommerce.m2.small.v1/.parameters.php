<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
IncludeModuleLangFile(__FILE__);
use Bitrix\Main\Web\Json;
use Alexkova\Bxready2\Component;

$arElementParameters['BXR_USE_FAST_VIEW'] = array(
    'PARENT' => 'BXR_ELEMENT_SETTINGS',
    'NAME' => GetMessage('USE_FAST_VIEW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y',
    'DEFAULT' => 'N'
);

if ((isset($arCurrentValues['BXR_USE_FAST_VIEW'.$addContext]) && $arCurrentValues['BXR_USE_FAST_VIEW'.$addContext] === 'Y')) {
    $arElementParameters['MESS_BTN_FAST_VIEW'] = array(
        'PARENT' => 'BXR_ELEMENT_SETTINGS',
        'NAME' => GetMessage('MESS_BTN_FAST_VIEW'),
        'TYPE' => 'STRING',
        'DEFAULT' => GetMessage('FAST_VIEW_BTN_TEXT')
    );
}

$arElementParameters['BXR_IMG_MAX_WIDTH'] = array(
    'PARENT' => 'BXR_ELEMENT_SETTINGS',
    'NAME' => GetMessage('BXR_IMG_MAX_WIDTH'),
    'TYPE' => 'STRING',
    'DEFAULT' => 90
);

$arElementParameters['BXR_IMG_MAX_HEIGHT'] = array(
    'PARENT' => 'BXR_ELEMENT_SETTINGS',
    'NAME' => GetMessage('BXR_IMG_MAX_HEIGHT'),
    'TYPE' => 'STRING',
    'DEFAULT' => 90
);