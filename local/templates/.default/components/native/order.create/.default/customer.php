<?php
/*
 * @updated 15.02.2021, 19:37
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2021, Компания Webco <hello@wbc.cx>
 * @link https://wbc.cx
 */

/**
 * @var $APPLICATION
 * @var $USER
 * @var $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

$stepCode = str_replace('.php', '', basename(__FILE__));
?>

<div class="<?= $arResult['FORM'][$stepCode]['code'] ?>">
    <form action="" name="<?= $arResult['FORM'][$stepCode]['code'] ?>"
          data-type="<?= $arResult['ORDER']['LAST']['CUSTOMER']['TYPE'] ?>">

        <div class="order-customer-type">
            <div class="order-form-field mb-0 mr-5">
                <input type="radio"
                       required
                       name="customerType"
                       id="customer-type-<?= \Native\App\Sale\Person::PHYSICAL_CODE ?>"
                       value="<?= \Native\App\Sale\Person::PHYSICAL_CODE ?>"
                       <?php if ($arResult['ORDER']['LAST']['CUSTOMER']['TYPE'] === \Native\App\Sale\Person::PHYSICAL_CODE): ?>checked<?php endif ?>
                >
                <label for="customer-type-<?= \Native\App\Sale\Person::PHYSICAL_CODE ?>">Физ. лицо</label>
            </div>
            <div class="order-form-field">
                <input type="radio"
                       required
                       name="customerType"
                       id="customer-type-<?= \Native\App\Sale\Person::LEGAL_CODE ?>"
                       value="<?= \Native\App\Sale\Person::LEGAL_CODE ?>"
                       <?php if ($arResult['ORDER']['LAST']['CUSTOMER']['TYPE'] === \Native\App\Sale\Person::LEGAL_CODE): ?>checked<?php endif ?>
                >
                <label for="customer-type-<?= \Native\App\Sale\Person::LEGAL_CODE ?>">Юр. лицо</label>
            </div>
        </div>

        <div class="order-customer-data mt-4">
            <div class="order-form-field">
                <input type="text" required name="lastName" id="lastName" autocomplete="on"
                       value="<?= $arResult['ORDER']['LAST']['CUSTOMER']['LAST_NAME'] ?>">
                <label for="lastName">Фамилия</label>
            </div>
            <div class="order-form-field">
                <input type="text" required name="name" id="name" autocomplete="on"
                       value="<?= $arResult['ORDER']['LAST']['CUSTOMER']['NAME'] ?>">
                <label for="name">Имя</label>
            </div>
            <div class="order-form-field">
                <input type="text" required name="secondName" id="secondName" autocomplete="on"
                       value="<?= $arResult['ORDER']['LAST']['CUSTOMER']['SECOND_NAME'] ?>">
                <label for="secondName">Отчество</label>
            </div>
            <div class="order-form-field">
                <input type="text" required name="phone" id="phone" autocomplete="on"
                       value="<?= $arResult['ORDER']['LAST']['CUSTOMER']['PHONE'] ? $arResult['ORDER']['LAST']['CUSTOMER']['PHONE'] : '+7' ?>">
                <label for="phone">Телефон</label>
            </div>
            <div class="order-form-field">
                <input type="email" required name="email" id="email" autocomplete="on"
                       value="<?= $arResult['ORDER']['LAST']['CUSTOMER']['EMAIL'] ?>">
                <label for="email">E-mail</label>
            </div>
            <? /*if (!$USER->isAuthorized()): ?>
                <div class="order-form-field">
                    <input type="email" required name="confirmEmail" id="confirmEmail"
                           value="<?= $arResult['ORDER']['LAST']['CUSTOMER']['CONFIRM_EMAIL'] ?>">
                    <label for="confirmEmail">Подтвердить E-mail</label>
                </div>
            <? endif*/ ?>

            <fieldset class="order-form-field-customer-type-<?= \Native\App\Sale\Person::LEGAL_CODE ?>">
                <div class="order-form-field">
                    <input type="text" required name="companyName" id="companyName"
                           data-customer-type="<?= \Native\App\Sale\Person::LEGAL_CODE ?>"
                           value="<?= $arResult['ORDER']['LAST']['CUSTOMER']['COMPANY_NAME'] ?>">
                    <label for="companyName">Наименование организации</label>
                </div>
                <div class="order-form-field">
                    <input type="text" required name="companyAddress" id="companyAddress"
                           data-customer-type="<?= \Native\App\Sale\Person::LEGAL_CODE ?>"
                           value="<?= $arResult['ORDER']['LAST']['CUSTOMER']['COMPANY_ADR'] ?>">
                    <label for="companyAddress">Адрес регистрации</label>
                </div>
                <div class="order-form-field">
                    <input type="text" required name="inn" id="inn"
                           data-customer-type="<?= \Native\App\Sale\Person::LEGAL_CODE ?>"
                           value="<?= $arResult['ORDER']['LAST']['CUSTOMER']['INN'] ?>">
                    <label for="inn">ИНН</label>
                </div>
                <div class="order-form-field">
                    <input type="text" required name="kpp" id="kpp"
                           data-customer-type="<?= \Native\App\Sale\Person::LEGAL_CODE ?>"
                           value="<?= $arResult['ORDER']['LAST']['CUSTOMER']['KPP'] ?>">
                    <label for="kpp">КПП</label>
                </div>
            </fieldset>

        </div>
        <div class="order-customer-agreement mt-4">
            <div class="order-form-field">
                <input type="checkbox" required name="agreementProcessingPersonalData"
                       id="agreementProcessingPersonalData" value=""
                       <?php if ($arResult['ORDER']['LAST']['AGREEMENT']['PROCESSING_DATA']): ?>checked<?php endif ?>>
                <label for="agreementProcessingPersonalData">Нажимая Далее вы соглашаетесь на <a href="/terms-of-use/" style="color: inherit; font-weight: 700;">обработку персональных данных</a></label>
            </div>
            <div class="order-form-field">
                <input type="checkbox" name="agreementSubscribe" id="agreementSubscribe" value=""
                       <?php if ($arResult['ORDER']['LAST']['AGREEMENT']['SUBSCRIBE']): ?>checked<?php endif ?>>
                <label for="agreementSubscribe">Я хочу подписаться на рассылку, чтобы получать рекомендации диетолога и информацию об акциях</label>
            </div>
        </div>
    </form>
</div>
