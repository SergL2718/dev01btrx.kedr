<?php
    /*
     * Изменено: 29 декабря 2021, среда
     * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
     * copyright (c) 2022
     */

    /**
     * @global CMain $APPLICATION
     */

    if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
?>
</main>
<footer class="footer">
    <div class="footer-content-wrapper">
        <div class="container">
            <div class="footer-contacts-wrapper">
                <h3 class="mb-4">КОНТАКТЫ</h3>
                <ul>
                    <li>
                        <a href="tel:88003500270" target="_blank">8-800-350-0270</a>
                    </li>
                    <li>
                        <a href="mailto:<?php $APPLICATION->IncludeComponent(
                            'bitrix:main.include',
                            "",
                            [
                                'AREA_FILE_SHOW'       => 'file',
                                'AREA_FILE_SUFFIX'     => 'inc',
                                'COMPOSITE_FRAME_MODE' => 'A',
                                'COMPOSITE_FRAME_TYPE' => 'AUTO',
                                'EDIT_TEMPLATE'        => '',
                                'PATH'                 => SITE_TEMPLATE_PATH . '/includes/email-admin-megre.php',
                            ]) ?>" target="_blank">
                            <?php $APPLICATION->IncludeComponent(
                                'bitrix:main.include',
                                "",
                                [
                                    'AREA_FILE_SHOW'       => 'file',
                                    'AREA_FILE_SUFFIX'     => 'inc',
                                    'COMPOSITE_FRAME_MODE' => 'A',
                                    'COMPOSITE_FRAME_TYPE' => 'AUTO',
                                    'EDIT_TEMPLATE'        => '',
                                    'PATH'                 => SITE_TEMPLATE_PATH . '/includes/email-admin-megre.php',
                                ]) ?>
                        </a>
                    </li>
                    <li>
                        <a href="https://yandex.ru/maps/-/CCUmRZTPHD" target="_blank">Россия, г. Новосибирск, ул. Коммунистическая 2, оф. 516</a>
                    </li>
                </ul>
                <ul class="mt-4">
                    <li class="mb-2"><b>Розничные заказы в другие страны:</b></li>
                    <li><a href="mailto:hello@megrellc.com" target="_blank">hello@megrellc.com</a></li>
                    <li><a href="/~44yEj" target="_blank">megrellc.com</a></li>
                </ul>
                <ul class="mt-4">
                    <li class="mb-2"><b>Оптовые заказы:</b></li>
                    <li><a href="tel:79139150270">+7-913-915-02-70</a></li>
                    <li><a href="tel:73833638651">+7-383-363-86-51</a></li>
                    <li><a href="mailto:opt@megre.ru" target="_blank">opt@megre.ru</a></li>
                </ul>
                <ul class="mt-4">
                    <li class="mb-2"><b>ООО «Звенящие Кедры»</b></li>
                    <li>ИНН 5404428665</li>
                    <li>КПП 540401001</li>
                    <li>ОГРН 1115476002707</li>
                    <li>Россия, 630121, г. Новосибирск, ул.Невельского, д.69, кв. 91</li>
                </ul>
            </div>
            <div class="footer-main-wrapper">
                <div class="footer-links-wrapper">
                    <div class="footer-about-wrapper">
                        <h3 class="mb-4">ЗВЕНЯЩИЕ КЕДРЫ</h3>
                        <?php $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "footer.menu",
                            [
                                "ALLOW_MULTI_SELECT"    => "N",
                                "CHILD_MENU_TYPE"       => "",
                                "COMPOSITE_FRAME_MODE"  => "A",
                                "COMPOSITE_FRAME_TYPE"  => "AUTO",
                                "DELAY"                 => "N",
                                "MAX_LEVEL"             => "1",
                                "MENU_CACHE_GET_VARS"   => [""],
                                "MENU_CACHE_TIME"       => "86400000",
                                "MENU_CACHE_TYPE"       => "A",
                                "MENU_CACHE_USE_GROUPS" => "N",
                                "ROOT_MENU_TYPE"        => "footer_about",
                                "USE_EXT"               => "N",
                            ]
                        ) ?>
                    </div>
                    <div class="footer-catalog-wrapper">
                        <h3 class="mb-4">МАГАЗИН</h3>
                        <?php $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "footer.menu",
                            [
                                "ALLOW_MULTI_SELECT"    => "N",
                                "CHILD_MENU_TYPE"       => "",
                                "COMPOSITE_FRAME_MODE"  => "A",
                                "COMPOSITE_FRAME_TYPE"  => "AUTO",
                                "DELAY"                 => "N",
                                "MAX_LEVEL"             => "1",
                                "MENU_CACHE_GET_VARS"   => [""],
                                "MENU_CACHE_TIME"       => "86400000",
                                "MENU_CACHE_TYPE"       => "A",
                                "MENU_CACHE_USE_GROUPS" => "N",
                                "ROOT_MENU_TYPE"        => "footer_catalog",
                                "USE_EXT"               => "N",
                            ]
                        ) ?>
                    </div>
                    <div class="footer-cooperation-wrapper">
                        <h3 class="mb-4">СОТРУДНИЧЕСТВО</h3>
                        <?php $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "footer.menu",
                            [
                                "ALLOW_MULTI_SELECT"    => "N",
                                "CHILD_MENU_TYPE"       => "",
                                "COMPOSITE_FRAME_MODE"  => "A",
                                "COMPOSITE_FRAME_TYPE"  => "AUTO",
                                "DELAY"                 => "N",
                                "MAX_LEVEL"             => "1",
                                "MENU_CACHE_GET_VARS"   => [""],
                                "MENU_CACHE_TIME"       => "86400000",
                                "MENU_CACHE_TYPE"       => "A",
                                "MENU_CACHE_USE_GROUPS" => "N",
                                "ROOT_MENU_TYPE"        => "footer_cooperation",
                                "USE_EXT"               => "N",
                            ]
                        ) ?>
                        <ul class="mt-4">
                            <li class="mb-2"><b>Полезные ссылки:</b></li>
                            <li><a href="/~TeUyx" target="_blank">prirodapteka.ru</a></li>
                            <li><a href="/~4vGeK" target="_blank">vmegre.com</a></li>
                            <li><a href="/~1e7Ga" target="_blank">anastasia.ru</a></li>
                            <li><a href="/~44yEj" target="_blank">megrellc.com</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-social-wrapper mt-2">
                    <div class="footer-social-links-wrapper">
                        <?php $APPLICATION->IncludeComponent('native:static.block', 'footer.social.links') ?>
                    </div>
                    <div class="footer-logo-wrapper">
                        <?php $APPLICATION->IncludeComponent('native:static.block', 'footer.logo') ?>
                    </div>
                </div>
                <div class="footer-copyright-wrapper mt-2">
                    <b>Звенящие кедры России. &copy; <?= date('Y') ?>. Все права защищены.</b>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-content-wrapper footer-content-wrapper_mobile">
        <div class="container">
            <div class="footer-content-top">
                <h3 class="mb-4">КОНТАКТЫ</h3>
                <div class="footer-content-container">
                    <ul>
                        <li>
                            <a href="tel:88003500270" target="_blank">8-800-350-0270</a>
                            <a href="mailto:admin@megre.ru" target="_blank">admin@megre.ru</a>
                        </li>
                        <li>
                            <a href="mailto:<?php $APPLICATION->IncludeComponent(
                                'bitrix:main.include',
                                "",
                                [
                                    'AREA_FILE_SHOW'       => 'file',
                                    'AREA_FILE_SUFFIX'     => 'inc',
                                    'COMPOSITE_FRAME_MODE' => 'A',
                                    'COMPOSITE_FRAME_TYPE' => 'AUTO',
                                    'EDIT_TEMPLATE'        => '',
                                    'PATH'                 => SITE_TEMPLATE_PATH . '/includes/email-admin-megre.php',
                                ]) ?>" target="_blank">
                                <?php $APPLICATION->IncludeComponent(
                                    'bitrix:main.include',
                                    "",
                                    [
                                        'AREA_FILE_SHOW'       => 'file',
                                        'AREA_FILE_SUFFIX'     => 'inc',
                                        'COMPOSITE_FRAME_MODE' => 'A',
                                        'COMPOSITE_FRAME_TYPE' => 'AUTO',
                                        'EDIT_TEMPLATE'        => '',
                                        'PATH'                 => SITE_TEMPLATE_PATH . '/includes/email-admin-megre.php',
                                    ]) ?>
                            </a>
                        </li>
                        <li>
                            <a href="https://yandex.ru/maps/-/CCUmRZTPHD" target="_blank">Россия, г. Новосибирск, ул. Коммунистическая 2, оф. 516</a>
                        </li>
                    </ul>
                    <?php $APPLICATION->IncludeComponent('native:static.block', 'footer.social.links') ?>
                </div>
                <div class="footer-content-container">
                    <ul>
                        <li class="mb-2"><b>ООО «Звенящие Кедры»</b></li>
                        <li>ИНН 5404428665</li>
                        <li>КПП 540401001</li>
                        <li>ОГРН 1115476002707</li>
                        <li>Россия, 630121, г. Новосибирск, ул.Невельского, д.69, кв. 91</li>
                    </ul>
                    <?php $APPLICATION->IncludeComponent('native:static.block', 'footer.logo') ?>
                </div>
                <div class="footer-content-container">
                    <ul>
                        <li class="mb-2"><b>Оптовые заказы:</b></li>
                        <li><a href="tel:79139150270">+7-913-915-02-70</a></li>
                        <li><a href="tel:73833638651">+7-383-363-86-51</a></li>
                        <li><a href="mailto:opt@megre.ru" target="_blank">opt@megre.ru</a></li>
                    </ul>
                    <ul>
                        <li class="mb-2"><b>Розничные заказы в другие страны:</b></li>
                        <li><a href="mailto:hello@megrellc.com" target="_blank">hello@megrellc.com</a></li>
                        <li><a href="/~44yEj" target="_blank">megrellc.com</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-about-wrapper">
                <h3 class="mb-4">ЗВЕНЯЩИЕ КЕДРЫ</h3>
                <?php $APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "footer.menu",
                    [
                        "ALLOW_MULTI_SELECT"    => "N",
                        "CHILD_MENU_TYPE"       => "",
                        "COMPOSITE_FRAME_MODE"  => "A",
                        "COMPOSITE_FRAME_TYPE"  => "AUTO",
                        "DELAY"                 => "N",
                        "MAX_LEVEL"             => "1",
                        "MENU_CACHE_GET_VARS"   => [""],
                        "MENU_CACHE_TIME"       => "86400000",
                        "MENU_CACHE_TYPE"       => "A",
                        "MENU_CACHE_USE_GROUPS" => "N",
                        "ROOT_MENU_TYPE"        => "footer_about",
                        "USE_EXT"               => "N",
                    ]
                ) ?>
            </div>
            <div class="footer-terms-container"></div>
            <div class="footer-content-container">
                <div class="footer-cooperation-wrapper">
                    <h3 class="mb-4">СОТРУДНИЧЕСТВО</h3>
                    <?php $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "footer.menu",
                        [
                            "ALLOW_MULTI_SELECT"    => "N",
                            "CHILD_MENU_TYPE"       => "",
                            "COMPOSITE_FRAME_MODE"  => "A",
                            "COMPOSITE_FRAME_TYPE"  => "AUTO",
                            "DELAY"                 => "N",
                            "MAX_LEVEL"             => "1",
                            "MENU_CACHE_GET_VARS"   => [""],
                            "MENU_CACHE_TIME"       => "86400000",
                            "MENU_CACHE_TYPE"       => "A",
                            "MENU_CACHE_USE_GROUPS" => "N",
                            "ROOT_MENU_TYPE"        => "footer_cooperation",
                            "USE_EXT"               => "N",
                        ]
                    ) ?>
                    <ul class="mt-4">
                        <li class="mb-2"><b>Полезные ссылки:</b></li>
                        <li><a href="/~TeUyx" target="_blank">prirodapteka.ru</a></li>
                        <li><a href="/~4vGeK" target="_blank">vmegre.com</a></li>
                        <li><a href="/~1e7Ga" target="_blank">anastasia.ru</a></li>
                        <li><a href="/~44yEj" target="_blank">megrellc.com</a></li>
                    </ul>
                </div>
                <div class="footer-catalog-wrapper footer-shops-wrapper">
                    <h3 class="mb-4">МАГАЗИН</h3>
                    <?php $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "footer.menu",
                        [
                            "ALLOW_MULTI_SELECT"    => "N",
                            "CHILD_MENU_TYPE"       => "",
                            "COMPOSITE_FRAME_MODE"  => "A",
                            "COMPOSITE_FRAME_TYPE"  => "AUTO",
                            "DELAY"                 => "N",
                            "MAX_LEVEL"             => "1",
                            "MENU_CACHE_GET_VARS"   => [""],
                            "MENU_CACHE_TIME"       => "86400000",
                            "MENU_CACHE_TYPE"       => "A",
                            "MENU_CACHE_USE_GROUPS" => "N",
                            "ROOT_MENU_TYPE"        => "footer_catalog",
                            "USE_EXT"               => "N",
                        ]
                    ) ?>
                </div>
            </div>
            <div class="footer-copyright-wrapper">
                <b>Звенящие кедры России. &copy; <?= date('Y') ?>.<br/> Все права защищены.</b>
            </div>
        </div>
    </div>
