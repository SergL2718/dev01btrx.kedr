<?php
/*
 * Изменено: 29 сентября 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

use Bitrix\Main\Loader;

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

(new UserBar($_REQUEST))->response();

class UserBar
{
	private array $request;

	public function __construct ($request)
	{
		$this->request = $request;
	}

	public function response ()
	{
		$request =& $this->request;
		$action = $request['action'];
		$this->$action();
		die;
	}

	private function getTemplate ()
	{
		header('Content-Type: text/html; charset=' . SITE_CHARSET);
		Loader::includeModule('native.app');
		$id =& $this->request['ID'];
		$item = \Native\App\Catalog\Product::getById($id);
		$previewImageWidth = 80;
		$previewImageHeight = 80;
		if (!empty($item['PREVIEW_PICTURE'])) {
			$item['PREVIEW_PICTURE'] = [
				'ID'  => $item['PREVIEW_PICTURE'],
				'SRC' => \CFile::ResizeImageGet($item['PREVIEW_PICTURE'], ['width' => $previewImageWidth, 'height' => $previewImageHeight])['src'],
			];
		}
		include 'favorite-item-template.php';
	}
}
