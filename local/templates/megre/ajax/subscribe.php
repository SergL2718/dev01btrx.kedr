<?php
/*
 * Изменено: 09 декабря 2021, четверг
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

use Bitrix\Main\UserTable;
use Native\App\Foundation\Bitrix24;
use Native\App\Provider\Bitrix24\Contact;

const SM_SAFE_MODE = true;
const PERFMON_STOP = true;
const PUBLIC_AJAX_MODE = true;
const STOP_STATISTICS = true;
const NO_AGENT_STATISTIC = 'Y';
const NO_AGENT_CHECK = true;
const NO_KEEP_STATISTIC = true;
const DisableEventsCheck = true;
const BX_SECURITY_SHOW_MESSAGE = false;
const NOT_CHECK_PERMISSIONS = true;

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

(new Subscribe($_REQUEST))->response();

class Subscribe
{
	private array $request;

	public function __construct ($request)
	{
		header('Content-Type: application/json; charset=' . SITE_CHARSET);
		$this->request = $request;
	}

	public function response ()
	{
		$request =& $this->request;
		$action = $request['action'];
		echo json_encode($this->$action());
		die;
	}

	private function subscribe ()
	{
		$email = $this->request['EMAIL'];
		if (empty($email)) {
			return [
				'status' => 'error',
				'error'  => 'E-mail не указан',
			];
		}
		$matches = [];
		preg_match("/[а-яё]/iu", $email, $matches, PREG_OFFSET_CAPTURE);
		if (!empty($matches)) {
			return [
				'status' => 'error',
				'error'  => 'Обнаружены кириллические символы',
			];
		}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return [
				'status' => 'error',
				'error'  => 'E-mail не валидный',
			];
		}
		Bitrix\Main\Loader::includeModule('subscribe');
		global $USER;
		$user = [];
		if ($USER->IsAuthorized()) {
			$filter = ['=ID' => $USER->GetID()];
		} else {
			$filter = ['=EMAIL' => $email];
		}
		$r = UserTable::getList([
			'select' => [
				'ID',
				'NAME',
				'LAST_NAME',
				'SECOND_NAME',
			],
			'filter' => $filter,
			'limit'  => 1,
		]);
		if ($r->getSelectedRowsCount() > 0) {
			$user = $r->fetchRaw();
		}
		$arFields = [
			'FORMAT'       => 'html',
			'EMAIL'        => $email,
			'ACTIVE'       => 'Y',
			'CONFIRMED'    => 'Y',
			'SEND_CONFIRM' => 'N',
			'RUB_ID'       => [4],
		];
		if ($user['ID'] > 0) {
			$arFields['USER_ID'] = $user['ID'];
		}
		$subscription = new \CSubscription;
		$id = $subscription->Add($arFields);
		if ($id > 0) {
			$bitrix24 = new Bitrix24();
			$contact = Contact::getInstance();
			if ($user = $contact->getByEmail($email)) {
				$arSubscribe = $user[$bitrix24->getFieldCode('subscriptionName')];
				$arLang = $user[$bitrix24->getFieldCode('subscriptionLanguage')];
				if (!in_array($bitrix24->getSubscriptionId('megre.ru'), $arSubscribe) || !in_array($bitrix24->getSubscriptionLanguageId('russian'), $arLang)) {
					if (!in_array($bitrix24->getSubscriptionId('megre.ru'), $arSubscribe)) {
						$arSubscribe[] = $bitrix24->getSubscriptionId('megre.ru');
					}
					if (!in_array($bitrix24->getSubscriptionLanguageId('russian'), $arLang)) {
						$arLang[] = $bitrix24->getSubscriptionLanguageId('russian');
					}
					$contact->update($user['ID'], [
						$bitrix24->getFieldCode('subscriptionName')     => $arSubscribe,
						$bitrix24->getFieldCode('subscriptionLanguage') => $arLang,
					]);
				}
			} else {
				$user['NAME'] = $user['NAME'] ?? 'Анонимный';
				$user['LAST_NAME'] = $user['LAST_NAME'] ?? '';
				$user['SECOND_NAME'] = $user['SECOND_NAME'] ?? '';
				$user['EMAIL'] = [['VALUE' => $email, 'VALUE_TYPE' => 'WORK']];
				$user[$bitrix24->getFieldCode('subscriptionName')][$bitrix24->getSubscriptionId('megre.ru')] = $bitrix24->getSubscriptionId('megre.ru');
				$user[$bitrix24->getFieldCode('subscriptionLanguage')][$bitrix24->getSubscriptionLanguageId('russian')] = $bitrix24->getSubscriptionLanguageId('russian');
				$contact->export($user);
			}
			return [
				'status'  => 'success',
				'message' => 'Подписка оформлена',
			];
		}
		return [
			'status' => 'error',
			'error'  => $subscription->LAST_ERROR,
		];
	}
}
