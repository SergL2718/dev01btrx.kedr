<?php
/*
 * Изменено: 08 ноября 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */


namespace Native\App\Agent;


use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Loader;
use Bitrix\Sale\Internals\ShipmentTable;
use Bitrix\Sale\Order;
use Native\App\Foundation\Agent;
use Native\App\Provider\Boxberry;
use Native\App\Provider\RussianPost;
use Native\App\Sale\DeliverySystem;

class DownloadOrderInformationFromExternalDeliveryServices extends Agent
{
	//private $ids = [];
	private $dateFrom = null;
	private $dateTo   = null;

	public function __construct ()
	{
		$this->function = '(new Native\App\Agent\DownloadOrderInformationFromExternalDeliveryServices())->run();';
	}

	public function run ($dateFrom = null, $dateTo = null): string
	{
		global $USER;
		$userId = 97960; // Администратор Интернет-магазина, tech.admin@megre.ru
		$this->dateFrom = $dateFrom;
		$this->dateTo = $dateTo;
		if (!is_object($USER)) {
			$USER = new \CUser();
		}
		if (!$USER->IsAuthorized()) {
			$USER->Authorize($userId);
		}
		Loader::includeModule('sale');
		if ($this->dateFrom === null) {
			$this->dateFrom = date('d.m.Y H:i:s', time() - (86400 * 30));
		}
		if ($this->dateTo === null) {
			$this->dateTo = date('d.m.Y H:i:s');
		}

		//pr(date('H:i:s'));

		$trackingList = [];
		$accessDeliveryList = [
			DeliverySystem::getInstance()->getIdByCode(Boxberry::COURIER)         => Boxberry::COURIER,
			DeliverySystem::getInstance()->getIdByCode(Boxberry::POINT_FREE)      => Boxberry::POINT_FREE,
			DeliverySystem::getInstance()->getIdByCode(Boxberry::POINT)           => Boxberry::POINT,
			DeliverySystem::getInstance()->getIdByCode(RussianPost::CLASSIC)      => RussianPost::CLASSIC,
			DeliverySystem::getInstance()->getIdByCode(RussianPost::CLASSIC_FREE) => RussianPost::CLASSIC_FREE,
			DeliverySystem::getInstance()->getIdByCode(RussianPost::EMS)          => RussianPost::EMS,
			DeliverySystem::getInstance()->getIdByCode(RussianPost::AIR)          => RussianPost::AIR,
			DeliverySystem::getInstance()->getIdByCode(RussianPost::SURFACE)      => RussianPost::SURFACE,
		];
		$filter = [
			'=ACCOUNT_NUMBER'       => '66927-INT',
			'>=DATE_INSERT'         => $this->dateFrom,
			[
				'LOGIC'         => 'AND',
				'>=DATE_UPDATE' => $this->dateFrom,
				'<=DATE_UPDATE' => $this->dateTo,
			],
			'=PAYED'                => 'Y',
			'=CANCELED'             => 'N',
			'=DELIVERY_ID'          => array_keys($accessDeliveryList),
			'=DATE_DELIVERED.CODE'  => 'SYS_DATE_DELIVERED',
			'=DATE_DELIVERED.VALUE' => false,

			/*'=DELIVERY_TOTAL_PRICE.CODE' => 'SYS_DELIVERY_TOTAL_PRICE',
			[
				'LOGIC'                      => 'OR',
				'DELIVERY_TOTAL_PRICE.VALUE' => false,
				'DATE_DELIVERED.VALUE'       => false,
			],*/
		];

		/*$exclude = [];
		if (File::isFileExists($_SERVER['DOCUMENT_ROOT'] . '/exclude.csv')) {
			$ids = File::getFileContents($_SERVER['DOCUMENT_ROOT'] . '/exclude.csv');
			if (!empty($ids)) {
				$ids = explode("\r", $ids);
				foreach ($ids as $row) {
					$row = explode(',', $row);
					if ($row[0] > 0) {
						$exclude[$row[0]] = $row[0];
					}
				}
			}
		}
		if (File::isFileExists($_SERVER['DOCUMENT_ROOT'] . '/exclude-not-found-track-number.csv')) {
			$ids = File::getFileContents($_SERVER['DOCUMENT_ROOT'] . '/exclude-not-found-track-number.csv');
			if (!empty($ids)) {
				$ids = explode("\r", $ids);
				foreach ($ids as $row) {
					$row = explode(',', $row);
					if ($row[0] > 0) {
						$exclude[$row[0]] = $row[0];
					}
				}
			}
		}
		if (!empty($exclude)) {
			$filter['!=ID'] = $exclude;
		}*/

		//pr($filter);
		//return $this->function;

		$r = Order::getList([
			'select'  => [
				'ID',
				'DATE_INSERT',
				'DELIVERY_ID',
				'ACCOUNT_NUMBER',
				'TRACKING_NUMBER',
				//'DELIVERY_TOTAL_PRICE.VALUE',
				//'DATE_DELIVERED.VALUE',
			],
			'filter'  => $filter,
			'runtime' => [
				/*new ReferenceField(
					'DELIVERY_TOTAL_PRICE',
					'\Bitrix\sale\Internals\OrderPropsValueTable',
					["=this.ID" => "ref.ORDER_ID"],
					["join_type" => "left"]
				),*/
				new ReferenceField(
					'DATE_DELIVERED',
					'\Bitrix\sale\Internals\OrderPropsValueTable',
					["=this.ID" => "ref.ORDER_ID"],
					["join_type" => "left"]
				),
			],
			'order'   => [
				'ID' => 'asc',
			],
			//'limit'   => 40,
		]);

		//pr('total: ' . $r->getSelectedRowsCount());

		if ($r->getSelectedRowsCount() === 0) {
			if ($USER->GetID() === $userId) {
				$USER->Logout();
			}
			\CEventLog::Add([
				'AUDIT_TYPE_ID' => 'ORDER_DELIVERED',
				'MODULE_ID'     => 'sale',
				'ITEM_ID'       => 'native.app',
				'DESCRIPTION'   => 'Проверка завершена<br>Период проверки: ' . $this->dateFrom . ' - ' . $this->dateTo,
			]);
			return $this->function;
		}

		while ($ar = $r->fetch()) {
			$code = 'RussianPost';
			if (
				$accessDeliveryList[$ar['DELIVERY_ID']] === Boxberry::COURIER
				|| $accessDeliveryList[$ar['DELIVERY_ID']] === Boxberry::POINT_FREE
				|| $accessDeliveryList[$ar['DELIVERY_ID']] === Boxberry::POINT
			) {
				$code = 'Boxberry';
			}
			if (empty($ar['TRACKING_NUMBER'])) {
				$s = ShipmentTable::getList([
					'select' => [
						'ID',
						'TRACKING_NUMBER',
					],
					'filter' => [
						'=ORDER_ID'        => $ar['ID'],
						'!=SYSTEM'         => 'Y',
						'!TRACKING_NUMBER' => false,
					],
					'limit'  => 1,
				]);
				if ($s->getSelectedRowsCount() === 0) {
					/*$row = implode(',', [
							'ID'             => $ar['ID'],
							'DATE_INSERT'    => $ar['DATE_INSERT']->toString(),
							'ACCOUNT_NUMBER' => $ar['ACCOUNT_NUMBER'],
							'STATUS'         => 'Трек-номер не найден',
							'DATE_STATUS'    => '-',
						]) . "\r";
					File::putFileContents(Application::getDocumentRoot() . '/exclude-not-found-track-number.csv', $row, File::APPEND);*/
					continue;
				}
				/*$row = implode(',', [
						'ID'             => $ar['ID'],
						'DATE_INSERT'    => $ar['DATE_INSERT']->toString(),
						'ACCOUNT_NUMBER' => $ar['ACCOUNT_NUMBER'],
						'STATUS'         => 'Не было трек-номера в системном поле заказа',
						'DATE_STATUS'    => '-',
					]) . "\r";
				File::putFileContents(Application::getDocumentRoot() . '/exclude-not-exist-sys-track-number.csv', $row, File::APPEND);*/

				$s = $s->fetchRaw();
				$ar['TRACKING_NUMBER'] = $s['TRACKING_NUMBER'];
			}
			if (mb_strpos($ar['TRACKING_NUMBER'], ',') !== false) {
				$ar['TRACKING_NUMBER'] = explode(',', $ar['TRACKING_NUMBER'])[0];
				$ar['TRACKING_NUMBER'] = trim($ar['TRACKING_NUMBER']);
			}
			if ($ar['TRACKING_NUMBER']) {
				$trackingList[$code][$ar['ID']] = $ar['TRACKING_NUMBER'];
			}

			/*$this->ids[$ar['ID']] = [
				'ID'             => $ar['ID'],
				'ACCOUNT_NUMBER' => $ar['ACCOUNT_NUMBER'],
				'DATE_INSERT'    => $ar['DATE_INSERT']->toString(),
			];*/

		}

		//pr($trackingList);

		if (!empty($trackingList['Boxberry'])) {
			$this->getOrderInformationFromBoxberry($trackingList['Boxberry']);
		}
		if (!empty($trackingList['RussianPost'])) {
			$this->getOrderInformationFromRussianPost($trackingList['RussianPost']);
		}
		if ($USER->GetID() === $userId) {
			$USER->Logout();
		}

		//pr(date('H:i:s'));

		\CEventLog::Add([
			'AUDIT_TYPE_ID' => 'ORDER_DELIVERED',
			'MODULE_ID'     => 'sale',
			'ITEM_ID'       => 'native.app',
			'DESCRIPTION'   => 'Проверка завершена<br>Период проверки: ' . $this->dateFrom . ' - ' . $this->dateTo,
		]);

		return $this->function;
	}

