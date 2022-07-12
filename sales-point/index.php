<?php
/*
 * Изменено: 14 декабря 2021, вторник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
LocalRedirect('/stores/');
if (\Native\App\Template::isNewVersion()) {

}

$APPLICATION->SetTitle("Где купить нашу продукцию");
?><p>
	Покупка онлайн быстро и удобно на <a href="https://megre.ru">www.megre.ru</a> <br>
	<br>
	<b>8 (800) 350-02-70</b> - Звонок по РФ бесплатный.<br>
	Телефон горячей линии - поможем подобрать нужный продукт и оформить заказ <br>
	<br>
	<b>Отдел оптовых продаж:</b>
</p>
<p>
    +7-913-915-02-70<br>
    +7 (383) 363-86-51 <br>
    <br>
    <b>Электронная почта: </b><a href="mailto:sales@megre.ru" target="_blank">sales@megre.ru</a> <br>
    <br>
    <a href="https://megrellc.com" target="_blank"><b>megrellc.com</b></a><b> - Доставка продукции по всему Миру.
        Официальный англоязычный Интернет-магазин. </b>
</p>
<b> </b>
<p>
    <b> </b>
    <noindex><a href="http://www.megrellc.com/" target="_blank"><b>www.megrellc.com</b></a></noindex>
    <b>&nbsp;- our international store</b><br>
</p>
<hr style="margin-top: 25px;margin-bottom: 30px;border-top: 2px solid #b0d683;">
<? $APPLICATION->IncludeComponent(
    "native:search.sales.point",
    "",
    array(),
    false,
		[
				'HIDE_ICONS' => 'Y',
		]
); ?>
	<div class="bx-newsdetail-youtube embed-responsive embed-responsive-16by9" style="display: block;">
		<iframe src="https://www.youtube.com/embed/Hnxm4eg70Xo?rel=0&amp;showinfo=0" frameborder="0"
				allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
				allowfullscreen=""></iframe>
	</div>
	<br>
<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';