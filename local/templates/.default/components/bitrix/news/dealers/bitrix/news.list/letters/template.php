<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
$count = 4;
$counter = 0;
?>

<? if ($arResult['SECTION']['DESCRIPTION']): ?>
    <div class="section-description">
        <?= $arResult['SECTION']['DESCRIPTION'] ?>
    </div>
<? endif; ?>
<a href="/dealers/map/" class="show-map">Посмотреть карту</a>
<div class="clearfix"></div>
<div class="data">
    <? if ($arResult['CITIES']): ?>
        <?
        foreach ($arResult['CITIES'] as $letter => $cities) {
            ?>
            <div class="cities">
                <div class="letter"><?= $letter ?></div>
                <?
                foreach ($cities as $city) {
                    ?>
                    <div class="city">
                        <a class="name" href="<?= $city['URL'] ?>">
                            <?= $city['NAME'] ?><span class="count"><?= $city['COUNT'] ?></span>
                        </a>
                    </div>
                    <?
                }
                ?>
            </div>
            <?
            $counter++;
            if ($counter == $count) {
                ?>
                <div class="clear"></div>
                <?
                $counter = 0;
            }
        }
        ?>
    <? else: ?>
        <?
        foreach ($arResult['STORES'] as $ar) {
            ?>
            <div class="store">
                <div class="name"><?= $ar['NAME'] ?></div>
                <div class="contacts">
                    <ul>
                        <?
                        if ($ar['ADDRESS']): ?>
                            <li><span class="caption">Адрес:</span><?= $ar['ADDRESS'] ?></li>
                        <? endif; ?>
                        <?
                        if ($ar['TC']): ?>
                            <li><span class="caption">Торговый центр:</span><?= $ar['TC'] ?></li>
                        <? endif; ?>
                        <?
                        if ($ar['TIME']): ?>
                            <li><span class="caption">Время работы:</span><?= $ar['TIME'] ?></li>
                        <? endif; ?>
                        <?
                        if ($ar['PHONE']): ?>
                            <? foreach ($ar['PHONE'] as $phone): ?>
                                <li><i class="fa fa-phone"></i> <?= $phone ?></li>
                            <? endforeach; ?>
                        <? endif; ?>
                        <?
                        if ($ar['EMAIL']): ?>
                            <li><i class="fa fa-envelope"></i> <a href="mailto:<?= $ar['EMAIL'] ?>"
                                                                  target="_blank"><?= $ar['EMAIL'] ?></a>
                            </li><? endif; ?>
                        <?
                        if ($ar['WWW']): ?>
                            <li><i class="fa fa-globe"></i> <a href="<?= $ar['WWW_LINK'] ?>"
                                                               target="_blank"><?= $ar['WWW'] ?></a></li><? endif; ?>
                        <?
                        if ($arResult['SECTION']['DEPTH_LEVEL'] == 1 && !in_array($arResult['SECTION']['CODE'], $arResult['SHOW_CITIES'])): ?>
                            <li class="additional-info">Если у нашего представителя нет интересующей Вас продукции, Вы
                                можете сделать заказ в Интернет-магазине с доставкой по всему миру:
                                <a href="https://megrellc.com">www.megrellc.com</a></li>
                        <? endif; ?>
                    </ul>
                </div>
            </div>
            <?
        }
        ?>
    <? endif; ?>
</div>
