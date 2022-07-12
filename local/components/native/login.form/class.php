<?php
/**
 * Copyright (c) 2019 Артамонов Денис
 * Дата создания: 10/25/19 7:11 PM
 * Email: artamonov.ceo@gmail.com
 */

use Bitrix\Main\Application;
use Bitrix\Main\Engine\Contract\Controllerable;
use CEvent;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class LoginForm extends CBitrixComponent implements Controllerable
{
    private $table = 'b_registration_code';
    private $_request;

    public function executeComponent()
    {
        $this->setFrameMode(false);
        //$this->checkTable();
        $this->checkUser();
        $this->social();
        $this->includeComponentTemplate();
    }

    public function configureActions()
    {
        return [
            'loginByEmail' => ['prefilters' => []],
            'loginByPassword' => ['prefilters' => []],
            'loginByLoginOrEmailAndPassword' => ['prefilters' => []],
        ];
    }

    private function checkUser()
    {
        /*if (isset($_GET['sessionId']) && $_GET['sessionId'] != session_id()) {
            $this->arResult['ERROR']['MESSAGE'] = 'Срок действия текущей сессии истек';
            $this->arResult['ERROR']['TYPE'] = 'LINK';
            return;
        }*/
        if ($_GET['email'] && $_GET['code']) {
            $email = Application::getConnection()->getSqlHelper()->forSql($_GET['email']);
            $code = Application::getConnection()->getSqlHelper()->forSql($_GET['code']);
            if (!Application::getConnection()->query('SELECT code FROM ' . $this->table . ' WHERE code="' . $code . '" AND email="' .mb_strtolower($email) . '" AND expire_at > NOW() ORDER BY expire_at DESC LIMIT 1')->fetch()['code']) {
                $this->arResult['ERROR']['MESSAGE'] = 'Срок действия текущей сессии истек';
                $this->arResult['ERROR']['TYPE'] = 'LINK';
                return;
            }
            $user = \CUser::GetList($by = '', $order = '', ['EMAIL' => $email/*, 'LOGIN' => $email*/])->fetch();
            if ($user) {
                $this->arResult['SUCCESS']['MESSAGE'] = 'Вы успешно авторизованы на сайте.<br>Перейти в <a href="/catalog/">каталог продукции</a>.';
                $this->arResult['SUCCESS']['TYPE'] = 'EXIST';
                if (!$GLOBALS['USER']->IsAuthorized() || $_GET['email'] != $GLOBALS['USER']->GetEmail()) {
                    $GLOBALS['USER']->Authorize($user['ID'], true, true);
                    LocalRedirect('/personal/');
                }
                return;
            } else {
                $this->arResult['ERROR']['MESSAGE'] = 'Авторизация не удалась, пользователь не найден';
                $this->arResult['ERROR']['TYPE'] = 'NOT_EXIST';
                return;
            }
        }
    }

    public function loginByEmailAction($request)
    {
        $this->_request = $request;

        if (empty($this->_request['email'])) {
            return [
                'error' => 'Не указан E-mail'
            ];
        }
        if (!filter_var($this->_request['email'], FILTER_VALIDATE_EMAIL)) {
            return [
                'error' => 'E-mail не валидный'
            ];
        }
        /*if (!(new \Zk\Main\Spam\Protect())->check($_SERVER['REMOTE_ADDR'], $this->_request['email'])) {
            return [
                'success' => false,
                'message' => 'E-mail был отклонен из-за подозрения на Спам'
            ];
        }*/
        if (!\CUser::GetList($by = '', $order = '', ['EMAIL' => $this->_request['email']])->fetch()) {
            return [
                'error' => 'E-mail в базе не найден',
                'type' => 'USER_NOT_FOUND',
            ];
        }
        $this->createLoginLink();
        $this->sendEmail();
        return [
            'message' => 'На e-mail <b>' . $this->_request['email'] . '</b> отправлено письмо.<br>Следуйте указанным в нём инструкциям.'
        ];
    }

    public function loginByPasswordAction($request)
    {
        global $USER;

        $error = [];

        if (empty($request['login'])) {
            $error[] = 'Не указан Логин';
        }
        if (empty($request['password'])) {
            $error[] = 'Не указан Пароль';
        }
        if (count($error) > 0) {
            return [
                'error' => $error
            ];
        }
        if (!\CUser::GetList($by = '', $order = '', ['LOGIN' => $request['login']])->fetch()) {
            return [
                'error' => 'Логин в базе не найден',
                'type' => 'USER_NOT_FOUND',
            ];
        }

        $login = $USER->Login($request['login'], $request['password'], 'Y', 'Y');

        if ($login['TYPE'] === 'ERROR') {
            return [
                'error' => str_replace('.', '', $login['MESSAGE'])
            ];
        }

        return [
            'redirect' => '/personal/',
            'message' => 'Вы успешно авторизованы на сайте.<br>Перейти в <a href="/catalog/">каталог продукции</a>.'
        ];
    }

    public function loginByLoginOrEmailAndPasswordAction($request)
    {
        global $USER;

        $error = [];
        $login =& $request['login'];
        $password =& $request['password'];
        $remember = $request['remember'] === 'true' ? 'Y' : 'N';

        $loginType = mb_strpos($login, '@') ? 'email' : 'login';

        if ($loginType === 'email') {
            if (empty($login) || !filter_var($login, FILTER_VALIDATE_EMAIL)) {
                $error[] = 'Неверно введён E-mail';
            }
        } else if (empty($login)) {
            $error[] = 'Неверно введён Логин';
        }
        if (empty($password)) {
            $error[] = 'Неверно введён Пароль';
        }
        if (count($error) > 0) {
            return [
                'error' => true,
                'message' => $error
            ];
        }

        if ($loginType === 'email') {
            $user = \CUser::GetList($by = '', $order = '', ['=EMAIL' => $login], ['FIELDS' => ['LOGIN'], 'NAV_PARAMS' => ['nTopCount' => 1]])->fetch();
            if (!$user['LOGIN']) {
                return [
                    'error' => true,
                    'message' => 'E-mail в базе не найден',
                ];
            }
        } else {
            $user = \CUser::GetList($by = '', $order = '', ['LOGIN' => $login], ['FIELDS' => ['LOGIN'], 'NAV_PARAMS' => ['nTopCount' => 1]])->fetch();
            if (!$user['LOGIN']) {
                return [
                    'error' => true,
                    'message' => 'Логин в базе не найден',
                ];
            }
        }

        $result = $USER->Login($user['LOGIN'], $password, $remember, 'Y');

        if ($result['TYPE'] === 'ERROR') {
            return [
                'error' => true,
                'message' => [
                    'Авторизация не удалась',
                    'Неверный пароль',
                ],
            ];
        }

        return [
            'success' => true
        ];
    }

    private function createLoginLink()
    {
        $url = $_SERVER['HTTP_HOST'];
        $url .= '/login/';
        $url .= '?email=' . $this->_request['email'];
        $url .= '&code=' . $this->createUniqueCode();
        //$url .= '&sessionId=' . $this->_request['sessionId'];
        $this->_request['url'] = $url;
    }

    private function createUniqueCode()
    {
        $code = \Zk\Main\Helper::generateUniqueCode(6);
        if (Application::getConnection()->query('SELECT code FROM ' . $this->table . ' WHERE code="' . $code . '" AND email="' .mb_strtolower($this->_request['email']) . '" AND expire_at > NOW() ORDER BY expire_at DESC LIMIT 1')->fetch()['code']) {
            $code = \Zk\Main\Helper::generateUniqueCode(6);
        }
        $created_at = new \Bitrix\Main\Type\DateTime();
        $expire_at = new \Bitrix\Main\Type\DateTime();
        Application::getConnection()->add($this->table, [
            'code' => $code,
            'email' =>mb_strtolower($this->_request['email']),
            'created_at' => $created_at,
            'expire_at' => $expire_at->add('5 minutes'),
        ]);
        return $code;
    }

    private function sendEmail()
    {
        CEvent::SendImmediate('AUTHORIZE', \Zk\Main\Helper::siteId(), [
            'USER_EMAIL' => $this->_request['email'],
            'URL' => $this->_request['url']
        ], 'Y', 142);
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

    private function checkTable()
    {
        return;

        $sql = 'CREATE TABLE IF NOT EXISTS ' . $this->table . '
        (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `created_at` DATETIME NULL,
        `expire_at` DATETIME NULL,
        
        `code` VARCHAR(6),
        `email` VARCHAR(50),
        
        PRIMARY KEY(`id`),

        INDEX (`expire_at`, `code`, `email`)
        
        )';
        if (Application::getConnection()->isTableExists($this->table)) {
            //Application::getConnection()->dropTable($this->table);
        }
        Application::getConnection()->queryExecute($sql);
    }
}
