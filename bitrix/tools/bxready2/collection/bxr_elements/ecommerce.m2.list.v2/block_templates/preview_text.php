<?if ($arElement["PREVIEW_TEXT"]) {?>
    <div class="bxr-element-anounce">
        <?if ($arElement['PREVIEW_TEXT_TYPE']=='text' && $arElementParams['ANOUNCE_TRUNCATE_LEN'] && $arElementParams['ANOUNCE_TRUNCATE_LEN']>0){?>
            <?=TruncateText($arElement["~PREVIEW_TEXT"], $arElementParams['ANOUNCE_TRUNCATE_LEN'])?>
        <?}else{?>
            <?=$arElement["~PREVIEW_TEXT"]?>
        <?}?>
    </div>
<?}?>