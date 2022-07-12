<?php
/**
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @updated 24.06.2020, 14:41
 * @copyright 2011-2020
 */

/**
 * @var $APPLICATION
 * @var $USER
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die;

// https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/2021/
// Проверим наличие товаров исключенных из скидок корзины
// Если такие товары имеются, тогда вернем их в корзину и очистим временную таблицу
$fUserId = \Bitrix\Sale\Fuser::getId();
$products = \Bitrix\Main\Application::getConnection()->query('select ID, PRODUCT_ID, QUANTITY from app_product_excluded_from_discount where FUSER_ID="' . $fUserId . '"');
if ($products) {
    $reloadBasket = false;
    // Если находимся на главной странице или странице каталога
    // Тогда вернем товары в корзину - так как на данных страницах имеются товарные блоки
    // Иначе, лишь визуально увеличим количество товаров
    if (
        $APPLICATION->GetCurPage(true) === SITE_DIR . 'index.php' ||
        mb_strpos($_SERVER['REQUEST_URI'], 'catalog/') !== false
    ) {
        while ($ar = $products->fetchRaw()) {
            $result = Bitrix\Catalog\Product\Basket::addProduct([
                'PRODUCT_ID' => $ar['PRODUCT_ID'],
                'QUANTITY' => $ar['QUANTITY'],
            ]);
            if ($result->isSuccess()) {
                \Bitrix\Main\Application::getConnection()->queryExecute('delete from app_product_excluded_from_discount where ID="' . $ar['ID'] . '" limit 1');
                $reloadBasket = true;
            }
        }
    } else {
        for ($i = 0; $i < $products->getSelectedRowsCount(); $i++) {
            $arResult['BASKET_ITEMS']['CAN_BUY'][] = [];
        }
    }

    if ($reloadBasket) LocalRedirect($_SERVER['REQUEST_URI']);
}
