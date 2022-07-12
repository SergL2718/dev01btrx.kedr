<?php
/*
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @updated 12.08.2020, 14:59
 * @copyright 2011-2020
 */

/**
 * Файл принимает входящий колбэк от портала Битрикс24
 */

require __DIR__ . '/../../index.php'; // Bitrix core

$request =& $_GET;

$entityType =& $request['entityType'];
$action =& $request['action'];

$handler = __DIR__ . '/../entity/' . $entityType . '/action/' . $action . '.php';

if (empty($entityType) || empty($action) || !is_file($handler)) die;

require $handler;

die;
