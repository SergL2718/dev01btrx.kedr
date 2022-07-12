<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Zk\Main\Search;


use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Application;
use Bitrix\Main\Db\SqlQueryException;
use Zk\Main\Helper;

class EventHandler
{
    /**
     * Часто бывает, что на сайт выгружаются каталоги товаров, и например,
     * в анонсе или детальном представлении какого-то товара дублируется информация.
     * Штатно модуль поиска всё честно добавляет в поисковый индекс, не обращая информации на дубли.
     * Исправляем
     *
     * @param $arFields
     * @return mixed
     * @throws SqlQueryException
     */
    public function BeforeIndex($arFields)
    {
        //ID инфоблоков, для которых производить модификацию
        $iBlocks = [
            Helper::IBLOCK_SHOP_ID,
            Helper::IBLOCK_OFFERS_SHOP_ID
        ];

        if ($arFields['MODULE_ID'] === 'iblock' && in_array($arFields['PARAM2'], $iBlocks) && intval($arFields['ITEM_ID']) > 0) {
            // Разделы товары которых исключаем из индекса и поиска в целом
            $excluded = [
                1230
            ];
            $element = \CIBlockElement::GetList([], ['=ID' => $arFields['ITEM_ID']], false, false, ['PROPERTY_HIDDEN', 'PROPERTY_DUPLICATED', 'IBLOCK_SECTION_ID'])->Fetch();

            // Доработка функционала
            // Скрываем товар из каталога
            // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/10803/
            // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/21639/
            if (
                in_array($element['IBLOCK_SECTION_ID'], $excluded) ||
                $element['PROPERTY_HIDDEN_ENUM_ID'] > 0 ||
                !empty($element['PROPERTY_DUPLICATED_VALUE'])
            ) {
                $arFields['BODY'] = $arFields['TITLE'] = '';
                return $arFields;
            }

            if ($arFields['TAGS']) {
                $arFields['TITLE'] .= ' ' . $arFields['TAGS'];
            }
            if (mb_strpos($arFields['TITLE'], '-')) {
                $arFields['TITLE'] = str_replace('-', ' ', $arFields['TITLE']);
            }
            $arFields['BODY'] = $arFields['TITLE'];
        }
        return $arFields;
    }
}
