<?php
/*
 * Изменено: 08 ноября 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

use Bitrix\Main\ArgumentException;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectNotFoundException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Native\App\Sale\Basket;
use Native\App\Sale\Favorites;

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

(new Product($_REQUEST))->response();

class Product
{
	private array $request;

	/**
	 * @param $request
	 */
	public function __construct ($request)
	{
		header('Content-Type: application/json; charset=' . SITE_CHARSET);
		$this->request = $request;
	}

	public function response ()
	{
		$request =& $this->request;
		$action = $request['action'];
		echo json_encode($this->$action());
		die;
	}

	/**
	 * @throws LoaderException
	 * @throws ArgumentException
	 * @throws ObjectNotFoundException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	private function addToBasket (): array
	{
		$id =& $this->request['ID'];
		if (!$id) {
			return [
				'status' => 'error',
			];
		}
		$quantity = 1;
		if ($this->request['QUANTITY']) {
			$quantity = $this->request['QUANTITY'];
		}
		$quantity = (INT)$quantity;
		if(!$quantity)$quantity = 1;
		$r = Basket::add($id, $quantity);
		if ($r->isSuccess()) {
			return [
				'status' => 'success',
				'itemId' => $r->getData()['ID'],
				'count'  => Basket::count(),
			];
		}
		return [
			'status' => 'error',
			'error'  => $r->getErrorMessages(),
			'data_id' => $id,
			'data_quantity' => $quantity,
		];
	}

	/**
	 * @throws LoaderException
	 * @throws ArgumentException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	private function deleteFromBasket (): array
	{
		$id =& $this->request['ID'];
		if (!$id) {
			return [
				'status' => 'error',
			];
		}
		$r = Basket::delete($id);
		if ($r === false) {
			return [
				'status' => 'error',
			];
		}
		return [
			'status' => 'success',
		];
	}

	/**
	 * @return array|string[]
	 */
	private function addToFavorites (): array
	{
		$id =& $this->request['ID'];
		if (!$id) {
			return [
				'status' => 'error',
			];
		}
		try {
			$r = Favorites::add($id);
			if ($r === false) {
				return [
					'status' => 'error',
				];
			}
			return [
				'status' => 'success',
				'count'  => $r,
			];
		}
		catch (Exception $e) {
			return [
				'status' => 'error',
				'error'  => $e->getMessage(),
			];
		}
	}

	/**
	 * @return array|string[]
	 */
	private function deleteFromFavorites (): array
	{
		$id =& $this->request['ID'];
		if (!$id) {
			return [
				'status' => 'error',
			];
		}
		try {
			$r = Favorites::delete($id);
			if ($r === false) {
				return [
					'status' => 'error',
				];
			}
			return [
				'status' => 'success',
				'count'  => $r,
			];
		}
		catch (Exception $e) {
			return [
				'status' => 'error',
				'error'  => $e->getMessage(),
			];
		}
	}
}
