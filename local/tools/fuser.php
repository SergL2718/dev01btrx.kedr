<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

 
 
// {{{ settings
$limitPerStep = 300;
 
$dayAfterDelete = COption::GetOptionString('sale', 'delete_after', 30);
 
$fileLog = $_SERVER['DOCUMENT_ROOT'] . '/~clear_old_basket.log';
// }}}
 
 
// {{{ logic
if (intval($dayAfterDelete) <= 0) {
    $dayAfterDelete = 30;
}
 
global $DB;
 
@set_time_limit(0);
 
@ignore_user_abort(true);
 
@ini_set('memory_limit', '4G');
 
$c = 0;
 
$count = 0;
 
@file_put_contents($fileLog, PHP_EOL . "Counting the total number of old baskets.." . PHP_EOL);
 
$countIterator = $DB->Query(
    sprintf(
        "SELECT COUNT(f.ID) as TOTAL
        FROM b_sale_fuser f
        LEFT JOIN b_sale_order o ON (o.USER_ID = f.USER_ID)
        WHERE
            TO_DAYS(f.DATE_UPDATE)<(TO_DAYS(NOW())-%s)
            AND f.USER_ID is null
            AND o.ID is null",
        $dayAfterDelete
    )
);
 
if ($countData = $countIterator->Fetch()) {
    $count = $countData['TOTAL'];
}
 
if ($count > 0) {
 
    for ($i = 0, $l = ceil($count / $limitPerStep); $i <= $l; $i++) {
 
        $res = $DB->Query(
            sprintf(
                "SELECT f.ID
                FROM b_sale_fuser f
                LEFT JOIN b_sale_order o ON (o.USER_ID = f.USER_ID)
                WHERE
                    TO_DAYS(f.DATE_UPDATE)<(TO_DAYS(NOW())-%s)
                    AND f.USER_ID is null
                    AND o.ID is null
                LIMIT %s",
                    $dayAfterDelete,
                    $limitPerStep
            )
        );
 
        while ($ar = $res->Fetch()) {
 
            $resB = $DB->Query(
                sprintf(
                    "SELECT b.ID FROM `b_sale_basket` b WHERE b.FUSER_ID = '%s' and b.ORDER_ID IS NULL;", $ar['ID']
                )
            );
 
            while ($arB = $resB->Fetch()) {
                $DB->Query(sprintf("DELETE FROM b_sale_basket_props WHERE BASKET_ID = '%s'", $arB['ID']), true);
                $DB->Query(sprintf("DELETE FROM b_sale_store_barcode WHERE BASKET_ID = '%s'", $arB['ID']), true);
                $DB->Query(sprintf("DELETE FROM b_sale_basket WHERE ID = '%s'", $arB['ID']), true);
            }
 
            $DB->Query(sprintf("DELETE FROM b_sale_fuser WHERE ID = '%s'", $ar['ID']), true);
 
        }
 
        $c += $limitPerStep;
 
        @file_put_contents(
            $fileLog,
            sprintf(
                PHP_EOL . "Deleted baskets %s of %s" . PHP_EOL,
                    $c,
                    $count
            )
        );
    }
 
} else {
    @file_put_contents(
        $fileLog,
        sprintf(
            PHP_EOL . "Old baskets not found" . PHP_EOL
        )
    );
}



require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");