	private function getOrderInformationFromBoxberry (array $trackingList): void
	{
		if (empty($trackingList)) {
			return;
		}
		foreach ($trackingList as $orderId => $trackingCode) {
			$arStatuses = Boxberry::getInstance()->getListStatuses($trackingCode);
			if (empty($arStatuses)) continue;
			$lastStatus = $arStatuses[array_key_last($arStatuses)];
			if (mb_strtolower($lastStatus->Name) !== 'выдано') {
				/*$row = implode(',', [
						'ID'             => $orderId,
						'DATE_INSERT'    => $this->ids[$orderId]['DATE_INSERT'],
						'ACCOUNT_NUMBER' => $this->ids[$orderId]['ACCOUNT_NUMBER'],
						'STATUS'         => $lastStatus->Name,
						'DATE_STATUS'    => date('d.m.Y', strtotime($lastStatus->Date)),
						'DELIVERY'       => 'Boxberry',
					]) . "\r";
				//echo $orderId . ' - ' . $this->ids[$orderId]['DATE_INSERT'] . '<br>';
				File::putFileContents(Application::getDocumentRoot() . '/exclude.csv', $row, File::APPEND);*/
				continue;
			}
			$dateDelivered = date('d.m.Y', strtotime($lastStatus->Date));
			$this->setDateDelivered($orderId, $dateDelivered);
		}
	}

