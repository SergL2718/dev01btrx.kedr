<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
IncludeModuleLangFile(__FILE__);
use Bitrix\Main\Web\Json;
use Alexkova\Bxready2\Component;

$arRatingValues = array(
    'N' => GetMessage('BXR_RATING_NONE'),
    'vote_avg' => GetMessage('BXR_RATING_VOTE_AVG'),
    'avg' => GetMessage('BXR_RATING_AVG')    
);

$arElementParameters['BXR_SHOW_RATING'] = array(
        'PARENT' => 'BXR_ELEMENT_SETTINGS',
        'NAME' => GetMessage('CP_BCS_TPL_SHOW_RATING'),
        'TYPE' => 'LIST',
        'VALUES' => $arRatingValues,
        'DEFAULT' => 'N',
);

$arTimerValues = array(
    'N' => GetMessage('BXR_ACTION_TIMER_NONE'),
    'LIGHT' => GetMessage('BXR_ACTION_TIMER_LIGHT'),
    'DARK' => GetMessage('BXR_ACTION_TIMER_DARK')
);

$arElementParameters['BXR_SHOW_ACTION_TIMER'] = array(
    'PARENT' => 'BXR_ELEMENT_SETTINGS',
    'NAME' => GetMessage('CP_BCS_TPL_SHOW_ACTION_TIMER'),
    'TYPE' => 'LIST',
    'VALUES' => $arTimerValues,
    'DEFAULT' => 'N',
);

$arElementParameters['BXR_TILE_SHOW_PROPERTIES'] = array(
    'PARENT' => 'BXR_ELEMENT_SETTINGS',
    'NAME' => GetMessage('BXR_TILE_SHOW_PROPERTIES'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
);

$arMarkersValues = \Alexkova\Bxready2\Component::getMarkerListParams();

$arElementParameters['BXREADY_LIST_MARKER_TYPE'] = array(
    'PARENT' => 'BXR_ELEMENT_SETTINGS',
    'NAME' => GetMessage('BXREADY_LIST_MARKER_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arMarkersValues["system"],
    'DEFAULT' => 'not',
);

$arElementParameters['BXREADY_LIST_OWN_MARKER_USE'] = array(
    'PARENT' => 'BXR_ELEMENT_SETTINGS',
    'NAME' => GetMessage('BXREADY_LIST_OWN_MARKER_USE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y',
    'DEFAULT' => 'N',
);

if ((isset($arCurrentValues['BXREADY_LIST_OWN_MARKER_USE'.$addContext]) && $arCurrentValues['BXREADY_LIST_OWN_MARKER_USE'.$addContext] === 'Y')) {
    $arElementParameters['BXREADY_LIST_OWN_MARKER_TYPE'] = array(
        'PARENT' => 'BXR_ELEMENT_SETTINGS',
        'NAME' => GetMessage('BXREADY_LIST_OWN_MARKER_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arMarkersValues["user"],
        'DEFAULT' => '',
    );
}

//$arElementParameters['BXR_SHOW_ARTICLE'] = array(
//    'PARENT' => 'BXR_ELEMENT_SETTINGS',
//    'NAME' => GetMessage('BXR_SHOW_ARTICLE'),
//    'TYPE' => 'CHECKBOX',
//    'DEFAULT' => 'Y',
//);

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