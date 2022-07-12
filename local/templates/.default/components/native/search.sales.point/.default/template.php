<?php
/*
 * Изменено: 16 июля 2021, пятница
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
?>
<form class="form" name="SearchSalePoint">
    <div class="form__name">ПОИСК ОТДЕЛА В ВАШЕМ ГОРОДЕ</div>
    <input type="hidden" name="sessionId" value="<?= session_id() ?>">
    <div class="form__row">
        <div class="form__column">
            <input class="form__input" type="text" name="entity" placeholder="Страна/Город" maxlength="40">
        </div>
        <div class="form__column">
            <button class="button green" data-type="search">Найти отдел</button>
        </div>
    </div>
    <div class="form__result">
        <div class="form__search">
            Чтобы начать поиск, пожалуйста, введите в поле запроса название Страны или Города.
        </div>
        <div class="form__not__found">
            К сожалению, по вашему запросу ничего не найдено. Но Вы всегда можете оформить заказ на нашем сайте, выбрав
            продукцию
            <a href="/catalog/">в каталоге</a>.
        </div>
        <div class="form__found">
            По вашему запросу найдены фирменные отделы:
            <ul class="form__list"></ul>
        </div>
    </div>
</form>
<script>
    SearchSalePointComponent.construct(<?= $arResult['JS'] ?>)
</script>
