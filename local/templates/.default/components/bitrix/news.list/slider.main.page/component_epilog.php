<?
/*
 * @updated 11.12.2020, 12:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

/**
 * @var $APPLICATION
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/slick/slick.js');
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/js/slick/slick.css');
