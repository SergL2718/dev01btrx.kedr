<?php
/*
 * Изменено: 29 декабря 2021, среда
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

/**
 * @global CMain $APPLICATION
 */

use Bitrix\Main\Page\Asset;

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

// Чтобы пока еще радел не готов, то чтобы никто не мог зайти, через адресную строку
if (!\Native\App\Template::isNewVersion()) {
	//LocalRedirect('/');
}

Asset::getInstance()->addCss('/' . basename(__DIR__) . '/style.css');
$APPLICATION->SetTitle("Где купить");
$APPLICATION->SetPageProperty('title', 'Где купить');
$APPLICATION->SetPageProperty('description', 'Где купить продукцию компании Звенящие Кедры');
$APPLICATION->SetPageProperty('keywords', 'магазины');
?><div class="container">
	<h1 class="page-title mb-4">ГДЕ КУПИТЬ НАШУ ПРОДУКЦИЮ</h1>
	<p>
		 Покупка онлайн быстро и удобно на сайте <a href="/" class="link"><b>megre.ru</b></a>
	</p>
	<div class="page-two-columns stores">
		<div class="page-column">
			<p class="mt-4 pt-2">
 <a class="main-phone" href="tel:88003500270" target="_blank"><b>8-800-350-0270</b></a> <br>
 <br>
				 Звонок по РФ бесплатный <br>
				 Поможем подобрать нужный продукт и оформить заказ
			</p>
			<div class="additional-contacts">
				<div>
					<div class="addition-contact-title">
 <i class="home"></i>
						<h3>москва</h3>
					</div>
					<p class="addition-contact-content">
 <a href="tel:89152112646" target="_blank">8-915-211-2646</a> <br>
						 ПН-ПТ с 12:00 до 20:00 (по предварительному звонку) <br>
 <a href="https://yandex.ru/maps/-/CCUuvSVhXC" target="_blank">ул. Новослободская 18, офис 203</a>
					</p>
				</div>
				<div>
					<div class="addition-contact-title">
 <i class="earth"></i>
						<h3>Заказ в другие страны</h3>
					</div>
					<p class="addition-contact-content">
 <a href="https://megrellc.com" target="_blank">megrellc.com</a><br>
 <a href="mailto:hello@megrellc.com" target="_blank" class="link"><b>hello@megrellc.com</b></a><br>
						 Доставка по всему миру
					</p>
				</div>
				<div>
					<div class="addition-contact-title">
 <i class="truck"></i>
						<h3>Оптовые заказы</h3>
					</div>
					<p class="addition-contact-content">
 <a href="tel:89139150270" target="_blank">+7-913-915-02-70</a><br>
 <a href="mailto:opt@megre.ru" target="_blank" class="link"><b>opt@megre.ru</b></a>
					</p>
				</div>
				<div>
					<div class="addition-contact-title">
 <i class="pine-cone"></i>
						<h3>Производителям</h3>
					</div>
					<p class="addition-contact-content">
 <a href="/cooperation/manufacturers-patrimonial-estates/" class="link"><b>Условия сотрудничества</b></a><br>
 <a href="mailto:zakup@megre.ru" target="_blank" class="link"><b>zakup@megre.ru</b></a>
					</p>
				</div>
			</div>
			<div>
				<div class="page-title mt-0 mb-4">
					 ПОИСК ОТДЕЛА В ВАШЕМ ГОРОДЕ
				</div>
				<div class="my-4 pt-3 pb-4">
					 <?$APPLICATION->IncludeComponent(
	"native:store.list",
	"",
	Array(
		"IBLOCK_ID" => 53
	),
false,
Array(
	'HIDE_ICONS' => 'Y'
)
);?>
				</div>
			</div>
			<div class="video-wrapper mb-5">
				 <iframe src="https://www.youtube.com/embed/Hnxm4eg70Xo?rel=0&showinfo=0" frameborder="0"
							allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
							allowfullscreen=""></iframe>
			</div>
			<div class="social-links-wrap mb-5 d-flex d-lg-none">
				<h3 class="mb-4">Мы в социальных сетях</h3>
				 <?$APPLICATION->IncludeComponent(
	"native:static.block",
	"sidebar.social.links",
Array()
);?>
			</div>
		</div>
		<div class="page-column justify-content-start d-none d-lg-flex">
			<div>
				 <?$APPLICATION->IncludeComponent(
	"native:static.block",
	"sidebar.banner.gift.from.taiga",
Array()
);?>
			</div>
			<div class="social-links-wrap my-5">
				<h3 class="mb-4">Мы в социальных сетях</h3>
				 <?$APPLICATION->IncludeComponent(
	"native:static.block",
	"sidebar.social.links",
Array()
);?>
			</div>
		</div>
	</div>
</div>
<br>

<?require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';