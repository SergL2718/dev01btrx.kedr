<?
/*
 * @updated 09.03.2021, 13:10
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2021, Компания Webco <hello@wbc.cx>
 * @link https://wbc.cx
 */

/**
 * @var array $arResult
 */

use Bitrix\Main\Web\Json;
use Native\App\Sale\Location;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
?>

<div class="user__location">
    <div class="user__location__city__title" data-controller="showLocationList">
        <?= $arResult['LOCATION']['TITLE'] ?>
    </div>
    <i class="fas fa-angle-down"></i>
</div>
<div class="user__location__list hidden">
    <div>Ваш регион доставки <?= Location::MOSCOW_CITY_TITLE_NORMAL ?>?</div>
    <div><a href="javascript:void(0)" data-controller="setLocation" data-city-code="MSK">запомнить выбор</a></div>
    <div><a href="javascript:void(0)" data-controller="setLocation" data-city-code="NSK">Новосибирск</a></div>
    <div><a href="javascript:void(0)" data-controller="setLocation" data-city-code="OTHER">другой город</a></div>
</div>

<script>
    UserLocationComponent.run(<?= Json::encode([
        'COOKIE' => $arResult['COOKIE'],
        'LOCATION' => $arResult['LIST'],
    ]) ?>)
</script>