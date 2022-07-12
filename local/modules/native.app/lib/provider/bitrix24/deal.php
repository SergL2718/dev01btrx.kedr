<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Native\App\Provider\Bitrix24;


use Native\App\Foundation\Bitrix24;

class Deal
{
    private static ?Deal $instance = null;

    private $bitrix24 = false; // объект Битрикс24
    private $userBitrix24 = false; // пользователь под которым выполняются действия в Битрикс24
    private $id = null; // ID сделки в Битрикс24
    private $number = false; // номер сделки в Битрикс24

    /**
     * Получить данные сделки по ID
     * @param $id
     * @return array|bool
     */
    public function getById($id)
    {
        return $this->getBitrix24()->request('crm.deal.get', ['id' => $id]);
    }

    /**
     * Возвращает ID сделки Битрикс24 по номеру заказа
     * @param $orderNumber
     * @return null|int
     */
    public function getIdByOrderNumber($orderNumber)
    {
        if ($this->id === null) {
            if (strlen($orderNumber) > 0) {
                $response = $this->getBitrix24()->request('crm.deal.list', [
                    'filter' => [
                        'TITLE' => $this->getNumberByOrderNumber($orderNumber)
                    ],
                    'select' => ['ID']
                ]);
                if ($response) {
                    $this->id = $response[0]['ID'];
                }
            }
        }
        return $this->id;
    }

    /**
     * Получить номер сделки в Битрикс24 по номеру заказа
     * @param $orderNumber
     * @return string
     */
    public function getNumberByOrderNumber($orderNumber): string
    {
        return $this->number = $this->getBitrix24()->getEntityPrefix('deal') . $orderNumber;
    }

    /**
     * Обновить сделку по ID
     * @param $id
     * @param $fields
     * @param $comment
     * @return bool|mixed
     */
    public function update($id, $fields, $comment = '')
    {
        if (count($fields) === 0) return false;
        $response = $this->getBitrix24()->request('crm.deal.update', ['id' => $id, 'params' => ['REGISTER_SONET_EVENT' => 'N'], 'fields' => $fields]);
        if ($response && $comment) {
            TimelineComment::getInstance()->add('deal', $id, $comment);
        }
        return $response;
    }

    private function getBitrix24()
    {
        if ($this->bitrix24 === false) {
            $this->bitrix24 = new Bitrix24();
            if ($this->userBitrix24 !== false) {
                $this->bitrix24->setUser($this->userBitrix24);
            }
        }
        return $this->bitrix24;
    }

    /**
     * Метод устанавливает пользователя под которым будет производиться подключение к Битрикс24
     * @param $name
     */
    public function setUser($name)
    {
        $this->userBitrix24 = $name;
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
