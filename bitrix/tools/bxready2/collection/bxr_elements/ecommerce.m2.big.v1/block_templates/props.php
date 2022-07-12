<?if ($arElementParams["BXR_PRESENT_SETTINGS"]["BXR_TILE_SHOW_PROPERTIES"] == "Y"){?>
    <div class="bxr-element-props-table-wrap">
        <table class="bxr-element-props-table">
            <tbody>
            <?foreach ($arElement["DISPLAY_PROPERTIES"] as $arProperty) {?>
                <?if (!is_array($arProperty["DISPLAY_VALUE"]) && $arProperty["DISPLAY_VALUE"]){?>
                    <tr>
                        <td class="bxr-props-table-name"><span><?=trim($arProperty["NAME"])?></span></td>
                        <td class="bxr-props-table-value"><span><?=trim($arProperty["DISPLAY_VALUE"])?></span></td>
                    </tr>
                <?}?>
            <?}?>
            </tbody>
        </table>
    </div>
<?}?>