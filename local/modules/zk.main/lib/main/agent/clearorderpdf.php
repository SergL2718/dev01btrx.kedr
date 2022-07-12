<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Zk\Main\Main\Agent;


use Zk\Main\Sale\Bill;

/**
 * Class ClearOrderPdf
 * @package Zk\Main\Main\Agent
 * @deprecated since 2020-07-15
 */
class ClearOrderPdf extends Base
{
    public function __construct()
    {
        $this->function = '(new \Zk\Main\Main\Agent\ClearOrderPdf())->run();';
    }

    /**
     * Очищаем сформированные PDF-файлы со счетами
     * Которые были сформированы и отправлены на электронные адреса покупателей
     *
     * @return string
     */
    public function run()
    {
        return false;
        $bill = new Bill();
        $path = $bill->getPath();

        $need = ['image', 'pdf'];

        foreach ($need as $dir) {
            $files = array_diff(scandir($path . '/' . $dir), ['.', '..']);
            foreach ($files as $file) {
                $file = $path . '/' . $dir . '/' . $file;
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }

        $this->log('CLEAR', 'PDF', 'Все файлы по заказам удалены');
        return $this->function;
    }
}

