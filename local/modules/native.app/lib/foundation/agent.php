<?php
/*
 * Изменено: 18 февраля 2022, пятница
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

namespace Native\App\Foundation;


/**
 * В новых версиях агентов - все агенты изолированы
 * @deprecated since 2022-02-18
 */
abstract class Agent
{
    protected string $function;

    protected bool $log = true;

    protected function log($fields)
    {
        if (!$this->log) return;
        $fields['MODULE_ID'] = 'native.app';
        \CEventLog::add($fields);
    }
}
