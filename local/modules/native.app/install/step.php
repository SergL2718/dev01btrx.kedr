<?
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

if (!check_bitrix_sessid()) return;

echo CAdminMessage::ShowNote(GetMessage('NATIVE_APP_INSTALL_TITLE'));
