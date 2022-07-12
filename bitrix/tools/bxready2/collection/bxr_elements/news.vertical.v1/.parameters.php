<?
IncludeModuleLangFile(__FILE__);
$arElementParameters = array(
    "BXR_PRST_STYLE" => array(
            "PARENT" => "BXR_ELEMENT_SETTINGS",
            "NAME" => GetMessage("BXR_PRST_STYLE"),
            "TYPE" => "LIST",
            "VALUES" => array(
                'center' => GetMessage("BXR_PRST_STYLE_CENTER"),
                'left' => GetMessage("BXR_PRST_STYLE_LEFT"),
            ),
            "DEFAULT" => "center"
    ),
    "BXR_PRST_SHOW_BORDER" => array(
            "PARENT" => "BXR_ELEMENT_SETTINGS",
            "NAME" => GetMessage("BXR_PRST_SHOW_BORDER"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N"
    ),
);
?>