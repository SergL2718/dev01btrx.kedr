<?php
/*
 * Изменено: 09 декабря 2021, четверг
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @global CUser $USER
 * @global CMain $APPLICATION
 */

use Bitrix\Main;
use Bitrix\Main\Authentication\ApplicationPasswordTable as ApplicationPasswordTable;

if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
	header('Access-Control-Allow-Methods: POST, OPTIONS');
	header('Access-Control-Max-Age: 60');
	header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept');
	die('');
}

define("ADMIN_SECTION", false);
require($_SERVER["DOCUMENT_ROOT"] . "/desktop_app/headers.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if (!IsModuleInstalled('bitrix24')) {
	header('Access-Control-Allow-Origin: *');
}

if ($_POST['action'] != 'login') {
	sendResponse(["success" => false, "reason" => 'Method not permitted'], "403 Forbidden");
	exit;
}

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/im/install/public/desktop_app/login/index.php");

$result = $USER->Login($_POST['login'], $_POST['password']);
if ($_POST['otp']) {
	$result = $USER->LoginByOtp($_POST['otp']);
}

if ($result !== true || !$USER->IsAuthorized()) {
	if (IsModuleInstalled('bitrix24')) {
		header('Access-Control-Allow-Origin: *');
	}

	$answer = [
		"success" => false,
	];

	if (\Bitrix\Main\Loader::includeModule('bitrix24') && ($captchaInfo = CBitrix24::getStoredCaptcha())) {
		$answer["captchaCode"] = $captchaInfo["captchaCode"];
		$answer["captchaURL"] = $captchaInfo["captchaURL"];
	} else if ($APPLICATION->NeedCAPTHAForLogin($_POST['login'])) {
		$answer["captchaCode"] = $APPLICATION->CaptchaGetCode();
	}

	if (CModule::IncludeModule("security") && \Bitrix\Security\Mfa\Otp::isOtpRequired()) {
		//user must enter OTP
		$answer["needOtp"] = true;
	}

	if ($result && $result["CODE"]) {
		if ($result["CODE"] === 'ERROR_NETWORK') {
			sendResponse($answer, "521 Internal Bitrix24.Network error");
			exit;
		}

		$answer["error_code"] = $result["CODE"];
	}

	sendResponse($answer, "401 Unauthorized");
	exit;
}

if ($USER->IsAuthorized() && !isAccessAllowed()) {
	sendResponse(["success" => false, "reason" => 'Access denied for this type of user'], "401 Unauthorized");
	exit;
}

$answer = [
	"success"         => true,
	"sessionId"       => session_id(),
	"bitrixSessionId" => bitrix_sessid(),
];

if (
	($_POST['renew_password'] == 'y' || $_POST['otp'] <> '')
	&& $USER->GetParam("APPLICATION_ID") === null
) {
	$code = '';
	if (strlen($_POST['user_os_mark']) > 0) {
		$code = md5($_POST['user_os_mark'] . $_POST['user_account']);
	}

	if ($code <> '') {
		$orm = ApplicationPasswordTable::getList([
			'select' => ['ID'],
			'filter' => [
				'=USER_ID' => $USER->GetID(),
				'=CODE'    => $code,
			],
		]);
		if ($row = $orm->fetch()) {
			ApplicationPasswordTable::delete($row['ID']);
		}
	}

	$password = ApplicationPasswordTable::generatePassword();

	$res = ApplicationPasswordTable::add([
		'USER_ID'        => $USER->GetID(),
		'APPLICATION_ID' => 'desktop',
		'PASSWORD'       => $password,
		'DATE_CREATE'    => new Main\Type\DateTime(),
		'CODE'           => $code,
		'COMMENT'        => GetMessage('DESKTOP_APP_GENERATOR'),
		'SYSCOMMENT'     => GetMessage('DESKTOP_APP_TITE'),
	]);
	if ($res->isSuccess()) {
		$answer["appPassword"] = $password;
	}
}

sendResponse($answer);


// helper function
function sendResponse (array $answer, string $httpCode = '200 OK')
{
	\CHTTP::SetStatus($httpCode);

	if (isset($_REQUEST['json']) && $_REQUEST['json'] == 'y') {
		header('Content-Type: application/json');
		echo Main\Web\Json::encode($answer);
		return true;
	}

	$answerParts = [];
	foreach ($answer as $attr => $value) {
		switch (gettype($value)) {
			case 'string':
				$value = "'" . CUtil::JSEscape($value) . "'";
				break;
			case 'boolean':
				$value = ($value === true ? 'true' : 'false');
				break;
			case 'array':
				$value = toJsObject($value);
				break;
		}

		$answerParts[] = $attr . ": " . $value;
	}

	echo "{" . implode(", ", $answerParts) . "}";
	return true;
}

function isAccessAllowed ()
{
	if (!\Bitrix\Main\Loader::includeModule('intranet')) {
		return true;
	}

	global $USER;

	$userId = $USER->GetID();
	$userData = \Bitrix\Intranet\UserTable::getByPrimary($userId, ['select' => ['USER_TYPE_INNER']])->fetch();
	if ($userData && $userData['USER_TYPE_INNER'] === 'employee') {
		return true;
	}

	if (!\Bitrix\Main\ModuleManager::isModuleInstalled('extranet')) {
		return false;
	}

	$extranetGroupId = (int) \Bitrix\Main\Config\Option::get('extranet', 'extranet_group', 0);
	$userGroups = array_map(function ($value) {
		return (int) $value;
	}, $USER->GetUserGroupArray());
	if ($extranetGroupId && in_array($extranetGroupId, $userGroups, true)) {
		return true;
	}

	return false;
}