<? require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
/*
 * Изменено: 09 декабря 2021, четверг
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

$APPLICATION->SetTitle("Производителям из родовых поместий");
?><p>
</p>
<p style="text-align: justify;">
	 Идея компании «Звенящие Кедры» в том, чтобы объединить товары, сделанные с любовью к природе и своему делу в родовых поместьях России. Мы поддерживаем производителей, которые живут и творят на земле, возрождая традиции предков и создавая свои. Силу, качество и действие таких продуктов покупатели ценят всё больше.
</p>
<p style="text-align: justify;">
</p>
<p style="text-align: justify;">
	 Мы открыты новому сотрудничеству и готовы представить Ваш продукт под знаком «Звенящие кедры России». Товар может быть представлен для отдельных регионов или на всей территории России, на международном рынке, в интернет-магазине megre.ru.
</p>
<p style="text-align: justify;">
</p>
<p style="text-align: justify;">
	 Мы дорожим чистой репутацией, сложившейся за более 19 лет существования марки, и доверием клиентов, потому представляем только продукцию, соответствующую нашим взглядам на производство, сознание и образ жизни производителя.
</p>
<p style="text-align: justify;">
</p>
<p style="text-align: justify;">
	 &nbsp;
</p>
<p style="text-align: justify;">
</p>
<p style="text-align: center;">
 <img width="604" alt="image (2).jpg" src="/upload/medialibrary/6c4/image-_2_.jpg" height="453" title="image (2).jpg" align="middle"><br>
</p>
<p style="text-align: justify;">
</p>
<p style="text-align: justify;">
	 &nbsp;
</p>
<p style="text-align: justify;">
</p>
<p style="text-align: justify;">
 <b><i>Какая продукция может продаваться под знаком «Звенящие кедры России»?</i></b><br>
	 Обязательные условия, на основании которых продукт попадает в наш ассортимент:
</p>
<p style="text-align: justify;">
</p>
<p style="text-align: justify;">
	 - производится в родовом поместье или личном подворье, в семьях или у мастеров, проживающих на земле<br>
	 - 100% экологичность, продукция изготовлена из природных материалов, без химических веществ и консервантов в составе<br>
	 - выращивание и сбор сырья осуществляется осознанно, с уважением к земле и природе, щадящими, экологичными способами, это значит, что не используются гормоны и ускоряющие рост препараты<br>
	 - использование преимущественно ручного труда
</p>
<p style="text-align: justify;">
</p>
<p style="text-align: justify;">
	 &nbsp;
</p>
<p style="text-align: justify;">
</p>
<p style="text-align: center;">
 <img width="560" alt="image (3).jpg" src="/upload/medialibrary/96c/image-_3_.jpg" height="374" title="image (3).jpg" align="middle"><br>
</p>
<p style="text-align: justify;">
</p>
<p style="text-align: justify;">
	 &nbsp;
</p>
<p style="text-align: justify;">
</p>
<p style="text-align: center;">
 <img width="877" alt="image (4).jpg" src="/upload/medialibrary/de8/image-_4_.jpg" height="658" title="image (4).jpg" align="middle"><br>
</p>
<p style="text-align: justify;">
</p>
<p style="text-align: justify;">
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</p>
<p style="text-align: justify;">
</p>
<p style="text-align: justify;">
 <b><i>Чтобы начать сотрудничество с нами, необходимо:</i></b>
</p>
<p style="text-align: justify;">
</p>
<p style="text-align: justify;">
	 - зарегистрировать юридическое лицо или ИП <br>
	 - иметь банковский расчетный счет <br>
	 - сделать сертификаты и другую необходимую документацию на продукцию<br>
	 - отправить предложение на почту&nbsp;<a href="mailto:zakup@megre.ru">zakup@megre.ru</a>&nbsp;и указать Ваши контакты
</p>
<p style="text-align: justify;">
</p>
<p style="text-align: justify;">
	 Мы все внимательно изучим и свяжемся с Вами. Мы открыты новым идеям и с радостью их рассматриваем, предлагайте.
</p>
<h3>Оставить запрос</h3>
<p>
</p>
 <?$APPLICATION->IncludeComponent(
	"altasib:feedback.form",
		"",
		[
				"ACTIVE_ELEMENT"                    => "Y",
				"ADD_HREF_LINK"                     => "Y",
				"ADD_LEAD"                          => "N",
				"ALX_LINK_POPUP"                    => "N",
				"BBC_MAIL"                          => "opt@megre.ru",
				"CAPTCHA_TYPE"                      => "default",
				"CATEGORY_SELECT_NAME"              => "Выберите категорию",
				"CHANGE_CAPTCHA"                    => "N",
				"CHECKBOX_TYPE"                     => "CHECKBOX",
				"CHECK_ERROR"                       => "Y",
				"COLOR_OTHER"                       => "#009688",
				"COLOR_SCHEME"                      => "BRIGHT",
				"COLOR_THEME"                       => "c4",
				"COMPOSITE_FRAME_MODE"              => "A",
				"COMPOSITE_FRAME_TYPE"              => "AUTO",
				"EVENT_TYPE"                        => "ALX_FEEDBACK_FORM",
				"FB_TEXT_NAME"                      => "Ваше сообщение",
				"FB_TEXT_SOURCE"                    => "PREVIEW_TEXT",
				"FORM_ID"                           => "2",
				"IBLOCK_ID"                         => "49",
				"IBLOCK_TYPE"                       => "altasib_feedback",
				"INPUT_APPEARENCE"                  => ["DEFAULT"],
				"JQUERY_EN"                         => "jquery2",
				"LINK_SEND_MORE_TEXT"               => "Отправить ещё одно сообщение",
				"LOCAL_REDIRECT_ENABLE"             => "N",
				"MASKED_INPUT_PHONE"                => [],
				"MESSAGE_OK"                        => "Ваше сообщение было успешно отправлено",
				"NAME_ELEMENT"                      => "ALX_DATE",
				"NOT_CAPTCHA_AUTH"                  => "Y",
				"PROPERTY_FIELDS"                   => ["EMAIL", "PRODUCTIONS", "PHONE", "FIO", "FEEDBACK_TEXT"],
				"PROPERTY_FIELDS_REQUIRED"          => ["EMAIL", "PHONE", "FIO"],
				"PROPS_AUTOCOMPLETE_EMAIL"          => ["EMAIL"],
				"PROPS_AUTOCOMPLETE_NAME"           => ["FIO"],
				"PROPS_AUTOCOMPLETE_PERSONAL_PHONE" => ["PHONE"],
				"PROPS_AUTOCOMPLETE_VETO"           => "N",
				"REQUIRED_SECTION"                  => "N",
				"SECTION_FIELDS_ENABLE"             => "N",
				"SECTION_MAIL_ALL"                  => "zakup@megre.ru",
				"SEND_IMMEDIATE"                    => "Y",
				"SEND_MAIL"                         => "N",
				"SHOW_LINK_TO_SEND_MORE"            => "N",
				"SHOW_MESSAGE_LINK"                 => "Y",
				"SPEC_CHAR"                         => "N",
				"USERMAIL_FROM"                     => "N",
				"USER_CONSENT"                      => "Y",
				"USER_CONSENT_ID"                   => "1",
				"USER_CONSENT_INPUT_LABEL"          => "",
				"USER_CONSENT_IS_CHECKED"           => "N",
				"USER_CONSENT_IS_LOADED"            => "N",
				"USE_CAPTCHA"                       => "Y",
				"WIDTH_FORM"                        => "50%",
		]
); ?>
	<p style="text-align: justify;">
	</p>
<p style="text-align: center;">
</p>
<p>
 <img width="800" alt="image (1).jpeg" src="/upload/medialibrary/b58/image-_1_.jpeg" height="600" title="image (1).jpeg" align="middle"><br>
</p>
<p>
 <br>
</p>
<hr><? require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php'); ?>