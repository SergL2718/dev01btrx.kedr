<?if ($arElement["SHOW_TIMER"] && $arElement["DISCOUNT_PERIOD_TO"] != "" && $arElementParams['BXR_PRESENT_SETTINGS']['BXR_SHOW_ACTION_TIMER'] != "N") {?>
    <div class="bxr-discount-timer" id="<?=$arItemIDs["DISCOUNT_TIMER"]?>">
        <div id="main-offer-countdown-<?=$arItemIDs["DISCOUNT_TIMER"]?>" class="bxr-countdown">
            <div id='main-offer-tiles-<?=$arItemIDs["DISCOUNT_TIMER"]?>' class="bxr-tiles<?=($arElementParams['BXR_PRESENT_SETTINGS']['BXR_SHOW_ACTION_TIMER'] == "DARK") ? " gray" : ""?>"></div>
            <div class="labels<?=($arElementParams['BXR_PRESENT_SETTINGS']['BXR_SHOW_ACTION_TIMER'] == "DARK") ? " gray" : ""?>">
                <li class="days"><?=GetMessage('DAYS');?></li>
                <li class="hours"><?=GetMessage('HOURS');?></li>
                <li class="minutes"><?=GetMessage('MINUTES');?></li>
                <li class="seconds"><?=GetMessage('SECONDS');?></li>
            </div>
        </div>
    </div>
    <script>
        var <?=$arItemIDs["DISCOUNT_TIMER"]?> = new countdownBXR('<?=$arElement["DISCOUNT_PERIOD_TO"]?>',document.getElementById("main-offer-tiles-<?=$arItemIDs["DISCOUNT_TIMER"]?>"));
        <?=$arItemIDs["DISCOUNT_TIMER"]?>.start();
    </script>
<?}?>