<?php
/*
 * Изменено: 13 сентября 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
?>
<div class="static-banner-wrapper">
	<div class="static-banner-background">
        <picture>
            <source srcset="<?= $this->getFolder() ?>/images/bg-mob.jpg" media="(max-width: 767px)">
            <img src="<?= $this->getFolder() ?>/images/bg.jpeg" alt="Бесконтактная доставка">
        </picture>
	</div>
	<div class="static-banner-content">
		<div class="static-banner-title">Бесконтактная<br>доставка</div>
		<div class="static-banner-description">При заказе от 5000 рублей стандартная наземная доставка Почтой России и Boxberry (пункт выдачи) - бесплатно.</div>
		<div class="static-banner-detail"><a href="/catalog/">В магазин</a></div>
	</div>
</div>