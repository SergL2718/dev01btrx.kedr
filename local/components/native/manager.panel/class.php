<?php
/**
 * Copyright (c) 2019 Denis Artamonov
 * Created: 3/20/19 5:01 PM
 * Author: Denis Artamonov
 * Email: artamonov.ceo@gmail.com
 */

use Bitrix\Main\Engine\Contract\Controllerable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class ManagerPanel extends CBitrixComponent implements Controllerable
{
    public function executeComponent()
    {
        if (!$GLOBALS['USER']->isAdmin() && !CSite::InGroup([
                6 // Администраторы интернет-магазина
            ])) return;
        $this->setFrameMode(false);
        $this->includeComponentTemplate();
    }

    public function configureActions()
    {
        return [];
    }
}
