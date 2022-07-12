<?php
/*
 * Изменено: 30 июня 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

namespace Native\App\Sale;


use Bitrix\Main\Application;
use Bitrix\Main\IO\File;
use Bitrix\Main\Loader;
use Bitrix\Sale\PaySystem\BaseServiceHandler;
use Bitrix\Sale\PaySystem\Manager;

class Document
{
    private string $directory = '/upload/tmp';
    private $order;
    private string $orderAccountNumber;
    private string $template;
    private string $filePath;

    public function __construct()
    {
        Loader::IncludeModule('sale');
        $this->directory = $_SERVER['DOCUMENT_ROOT'] . $this->directory;
    }

    private function create(): bool
    {
        if ($this->orderAccountNumber === null && $this->order === null) {
            return false;
        }
        $payment = $this->order->getPaymentCollection()[0];
        if ($payment->getField('PAY_SYSTEM_ID') != PaymentSystem::getInstance()->getIdByCode(PaymentSystem::BILL_CODE)) {
            return false;
        }
        $paySystem = Manager::getObjectById($payment->getField('PAY_SYSTEM_ID'));
        $context = Application::getInstance()->getContext();
        $_REQUEST['pdf'] = $_REQUEST['GET_CONTENT'] = 'Y';
        $r = $paySystem->initiatePay($payment, $context->getRequest(), BaseServiceHandler::STRING);
        if ($r->isSuccess()) {
            $this->template = $r->getTemplate();
            return true;
        }
        return false;
    }

    public function pdf($orderAccountNumber = null, $order = null)
    {
        if ($orderAccountNumber === null && $order === null) {
            return false;
        }
        if ($orderAccountNumber !== null && $order === null) {
            $order = \Bitrix\Sale\Order::loadByAccountNumber($orderAccountNumber);
        } else if ($orderAccountNumber === null && $order !== null) {
            $orderAccountNumber = $order->getField('ACCOUNT_NUMBER');
        }
        if ($orderAccountNumber !== null) {
            $this->orderAccountNumber = $orderAccountNumber;
        }
        if ($order !== null) {
            $this->order = $order;
        }
        if (!$this->create()) {
            return false;
        }
        $this->filePath = $this->directory . '/' . $this->orderAccountNumber . '.pdf';
        File::putFileContents($this->filePath, $this->template);
        return $this->filePath;
    }

    public function convertToJpg()
    {
        if (!$this->filePath) {
            return false;
        }
        $image = $this->directory . '/' . $this->orderAccountNumber . '.jpg';
        exec('convert -density 270 "' . $this->filePath . '" "' . $image . '"');
        $arFile = \CFile::MakeFileArray($image);
        $fileId = \CFile::SaveFile($arFile, 'tmp');
        File::deleteFile($this->filePath);
        File::deleteFile($image);
        return $fileId;
    }
}
