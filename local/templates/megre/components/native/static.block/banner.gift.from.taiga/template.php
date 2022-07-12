<?php
/*
 * Изменено: 09 декабря 2021, четверг
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
?>
<div class="static-banner-wrapper" data-code="gift-from-taiga">
	<div class="static-banner-background">
        <picture>
            <source srcset="<?= $this->getFolder() ?>/images/bg-mob.jpg" media="(max-width: 767px)">
            <img src="<?= $this->getFolder() ?>/images/bg.jpeg" alt="Хотите подарок с таёжного производства?">
        </picture>
	</div>
	<div class="static-banner-content">
		<div class="static-banner-title">Хотите подарок с таёжного производства?</div>
		<div class="static-banner-description">Дарим дорожный размер нашей любимой зубной пасты при подписке на новости</div>
		<div class="static-banner-detail">
			<input type="email" name="email" placeholder="Ваш E-mail">
			<a href="javascript:void(0)">Подписаться</a>
		</div>
	</div>
</div>