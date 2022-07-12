<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Zk\Main\Catalog;


class Product
{
    // Перенесем вес для торгового предложения из Свойства в свойство торгового каталога
    public function setWeight($id, &$arFields)
    {
        if ($weight = \CIBlockElement::GetProperty(\CIBlockElement::GetByID($id)->fetch()['IBLOCK_ID'], $id, [], ['CODE' => 'VES_TORGOVOGO_PREDLOZHENIYA'])->fetch()['VALUE_ENUM']) {
            $arFields['WEIGHT'] = $weight * 1000;
        }
    }

    /**
     * Если НДС не равно Без НДС
     * Тогда установим значение Без НДС
     *
     * @param $id
     * @param $arFields
     */
    public function checkVat($id, &$arFields)
    {
        // 1 - Без НДС
        $arFields['VAT_ID'] = 1;
    }
}
