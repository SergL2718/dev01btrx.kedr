<?php
/**
 * Copyright (c) 2019 Denis Artamonov
 * Created: 3/3/19 5:42 PM
 * Author: Denis Artamonov
 * Email: artamonov.ceo@gmail.com
 */

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\LoaderException;
use CEvent;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class RegistrationForm extends CBitrixComponent implements Controllerable
{
    private $table = 'b_registration_code';
    private $_request;

    public function executeComponent()
    {
        $this->setFrameMode(false);
        $this->social();
        $this->includeComponentTemplate();
    }

    private function social()
    {
        $arResult = &$this->arResult;

        $arResult["AUTH_SERVICES"] = false;
        $arResult["CURRENT_SERVICE"] = false;
        $arResult["FOR_INTRANET"] = false;
        if (IsModuleInstalled("intranet") || IsModuleInstalled("rest"))
            $arResult["FOR_INTRANET"] = true;

        if (!$GLOBALS['USER']->IsAuthorized() && Bitrix\Main\Loader::IncludeModule('socialservices')) {
            $oAuthManager = new CSocServAuthManager();
            $arServices = $oAuthManager->GetActiveAuthServices(array(
                'BACKURL' => $arResult['~BACKURL'],
                'FOR_INTRANET' => $arResult['FOR_INTRANET'],
            ));

            if (!empty($arServices)) {
                $arResult["AUTH_SERVICES"] = $arServices;
                if (isset($_REQUEST["auth_service_id"]) && $_REQUEST["auth_service_id"] <> '' && isset($arResult["AUTH_SERVICES"][$_REQUEST["auth_service_id"]])) {
                    $arResult["CURRENT_SERVICE"] = $_REQUEST["auth_service_id"];
                    if (isset($_REQUEST["auth_service_error"]) && $_REQUEST["auth_service_error"] <> '') {
                        $arResult['ERROR_MESSAGE'] = $oAuthManager->GetError($arResult["CURRENT_SERVICE"], $_REQUEST["auth_service_error"]);
                    } elseif (!$oAuthManager->Authorize($_REQUEST["auth_service_id"])) {
                        $ex = $GLOBALS['APPLICATION']->GetException();
                        if ($ex)
                            $arResult['ERROR_MESSAGE'] = $ex->GetString();
                    }
                }
            }
        }
    }

    public function configureActions()
    {
        return [
            'registration' => ['prefilters' => []],
            'registerByEmailAndPassword' => ['prefilters' => []],
        ];
    }

    /**
     * @param $request
     * @return array
     */
    public function registrationAction($request)
    {
        global $USER;

        $error = [];

        if (empty($request['name'])) {
            $error[] = 'Не указано Имя';
        }
        if (strlen($request['name']) < 2) {
            $error[] = 'Имя слишком короткое';
        }
        if (empty($request['lastName'])) {
            $error[] = 'Не указана Фамилия';
        }
        if (strlen($request['lastName']) < 2) {
            $error[] = 'Фамилия слишком короткая';
        }
        if (empty($request['email'])) {
            $error[] = 'Не указан E-mail';
        }
        if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
            $error[] = 'E-mail не валидный';
        }
        if (empty($request['login'])) {
            $error[] = 'Не указан Логин';
        }
        if (empty($request['password'])) {
            $error[] = 'Не указан Пароль';
        }
        if (empty($request['confirmPassword'])) {
            $error[] = 'Не указано Подтверждение пароля';
        }
        if (!empty($request['password']) && !empty($request['confirmPassword']) && $request['password'] !== $request['confirmPassword']) {
            $error[] = 'Пароль и его подтверждение не совпадают';
        }
        if (count($error) > 0) {
            return [
                'error' => $error
            ];
        }
        if (\CUser::GetList($by = '', $order = '', ['EMAIL' => $request['email']])->fetch()) {
            return [
                'error' => 'E-mail уже зарегистрирован в базе',
                'type' => 'USER_FOUND',
            ];
        }
        if (\CUser::GetList($by = '', $order = '', ['LOGIN' => $request['login']])->fetch()) {
            return [
                'error' => 'Логин уже зарегистрирован в базе',
                'type' => 'USER_FOUND',
            ];
        }

        $user = new \CUser;
        $groups = COption::GetOptionString('main', 'new_user_registration_def_group', '');

        $result = $user->Add([
            'NAME' => $request['name'],
            'LAST_NAME' => $request['lastName'],
            'EMAIL' => $request['email'],
            'LOGIN' => $request['login'],
            'ACTIVE' => 'Y',
            'GROUP_ID' => !empty($groups) ? explode(',', $groups) : [],
            'PASSWORD' => $request['password'],
            'CONFIRM_PASSWORD' => $request['confirmPassword'],
            'LANGUAGE_ID' => LANGUAGE_ID,
            'LID' => SITE_ID,
            'USER_IP' => $_SERVER['REMOTE_ADDR'],
            'USER_HOST' => @gethostbyaddr($_SERVER['REMOTE_ADDR']),
        ]);

        if (intval($result) > 0) {

            $request ['userId'] = $result;

            $this->sendEmailAboutRegistration($request);

            $USER->Login($request['login'], $request['password'], 'Y', 'Y');

            //$this->sendEmailWithCoupon($request);

            if (COption::GetOptionString('main', 'event_log_register', 'N') === 'Y') {
                CEventLog::Log('SECURITY', 'USER_REGISTER', 'main', $result);
            }

            return [
                'message' => 'Вы успешно зарегистрировались на сайте.<br>Перейти в <a href="/catalog/">каталог продукции</a>.'
            ];

        }

        if ($user->LAST_ERROR) {
            if (COption::GetOptionString('main', 'event_log_register_fail', 'N') === 'Y') {
                CEventLog::Log('SECURITY', 'USER_REGISTER_FAIL', 'main', $request['login'], $user->LAST_ERROR);
            }

            return [
                'error' => $user->LAST_ERROR
            ];
        }

        return [
            'error' => true,
        ];
    }

    public function registerByEmailAndPasswordAction($request)
    {
        global $USER;

        $error = [];
        $email =& $request['email'];
        $emailConfirm =& $request['emailConfirm'];
        $password =& $request['password'];
        $passwordConfirm =& $request['passwordConfirm'];

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error[] = 'Неверно введён E-mail';
        } else if ($email !== $emailConfirm) {
            $error[] = 'E-mail не совпадают';
        }
        if (empty($password)) {
            $error[] = 'Неверно введён Пароль';
        } else if ($password !== $passwordConfirm) {
            $error[] = 'Пароли не совпадают';
        }
        if (count($error) > 0) {
            $error[] = 'Проверьте правильность написания';
            return [
                'error' => true,
                'message' => $error
            ];
        }

        if (\CUser::GetList($by = 'EMAIL', $order = 'DESC', ['EMAIL' => $email], ['FIELDS' => ['ID'], 'NAV_PARAMS' => ['nTopCount' => 1]])->fetch()) {
            return [
                'error' => true,
                'message' => 'E-mail уже зарегистрирован в базе',
            ];
        }

        $user = new \CUser;
        $groups = COption::GetOptionString('main', 'new_user_registration_def_group', '');

        $result = $user->Add([
            'EMAIL' => $email,
            'LOGIN' => $email,
            'ACTIVE' => 'Y',
            'GROUP_ID' => !empty($groups) ? explode(',', $groups) : [],
            'PASSWORD' => $password,
            'CONFIRM_PASSWORD' => $passwordConfirm,
            'LANGUAGE_ID' => LANGUAGE_ID,
            'LID' => SITE_ID,
            'USER_IP' => $_SERVER['REMOTE_ADDR'],
            'USER_HOST' => @gethostbyaddr($_SERVER['REMOTE_ADDR']),
        ]);

        if (intval($result) > 0) {

            $request ['userId'] = $result;
            $request['login'] = $email;

            $this->sendEmailAboutRegistration($request);

            $USER->Login($email, $passwordConfirm, 'Y', 'Y');

            //$coupon = $this->sendEmailWithCoupon($request);

            if (COption::GetOptionString('main', 'event_log_register', 'N') === 'Y') {
                CEventLog::Log('SECURITY', 'USER_REGISTER', 'main', $result);
            }
            return [
                'success' => true,
                //'coupon' => $coupon
            ];
        }

        if ($user->LAST_ERROR) {
            if (COption::GetOptionString('main', 'event_log_register_fail', 'N') === 'Y') {
                CEventLog::Log('SECURITY', 'USER_REGISTER_FAIL', 'main', $email, $user->LAST_ERROR);
            }

            return [
                'error' => true,
                'message' => $user->LAST_ERROR
            ];
        }

        return [
            'error' => true,
        ];
    }

    /**
     * Оправка письма о регистрации
     * @param $request
     * @return bool
     */
    private function sendEmailAboutRegistration($request)
    {
        return \Bitrix\Main\Mail\Event::sendImmediate([
            'EVENT_NAME' => 'AUTHORIZE',
            'MESSAGE_ID' => 147,
            'LANGUAGE_ID' => LANGUAGE_ID,
            'LID' => SITE_ID,
            'C_FIELDS' => [
                'NAME' => $request['name'] ? $request['name'] : '',
                'LAST_NAME' => $request['lastName'] ? $request['lastName'] : '',
                'USER_EMAIL' => $request['email'],
                'LOGIN' => $request['login'],
                'PASSWORD' => $request['password'],
            ]
        ]);
    }

    /**
     * @param $request
     * @return false|string
     * @throws ArgumentTypeException
     * @throws LoaderException
     * @deprecated since 2020-09-04
     * @link https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/5107/
     */
    private function sendEmailWithCoupon($request)
    {
        return false;

        $result = false;
        // Отправляем приветственный промокод
        if ($request['email']) {
            \Bitrix\Main\Loader::IncludeModule('sale');
            $today = new \Bitrix\Main\Type\DateTime;
            $coupon = \Bitrix\Sale\Internals\DiscountCouponTable::generateCoupon(true);
            $fields = [
                'COUPON' => $coupon,
                'DISCOUNT_ID' => 81,
                'MAX_USE' => 1,
                'USER_ID' => $request ['userId'],
                'ACTIVE_FROM' => clone($today),
                'ACTIVE_TO' => $today->add('+1 MONTH'),
                'TYPE' => \Bitrix\Sale\Internals\DiscountCouponTable::TYPE_ONE_ORDER,
            ];

            $res = \Bitrix\Sale\Internals\DiscountCouponTable::add($fields);

            if ($res->isSuccess()) {
                \Bitrix\Main\Mail\Event::sendImmediate([
                    'EVENT_NAME' => 'PROMO_CODE',
                    'MESSAGE_ID' => 146,
                    'LANGUAGE_ID' => LANGUAGE_ID,
                    'LID' => SITE_ID,
                    'C_FIELDS' => [
                        'PROMO_CODE' => $coupon,
                        'EMAIL' => $request['email'],
                        'NAME' => $request['name'] ? $request['name'] : '',
                        'LAST_NAME' => $request['lastName'] ? $request['lastName'] : '',
                    ]
                ]);

                $result = $coupon;
            }
        }
        return $result;
    }
}
