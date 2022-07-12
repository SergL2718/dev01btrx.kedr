<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Zk\Main\Main;


use Zk\Main\Spam\Protect;

class EventHandler
{
    /**
     * @param $event
     * @param $lid
     * @param $arFields
     * @param $message_id
     * @param $files
     */
    /*public function OnBeforeEventAdd(&$event, &$lid, &$arFields, &$message_id, &$files)
    {
        if ($event === 'SALE_STATUS_CHANGED_N') {
            if ($file = (new \Zk\Main\Sale\Bill)->getFile($arFields['ORDER_ID'])) {
                $files[] = $file;
            }
        }
    }*/

    /**
     * @param $arFields
     * @param $arTemplate
     * @return bool
     */
    /*public function OnBeforeEventSend(&$arFields, &$arTemplate)
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
    }*/

    /**
     * @param $arFields
     * @return bool
     */
    public function OnBeforeUserRegister(&$arFields)
    {
        // 1. Проверка пользователя по базе спама, если он присутствует в ней, отклоняем регистрацию
        if (!(new \Zk\Main\User\EventHandler())->OnBeforeUserRegister($arFields)) {
            return false;
        }
    }

    /**
     * @param $arFields
     */
    public function OnAfterUserRegister(&$arFields)
    {
        // 1. Высылаем пользователю его регистрационную информацию
        (new \Zk\Main\User\EventHandler())->OnAfterUserRegister($arFields);
    }

    /**
     *
     */
    /*public function OnAdminSaleOrderView()
    {
        if ($orderId = (int)$_GET['ID']) {
            // Данные для javascript
            $arSelect = [
                'ID',
                'ACCOUNT_NUMBER',
                'USER_EMAIL',
                'PAY_SYSTEM_ID'
            ];
            $order = \CSaleOrder::GetList([], ['ID' => $orderId], false, ['nTopCount' => 1], $arSelect)->fetch();
            setcookie('jsOrder', json_encode($order));
            $GLOBALS['APPLICATION']->AddHeadScript('/local/js/order.min.js');
        }
    }*/

    /**
     * @param $form
     */
    /*public function OnAdminTabControlBegin(&$form)
    {
        if ($GLOBALS["APPLICATION"]->GetCurPage() !== '/bitrix/admin/sale_order_shipment_edit.php') return;

        // http://task.anastasia.ru/issues/4482
        $GLOBALS['APPLICATION']->AddHeadScript('/local/js/shipment.min.js');
    }*/
}
