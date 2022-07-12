<?php
/*
 * Изменено: 16 августа 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

use Bitrix\Main\Application;
use Bitrix\Main\Engine\Contract\Controllerable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class ManagerLoginForm extends CBitrixComponent implements Controllerable
{
	private string $table = 'b_registration_code';
	private        $_request;

	public function executeComponent ()
	{
		$this->setFrameMode(false);
		//$this->checkTable();
		$this->checkUser();
		$this->includeComponentTemplate();
	}

	public function configureActions ()
	{
		return [
			'login' => [
				'prefilters' => [],
			],
		];
	}

	private function checkUser ()
	{
		/*if (isset($_GET['sessionId']) && $_GET['sessionId'] != session_id()) {
			$this->arResult['ERROR']['MESSAGE'] = 'Срок действия текущей сессии истек';
			$this->arResult['ERROR']['TYPE'] = 'LINK';
			return;
		}*/
		if ($_GET['email'] && $_GET['code']) {
			$email = Application::getConnection()->getSqlHelper()->forSql($_GET['email']);
			$code = Application::getConnection()->getSqlHelper()->forSql($_GET['code']);
			if (!Application::getConnection()->query('SELECT code FROM ' . $this->table . ' WHERE code="' . $code . '" AND email="' . mb_strtolower($email) . '" AND expire_at > NOW() ORDER BY expire_at DESC LIMIT 1')->fetch()['code']) {
				$this->arResult['ERROR']['MESSAGE'] = 'Срок действия текущей сессии истек';
				$this->arResult['ERROR']['TYPE'] = 'LINK';
				return;
			}
			$user = \CUser::GetList($by = '', $order = '', ['EMAIL' => $email/*, 'LOGIN' => $email*/])->fetch();
			if ($user) {
				$this->arResult['SUCCESS']['MESSAGE'] = 'Вы успешно авторизованы на сайте.<br>Перейти в <a href="/catalog/">каталог продукции</a>.';
				$this->arResult['SUCCESS']['TYPE'] = 'EXIST';
				if (!$GLOBALS['USER']->IsAuthorized()) {
					$GLOBALS['USER']->Authorize($user['ID'], true);
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

	public function loginAction ($request)
	{
		$this->_request = $request;
		$this->_request['email'] = trim($this->_request['email']);

		if (!$GLOBALS['USER']->GetEmail()) {
			return [
				'message' => 'Не указан E-mail менеджера',
			];
		}
		if (!$this->_request['email']) {
			return [
				'message' => 'Все поля обязательны к заполнению',
			];
		}
		$matches = [];
		preg_match("/[а-яё]/iu", $this->_request['email'], $matches, PREG_OFFSET_CAPTURE);
		if (!empty($matches)) {
			return [
				'message' => 'Обнаружены кириллические символы',
			];
		}
		if (!filter_var($this->_request['email'], FILTER_VALIDATE_EMAIL)) {
			return [
				'message' => 'E-mail не валидный',
			];
		}
		/*if (!(new \Zk\Main\Spam\Protect())->check($_SERVER['REMOTE_ADDR'], $this->_request['email'])) {
			return [
				'message' => 'E-mail был отклонен из-за подозрения на Спам'
			];
		}*/
		$r = \Bitrix\Main\UserTable::getList([
			'select' => ['ID'],
			'filter' => ['=EMAIL' => $this->_request['email']],
			'limit'  => 1,
		]);
		if ($r->getSelectedRowsCount() === 0) {
			return [
				'message' => 'E-mail не найден',
				'type'    => 'EMAIL_NOT_FOUND',
			];
		}
		$this->createLoginLink();
		$this->sendEmail();
		return [
			'sent'    => true,
			'message' => 'На e-mail <b>' . $GLOBALS['USER']->GetEmail() . '</b> отправлено письмо. Для авторизации под пользователем с e-mail <b>' . $this->_request['email'] . '</b> следуйте указанным в нём инструкциям.',
		];
	}

	private function createLoginLink ()
	{
		$url = $_SERVER['HTTP_HOST'];
		$url .= '/login/';
		$url .= '?email=' . $this->_request['email'];
		$url .= '&code=' . $this->createUniqueCode();
		//$url .= '&sessionId=' . $this->_request['sessionId'];
		$this->_request['url'] = $url;
	}

	private function createUniqueCode ()
	{
		$code = \Zk\Main\Helper::generateUniqueCode(6);
		if (Application::getConnection()->query('SELECT code FROM ' . $this->table . ' WHERE code="' . $code . '" AND email="' . mb_strtolower($this->_request['email']) . '" AND expire_at > NOW() ORDER BY expire_at DESC LIMIT 1')->fetch()['code']) {
			$code = \Zk\Main\Helper::generateUniqueCode(6);
		}
		$created_at = new \Bitrix\Main\Type\DateTime();
		$expire_at = new \Bitrix\Main\Type\DateTime();
		Application::getConnection()->add($this->table, [
			'code'       => $code,
			'email'      => mb_strtolower($this->_request['email']),
			'created_at' => $created_at,
			'expire_at'  => $expire_at->add('5 minutes'),
		]);
		return $code;
	}

	private function sendEmail ()
	{
		CEvent::SendImmediate('AUTHORIZE', \Zk\Main\Helper::siteId(), [
			'USER_EMAIL' => $GLOBALS['USER']->GetEmail(),
			'URL'        => $this->_request['url'],
		], 'Y', 142);
	}

	private function checkTable ()
	{
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
