<?if(!empty($arElement["PROPERTIES"]["BXR_INSTOCK"]["VALUE"])):?>
    <div class="bxr-element-availability">
        <span class="xml-availability-<?=$arElement["PROPERTIES"]["BXR_INSTOCK"]["VALUE_XML_ID"];?>">
            <?=$arElement["PROPERTIES"]["BXR_INSTOCK"]["VALUE"];?>
        </span>
    </div>
<?endif;?>