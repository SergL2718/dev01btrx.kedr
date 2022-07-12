<?if ($arElementParams["BXR_PRESENT_SETTINGS"]["BXR_SHOW_ARTICLE"] == "Y" && $arElement["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) {?>
    <div class="bxr-element-article">
        <?=GetMessage("ARTICLE")?><?=$arElement["PROPERTIES"]["CML2_ARTICLE"]["VALUE"];?>
    </div>
<?}?>