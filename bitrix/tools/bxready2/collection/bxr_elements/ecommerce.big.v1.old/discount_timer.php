<?
if ($arElement["SHOW_TIMER"]) {?>
    <div class="bxr-discount-timer">
        <div id="main-offer-countdown" class="bxr-countdown">
            <div class="bxr-countdown-title"><?=GetMessage('TIME_LEFT');?></div>
            <div id='main-offer-tiles' class="bxr-tiles"></div>
            <div class="labels">
                <li><?=GetMessage('DAYS');?></li>
                <li><?=GetMessage('HOURS');?></li>
                <li><?=GetMessage('MINUTES');?></li>
                <li><?=GetMessage('SECONDS');?></li>
            </div>
        </div>
    </div>
<?}?>