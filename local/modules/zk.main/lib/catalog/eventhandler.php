<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Zk\Main\Catalog;


class EventHandler
{
    public function OnBeforeProductUpdate($id, &$arFields)
    {
        $product = new Product();

        // Перенесем вес для торгового предложения из Свойства в свойство торгового каталога
        $product->setWeight($id, $arFields);

        // Если НДС не равно Без НДС
        // Тогда установим значение Без НДС
        $product->checkVat($id, $arFields);
    }
}
