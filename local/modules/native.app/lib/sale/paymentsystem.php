<?php
/*
 * Изменено: 30 июня 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

namespace Native\App\Sale;


use Bitrix\Main\Application;

class PaymentSystem
{
    private static ?PaymentSystem $instance = null;
    private static array $paySystemId = [];
    private static array $paySystemCode = [];

    const BILL_CODE = 'bill';
    const CARD_CODE = 'cards';
    const CARD_FAKE_CODE = 'cards-fake';
    const IN_STORE = 'in-store';

    private static array $services = [
        self::IN_STORE => [
            'restriction' => [
                'country' => [
                    'RU' => [
                        'access' => true,
                    ]
                ],
                'city' => [
                    'новосибирск' => true,
                    'бердск' => true,
                ],
                'delivery' => [
                    DeliverySystem::PICKUP_NSK
                ]
            ]
        ]
    ];

    public function getServiceByCode(string $code)
    {
        return self::$services[$code] ?? false;
    }

    /**
     * Возвращает ID платежной системы по ее коду
     * @param $code
     * @return integer|null
     */
    public function getIdByCode($code): ?int
    {
        return self::$paySystemId[$code] ?? null;
    }

    /**
     * Возвращает код платежной системы по ее ID
     * @param $id
     * @return string|bool
     */
    public function getCodeById($id)
    {
        return self::$paySystemCode[$id] ?? false;
    }

    public static function getInstance(): ?PaymentSystem
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
            $rows = Application::getConnection()->query('select ID, CODE from b_sale_pay_system_action where CODE!=""');
            while ($row = $rows->fetch()) {
                self::$paySystemId[$row['CODE']] = $row['ID'];
                self::$paySystemCode[$row['ID']] = $row['CODE'];
                if (isset(self::$services[$row['CODE']])) {
                    self::$services[$row['CODE']]['ID'] = $row['ID'];
                    self::$services[$row['CODE']]['CODE'] = $row['CODE'];
                }
            }
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
