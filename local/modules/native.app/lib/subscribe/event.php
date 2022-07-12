<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Native\App\Subscribe;


class Event
{
    /**
     * @param $arFields
     */
    public function OnStartSubscriptionAdd(&$arFields)
    {
        // Выгрузка подписчиков в Битрикс24
        self::exportSubscriberToBitrix24($arFields);
    }

    /**
     * @param $arFields
     */
    public function OnStartSubscriptionUpdate(&$arFields)
    {
    }

    private static function exportSubscriberToBitrix24($ar)
    {
        if ($ar['ID'] && $ar['CONFIRM_CODE']) {

            $temp = [];
            $sql = 'select u.NAME, u.LAST_NAME, s.ID, r.LIST_RUBRIC_ID, s.EMAIL, s.ACTIVE from b_subscription as s ';
            $sql .= 'left join b_subscription_rubric as r on s.ID=r.SUBSCRIPTION_ID ';
            $sql .= 'left join b_user as u on s.USER_ID=u.ID ';
            $sql .= 'where s.ACTIVE="Y" ';
            $sql .= 'and s.ID="' . $ar['ID'] . '" and s.CONFIRM_CODE="' . $ar['CONFIRM_CODE'] . '"';

            $result = \Bitrix\Main\Application::getConnection()->query($sql);

            while ($subscribe = $result->fetchRaw()) {
                $temp[$subscribe['ID']]['NAME'] = $subscribe['NAME'];
                $temp[$subscribe['ID']]['LAST_NAME'] = $subscribe['LAST_NAME'];
                $temp[$subscribe['ID']]['EMAIL'] = $subscribe['EMAIL'];
                $temp[$subscribe['ID']]['ACTIVE'] = $subscribe['ACTIVE'];
                $temp[$subscribe['ID']]['CONFIRMED'] = 'Y';
                if ($subscribe['LIST_RUBRIC_ID'] > 0) {
                    $temp[$subscribe['ID']]['RUB_ID'][] = $subscribe['LIST_RUBRIC_ID'];
                }
            }

            $ar = $temp[$ar['ID']];
        }

        if ($ar['ACTIVE'] === 'N' || $ar['CONFIRMED'] === 'N' || !isset($ar['RUB_ID'][0]) || empty($ar['EMAIL'])) return;

        if (empty($ar['NAME']) && $ar['USER_ID'] > 0) {
            $user = $GLOBALS['USER']->GetByID($ar['USER_ID'])->fetch();
            $ar['NAME'] = $user['NAME'];
            $ar['LAST_NAME'] = $user['LAST_NAME'];
            $ar['SECOND_NAME'] = $user['SECOND_NAME'];
        }

        //define('LOG_FILENAME', $_SERVER['DOCUMENT_ROOT'] . '/Subscribe.log');
        //AddMessage2Log($ar);


        $bitrix24 = new \Native\App\Foundation\Bitrix24();
        $contact = \Native\App\Provider\Bitrix24\Contact::getInstance();

        // Обновим контакт, если он еще не подписан на рассылку Новости
        if ($subscriber = $contact->getByEmail($ar['EMAIL'])) {

            $arSubscribe = $subscriber[$bitrix24->getFieldCode('subscriptionName')];
            $arLang = $subscriber[$bitrix24->getFieldCode('subscriptionLanguage')];

            if (!in_array($bitrix24->getSubscriptionId('megre.ru'), $arSubscribe) || !in_array($bitrix24->getSubscriptionLanguageId('russian'), $arLang)) {

                if (!in_array($bitrix24->getSubscriptionId('megre.ru'), $arSubscribe)) {
                    $arSubscribe[] = $bitrix24->getSubscriptionId('megre.ru');
                }

                if (!in_array($bitrix24->getSubscriptionLanguageId('russian'), $arLang)) {
                    $arLang[] = $bitrix24->getSubscriptionLanguageId('russian');
                }

                $contact->update($subscriber['ID'], [
                    $bitrix24->getFieldCode('subscriptionName') => $arSubscribe,
                    $bitrix24->getFieldCode('subscriptionLanguage') => $arLang
                ]);
            }

            return;
        }

        $subscriber['NAME'] = $ar['NAME'] ? $ar['NAME'] : 'Анонимный';
        $subscriber['LAST_NAME'] = $ar['LAST_NAME'] ? $ar['LAST_NAME'] : '';
        $subscriber['SECOND_NAME'] = $ar['SECOND_NAME'] ? $ar['SECOND_NAME'] : '';
        $subscriber['EMAIL'] = [['VALUE' => $ar['EMAIL'], 'VALUE_TYPE' => 'WORK']];

        $subscriber[$bitrix24->getFieldCode('subscriptionName')][$bitrix24->getSubscriptionId('megre.ru')] = $bitrix24->getSubscriptionId('megre.ru');
        $subscriber[$bitrix24->getFieldCode('subscriptionLanguage')][$bitrix24->getSubscriptionLanguageId('russian')] = $bitrix24->getSubscriptionLanguageId('russian');

        $contact->export($subscriber);


        /*$emailTemplateId = null;

        if (
            isset($subscriber[$bitrix24->languageFieldCode][$bitrix24->russian]) &&
            !empty($subscriber['NAME']) &&
            $subscriber['NAME'] !== 'Анонимный'
        ) {
            $emailTemplateId = 123;
        } else if (
            isset($subscriber[$bitrix24->languageFieldCode][$bitrix24->russian]) &&
            (
                empty($subscriber['NAME'])
                ||
                $subscriber['NAME'] === 'Анонимный'
            )
        ) {
            $emailTemplateId = 125;
        } else if (
            isset($subscriber[$bitrix24->languageFieldCode][$bitrix24->english]) &&
            !empty($subscriber['NAME']) &&
            $subscriber['NAME'] !== 'Анонимный'
        ) {
            $emailTemplateId = 124;
        } else if (
            isset($subscriber[$bitrix24->languageFieldCode][$bitrix24->english]) &&
            (
                empty($subscriber['NAME'])
                ||
                $subscriber['NAME'] === 'Анонимный'
            )
        ) {
            $emailTemplateId = 126;
        }

        if ($emailTemplateId > 0) {
            \CEvent::SendImmediate('SUBSCRIBE_WELCOME', 's1', [
                'EMAIL_TO' => $ar['EMAIL'],
                'USER_NAME' => $subscriber['NAME'],
            ], 'Y', $emailTemplateId);
        }*/


        $log[] = $ar['EMAIL'];
        $log = json_encode($log);
        \CEventLog::add([
            'MODULE_ID' => 'native.app',
            'DESCRIPTION' => $log,
            'AUDIT_TYPE_ID' => 'Экспорт: Битрикс24',
            'ITEM_ID' => 'Подписчики'
        ]);
    }
}
