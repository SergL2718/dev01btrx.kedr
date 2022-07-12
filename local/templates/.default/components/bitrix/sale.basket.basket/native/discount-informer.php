<?php
/*
 * Изменено: 03 ноября 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

return;
$amountFormatted = CCurrencyLang::CurrencyFormat($arResult['DISCOUNT']['NEXT']['VALUE'] - $arResult['AMOUNT']['BASE']['VALUE'], $arResult['CURRENCY']['VALUE'], true);
?>

<div class="discount-informer mb-5"
     <?php if ($arResult['DISCOUNT']['NEXT']['VALUE'] === 0 ): ?>style="display: none;" <?php endif ?>>
    До скидки <b class="discount-informer-percent"><?= $arResult['DISCOUNT']['NEXT']['PERCENT']['FORMATTED'] ?></b> Вам не хватает суммы: <b
            class="discount-informer-amount"><?= $amountFormatted ?></b>
</div>