	private function getOrderInformationFromRussianPost (array $trackingList): void
	{
		if (empty($trackingList)) {
			return;
		}
		foreach ($trackingList as $orderId => $trackingCode) {
			$lastStatus = RussianPost::getInstance()->getListStatuses($trackingCode);
			if (empty($lastStatus)) {
				/*$row = implode(',', [
						'ID'             => $orderId,
						'DATE_INSERT'    => $this->ids[$orderId]['DATE_INSERT'],
						'ACCOUNT_NUMBER' => $this->ids[$orderId]['ACCOUNT_NUMBER'],
						'STATUS'         => 'Неизвестен',
						'DATE_STATUS'    => '-',
						'DELIVERY'       => 'RussianPost',
					]) . "\r";
				//echo $orderId . ' - ' . $this->ids[$orderId]['DATE_INSERT'] . '<br>';
				File::putFileContents(Application::getDocumentRoot() . '/exclude.csv', $row, File::APPEND);*/

				continue;
			}
			if (mb_strtolower($lastStatus['human-operation-name']) !== 'получено адресатом') {
				/*$row = implode(',', [
						'ID'             => $orderId,
						'DATE_INSERT'    => $this->ids[$orderId]['DATE_INSERT'],
						'ACCOUNT_NUMBER' => $this->ids[$orderId]['ACCOUNT_NUMBER'],
						'STATUS'         => $lastStatus['human-operation-name'],
						'DATE_STATUS'    => date('d.m.Y', strtotime($lastStatus['last-oper-date'])),
						'DELIVERY'       => 'RussianPost',
					]) . "\r";
				//echo $orderId . ' - ' . $this->ids[$orderId]['DATE_INSERT'] . '<br>';
				File::putFileContents(Application::getDocumentRoot() . '/exclude.csv', $row, File::APPEND);*/
				continue;
			}
			$dateDelivered = date('d.m.Y', strtotime($lastStatus['last-oper-date']));
			$this->setDateDelivered($orderId, $dateDelivered);
		}
	}

	private function setDateDelivered ($orderId, $dateDelivered)
	{
		//echo 'OK: '.$orderId.'<br>';
		$order = Order::load($orderId);
		$shipment = $order->getShipmentCollection()->current();
		$propertyDateDelivered = $order->getPropertyCollection()->getItemByOrderPropertyCode('SYS_DATE_DELIVERED');
		if ($dateDelivered) {
			$statusText = 'Отгрузка выдана получателю';
			$shipment->setFields([
				'STATUS_ID' => 'SI', // Отгрузка выдана
				'COMMENTS'  => $statusText,
			]);
			$statusText .= '<br>Дата выдачи: ' . $dateDelivered;
			$propertyDateDelivered->setValue($dateDelivered);
		} else {
			$statusText = 'Не удалось получить информацию о статусе посылки';
			$shipment->setFields([
				'COMMENTS' => $statusText,
			]);
		}
		$statusText .= '<br>Период проверки: ' . $this->dateFrom . ' - ' . $this->dateTo;
		$r = $order->save();
		if ($r->isSuccess()) {
			\CEventLog::Add([
				'AUDIT_TYPE_ID' => 'ORDER_DELIVERED',
				'MODULE_ID'     => 'sale',
				'ITEM_ID'       => 'Заказ: ' . $order->getField('ACCOUNT_NUMBER'),
				'DESCRIPTION'   => $statusText,
			]);
		}
	}
}
