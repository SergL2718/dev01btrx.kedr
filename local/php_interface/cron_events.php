<?php
/*
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @updated 12.08.2020, 16:08
 * @copyright 2011-2020
 */

define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('CHK_EVENT', true);

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

@set_time_limit(0);
@ignore_user_abort(true);

CAgent::CheckAgents();
define('BX_CRONTAB_SUPPORT', true);
define('BX_CRONTAB', true);
CEvent::CheckEvents();

if (CModule::IncludeModule('subscribe')) {
    $cPosting = new CPosting;
    $cPosting->AutoSend();
}

if (CModule::IncludeModule('sender')) {
    \Bitrix\Sender\MailingManager::checkPeriod(false);
    \Bitrix\Sender\MailingManager::checkSend();
}
