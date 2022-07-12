<?$prop = $arParams['ELEMENT_RESULT']['PROPERTIES'];?>
<div class="tariff_ribbons_va6 <?=$arResult['ELEMENT']['CLASS_NAME']?>">
    <div class="tariff_ribbons_top valign">
        <div>
            <div><p><?=$arResult['ELEMENT']['NAME']?></p></div>
        </div>
    </div>
    <div class="tariff_ribbons_cost">
        <?=$prop['COST']['VALUE']?> <span class="tariff_ribbons_dopcost"><?=$prop['MORE_COST']['VALUE']?></span>
    </div>
    <div class="tariff_advantages">
        <ul>
            <?foreach($prop['ADVANTAGES']['VALUE'] as $k => $v):?>
                <li><?=$v;?></li>
            <?endforeach;?>
        </ul>
    </div>
    <div class="text-center tariff_action">
        <a class="tariff_action_button" href="<?=$prop['BUTTON']['DESCRIPTION']?>" ><?=$prop['BUTTON']['VALUE']?></a>
    </div>
</div>
<div class="clear" ></div>
