<?php
/*
 * Изменено: 27 января 2022, четверг
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

/**
 * @var $APPLICATION
 * @var $USER
 * @var $pageCode - from header.php
 */

use Alexkova\Market\Core;

$BXReady = \Alexkova\Market\Core::getInstance();

// LeftMenu
global $arLeftMenu;
if (strlen($arLeftMenu["TYPE"])) {
    switch ($arLeftMenu["TYPE"]) {
        case "with_catalog":
            $BXReady->setAreaType('left_menu_type', 'v3');
            break;
        case "only_catalog":
            $BXReady->setAreaType('left_menu_type', 'v2');
            break;
        case "without_catalog":
            $BXReady->setAreaType('left_menu_type', 'v1');
            break;
    }
}
if ($BXReady->getArea('left_menu_type')) {
    include($BXReady->getAreaPath('left_menu_type'));
}
?>
    <div class="subscription__form__vertical mb-5">
        <?php if ($APPLICATION->GetCurPage() !== '/personal/subscribe/'): ?>
            <?php $APPLICATION->IncludeComponent(
                "bitrix:subscribe.form",
                'native',
                [
                    "COMPONENT_TEMPLATE" => 'native',
                    "USE_PERSONALIZATION" => "Y",
                    "SHOW_HIDDEN" => "N",
                    "PAGE" => "/personal/subscribe/",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "3600000",
                    "SHOW_RUBRICS" => "N",
                ],
                false,
                [
                    "ACTIVE_COMPONENT" => "Y",
                ]
            ) ?>
        <?php endif ?>
    </div>
<?php
Alexkova\Market\Core::getInstance()->showBannerPlace("LEFT");
