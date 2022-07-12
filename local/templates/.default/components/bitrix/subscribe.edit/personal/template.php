<?php
/*
 * Изменено: 24 January 2022, Monday
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

/**
 * @var $APPLICATION
 * @var $USER
 * @var $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

foreach ($arResult["MESSAGE"] as $itemID => $itemValue)
    echo ShowMessage(["MESSAGE" => $itemValue, "TYPE" => "OK"]);

foreach ($arResult["ERROR"] as $itemID => $itemValue)
    echo ShowMessage(["MESSAGE" => $itemValue, "TYPE" => "ERROR"]);
?>

<?php if ($arResult["ALLOW_ANONYMOUS"] == "N" && !$USER->IsAuthorized()): ?>
    <?= ShowMessage(["MESSAGE" => GetMessage("CT_BSE_AUTH_ERR"), "TYPE" => "ERROR"]); ?>
<?php else: ?>

    <?php if (!$USER->IsAuthorized() && $_POST['SENDER_SUBSCRIBE_EMAIL'] && $arResult['NEW_SUBSCRIBE']): ?>
        E-mail <b><?= $_POST['SENDER_SUBSCRIBE_EMAIL'] ?></b> успешно подписан на новости.
    <?php endif ?>

    <?php if (!$USER->IsAuthorized() && $_POST['SENDER_SUBSCRIBE_EMAIL'] && $arResult['SUBSCRIPTION_MESSAGE']): ?>
        <?= $arResult['SUBSCRIPTION_MESSAGE'] ?>
    <?php endif ?>

    <?php if ($arResult['SUBSCRIPTIONS']): ?>
        <form name="unsubscribe" action="" method="post">
            <ul>
                <?php foreach ($arResult['SUBSCRIPTIONS'] as $id => $subscription): ?>
                    <li>
                        <input type="checkbox" id="EMAIL_<?php echo $id ?>" name="UNSUBSCRIBE_SUBSCRIPTIONS[]"
                               value="<?= $subscription["ID"] ?>" checked/><label
                                for="EMAIL_<?php echo $id ?>"><b><?php echo $subscription['EMAIL'] ?></b></label>
                    </li>
                <?php endforeach ?>
            </ul>
            <input type="submit" class="bxr-color-button" value="Отписаться от рассылок">
        </form>
    <?php /*else: ?>
        Подписки отсутствуют
    <? */
    endif ?>

    <div class="subscription__form__cabinet my-5">
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
    </div>
<?php endif; ?>
