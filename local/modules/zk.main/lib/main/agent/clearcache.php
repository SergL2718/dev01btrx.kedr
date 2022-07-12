<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Zk\Main\Main\Agent;


use Bitrix\Main\Loader;
use CIBlock;
use CIBlockElement;

class ClearCache extends Base
{
    private $iblockId = 19;

    public function __construct()
    {
        $this->function = '(new \Zk\Main\Main\Agent\ClearCache)->run();';
    }

    /**
     * Имеются проблемы с очисткой кэша инфоблока с баннерами
     * Кэш не хочет очищаться
     * Реализуем ручную проверку и очистку кэша для инфоблока
     *
     * @return string
     */
    public function run()
    {
        // Возникает какая-то нездоровая ситуация
        // Массово обновляются элементы инфоблоков
        // В связи с этим, на данный момент (12.11.20) текущую функцию отключаю
        // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/8203/
        // Плюс, у Битрикса, в Настройках Инфоблоков уже появилась галочка, для принудительной очистки кеша нужно инфоблока
        return false;


        $needClear = false;
        Loader::includeModule('iblock');
        $arFilter = [
            'IBLOCK_ID' => $this->iblockId,
            'ACTIVE' => 'Y',
            '!DATE_ACTIVE_TO' => false
        ];
        $arSelect = [
            'ID',
            'DATE_ACTIVE_TO',
        ];
        $res = \CIBlockElement::GetList(['DATE_ACTIVE_TO' => 'ASC'], $arFilter, false, false, $arSelect);
        while ($ar = $res->fetch()) {
            $time = strtotime($ar['DATE_ACTIVE_TO']);
            if ($time < (time() + 100)) {
                // Отключаем устаревшие баннеры
                $this->disable($ar['ID']);
                $needClear = true;
            }
        }
        if ($needClear === true) {
            $this->clearCache();
        }
        return $this->function;
    }

    private function disable($id)
    {
        // Возникает какая-то нездоровая ситуация
        // Массово обновляются элементы инфоблоков
        // В связи с этим, на данный момент (12.11.20) текущую функцию отключаю
        // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/8203/
        return true;

        (new CIBlockElement)->Update($id, ['ACTIVE' => 'N']);
    }

    private function clearCache()
    {
        // >= iblock 15.0.7
        if (method_exists('\CIBlock', 'clearIblockTagCache')) {
            \CIBlock::enableClearTagCache();
            \CIBlock::clearIblockTagCache($this->iblockId);
            \CIBlock::DisableClearTagCache();
        } else {
            BXClearCache(true);
            (new \Bitrix\Main\Data\ManagedCache())->cleanAll();
            (new \CStackCacheManager())->CleanAll();
        }

        /*if (
            method_exists('\CHTMLPagesCache', 'IsCompositeEnabled')
            && \CHTMLPagesCache::IsCompositeEnabled()
        ) {
            \CHTMLPagesCache::CleanAll();
        }*/
    }
}
