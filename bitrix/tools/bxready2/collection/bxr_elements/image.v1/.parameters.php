<?
IncludeModuleLangFile(__FILE__);
$arElementParameters = array(    
        "BXR_PRST_ELEMENT_HEIGHT" => array(
		"PARENT" => "BXR_ELEMENT_SETTINGS",
		"NAME" => GetMessage('BXR_ELEMENT_HEIGHT'),
		"TYPE" => "STRING",
		"DEFAULT" => "140",
	),
        "BXR_PRST_ELEMENT_WIDTH" => array(
		"PARENT" => "BXR_ELEMENT_SETTINGS",
		"NAME" => GetMessage('BXR_ELEMENT_WIDTH'),
		"TYPE" => "STRING",
		"DEFAULT" => "100",
	),
        "BXR_PRST_ELEMENT_REGIME" => array(
		"PARENT" => "BXR_ELEMENT_SETTINGS",
		"NAME" => GetMessage('BXR_ELEMENT_REGIME'),
		"TYPE" => "LIST",
		"VALUES" => array(
			'normal' => GetMessage('BXR_ELEMENT_REGIME_NORMAL'),
			'fancybox' => GetMessage('BXR_ELEMENT_REGIME_FANCYBOX')
		),
                "DEFAULT" => "100",
	),    
        "BXR_PRST_SHOW_NAME" => array(
		"PARENT" => "BXR_ELEMENT_SETTINGS",
		"NAME" => GetMessage('BXR_SHOW_NAME'),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
        "BXR_PRST_SHOW_BORDER" => array(
		"PARENT" => "BXR_ELEMENT_SETTINGS",
		"NAME" => GetMessage('BXR_SHOW_BORDER'),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
        "BXR_PRST_USE_BACKGROUND" => array(
		"PARENT" => "BXR_ELEMENT_SETTINGS",
		"NAME" => GetMessage('BXR_USE_BACKGROUND'),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
        "BXR_PRST_GRAYSCALE" => array(
		"PARENT" => "BXR_ELEMENT_SETTINGS",
		"NAME" => GetMessage('BXR_PRST_GRAYSCALE'),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	)
);
?>