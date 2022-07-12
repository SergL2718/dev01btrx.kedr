<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

use Bitrix\Main\Page\Asset;

class WindowPopup extends CBitrixComponent
{
    private $cacheTime = 31536000;

    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public function executeComponent()
    {
        $this->getParams();
        if ($this->StartResultCache($this->cacheTime, __CLASS__, $this->getCacheDir())) {
            $this->IncludeComponentTemplate();
        }
    }

    private function getParams()
    {
        $this->arParams['CACHE_TIME'] = $this->cacheTime;
    }

    private function getCacheDir()
    {
        return SITE_ID . '/' . __CLASS__;
    }
}