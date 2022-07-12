<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

IncludeModuleLangFile(__DIR__ . '/install.php');

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (class_exists('native_app')) return;

class native_app extends CModule
{
    public $MODULE_ID = 'native.app';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS = 'Y';
    public $PARTNER_NAME;
    public $PARTNER_URI;

    function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__ . '/version.php');
		$this->MODULE_SORT = 0;
        $this->MODULE_VERSION = isset($arModuleVersion['VERSION']) ? $arModuleVersion['VERSION'] : '';
        $this->MODULE_VERSION_DATE = isset($arModuleVersion['VERSION_DATE']) ? $arModuleVersion['VERSION_DATE'] : '';
        $this->MODULE_NAME = Loc::getMessage('NATIVE_APP_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('NATIVE_APP_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('NATIVE_APP_PARTNER');
        $this->PARTNER_URI = Loc::getMessage('NATIVE_APP_PARTNER_URI');
    }

    function DoInstall()
    {
        global $APPLICATION;
        RegisterModule($this->MODULE_ID);
        $this->InstallFiles();
        $APPLICATION->IncludeAdminFile(Loc::getMessage('NATIVE_APP_INSTALL_TITLE'), __DIR__ . '/step.php');
    }

    function DoUninstall()
    {
        global $APPLICATION;
        $this->UnInstallFiles();
        UnRegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile(Loc::getMessage('NATIVE_APP_UNINSTALL_TITLE'), __DIR__ . '/unstep.php');
    }

    function InstallFiles()
    {
        CopyDirFiles(__DIR__ . '/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin', true, true);
        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFiles(__DIR__ . '/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin');
        return true;
    }
}
