<?php
/*
 * Изменено: 12 июля 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

namespace Native\App\Foundation;


class Bitrix24
{
    private string $url = 'https://megre.bitrix24.ru/rest';
    //private $userName = 'База данных';
    private string $userName = 'Администратор Интернет-магазина';

    private array $fieldCodes = [
        'subscriptionName' => 'UF_CRM_1568308300', // Название рассылки
        'subscriptionLanguage' => 'UF_CRM_1568308511', // Язык подписки
        'paySystemName' => 'UF_CRM_1594048224', // Платежная система
        'deliveryName' => 'UF_CRM_1594048238', // Служба доставки
        'coupon' => 'UF_CRM_1594048260', // Примененный промокод
        'discountPercent' => 'UF_CRM_1594048287', // Размер скидки %
        'trackingNumber' => 'UF_CRM_1594203844', // Номер отслеживания посылки
        'wePaidForDelivery' => 'UF_CRM_1596214891', // Мы оплатили за доставку
        'dateReceiptOrder' => 'UF_CRM_1596195607438', // Дата получения заказа
    ];

    private array $userId = [
        'Нина Мегре' => 21,
        'База данных' => 8105,
        'Администратор Интернет-магазина' => 10553,
    ];

    private array $secretKey = [
        //'База данных' => 'ks0gi2rg3x603h39', // Доступ только для megre.ru
        'База данных' => 'n24toejeqnjoq88j', // Доступ только для megre.ru
        'Администратор Интернет-магазина' => 'mek68vg6t6ca7yn8', // Доступ только для megre.ru
    ];

    private array $entityTypeId = [
        'contact' => 3,
        'requisite' => 8,
        'requisitePhysical' => 5,
    ];

    private array $entityPrefix = [
        'deal' => 'MEGRERU #'
    ];

    private array $contactTypeId = [
        'subscriber' => 1
    ];

    private array $categoryId = [
        'internet' => 'C5'
    ];

    private array $sourceId = [
        'megre.ru' => 'WEB'
    ];

    private array $addressTypeId = [
        'residential' => 1, // Фактический адрес,
        'delivery' => 11, // Адрес доставки
    ];

    private array $subscriptionId = [
        'megre.ru' => 1967
    ];

    private array $subscriptionLanguageId = [
        'russian' => 75
    ];

    public function contactList($request)
    {
        return Request::getInstance()->send($this->url())->post('crm.contact.list', $request);
    }

    public function getContactById($id)
    {
        return Request::getInstance()->send($this->url())->post('crm.contact.get', ['id' => $id]);
    }

    /**
     * @param $email
     * @return false|mixed
     * @deprecated since 2020-08-16
     */
    public function getContactByEmail($email)
    {
        $contact = false;
        $contactId = Request::getInstance()->send($this->url())->post('crm.duplicate.findbycomm', [
            'entity_type' => 'CONTACT',
            'type' => 'EMAIL',
            'values' => [$email]
        ])['CONTACT'][0];
        if ($contactId) {
            $contact = $this->getContactById($contactId);
        }
        return $contact;
    }

    /**
     * @param $email
     * @return false|mixed
     * @deprecated since 2020-08-16
     */
    public function getContactIdByEmail($email)
    {
        if ($id = Request::getInstance()->send($this->url())->post('crm.duplicate.findbycomm', [
            'entity_type' => 'CONTACT',
            'type' => 'EMAIL',
            'values' => [$email]
        ])['CONTACT'][0]) {
            return $id;
        }
        return false;
    }

    /**
     * @param $request
     * @return false|mixed
     * @deprecated since 2020-08-16
     */
    public function exportContact($request)
    {
        if (count($request) === 0) return false;
        $request['params']['REGISTER_SONET_EVENT'] = 'N';
        $request['fields']['OPENED'] = 'N';
        $request['fields']['EXPORT'] = 'N';
        $request['fields']['TYPE_ID'] = $this->getContactTypeId('subscriber');
        $request['fields']['SOURCE_ID'] = $request['SOURCE_ID'] ? $request['SOURCE_ID'] : $this->getSourceId('megre.ru');
        $request['fields']['ASSIGNED_BY_ID'] = $this->getUserId($this->userName);
        $request['fields'] = array_merge($request['fields'], $request);

        return Request::getInstance()->send($this->url())->post('crm.contact.add', $request);
    }

    /**
     * @param $id
     * @param $request
     * @return false|mixed
     * @deprecated since 2020-08-16
     */
    public function updateContact($id, $request)
    {
        if (count($request) === 0) return false;
        $request['params']['REGISTER_SONET_EVENT'] = 'N';
        $request['id'] = $id;
        $request['fields'] = $request;
        return Request::getInstance()->send($this->url())->post('crm.contact.update', $request);
    }

    public function deleteContactById($id)
    {
        return Request::getInstance()->send($this->url())->post('crm.contact.delete', ['id' => $id]);
    }

    // CHECK

    public function checkContactByEmail($email): bool
    {
        $exist = Request::getInstance()->send($this->url())->post('crm.duplicate.findbycomm', [
            'entity_type' => 'CONTACT',
            'type' => 'EMAIL',
            'values' => [$email]
        ]);
        return (bool)$exist['CONTACT'];
    }

    public function checkContactByPhone($phone): bool
    {
        $exist = Request::getInstance()->send($this->url())->post('crm.duplicate.findbycomm', [
            'entity_type' => 'CONTACT',
            'type' => 'PHONE',
            'values' => [$phone]
        ]);
        return (bool)$exist['CONTACT'];
    }

    // ADDRESS

    public function getContactAddressListById($contactId)
    {
        $request = [
            'filter' => [
                'ENTITY_ID' => $contactId,
                'ANCHOR_ID' => $contactId,
                'ANCHOR_TYPE_ID' => $this->getEntityTypeId('contact'),
                'ENTITY_TYPE_ID' => $this->getEntityTypeId('contact'),
            ],
            'order' => [
                'ENTITY_TYPE_ID' => 'ASC'
            ],
            'select' => [
                'ENTITY_ID',
                'ENTITY_TYPE_ID',
                'ADDRESS_1',
                'ADDRESS_2',
                'CITY',
                'POSTAL_CODE',
                'REGION',
                'PROVINCE',
                'COUNTRY',
                'COUNTRY_CODE',
            ]
        ];
        $data = Request::getInstance()->send($this->url())->post('crm.address.list', $request);
        if (count($data) > 0) {
            foreach ($data as $key => $address) {
                if ($address['ENTITY_TYPE_ID'] != $this->getEntityTypeId('requisite')) {
                    unset($data[$key]);
                }
            }
        }
        return $data;
    }

    public function addContactAddress(int $contactId, array $address, array $requisite = ['NAME' => 'Физ. лицо'])
    {
        $request = [
            'fields' => [
                'ENTITY_TYPE_ID' => $this->getEntityTypeId('contact'),
                'ENTITY_ID' => $contactId,
                'PRESET_ID' => $this->getEntityTypeId('requisitePhysical'),
            ]
        ];
        $request['fields'] = array_merge($request['fields'], $requisite);
        $requisiteId = Request::getInstance()->send($this->url())->post('crm.requisite.add', $request);
        if ($requisiteId > 0) {
            $request['fields']['TYPE_ID'] = $this->getAddressTypeId('delivery');
            $request['fields']['ENTITY_TYPE_ID'] = $this->getEntityTypeId('requisite');
            $request['fields']['ENTITY_ID'] = $requisiteId;
            $request['fields']['ANCHOR_TYPE_ID'] = $this->getEntityTypeId('contact');
            $request['fields']['ANCHOR_ID'] = $contactId;
            $request['fields'] = array_merge($request['fields'], $address);
            return Request::getInstance()->send($this->url())->post('crm.address.add', $request);
        }
        return false;
    }

    // COMMON

    public function batch($request)
    {
        return Request::getInstance()->send($this->url())->post('batch', ['cmd' => $request]);
    }

    public function request($path, $request)
    {
        return Request::getInstance()->send($this->url())->post($path, $request);
    }

    public function url(): string
    {
        return $this->url . '/' . $this->getUserId($this->userName) . '/' . $this->getSecretKey();
    }

    /**
     * Метод возвращает ID пользователя в Битрикс24 по Имени
     * @param string $name
     * @return bool|int
     */
    public function getUserId(string $name = '')
    {
        if (!empty($name)) {
            return $this->userId[$name] ?? false;
        }
        return $this->userId[$this->userName];
    }

    /**
     * Метод возвращает пароль для пользователя под которым выполняется подключение к Битрикс24
     * @return bool|string
     */
    private function getSecretKey()
    {
        return $this->secretKey[$this->userName] ?? false;
    }

    /**
     * Метод возвращает код поля в Битрикс24 по коду
     * @param $code
     * @return bool|string
     */
    public function getFieldCode($code)
    {
        return $this->fieldCodes[$code] ?? false;
    }

    /**
     * Метод возвращает ID сущности в Битрикс24 по коду
     * @param $code
     * @return bool|int
     */
    public function getEntityTypeId($code)
    {
        return $this->entityTypeId[$code] ?? false;
    }

    /**
     * Метод возвращает префикс сущности в Битрикс24 по коду
     * @param $code
     * @return bool|string
     */
    public function getEntityPrefix($code)
    {
        return $this->entityPrefix[$code] ?? false;
    }

    /**
     * Метод возвращает ID типа контакта в Битрикс24 по коду
     * @param $code
     * @return bool|int
     */
    public function getContactTypeId($code)
    {
        return $this->contactTypeId[$code] ?? false;
    }

    /**
     * Метод возвращает ID категории (направления) сделки в Битрикс24 по коду
     * @param $code
     * @return bool|string
     */
    public function getCategoryId($code)
    {
        return $this->categoryId[$code] ?? false;
    }

    /**
     * Метод возвращает ID источника в Битрикс24 по коду
     * @param $code
     * @return bool|int
     */
    public function getSourceId($code)
    {
        return $this->sourceId[$code] ?? false;
    }

    /**
     * Метод возвращает ID типа адреса в Битрикс24 по коду
     * @param $code
     * @return bool|int
     */
    public function getAddressTypeId($code)
    {
        return $this->addressTypeId[$code] ?? false;
    }

    /**
     * Метод возвращает ID подписки в Битрикс24 по коду
     * @param $code
     * @return bool|int
     */
    public function getSubscriptionId($code)
    {
        return $this->subscriptionId[$code] ?? false;
    }

    /**
     * Метод возвращает ID языка подписки в Битрикс24 по коду
     * @param $code
     * @return bool|int
     */
    public function getSubscriptionLanguageId($code)
    {
        return $this->subscriptionLanguageId[$code] ?? false;
    }

    /**
     * Метод устанавливает пользователя под которым будет производиться подключение к Битрикс24
     * @param $name
     * @return bool
     */
    public function setUser($name): bool
    {
        if (!isset($this->userId[$name]) || !isset($this->secretKey[$name])) {
            return false;
        } else {
            $this->userName = $name;
            return true;
        }
    }
}
