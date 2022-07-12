<?php
/*
 * Изменено: 15 декабря 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

LocalRedirect('/contacts/');
if (\Native\App\Template::isNewVersion()) {

}

$APPLICATION->SetTitle("Наши контакты");
?><p class="bxr-contacts-block">
	<b>Служба поддержки:</b><br>
	<br>
	<b><a href="tel:88003500270" target="_blank">8-800-350-02-70</a> - Звонок по РФ бесплатный.</b><br>
	<b><a href="mailto:admin@megre.ru" target="_blank">admin@megre.ru</a></b><br>
	<br>
	<b style="font-style: italic">Мы на связи пн-пт с 08:00 до 20:00 по Мск (12:00 до 24:00 по родному Новосибирску).</b><br>
	<br>
	<b>Москва</b><br>
	<br>
	<b>Пункт самовывоза заказов: ул. Новослободская 18, офис 203</b><br>
	<b>График работы: пн-пт 12:00 - 20:00</b><br>
	<b><a href="tel:89152112646" target="_blank">8-915-211-2646</a></b><br>
	<b><a href="tel:88003500270" target="_blank">8-800-350-0270</a></b><br>
	<br>
	<br>
	<b>Заказы доставляем по России, Казахстану и Белоруссии.</b><br>
	<br>
	<b>Розничные заказы в другие страны:</b><br>
	<b><a href="mailto:hello@megrellc.com" target="_blank">hello@megrellc.com</a></b><br>
	<b><a href="https://megrellc.com" target="_blank">Megrellc.com</a></b><br>
	<br>
	<b>Оптовые заказы:</b><br>
	<b><a href="tel:89139150270" target="_blank">+7-913-915-02-70</a></b><br>
	<b><a href="tel:83833638651" target="_blank">+7 (383) 363-86-51</a></b><br>
	<b><a href="mailto:sales@megre.ru" target="_blank">sales@megre.ru</a></b><br>
	<br>
	<b>Фактический адрес:</b><br>
	г. Москва, ул. Новослободская 18, оф. 203<br>
	г. Новосибирск, ул. Коммунистическая 2, оф. 516<br>
	<br>
	<b>Юридический адрес:</b><br>
	ООО «Звенящие Кедры», Россия, 630121, г. Новосибирск, ул.Невельского, д.69, кв. 91 <br>
	<br>
	<b>Почтовый адрес:</b><br>
	Россия, 630121, г. Новосибирск, а/я 44<br>
	ИНН 5404428665<br>
	КПП 540401001<br>
	ОГРН 1115476002707
</p>
	<div style="margin-top: 40px">
		<a href="https://www.facebook.com/ringingcedarsmegre/" target="_blank"> <img width="57"
																					 alt="facebook"
																					 src="/upload/medialibrary/233/face.png"
																					 height="55"> </a>
		<a href="https://www.instagram.com/megre.ru/" target="_blank"> <img width="57"
																			alt="fot.png"
																			src="/upload/medialibrary/c31/fot.png"
																			height="55"> </a>
		<a href="https://www.youtube.com/playlist?list=PLBFup2l1YE8anj28g8OEQCYu_YTcwC0WF" target="_blank">
			<img width="57" alt="yout.png" src="/upload/medialibrary/260/yout.png" height="55"> </a>
		<a href="https://vk.com/ringingcedars" target="_blank"> <img width="57"
																	 alt="vk.png"
																	 src="/upload/medialibrary/79a/vk.png"
																	 height="55"> </a>
		<a href="/~511jB" target="_blank"> <img width="57"
																	alt="teleg.png"
																	src="/upload/medialibrary/f99/teleg.png"
																	height="55"> </a>
		<a href="https://ok.ru/group/55229491970291" target="_blank"> <img width="57"
																		   alt="teleg.png"
																		   src="/bitrix/templates/market_fullscreen/./images/ok.png"
																		   height="55"> </a>
	</div>
	<h3 style="margin-top: 40px">Отправить сообщение</h3>
	<p>
	</p>
<? $APPLICATION->IncludeComponent(
		"altasib:feedback.form",
		".default",
		[
				"ACTIVE_ELEMENT"                    => "Y",
				"ADD_HREF_LINK"                     => "Y",
				"ALX_LINK_POPUP"                    => "N",
				"BBC_MAIL"                          => "",
				"CAPTCHA_TYPE"                      => "default",
				"CATEGORY_SELECT_NAME"              => "Выберите категорию",
				"CHANGE_CAPTCHA"                    => "N",
				"CHECKBOX_TYPE"                     => "CHECKBOX",
				"CHECK_ERROR"                       => "Y",
				"COLOR_OTHER"                       => "#009688",
				"COLOR_SCHEME"                      => "BRIGHT",
				"COLOR_THEME"                       => "c4",
				"COMPONENT_TEMPLATE"                => ".default",
				"EVENT_TYPE"                        => "ALX_FEEDBACK_FORM",
				"FB_TEXT_NAME"                      => "Ваше сообщение",
				"FB_TEXT_SOURCE"                    => "PREVIEW_TEXT",
				"FORM_ID"                           => "2",
				"IBLOCK_ID"                         => "47",
				"IBLOCK_TYPE"                       => "altasib_feedback",
				"INPUT_APPEARENCE"                  => [0 => "DEFAULT",],
				"JQUERY_EN"                         => "jquery",
				"LINK_SEND_MORE_TEXT"               => "Отправить ещё одно сообщение",
				"LOCAL_REDIRECT_ENABLE"             => "N",
				"MASKED_INPUT_PHONE"                => [],
				"MESSAGE_OK"                        => "Ваше сообщение было успешно отправлено",
				"NAME_ELEMENT"                      => "ALX_DATE",
				"NOT_CAPTCHA_AUTH"                  => "N",
				"PROPERTY_FIELDS"                   => [0 => "THEME", 1 => "FIO", 2 => "EMAIL", 3 => "FEEDBACK_TEXT",],
				"PROPERTY_FIELDS_REQUIRED"          => [0 => "FIO", 1 => "EMAIL", 2 => "FEEDBACK_TEXT",],
				"PROPS_AUTOCOMPLETE_EMAIL"          => [0 => "EMAIL",],
				"PROPS_AUTOCOMPLETE_NAME"           => [0 => "FIO",],
				"PROPS_AUTOCOMPLETE_PERSONAL_PHONE" => [],
				"PROPS_AUTOCOMPLETE_VETO"           => "N",
				"RECAPTCHA_THEME"                   => "light",
				"RECAPTCHA_TYPE"                    => "image",
				"SECTION_FIELDS_ENABLE"             => "N",
				"SECTION_MAIL_ALL"                  => "admin@megre.ru",
				"SEND_IMMEDIATE"                    => "Y",
				"SEND_MAIL"                         => "N",
				"SHOW_LINK_TO_SEND_MORE"            => "N",
				"SHOW_MESSAGE_LINK"                 => "Y",
				"USERMAIL_FROM"                     => "N",
				"USER_CONSENT"                      => "Y",
				"USER_CONSENT_ID"                   => "1",
				"USER_CONSENT_INPUT_LABEL"          => "",
				"USER_CONSENT_IS_CHECKED"           => "N",
				"USER_CONSENT_IS_LOADED"            => "N",
				"USE_CAPTCHA"                       => "Y",
				"WIDTH_FORM"                        => "50%",
		]
); ?><?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>