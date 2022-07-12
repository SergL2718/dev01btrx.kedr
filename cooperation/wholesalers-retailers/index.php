<?
header('Location: /cooperation');
exit;
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetTitle("Оптовикам");
$APPLICATION->SetAdditionalCSS('/bitrix/css/main/font-awesome.min.css');
?><style>
    .opt-contacts {
        padding: 0;
        margin: 0;
        list-style-type: none;
    }

    .opt-contacts > li {
        padding-left: 20px;
        position: relative;
    }

    .opt-contacts > li:before {
        content: '✔';
        position: absolute;
        top: 0;
        left: 0;
    }
</style>
<p>
	 Если Вы хотите приобретать продукцию под знаком «Звенящие кедры России» оптом для дальнейшей продажи через торговые точки, для производства собственной продукции или личного потребления, оформляйте оптовый заказ – сумма заказа от 10 000 рублей. При таком заказе цены будут значительно ниже розничных.
</p>
 <br>
<h2>КАК ПРИОБРЕСТИ ПРОДУКЦИЮ ОПТОМ?</h2>
<p>
	 Свяжитесь с менеджером оптовых продаж любым удобным для вас способом:
</p>
<h2>СКЛАД В НОВОСИБИРСКЕ</h2>
<ul class="opt-contacts">
	<li>
	Телефоны:<br>
 <a href="tel:83833638651">8 (383) 363-86-51</a><br>
 <a href="tel:89139150270">8 (913) 915-02-70</a> <img src="https://prirodapteka.ru/images/whatsapp-32.png" alt=""><br>
 <a href="tel:88003500270">8 (800) 350-02-70</a> <i class="fa fa-phone-square" style="font-size: 22px;"></i>
	(звонок бесплатный) </li>
	<li>по e-mail <a href="mailto:sales@megre.ru" target="_blank">sales@megre.ru</a></li>
	 <? /*<li>Оформите заказ через интернет-магазин. Автоматически при покупке на сумму более 20 000 рублей, оформится
            оптовая скидка 20%.
        </li>*/ ?>
</ul>
 <br>
<h2>СКЛАД В ПЕРМИ</h2>
<ul class="opt-contacts">
	<li>
	Телефоны: <a href="tel:89024725950">8 (902) 472-59-50</a> <i class="fa fa-phone-square" style="font-size: 22px;"></i> </li>
	<li>по e-mail <a href="mailto:perm@megre.ru" target="_blank">perm@megre.ru</a></li>
</ul>
 <br>
<p>
	 Или заполните и отправьте форму заявки на сайте, мы с Вами свяжемся сами
</p>
 <br>
<h2 align="center">ФОРМА ЗАЯВКИ</h2>
 <?$APPLICATION->IncludeComponent(
	"altasib:feedback.form",
	".default",
	Array(
		"ACTIVE_ELEMENT" => "Y",
		"ADD_HREF_LINK" => "Y",
		"ALX_LINK_POPUP" => "N",
		"BBC_MAIL" => "",
		"CAPTCHA_TYPE" => "default",
		"CATEGORY_SELECT_NAME" => "Выберите категорию",
		"CHANGE_CAPTCHA" => "N",
		"CHECKBOX_TYPE" => "CHECKBOX",
		"CHECK_ERROR" => "Y",
		"COLOR_OTHER" => "#009688",
		"COLOR_SCHEME" => "BRIGHT",
		"COLOR_THEME" => "c4",
		"COMPONENT_TEMPLATE" => ".default",
		"EVENT_TYPE" => "ALX_FEEDBACK_FORM",
		"FB_TEXT_NAME" => "Ваше сообщение",
		"FB_TEXT_SOURCE" => "PREVIEW_TEXT",
		"FORM_ID" => "1",
		"IBLOCK_ID" => "50",
		"IBLOCK_TYPE" => "altasib_feedback",
		"INPUT_APPEARENCE" => array(0=>"DEFAULT",),
		"JQUERY_EN" => "jquery",
		"LINK_SEND_MORE_TEXT" => "Отправить ещё одно сообщение",
		"LOCAL_REDIRECT_ENABLE" => "N",
		"MASKED_INPUT_PHONE" => array(),
		"MESSAGE_OK" => "Ваше сообщение было успешно отправлено",
		"NAME_ELEMENT" => "ALX_DATE",
		"NOT_CAPTCHA_AUTH" => "Y",
		"PROPERTY_FIELDS" => array(0=>"REGION",1=>"PHONE",2=>"FIO",3=>"EMAIL",4=>"FEEDBACK_TEXT",),
		"PROPERTY_FIELDS_REQUIRED" => array(0=>"REGION",1=>"PHONE",2=>"FIO",3=>"EMAIL",),
		"PROPS_AUTOCOMPLETE_EMAIL" => array(0=>"EMAIL",),
		"PROPS_AUTOCOMPLETE_NAME" => array(0=>"FIO",),
		"PROPS_AUTOCOMPLETE_PERSONAL_PHONE" => array(),
		"PROPS_AUTOCOMPLETE_VETO" => "N",
		"SECTION_FIELDS_ENABLE" => "N",
		"SECTION_MAIL_ALL" => "opt@megre.ru",
		"SEND_IMMEDIATE" => "Y",
		"SEND_MAIL" => "N",
		"SHOW_LINK_TO_SEND_MORE" => "N",
		"SHOW_MESSAGE_LINK" => "Y",
		"USERMAIL_FROM" => "N",
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "1",
		"USER_CONSENT_INPUT_LABEL" => "",
		"USER_CONSENT_IS_CHECKED" => "N",
		"USER_CONSENT_IS_LOADED" => "N",
		"USE_CAPTCHA" => "Y",
		"WIDTH_FORM" => "50%"
	)
);?>
<p style="text-align: center;">
 <img width="858px" alt="DSC_5115.JPG" src="/upload/medialibrary/d0a/DSC_5134.jpg">
</p>
    <? /*Вы можете оставить свой запрос по телефону, или направить свои контактные по электронной почте.<br>
<br>
 Отдел оптовых продаж:<br>
 +7-913-915-02-70<br>
 +7 (383) 363-86-51<br>
 Электронная почта: <a href="mailto:sales@megre.ru">sales@megre.ru</a><br>
    <p>
        После регистрации Вам вышлют специальный прайс.<br>
    </p>
    <h2><br>
    </h2>
    <h2>УСЛОВИЯ ОПТОВОЙ ЗАКУПКИ</h2>
    <ul>
        <li>разовый объем заказа более 20 тысяч рублей</li>
        <li>формируется как полными коробками, так и поштучно</li>
        <li>отправка товаров осуществляется почтой, транспортной компанией, курьерской службой или через дилера компании
            в Вашем регионе
        </li>
    </ul>
    <h2><br>
    </h2>
    <h2>ЧТО МЫ МОЖЕМ ВАМ ГАРАНТИРОВАТЬ?</h2>
    <ul>
        <li>100% оригинальную продукцию «из первых рук», защищенную от подделок</li>
        <li>марку с безупречной репутацией, которой доверяют те, кто пробовал ее хоть однажды</li>
        <li>продукцию чистой и доброй энергетики, созданную вручную только из природных материалов, вдали от больших
            городов
        </li>
        <li>большую скидку от общего прайса в зависимости от объема заказа</li>
        <li>несколько вариантов решений по получению заказа, из которых вместе сможем подобрать наилучший для Вас</li>
        <li>консультации специалистов компании по продукции, которые помогут подобрать необходимый ассортимент, с учетом
            Ваших задач и региональных особенностей
        </li>
    </ul>
    <h2 align="center"></h2>
*/ ?><? require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php'); ?>