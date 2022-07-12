<?php
/*
 * @updated 31.01.2021, 15:35
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2021, Компания Webco <hello@wbc.cx>
 * @link https://wbc.cx
 */

namespace Native\App\Agent;


use Native\App\Foundation\Agent;
use Native\App\Foundation\Bitrix24;
use Native\App\Provider\Bitrix24\Contact;

class UnsubscribeContactBitrix24 extends Agent
{
    public function __construct()
    {
        $this->function = '(new Native\App\Agent\UnsubscribeContactBitrix24())->run();';
    }

    public function run()
    {
        return false;

        $fileCsv = $_SERVER['DOCUMENT_ROOT'] . '/emails.csv';
        $emails = file_get_contents($fileCsv);
        $emails = explode("\n", $emails);
        $emails = array_diff($emails, ['']);
        $emails = array_unique($emails);

        if (count($emails) === 0) {
            unlink($fileCsv);
            $this->log([
                'DESCRIPTION' => 'Обработка завершена',
                'AUDIT_TYPE_ID' => 'Контакты: Битрикс24',
                'ITEM_ID' => 'Отписка e-mail-ов'
            ]);
            return false;
        }

        $bitrix24 = new Bitrix24();
        $batch = []; // массив запросов к порталу
        $counter = 0;
        $maxCounter = 50;

        $log = [];

        foreach ($emails as $index => $email) {
            if ($counter === $maxCounter) break;
            $request = [
                'filter' => [
                    'EMAIL' => $email,
                    //'!' . $bitrix24->getFieldCode('subscriptionName') => false,
                ],
                'select' => [
                    'ID',
                    //$bitrix24->getFieldCode('subscriptionName')
                ]
            ];
            $batch['index_' . $index] = 'crm.contact.list?' . http_build_query($request);
            $counter++;
        }

        $update = [];

        $response = $bitrix24->batch($batch);

        foreach ($response['result'] as $index => $item) {
            if (!isset($item[0])) continue;
            $index = str_replace('index_', '', $index);
            $item = $item[0];
            $id = $item['ID'];
            // Уберем подписки у контакта
            $item[$bitrix24->getFieldCode('subscriptionName')] = [false];
            unset($item['ID']);
            $request = [
                'id' => $id,
                'params' => ['REGISTER_SONET_EVENT' => 'N'],
                'fields' => $item
            ];
            $update['index_' . $index] = 'crm.contact.update?' . http_build_query($request);
            continue;
        }

        // Если имеются контакты на обновление
        if (count($update) > 0) {
            $response = $bitrix24->batch($update);
            foreach ($response['result'] as $index => $status) {
                if ($status == 1) {
                    $index = str_replace('index_', '', $index);
                    $email = $emails[$index];
                    $log[] = $email . ': ' . 'отписан от рассылок';
                    unset($emails[$index]);
                }
            }
        }

        //pr('crm.contact.update');
        //pr($response);

        $emails = implode("\n", $emails);

        //pr('$emails');
        //pr($emails);

        // Запишем обновленный массив адресов
        file_put_contents($fileCsv, $emails);

        if (count($log) > 0) {
            $log = implode('<br>', $log);
            $this->log([
                'DESCRIPTION' => $log,
                'AUDIT_TYPE_ID' => 'Контакты: Битрикс24',
                'ITEM_ID' => 'Отписка e-mail-ов'
            ]);
        }

        return $this->function;
    }
}
