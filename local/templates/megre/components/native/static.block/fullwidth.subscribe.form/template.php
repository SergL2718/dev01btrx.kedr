<?php
/*
 * Изменено: 09 декабря 2021, четверг
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
?>
<div class="static-banner-wrapper" data-code="fullwidth-subscribe-form" style="background-image:url('<?= $this->getFolder() ?>/images/bg.jpeg?v=fullwidth');">
	<div class="container">
		<div class="static-banner-content">
			<div class="static-banner-title">Узнайте первыми о наших акциях и новинках!</div>
			<div class="static-banner-detail">
				<label for="email">Ваш E-mail</label>
				<input type="email" name="email" id="email">
				<a href="javascript:void(0)">Подписаться</a>
			</div>
			<div class="static-banner-privacy">
				Нажимая на кнопку, вы даете согласие на обработку ваших персональных данных в соответствии с
				<a href="/privacy-policy/">политикой конфиденциальности</a>
			</div>
		</div>
	</div>
</div>