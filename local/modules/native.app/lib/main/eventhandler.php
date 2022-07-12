<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Native\App\Main;


use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Native\App\Sale\DeliverySystem;
use Native\App\Spam\Protect;

class EventHandler
{
    /**
     * @param $event
     * @param $lid
     * @param $arFields
     * @param $message_id
     * @param $files
     * @return false
     * @throws LoaderException
     * @throws ArgumentNullException
     */
    public function OnBeforeEventAdd(&$event, &$lid, &$arFields, &$message_id, &$files)
    {
        // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/2415/

        // Отменим лишние события
        if (
            $event === 'SALE_NEW_ORDER' ||
            $event === 'SALE_STATUS_CHANGED_N' ||
            $event === 'SALE_STATUS_CHANGED_DA' ||
            $event === 'SALE_ORDER_DELIVERY' ||
            $event === 'SALE_STATUS_CHANGED_P'
        ) {
            return false;
        }

        switch ($event) {

            case 'SALE_ORDER_PAID':
                \Bitrix\Main\Loader::includeModule('sale');
                $order = \Bitrix\Sale\Order::load($arFields['ORDER_REAL_ID']);
                $paySystemCode = \Native\App\Sale\PaymentSystem::getInstance()->getCodeById($order->getField('PAY_SYSTEM_ID'));
                $deliveryCode = DeliverySystem::getInstance()->getCodeById($order->getField('DELIVERY_ID'));
                // Если оплата в заказе была не по счету, тогда отменим событие
                if ($paySystemCode !== \Native\App\Sale\PaymentSystem::BILL_CODE) {
                    return false;
                }
                // Для самовывоза и оплаты по счету отправляем иное письмо
                // https://megre.ru/bitrix/admin/message_edit.php?lang=ru&ID=38
                // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/18707/
                if ($deliveryCode === DeliverySystem::PICKUP_NSK) {
                    return false; // отменим дефолтное письмо
                }
                // Добавим Имя получателя
                if (!isset($arFields['USER_NAME'])) {
                    try {
                        $arFields['USER_NAME'] = $order->getPropertyCollection()->getItemByOrderPropertyCode('NAME')->getValue();
                    } catch (ObjectPropertyException | SystemException $e) {
                    }
                }
                break;

            case 'SALE_ORDER_TRACKING_NUMBER':
                \Bitrix\Main\Loader::includeModule('sale');
                $order = \Bitrix\Sale\Order::load($arFields['ORDER_REAL_ID']);
                $arFields['ORDER_DELIVERY_NAME'] = DeliverySystem::getInstance()->getNameById($order->getField('DELIVERY_ID'));
                break;

            case 'SALE_STATUS_CHANGED_C':
                \Bitrix\Main\Loader::includeModule('sale');
                $order = \Bitrix\Sale\Order::load($arFields['ORDER_REAL_ID']);
                // Добавим Имя получателя
                if (!isset($arFields['USER_NAME'])) {
                    try {
                        $arFields['USER_NAME'] = $order->getPropertyCollection()->getItemByOrderPropertyCode('NAME')->getValue();
                    } catch (ObjectPropertyException | SystemException $e) {
                    }
                }
                break;

            case 'SALE_STATUS_CHANGED_F':
                \Bitrix\Main\Loader::includeModule('sale');
                $order = \Bitrix\Sale\Order::load($arFields['ORDER_REAL_ID']);
                $deliveryCode = DeliverySystem::getInstance()->getCodeById($order->getField('DELIVERY_ID'));
                // Если не способ доставки Самовывоз из Офиса (Новосибирск, ул. Коммунистическая, 2)
                // Тогда отменяем отправку письма о статусе Выполнен
                // https://megre.ru/bitrix/admin/message_edit.php?lang=ru&ID=38
                if ($deliveryCode !== DeliverySystem::PICKUP_NSK) {
                    return false;
                }
                // Добавим Имя получателя
                if (!isset($arFields['USER_NAME'])) {
                    try {
                        $arFields['USER_NAME'] = $order->getPropertyCollection()->getItemByOrderPropertyCode('NAME')->getValue();
                    } catch (ObjectPropertyException | SystemException $e) {
                    }
                }
                break;

            default:
                break;
        }

        return true;
    }

    /**
     * @param $arFields
     * @param $arTemplate
     * @return bool
     */
    public function OnBeforeEventSend(&$arFields, &$arTemplate): bool
    {
        switch ($arTemplate['EVENT_NAME']) {

            // 1. Отключаем второе письмо о добавлении комментария
            case 'NEW_BLOG_COMMENT_WITHOUT_TITLE':
                if ($arFields['BLOG_URL'] == 'catalog_comments_zg' && $arFields['EMAIL_TO'] == '1c_exchange@megre.ru') {
                    return false;
                }
                break;

            // 2. Проверка форм на спам
            case 'ALX_FEEDBACK_FORM':
                if ($arTemplate['ID'] == 116) {

                    $message = $arFields['TEXT_MESSAGE'];
                    $theme = false;
                    $recipientEmail = false;

                    // recipient email
                    if (preg_match('/Электронный адрес: ([^"\n"]*)/', $message, $matches)) {
                        $recipientEmail = $matches[1];
                    }

                    if ($recipientEmail && !(new Protect())->check($_SERVER['REMOTE_ADDR'], $recipientEmail)) {
                        return false;
                    }

                    // bad data
                    $message = str_replace('Категория обращения: Не выбрано' . PHP_EOL, '', $message);

                    // theme
                    if (preg_match('/Тема: ([^"\n"]*)/', $message, $matches)) {
                        $theme = $matches[1];
                        $message = str_replace($matches[0] . PHP_EOL, '', $message);
                    }

                    if ($theme) {
                        $arFields['CATEGORY'] = $theme;
                    }

                    $arFields['TEXT_MESSAGE'] = $message;
                }
                break;
            default;
        }
        return true;
    }
}
