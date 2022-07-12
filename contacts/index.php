<?php
/*
 * Изменено: 14 декабря 2021, вторник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
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
$APPLICATION->SetTitle('Контактная информация');
$APPLICATION->SetPageProperty('title', 'Контактная информация');
$APPLICATION->SetPageProperty('description', 'Контактная информация ООО Звенящие Кедры');
$APPLICATION->SetPageProperty('keywords', 'ООО Звенящие Кедры, Телефон, амаил, email, социальные сети');
?>
	<div class="container">
		<h1 class="page-title mb-4 mb-md-5 pb-3"><?= $APPLICATION->GetTitle() ?></h1>
		<div class="content-side">
			<div class="column-content">
				<div class="main-contacts">
					<h3 class="mb-4 pb-2">Мы ценим Ваше время<br>и рады ответить на любой Ваш вопрос – звоните!</h3>
					<p>
						<a class="main-phone" href="tel:88003500270" target="_blank">8-800-350-0270</a>
					</p>
					<p class="mt-3">
						Часы работы: будние дни с 6:00 до 16:00 (время Московское)
					</p>
					<h3 class="mt-4 pt-2">Не любите говорить по телефону?</h3>
					<p class="mt-3">
						Тогда пишите нам на <a href="mailto:admin@megre.ru" target="_blank">admin@megre.ru</a>
					</p>
				</div>
				<div class="additional-contacts">
					<div>
						<div class="addition-contact-title"><i class="home"></i>
							<h3>Наш склад</h3></div>
						<p class="addition-contact-content">
							<a href="tel:89152112646" target="_blank">8-915-211-2646</a>
							<br>
							ПН-ПТ с 12:00 до 20:00 (по предварительному звонку)
							<br>
							<a href="https://yandex.ru/maps/-/CCUuvSVhXC"
							   target="_blank">ул. Новослободская 18, офис 203</a>
						</p>
					</div>
					<div>
						<div class="addition-contact-title"><i class="earth"></i>
							<h3>Заказ в другие страны</h3></div>
						<p class="addition-contact-content">
							<a href="https://megrellc.com" target="_blank">megrellc.com</a><br>
							<a href="mailto:hello@megrellc.com" target="_blank">hello@megrellc.com</a><br>
							Доставка по всему миру
						</p>
					</div>
					<div>
						<div class="addition-contact-title"><i class="truck"></i>
							<h3>Оптовые заказы</h3></div>
						<p class="addition-contact-content">
							<a href="tel:89139150270" target="_blank">+7-913-915-02-70</a><br>
							<a href="mailto:opt@megre.ru" target="_blank">opt@megre.ru</a>
						</p>
					</div>
					<div>
						<div class="addition-contact-title"><i class="pine-cone"></i>
							<h3>Производителям</h3></div>
						<p class="addition-contact-content">
							<a href="/cooperation/manufacturers-patrimonial-estates/">Условия сотрудничества</a><br>
							<a href="mailto:zakup@megre.ru" target="_blank">zakup@megre.ru</a>
						</p>
					</div>
				</div>
				<div>
					<div class="page-title mt-0 mb-4">Обратная связь</div>
					<p class="mb-4">
						Мы будем рады получить и учесть ваши пожелания по работе магазина, обработке и доставке заказа! Пожалуйста, указывайте номер своего заказа для более оперативной обработки запроса.
					</p>
					<div class="my-4 pt-3 pb-4">
						<?php $APPLICATION->IncludeComponent(
								"bitrix:form.result.new",
								"contacts",
								[
										"AJAX_MODE"              => "Y", // режим AJAX
										"AJAX_OPTION_SHADOW"     => "N", // затемнять область
										"AJAX_OPTION_JUMP"       => "N", // скролить страницу до компонента
										"AJAX_OPTION_STYLE"      => "Y", // подключать стили
										"AJAX_OPTION_HISTORY"    => "N",
										"CACHE_TIME"             => "3600",
										"CACHE_TYPE"             => "A",
										"CHAIN_ITEM_LINK"        => "",
										"CHAIN_ITEM_TEXT"        => "",
										"COMPOSITE_FRAME_MODE"   => "A",
										"COMPOSITE_FRAME_TYPE"   => "AUTO",
										"EDIT_URL"               => "",
										"IGNORE_CUSTOM_TEMPLATE" => "N",
										"LIST_URL"               => "",
										"SEF_MODE"               => "N",
										"SUCCESS_URL"            => "",
										"USE_EXTENDED_ERRORS"    => "Y",
										"VARIABLE_ALIASES"       => [],
										"WEB_FORM_ID"            => "1",
								]
						) ?>
					</div>
				</div>
                <div class="my-5 d-block d-lg-none">
                    <h3 class="mb-4">Мы в социальных сетях</h3>
                    <?php $APPLICATION->IncludeComponent('native:static.block', 'sidebar.social.links') ?>
                </div>
				<div class="mt-5">
					<h3 class="mb-4">Мы принимаем</h3>
					<?php $APPLICATION->IncludeComponent('native:static.block', 'payment.methods') ?>
					<p class="mt-4 mb-0">
						Доставка осуществляется почтой, курьером или самовывозом, а также Boxberry.
					</p>
				</div>
			</div>
			<div class="column-side">
				<div><?php $APPLICATION->IncludeComponent('native:static.block', 'sidebar.banner.gift.from.taiga') ?></div>
				<div class="my-5">
					<h3 class="mb-4">Мы в социальных сетях</h3>
					<?php $APPLICATION->IncludeComponent('native:static.block', 'sidebar.social.links') ?>
				</div>
				<div><?php $APPLICATION->IncludeComponent('native:static.block', 'sidebar.banner.free.delivery') ?></div>
			</div>
		</div>
	</div>
<?php $APPLICATION->IncludeComponent('native:static.block', 'fullwidth.subscribe.form') ?>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';