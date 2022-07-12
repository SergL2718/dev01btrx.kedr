<?
    global $APPLICATION;
    $APPLICATION->SetAdditionalCSS("/bitrix/tools/bxready2/collection/bxr_elements/news.vertical.v1/include/style.css");
    
    if($arElementParams["BXREADY_VERTICAL_ALIGN"] != 'N')
        $APPLICATION->AddHeadScript("/bitrix/tools/bxready2/collection/bxr_elements/news.vertical.v1/include/script.js");
?>