<?if(!empty($arElement["DISPLAY_PROPERTIES"]["BXR_INSTOCK"]["VALUE"])):?>
    <div class="bxr-element-availability">
        <span class="xml-availability-<?=$arElement["DISPLAY_PROPERTIES"]["BXR_INSTOCK"]["VALUE_XML_ID"];?>">
            <?=$arElement["DISPLAY_PROPERTIES"]["BXR_INSTOCK"]["VALUE"];?>
        </span>
    </div>
<?endif;?>