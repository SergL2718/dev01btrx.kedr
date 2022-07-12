<?
/*
 * @updated 15.10.2020, 16:00
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @copyright Copyright (c) 2020, Компания Webco
 * @link http://wbc.cx
 */

/**
 * @var $USER
 * @var $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

$this->setFrameMode(true);

if (count($arResult['ITEMS']) <= 0) return;

//pr($arResult['ITEMS']);

/*if ($USER->GetID() == 14) {
    $localTime = new DateTime();
    $localOffset = $localTime->getOffset();

    $tz = new DateTimeZone('Europe/Moscow');


    pr($localOffset/60);
    pr(\Bitrix\Main\Application::getInstance()->getContext());
}*/
?>

<div class="native-slider">

    <? foreach ($arResult['ITEMS'] as $key => $item): ?>
        <?
        $target = $item['PROPERTIES']['TARGET_BLANK']['VALUE_XML_ID'] === "Y" ? "target='_blank'" : "target='_self'";
        $link = $item['PROPERTIES']['BUTTON_LINK']['VALUE'] ? $item['PROPERTIES']['BUTTON_LINK']['VALUE'] : '/';
        ?>

        <? if ($item['PROPERTIES']['BANNER_LINK']['VALUE_XML_ID'] === 'Y'): ?><a <?= $target ?> href="<?= $link ?>"><? endif ?>

        <div class="native-slider-banner" style="background-image: url('<?= $item['DETAIL_PICTURE']['SAFE_SRC'] ?>')">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="native-slider-banner-content<? if ($item['SHOW_TIMER'] === 'N'): ?> without-timer<? endif ?>"
                             data-has-label="<?= $item['PROPERTIES']['LABEL']['VALUE'] ? 'Y' : 'N' ?>">
                            <? if ($item['PROPERTIES']['LABEL']['VALUE']): ?>
                                <div class="native-slider-label"
                                     <? if ($item['PROPERTIES']['LABEL_COLOR']['VALUE']): ?>style="background-color: <?= $item['PROPERTIES']['LABEL_COLOR']['VALUE'] ?>;"<? endif ?>>
                                    <?= $item['PROPERTIES']['LABEL']['VALUE'] ?>
                                </div>
                            <? endif ?>

                            <? if ($item['DETAIL_TEXT']): ?>
                                <div class="native-slider-title">
                                    <?= $item['DETAIL_TEXT'] ?>
                                </div>
                            <? endif ?>

                            <? if ($item['PREVIEW_TEXT']): ?>
                                <div class="native-slider-description">
                                    <?= $item['PREVIEW_TEXT'] ?>
                                </div>
                            <? endif ?>

                            <? if ($item['PROPERTIES']['BANNER_LINK']['VALUE_XML_ID'] === 'Y'): ?>

                                <? if ($item['PROPERTIES']['BUTTON_LINK']['VALUE']): ?>
                                    <div class="native-slider-button-wrapper">
                                        <div class="native-slider-button"
                                             <? if ($item['PROPERTIES']['BUTTON_COLOR']['VALUE']): ?>style="background-color: <?= $item['PROPERTIES']['BUTTON_COLOR']['VALUE'] ?>;"<? endif ?>>
                                            <?= $item['PROPERTIES']['BUTTON_TITLE']['VALUE'] ?>
                                        </div>
                                    </div>
                                <? endif ?>

                            <? else: ?>

                                <? if ($item['PROPERTIES']['BUTTON_LINK']['VALUE']): ?>
                                    <div class="native-slider-button-wrapper">
                                        <a <?= $target ?> href="<?= $link ?>" class="native-slider-button"
                                                          <? if ($item['PROPERTIES']['BUTTON_COLOR']['VALUE']): ?>style="border-color: <?= $item['PROPERTIES']['BUTTON_COLOR']['VALUE'] ?>;background-color: <?= $item['PROPERTIES']['BUTTON_COLOR']['VALUE'] ?>;"<? endif ?>>
                                            <?= $item['PROPERTIES']['BUTTON_TITLE']['VALUE'] ?>
                                        </a>
                                    </div>
                                <? endif ?>

                            <? endif ?>

                            <? if ($item['SHOW_TIMER'] === 'Y'): ?>
                                <div class="native-slider-timer" id="countdown-timer-<?= $item['ID'] ?>">

                                    <? if ($item['PROPERTIES']['TIMER_LABEL']['VALUE']): ?>
                                        <div class="native-slider-timer-label">
                                            <?= $item['PROPERTIES']['TIMER_LABEL']['VALUE'] ?>
                                        </div>
                                    <? endif ?>

                                    <div class="native-slider-timer-time">

                                        <? if ($item['PROPERTIES']['TIMER']['EXPIRE'] !== 'TODAY'): ?>
                                            <div class="native-slider-timer-time-section" data-type="days">
                                                <div class="native-slider-timer-time-section-numbers">
                                                    <div>0</div>
                                                    <div>0</div>
                                                </div>
                                                <div class="native-slider-timer-time-section-label">
                                                    Дней
                                                </div>
                                            </div>

                                            <div class="native-slider-timer-time-section-separator">:</div>
                                        <? endif ?>

                                        <div class="native-slider-timer-time-section" data-type="hours">
                                            <div class="native-slider-timer-time-section-numbers">
                                                <div>0</div>
                                                <div>0</div>
                                            </div>
                                            <div class="native-slider-timer-time-section-label">
                                                Часов
                                            </div>
                                        </div>

                                        <div class="native-slider-timer-time-section-separator">:</div>

                                        <div class="native-slider-timer-time-section" data-type="minutes">
                                            <div class="native-slider-timer-time-section-numbers">
                                                <div>0</div>
                                                <div>0</div>
                                            </div>
                                            <div class="native-slider-timer-time-section-label">
                                                Минут
                                            </div>
                                        </div>

                                        <? if ($item['PROPERTIES']['TIMER']['EXPIRE'] === 'TODAY'): ?>
                                            <div class="native-slider-timer-time-section-separator">:</div>

                                            <div class="native-slider-timer-time-section" data-type="seconds">
                                                <div class="native-slider-timer-time-section-numbers">
                                                    <div>0</div>
                                                    <div>0</div>
                                                </div>
                                                <div class="native-slider-timer-time-section-label">
                                                    Секунд
                                                </div>
                                            </div>
                                        <? endif ?>
                                    </div>
                                </div>

                                <script>
                                    initializeCountdownTimer('countdown-timer-<?= $item["ID"] ?>', <?= CUtil::PhpToJSObject($item['PROPERTIES']['TIMER']['TIMESTAMP']) ?>)
                                </script>
                            <? endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <? if ($item['PROPERTIES']['BANNER_LINK']['VALUE_XML_ID'] === 'Y'): ?></a><? endif ?>

    <? endforeach ?>

</div>
