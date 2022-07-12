<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Native\App\Provider\Bitrix24;


use Native\App\Foundation\Bitrix24;

class Contact
{
    private static ?Contact $instance = null;

    private $bitrix24 = false; // объект Битрикс24

    /**
     * https://dev.1c-bitrix.ru/rest_help/crm/contacts/crm_contact_list.php
     * @param $params
     * @return mixed
     */
    public function getList($params)
    {
        return $this->getBitrix24()->request('crm.contact.list', $params);
    }

    /**
     * Получить данные по E-mail
     * @param $email
     * @return array|bool
     */
    public function getByEmail($email)
    {
        $contact = false;
        $contactId = $this->getBitrix24()->request('crm.duplicate.findbycomm', [
            'entity_type' => 'CONTACT',
            'type' => 'EMAIL',
            'values' => [$email]
        ])['CONTACT'][0];
        if ($contactId) {
            $contact = $this->getById($contactId);
        }
        return $contact;
    }

    /**
     * Получить данные по ID
     * @param $id
     * @return array|bool
     */
    public function getById($id)
    {
        return $this->getBitrix24()->request('crm.contact.get', ['id' => $id]);
    }

    public function update($id, $request, $comment = '')
    {
        if (count($request) === 0) return false;
        $request['params']['REGISTER_SONET_EVENT'] = 'N';
        $request['id'] = $id;
        $request['fields'] = $request;
        $response = $this->getBitrix24()->request('crm.contact.update', $request);
        if ($response && $comment) {
            TimelineComment::getInstance()->add('contact', $id, $comment);
        }
        return $this->getBitrix24()->request('crm.contact.update', $request);
    }

    public function delete($id)
    {
        return $this->getBitrix24()->request('crm.contact.delete', ['id' => $id]);
    }

    public function export($request)
    {
        if (count($request) === 0) return false;
        $request['params']['REGISTER_SONET_EVENT'] = 'N';
        $request['fields']['OPENED'] = 'Y';
        $request['fields']['EXPORT'] = 'Y';
        $request['fields']['TYPE_ID'] = $this->getBitrix24()->getContactTypeId('subscriber');
        $request['fields']['SOURCE_ID'] = $request['SOURCE_ID'] ? $request['SOURCE_ID'] : $this->getBitrix24()->getSourceId('megre.ru');
        $request['fields']['ASSIGNED_BY_ID'] = $this->getBitrix24()->getUserId();
        $request['fields'] = array_merge($request['fields'], $request);
        return $this->getBitrix24()->request('crm.contact.add', $request);
    }

    private function getBitrix24()
    {
        if ($this->bitrix24 === false) {
            $this->bitrix24 = new Bitrix24();
        }
        return $this->bitrix24;
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
