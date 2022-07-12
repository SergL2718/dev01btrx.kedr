<? require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
/*
 * Изменено: 29 ноября 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

$APPLICATION->SetTitle("Клуб путешественников Звенящие Кедры");
?>

    <style>
        .tours {
            text-align: center;
            margin: 50px 0;
        }

        .tours a {
            display: inline-block;
            background: #fff;
            padding: 20px 70px 17px;
            color: #8bc34a;
            border: 2px solid #8bc34a;
            text-transform: uppercase;
            font-weight: bold;
        }
    </style>

    <img src="/upload/medialibrary/b4a/19917.jpg" alt="" style="width: 100%;margin-bottom: 25px;">

    <p>
        Клуб путешественников – это некоммерческий проект, который был создан для того, чтобы встретились и
        познакомились читатели книг Владимира Мегре и просто осознанные люди и сторонники экологичного образа жизни. Это
        наша давняя мечта – путешествия со смыслом, в компании тех, кто с тобой на одной волне, с кем и молчать в
        удовольствие. Путешествия – ведь не просто маленькая жизнь, это стиль всей жизни, это то, что объединяет близких
        по духу людей, меняет наше сознание и отношение к планете.
        <br><br>
        Идея создать такой клуб давно витала в воздухе, но появился этот проект только в 2018 году, и с первых же дней
        обрёл поддержку множества сторонников.
        <br><br>
    </p>

    <p>
        <b>ПУТЕШЕСТВИЯ ПО ВСЕЙ ЗЕМЛЕ</b><br><br>
        В горы Армении или в уединённые уголки Байкала, на Камчатку или в Карелию? Это тот случай, когда хоть на край
        света, не важно, куда, важно – с кем. В планах Клуба организовать огромное количество туров, разных по
        продолжительности и направлению, программам и наполнению. Это и поездки по популярным туристическим
        направлениям, и в потаённые уголки нашей страны и не только, о которых, возможно вы и не слышали.
        Отдельным направлением станут туры по поселениям Родовых поместий, их только в России на данный момент более
        370, а также путешествия к дольменам, описанным в книгах Владимира Мегре «Звенящие Кедры России».
    </p>

    <img src="/upload/medialibrary/f96/couple_discover_explore_forest_Favim.com_2712666.jpg" alt=""
         style="max-width: 610px;; margin: 25px auto;display: block;">

    <p>
        <b>КАК ПРИСОЕДИНИТЬСЯ</b><br><br>
        Клуб путешественников – это добровольное объединение единомышленников со всей планеты, которые хотят вместе
        путешествовать. Это клуб, не требующий взносов за членство, виртуальное сообщество, которое проявляется в
        конкретных путешествиях.

        <br><br>
        Как Вы можете стать членом Клуба путешественников?<br>
        1. Просто стать участником любого из <a href="tours">туров</a>.<br>
        2. Стать организатором тура. Организатором может быть туристическое агентство, любая организация или частное
        лицо. Для этого необходимо определиться со сроками, направлением и программой тура и согласовать организационные
        моменты с нашим координатором клуба.
    </p>

    <img src="/upload/medialibrary/490/el_despertar_ii_L_uI0xxu.jpeg" alt=""
         style="max-width: 450px;; margin: 25px auto;display: block;">

<p>
	По всем вопросам и предложениям звоните на нашу бесплатную линию 8-800-350-02-70, пишите на <a
			href="mailto:admin@megre.ru">admin@megre.ru</a> и ждите новостей и анонсов туров.
	Анонсы туров публикуются на сайте и на официальных аккаунтах #ЗвенящиеКедры в социальных сетях.
</p>

<p>
	<a href="https://www.facebook.com/ringingcedarsmegre/" target="_blank">
		<img width="57" alt="facebook" src="/upload/medialibrary/233/face.png" height="55">
	</a>
	<a href="https://www.instagram.com/megre.ru/" target="_blank">
		<img width="57" alt="fot.png" src="/upload/medialibrary/c31/fot.png" height="55">
	</a>
	<a href="https://www.youtube.com/playlist?list=PLBFup2l1YE8anj28g8OEQCYu_YTcwC0WF" target="_blank">
		<img width="57" alt="yout.png" src="/upload/medialibrary/260/yout.png" height="55">
	</a>
	<a href="https://vk.com/ringingcedars" target="_blank">
		<img width="57" alt="vk.png" src="/upload/medialibrary/79a/vk.png" height="55">
	</a>
	<a href="/~511jB" target="_blank">
		<img width="57" alt="teleg.png" src="/upload/medialibrary/f99/teleg.png" height="55">
        </a>
        <a href="https://ok.ru/group/55229491970291" target="_blank">
            <img width="57" alt="teleg.png" src="/bitrix/templates/market_fullscreen/./images/ok.png" height="55">
        </a>
        <br>
    </p>

    <div class="tours">
        <a href="/tourism/tours/">Список доступных туров</a>
    </div>

    <h3>Оставить запрос</h3>
<? $APPLICATION->IncludeComponent(
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
        "FB_TEXT_NAME" => "Комментарий",
        "FB_TEXT_SOURCE" => "PREVIEW_TEXT",
        "FORM_ID" => "59",
        "IBLOCK_ID" => "59",
        "IBLOCK_TYPE" => "altasib_feedback",
        "INPUT_APPEARENCE" => array(0 => "DEFAULT",),
        "JQUERY_EN" => "jquery",
        "LINK_SEND_MORE_TEXT" => "Отправить ещё один запрос",
        "LOCAL_REDIRECT_ENABLE" => "N",
        "MASKED_INPUT_PHONE" => array(0 => "PHONE",),
        "MESSAGE_OK" => "Ваш запрос был успешно отправлен",
        "NAME_ELEMENT" => "ALX_DATE",
        "NOT_CAPTCHA_AUTH" => "Y",
        "PROPERTY_FIELDS" => array(0 => "DATE", 1 => "NUMBER_MEMBERS", 2 => "PHONE", 3 => "FIO", 4 => "EMAIL", 5 => "FEEDBACK_TEXT", 6 => "TYPE", 7 => "TRIP"),
        "PROPERTY_FIELDS_REQUIRED" => array(0 => "DATE", 1 => "NUMBER_MEMBERS", 2 => "PHONE", 3 => "FIO", 4 => "EMAIL", 5 => "TYPE", 6 => "TRIP"),
        "PROPS_AUTOCOMPLETE_EMAIL" => array(0 => "EMAIL",),
        "PROPS_AUTOCOMPLETE_NAME" => array(0 => "FIO",),
        "PROPS_AUTOCOMPLETE_PERSONAL_PHONE" => array(0 => "PHONE",),
        "PROPS_AUTOCOMPLETE_VETO" => "N",
        "SECTION_FIELDS_ENABLE" => "N",
        "SECTION_MAIL_ALL" => "office@megre.ru",
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
); ?>


<? require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php'); ?>
