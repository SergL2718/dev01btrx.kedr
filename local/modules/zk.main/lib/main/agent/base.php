<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Zk\Main\Main\Agent;


use Zk\Main\Helper;

abstract class Base
{
    protected $function;

    protected function log($type, $item, $description)
    {
        \CEventLog::add(['AUDIT_TYPE_ID' => $type, 'MODULE_ID' => Helper::MODULE_ID, 'ITEM_ID' => $item, 'DESCRIPTION' => $description]);
    }
}
