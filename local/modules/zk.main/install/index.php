<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

if(class_exists('zk_main')) return;

class zk_main extends CModule
{
	public $MODULE_ID = 'zk.main';
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $MODULE_GROUP_RIGHTS = 'Y';
	public $PARTNER_NAME;
	public $PARTNER_URI;

	function __construct() {
		$arModuleVersion = [];
		include(__DIR__ . '/version.php');
		$this->MODULE_SORT = 0;
		$this->MODULE_VERSION = isset($arModuleVersion['VERSION']) ? $arModuleVersion['VERSION'] : '';
		$this->MODULE_VERSION_DATE = isset($arModuleVersion['VERSION_DATE']) ? $arModuleVersion['VERSION_DATE'] : '';
		$this->MODULE_NAME = 'Главный модуль [устарел с 17.02.2020]';
		$this->MODULE_DESCRIPTION = 'Основной модуль сайта. Объявлен устаревшим с 17.02.2020. Всю функциональность нужно добавлять в модуль native.app';
		$this->PARTNER_NAME = 'Компания Webco';
		$this->PARTNER_URI = 'https://marketplace.1c-bitrix.ru/partners/detail.php?ID=1469188.php';
	}

	function DoInstall() {
		RegisterModule($this->MODULE_ID);
        $this->InstallFiles();
		$GLOBALS['APPLICATION']->IncludeAdminFile('', __DIR__ . '/step.php');
	}

	function DoUninstall() {
        $this->UnInstallFiles();
		UnRegisterModule($this->MODULE_ID);
		$GLOBALS['APPLICATION']->IncludeAdminFile('', __DIR__ . '/unstep.php');
	}

	function InstallFiles() {
		CopyDirFiles(__DIR__ . '/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin', true, true);
		return true;
	}

	function UnInstallFiles() {
		DeleteDirFiles(__DIR__ . '/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin');
		return true;
	}
}
