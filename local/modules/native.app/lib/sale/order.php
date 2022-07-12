<?php
/*
 * Изменено: 04 июля 2021, воскресенье
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

namespace Native\App\Sale;


use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\NotSupportedException;
use Bitrix\Main\ObjectException;
use Bitrix\Main\SystemException;
use Bitrix\Sale\PaySystem\BaseServiceHandler;
use Bitrix\Sale\PaySystem\Manager;
use Native\App\Helper;
use Native\App\Foundation\Bitrix24;
use Native\App\Provider\Bitrix24\Deal;

class Order
{
    private static ?Order $instance = null;

    const TYPE_INTERNET = Helper::TYPE_INTERNET;
    const TYPE_RETAIL = Helper::TYPE_RETAIL;
    const TYPE_COMBINE = Helper::TYPE_COMBINE;
    const TYPE_MOSCOW = Helper::TYPE_MOSCOW;

    const SUFFIX_INTERNET = Helper::SUFFIX_INTERNET;
    const SUFFIX_RETAIL = Helper::SUFFIX_RETAIL;
    const SUFFIX_COMBINE = Helper::SUFFIX_COMBINE;
    const SUFFIX_MOSCOW = Helper::SUFFIX_MOSCOW;


    /**
     * Получить ссылку на оплату по карте через Сбербанк
     * @param null $orderId
     * @return false|string
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws ArgumentTypeException
     * @throws LoaderException
     * @throws NotSupportedException
     * @throws ObjectException
     */
    public function getPaymentSberbankUrl($orderId)
    {
        Loader::includeModule('sale');
        $order = \Bitrix\Sale\Order::load($orderId);
        $payment = $order->getPaymentCollection()->current();
        if ($payment->getField('PAY_SYSTEM_ID') != PaymentSystem::getInstance()->getIdByCode(PaymentSystem::CARD_CODE)) {
            return false;
        }
        $paySystem = Manager::getObjectById($payment->getField('PAY_SYSTEM_ID'));
        $r = $paySystem->initiatePay($payment, null, BaseServiceHandler::STRING);
        if ($r->isSuccess()) {
            return $r->getPaymentUrl();
        }
        return false;
    }

    /**
     * Отправляем данные в Сделку Битрикс24
     * @param $params
     * @deprecated since 2020-07-14
     */
    public function sendDataToDeal($params)
    {
        $order =& $params['order'];
        $fields =& $params['fields'];
        $old =& $params['old'];

        $bitrix24 = new Bitrix24();
        //$userName = 'Администратор Интернет-магазина';

        $orderNumber =& $fields['ACCOUNT_NUMBER'];
        $trackingNumber =& $fields['TRACKING_NUMBER'];

        //$finalStage = $bitrix24->getCategoryId('internet') . ':WON';

        $data = []; // данные для отправки в Битрикс24

        if (isset($old['TRACKING_NUMBER']) && $old['TRACKING_NUMBER'] !== $fields['TRACKING_NUMBER']) {
            $data[$bitrix24->getFieldCode('trackingNumber')] = $trackingNumber;
        };

        /*if (isset($old['STATUS_ID']) && $old['STATUS_ID'] !== $fields['STATUS_ID'] && $fields['STATUS_ID'] === 'F') {
            $data['STAGE_ID'] = $finalStage;
        }*/

        if (count($data) > 0) {
            $deal = Deal::getInstance();
            $dealId = $deal->getIdByOrderNumber($orderNumber);

            $comment = [];

            if (isset($data[$bitrix24->getFieldCode('trackingNumber')])) {
                $comment[] = 'На основании заказа №' . $orderNumber . ' были обновлены поля';
                $comment[] = '- Номер отслеживания посылки: ' . $trackingNumber;
            }

            $comment = implode("\n", $comment);

            $deal->update($dealId, $data, $comment); // обновим сделку

            // Изменение стадии сделки выполним отдельно
            /*if (isset($data['STAGE_ID']) && $data['STAGE_ID'] === $bitrix24->getCategoryId('internet') . ':WON') {

                if ($deal->update($dealId, [$bitrix24->getFieldCode('dealClosed') => true, 'MODIFY_BY_ID' => $bitrix24->getUserId($userName)])) {
                    $deal->update($dealId, ['STAGE_ID' => $data['STAGE_ID'], 'MODIFY_BY_ID' => $bitrix24->getUserId($userName)], $comment);
                }



                // Установим администратора магазина ответсвенным по сделке
                // Это нужно чтобы обойти условия срабатывания роботов и БП
                if ($deal->update($dealId, ['ASSIGNED_BY_ID' => $bitrix24->getUserId($userName), 'MODIFY_BY_ID' => $bitrix24->getUserId($userName)])) {
                    $comment = 'На основании заказа №' . $orderNumber . ' был обновлен статус сделки';
                    // Установим статус сделки Успешный
                    // От имени администратора магазина
                    // После, на стороне Б24 сработает Робот, который установит реального ответсвенного у сделки

                }
            }*/
        }
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct()
    {
    }

    public function __call($name, $arguments)
    {
        die('Method \'' . $name . '\' is not defined');
    }

    private function __clone()
    {
    }
}