</footer>

<?php
if (!$USER->IsAuthorized()){
?>
<div class="modal" id="modal-enter">
    <div class="modal-inner">
        <div class="modal-container modal-container_m">
            <div class="modal-header"><span>чтобы продолжить, войдите или зарегистрируйтесь</span>
                <div class="modal-close" data-modal-close>
                    <div class="icon icon-close"></div>
                </div>
            </div>
            <div class="modal-body">
                <div class="input">
                    <input placeholder="Введите номер телефона или e-mail" name="AUTH_START_IN">
                </div>
            </div>
            <div class="modal-footer">
                <div class="button button_primary" data-modal="modal-code" onClick="getAuthCode();">Получить код</div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="modal-code">
    <div class="modal-inner">
        <div class="modal-container modal-container_m">
            <div class="modal-header"><span>Введите код ПОДТВЕРЖДЕНИЯ</span>
                <div class="modal-close" data-modal-close>
                    <div class="icon icon-close"></div>
                </div>
            </div>
            <div class="modal-body">
                <div class="modal-text">Мы отправили код подтверждения на</div>
                <div class="error-modal-text"></div>
                <div class="input">
                    <input placeholder="Введите код из SMS или из письма" name="AUTH_CODE">
                </div>
                <div class="modal-recode">
                    <div class="modal-recode__text">Получить новый код можно через <span class="count_down">60 сек</span></div>
                    <div class="modal-recode__send">Не пришёл код?</div>
                    <div class="modal-recode__help">
                        <div class="modal-recode__help-container">
                            <div class="modal-recode__help-close">Назад
                                <div class="icon icon-close"></div>
                            </div>
                            <div class="modal-recode__help-header">Не пришёл код?</div>
                            <div class="modal-recode__help-body">Проверьте, что:<br/><br/>
                                <ul>
                                    <li>письмо не попало в папку спам;</li>
                                    <li>вы ввели правильный адрес электронной почты.</li>
                                </ul>
                                <br/>Иногда письмо приходит не сразу. Если письма нет 20 минут, попробуйте получить код
                                еще раз.<br/><br/>
                                Можно <span class="link" data-modal="modal-enter">войти по номеру телефона</span> или
                                обратиться за помощью в чат поддержки / по телефону 8-800-350-0270
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="button button_primary" onClick="authCode();">Продолжить</div>
            </div>
        </div>
    </div>
</div>
    <script>
        var intervalID = "";
        var countNum = 60;
        function getAuthCode(){
            $('#renew_code').remove();
            let AUTH_START_IN = $('input[name="AUTH_START_IN"]').val();
            if(AUTH_START_IN){
                if(intervalID)clearInterval(intervalID);
                //let countNum = 60;
                $.ajax({
                    type: "POST",
                    url: "/local/ajax/getCode.php",
                    data: { AUTH_START_IN: AUTH_START_IN }
                }).done(function( msg ) {

                    intervalID = setInterval(function(){
                        countNum = countNum - 1;
                        let countStr = countNum + " сек";
                        if(countNum>=0) {
                            $('.count_down').html(countStr);
                        }
                        else{
                            countNum = 60;
                            clearInterval(intervalID);
                            if(!$('#renew_code').length)$('#modal-code').find('.modal-footer .button_primary').before('<div class="btn" id="renew_code" onClick="getAuthCode();">Прислать новый код</div>')
                        }
                    }, 1000);
                    $('#modal-code').find('.modal-text').html( msg );
                    $('.error-modal-text').html("");
                });
            }
            else{

                return false;
                // error
            }
        }
        function authCode(){
            let AUTH_START_IN = $('input[name="AUTH_START_IN"]').val();
            let AUTH_CODE = $('input[name="AUTH_CODE"]').val();
            if(AUTH_START_IN && AUTH_CODE){
                $.ajax({
                    type: "POST",
                    url: "/local/ajax/authCode.php",
                    data: { AUTH_START_IN: AUTH_START_IN,AUTH_CODE: AUTH_CODE }
                }).done(function( msg ) {
                    if(msg == "success")document.location.reload();
                    else{
                        // error block
                        let text = "";
                        if(msg == "error auth")text = "Ошибка авторизации";
                        if(msg == "error expired")text = "Время кода истекло, запросите новый";
                        if(msg == "error no code")text = "неверный код";
                        if(msg == "error data")text = "Ошибка";
                        $('.error-modal-text').html(text);
                    }
                    //$('#modal-code').find('.modal-text').html( msg );
                });
            }
        }
    </script>
<?php
}
?>

</body>
</html>
