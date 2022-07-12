<?php
/*
 * Изменено: 09 декабря 2021, четверг
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
?>
<div class="static-banner-wrapper" data-code="sidebar-gift-from-taiga">
	<div class="static-banner-background">
		<img src="<?= $this->getFolder() ?>/images/bg.jpeg?v=sidebar" alt="Хотите подарок с таёжного производства?">
	</div>
	<div class="static-banner-content">
		<div class="static-banner-title">Хотите подарок<br>с таёжного производства?</div>
		<div class="static-banner-description">Дарим дорожный размер нашей любимой зубной пасты при подписке.</div>
		<div class="static-banner-detail">
			<label for="email">Ваш E-mail</label>
			<input type="email" name="email" id="email">
			<a href="javascript:void(0)">Подписаться</a>
		</div>
		<div class="static-banner-privacy">
			Нажимая кнопку «Подписаться», я даю свое согласие на обработку моих персональных данных, в соответствии с Федеральным законом от 27.07.2006 года №152-ФЗ «О персональных данных», на условиях и для целей, определенных в
			<a href="/privacy-policy/">Согласии на обработку персональных данных</a>.
		</div>
	</div>
</div>