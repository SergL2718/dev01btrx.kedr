<?if ($arElement["SHOW_TIMER"] && $arElement["DISCOUNT_PERIOD_TO"] != "" && $arElementParams['BXR_PRESENT_SETTINGS']['BXR_SHOW_ACTION_TIMER'] != "N") {?>
    <div class="bxr-discount-timer" id="<?=$arItemIDs["DISCOUNT_TIMER_MOBILE"]?>">
        <div id="main-offer-countdown-<?=$arItemIDs["DISCOUNT_TIMER_MOBILE"]?>" class="bxr-countdown">
            <?$colorType = ($arElementParams['BXR_PRESENT_SETTINGS']['BXR_SHOW_ACTION_TIMER'] == "GRAY") ? " gray" : (($arElementParams['BXR_PRESENT_SETTINGS']['BXR_SHOW_ACTION_TIMER'] == "DARK") ? " dark" : "")?>
            <div id='main-offer-tiles-<?=$arItemIDs["DISCOUNT_TIMER_MOBILE"]?>' class="bxr-tiles<?=$colorType?>"></div>
            <div class="labels<?=$colorType?>">
                <li class="days"><?=GetMessage('DAYS');?></li>
                <li class="hours"><?=GetMessage('HOURS');?></li>
                <li class="minutes"><?=GetMessage('MINUTES');?></li>
                <li class="seconds"><?=GetMessage('SECONDS');?></li>
            </div>
        </div>
    </div>
    <script>
        var <?=$arItemIDs["DISCOUNT_TIMER_MOBILE"]?> = new countdownBXR('<?=$arElement["DISCOUNT_PERIOD_TO"]?>',document.getElementById("main-offer-tiles-<?=$arItemIDs["DISCOUNT_TIMER_MOBILE"]?>"));
        <?=$arItemIDs["DISCOUNT_TIMER_MOBILE"]?>.start();
    </script>
<?}?>