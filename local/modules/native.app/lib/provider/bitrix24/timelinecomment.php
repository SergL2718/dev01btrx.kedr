<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Native\App\Provider\Bitrix24;


use Native\App\Foundation\Bitrix24;

class TimelineComment
{
    private static $instance = null;

    private $bitrix24 = false; // объект Битрикс24
    private $userBitrix24 = false; // пользователь под которым выполняются действия в Битрикс24

    /**
     * Добавить комментарий в живую ленту сущности
     * @param $entityType
     * @param $entityId
     * @param $comment
     * @return bool
     */
    public function add($entityType, $entityId, $comment)
    {
        return $this->getBitrix24()->request('crm.timeline.comment.add', [
            'fields' => [
                'ENTITY_ID' => $entityId,
                'ENTITY_TYPE' => $entityType,
                'COMMENT' => $comment,
            ]
        ]);
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
