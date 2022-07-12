<?
/*
 * @updated 09.12.2020, 18:54
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

/**
 * @var CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die;

use Bitrix\Main\Localization\Loc;

if (count($arResult['ORDERS']) === 0) {
    ?>
    <h2 style="color: #8fbb3c;">
        Ваша корзина пуста. Перейдите в <a href="/catalog/" style="text-decoration: underline">каталог</a> и добавьте товары в корзину.
    </h2>
    <?php
    return;
}
?>

<h2 style="color: #8fbb3c;" class="mb-5">
    У вас есть неоплаченный заказ. Оплатите его или добавьте новые товары в корзину.
</h2>

<?php
$paymentChangeData = array();

foreach ($arResult['ORDERS'] as $key => $order) {
    ?>
    <div class="col-md-12 col-sm-12 sale-order-list-container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 sale-order-list-title-container">
                <h2 class="sale-order-list-title">
                    <?= Loc::getMessage('SPOL_TPL_ORDER') ?>
                    <?= Loc::getMessage('SPOL_TPL_NUMBER_SIGN') . $order['ORDER']['ACCOUNT_NUMBER'] ?>
                    <?= Loc::getMessage('SPOL_TPL_FROM_DATE') ?>
                    <?= $order['ORDER']['DATE_INSERT']->format($arParams['ACTIVE_DATE_FORMAT']) ?>,
                    <?= count($order['BASKET_ITEMS']); ?>
                    <?
                    $count = count($order['BASKET_ITEMS']) % 10;
                    if ($count == '1') {
                        echo Loc::getMessage('SPOL_TPL_GOOD');
                    } elseif ($count >= '2' && $count <= '4') {
                        echo Loc::getMessage('SPOL_TPL_TWO_GOODS');
                    } else {
                        echo Loc::getMessage('SPOL_TPL_GOODS');
                    }
                    ?>
                    <?= Loc::getMessage('SPOL_TPL_SUMOF') ?>
                    <?= $order['ORDER']['FORMATED_PRICE'] ?>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 sale-order-list-inner-container">
						<span class="sale-order-list-inner-title-line">
							<span class="sale-order-list-inner-title-line-item"><?= Loc::getMessage('SPOL_TPL_PAYMENT') ?></span>
							<span class="sale-order-list-inner-title-line-border"></span>
						</span>
                <?
                $showDelimeter = false;
                foreach ($order['PAYMENT'] as $payment) {
                    if ($order['ORDER']['LOCK_CHANGE_PAYSYSTEM'] !== 'Y') {
                        $paymentChangeData[$payment['ACCOUNT_NUMBER']] = array(
                            "order" => htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER']),
                            "payment" => htmlspecialcharsbx($payment['ACCOUNT_NUMBER']),
                            "allow_inner" => $arParams['ALLOW_INNER'],
                            "refresh_prices" => $arParams['REFRESH_PRICES'],
                            "path_to_payment" => $arParams['PATH_TO_PAYMENT'],
                            "only_inner_full" => $arParams['ONLY_INNER_FULL']
                        );
                    }
                    ?>
                    <div class="row sale-order-list-inner-row">
                        <?
                        if ($showDelimeter) {
                            ?>
                            <div class="sale-order-list-top-border"></div>
                            <?
                        } else {
                            $showDelimeter = true;
                        }
                        ?>

                        <div class="sale-order-list-inner-row-body">
                            <div class="col-md-9 col-sm-8 col-xs-12 sale-order-list-payment">
                                <div class="sale-order-list-payment-title">
                                    <?
                                    $paymentSubTitle = Loc::getMessage('SPOL_TPL_BILL') . " " . Loc::getMessage('SPOL_TPL_NUMBER_SIGN') . htmlspecialcharsbx($payment['ACCOUNT_NUMBER']);
                                    if (isset($payment['DATE_BILL'])) {
                                        $paymentSubTitle .= " " . Loc::getMessage('SPOL_TPL_FROM_DATE') . " " . $payment['DATE_BILL']->format($arParams['ACTIVE_DATE_FORMAT']);
                                    }
                                    $paymentSubTitle .= ",";
                                    echo $paymentSubTitle;
                                    ?>
                                    <span class="sale-order-list-payment-title-element"><?= $payment['PAY_SYSTEM_NAME'] ?></span>
                                    <?
                                    if ($payment['PAID'] === 'Y') {
                                        ?>
                                        <span class="sale-order-list-status-success"><?= Loc::getMessage('SPOL_TPL_PAID') ?></span>
                                        <?
                                    } elseif ($order['ORDER']['IS_ALLOW_PAY'] == 'N') {
                                        ?>
                                        <span class="sale-order-list-status-restricted"><?= Loc::getMessage('SPOL_TPL_RESTRICTED_PAID') ?></span>
                                        <?
                                    } else {
                                        ?>
                                        <span class="sale-order-list-status-alert"><?= Loc::getMessage('SPOL_TPL_NOTPAID') ?></span>
                                        <?
                                    }
                                    ?>
                                </div>
                                <div class="sale-order-list-payment-price">
                                    <span class="sale-order-list-payment-element"><?= Loc::getMessage('SPOL_TPL_SUM_TO_PAID') ?>:</span>

                                    <span class="sale-order-list-payment-number"><?= $payment['FORMATED_SUM'] ?></span>
                                </div>
                                <?
                                if (!empty($payment['CHECK_DATA'])) {
                                    $listCheckLinks = "";
                                    foreach ($payment['CHECK_DATA'] as $checkInfo) {
                                        $title = Loc::getMessage('SPOL_CHECK_NUM', array('#CHECK_NUMBER#' => $checkInfo['ID'])) . " - " . htmlspecialcharsbx($checkInfo['TYPE_NAME']);
                                        if (strlen($checkInfo['LINK'])) {
                                            $link = $checkInfo['LINK'];
                                            $listCheckLinks .= "<div><a href='$link' target='_blank'>$title</a></div>";
                                        }
                                    }
                                    if (strlen($listCheckLinks) > 0) {
                                        ?>
                                        <div class="sale-order-list-payment-check">
                                            <div class="sale-order-list-payment-check-left"><?= Loc::getMessage('SPOL_CHECK_TITLE') ?>
                                                :
                                            </div>
                                            <div class="sale-order-list-payment-check-left">
                                                <?= $listCheckLinks ?>
                                            </div>
                                        </div>
                                        <?
                                    }
                                }

                                if ($order['ORDER']['IS_ALLOW_PAY'] == 'N' && $payment['PAID'] !== 'Y') {
                                    ?>
                                    <div class="sale-order-list-status-restricted-message-block">
                                        <span class="sale-order-list-status-restricted-message"><?= Loc::getMessage('SOPL_TPL_RESTRICTED_PAID_MESSAGE') ?></span>
                                    </div>
                                    <?
                                }
                                ?>

                            </div>
                            <?
                            if ($payment['PAID'] === 'N' && $payment['IS_CASH'] !== 'Y' && $payment['ACTION_FILE'] !== 'cash') {
                                if ($order['ORDER']['IS_ALLOW_PAY'] == 'N') {
                                    ?>
                                    <div class="col-md-3 col-sm-4 col-xs-12 sale-order-list-button-container">
                                        <a class="sale-order-list-button inactive-button">
                                            <?= Loc::getMessage('SPOL_TPL_PAY') ?>
                                        </a>
                                    </div>
                                    <?
                                } elseif ($payment['NEW_WINDOW'] === 'Y') {
                                    ?>
                                    <div class="col-md-3 col-sm-4 col-xs-12 sale-order-list-button-container">
                                        <a class="sale-order-list-button" target="_blank"
                                           href="<?= htmlspecialcharsbx($payment['PSA_ACTION_FILE']) ?>">
                                            <?= Loc::getMessage('SPOL_TPL_PAY') ?>
                                        </a>
                                    </div>
                                    <?
                                    if ($payment['PAY_SYSTEM_ID'] == 9):?>
                                        <div class="download-pdf-area">
                                            <a target="_blank"
                                               href="<?= htmlspecialcharsbx($payment['PSA_ACTION_FILE'] . '&pdf=1&DOWNLOAD=Y') ?>">
                                                Скачать (PDF)
                                            </a>
                                        </div>
                                    <? endif; ?>
                                    <?
                                } else {
                                    ?>
                                    <div class="col-md-3 col-sm-4 col-xs-12 sale-order-list-button-container">
                                        <a class="sale-order-list-button ajax_reload"
                                           href="<?= htmlspecialcharsbx($payment['PSA_ACTION_FILE']) ?>">
                                            <?= Loc::getMessage('SPOL_TPL_PAY') ?>
                                        </a>
                                    </div>
                                    <?
                                }
                            }
                            ?>

                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-10 col-xs-12 sale-order-list-inner-row-template">
                            <a class="sale-order-list-cancel-payment">
                                <i class="fa fa-long-arrow-left"></i> <?= Loc::getMessage('SPOL_CANCEL_PAYMENT') ?>
                            </a>
                        </div>
                    </div>
                    <?
                }
                if (!empty($order['SHIPMENT'])) {
                    ?>
                    <div class="sale-order-list-inner-title-line">
                        <span class="sale-order-list-inner-title-line-item"><?= Loc::getMessage('SPOL_TPL_DELIVERY') ?></span>
                        <span class="sale-order-list-inner-title-line-border"></span>
                    </div>
                    <?
                }
                $showDelimeter = false;
                foreach ($order['SHIPMENT'] as $shipment) {
                    if (empty($shipment)) {
                        continue;
                    }
                    ?>
                    <div class="row sale-order-list-inner-row">
                        <?
                        if ($showDelimeter) {
                            ?>
                            <div class="sale-order-list-top-border"></div>
                            <?
                        } else {
                            $showDelimeter = true;
                        }
                        ?>
                        <div class="col-md-9 col-sm-8 col-xs-12 sale-order-list-shipment">
                            <div class="sale-order-list-shipment-title">
									<span class="sale-order-list-shipment-element">
										<?= Loc::getMessage('SPOL_TPL_LOAD') ?>
                                        <?
                                        $shipmentSubTitle = Loc::getMessage('SPOL_TPL_NUMBER_SIGN') . htmlspecialcharsbx($shipment['ACCOUNT_NUMBER']);
                                        if ($shipment['DATE_DEDUCTED']) {
                                            $shipmentSubTitle .= " " . Loc::getMessage('SPOL_TPL_FROM_DATE') . " " . $shipment['DATE_DEDUCTED']->format($arParams['ACTIVE_DATE_FORMAT']);
                                        }

                                        if ($shipment['FORMATED_DELIVERY_PRICE']) {
                                            $shipmentSubTitle .= ", " . Loc::getMessage('SPOL_TPL_DELIVERY_COST') . " " . $shipment['FORMATED_DELIVERY_PRICE'];
                                        }
                                        echo $shipmentSubTitle;
                                        ?>
									</span>
                                <?
                                if ($shipment['DEDUCTED'] == 'Y') {
                                    ?>
                                    <span class="sale-order-list-status-success"><?= Loc::getMessage('SPOL_TPL_LOADED'); ?></span>
                                    <?
                                } else {
                                    ?>
                                    <span class="sale-order-list-status-alert"><?= Loc::getMessage('SPOL_TPL_NOTLOADED'); ?></span>
                                    <?
                                }
                                ?>
                            </div>

                            <div class="sale-order-list-shipment-status">
                                <span class="sale-order-list-shipment-status-item"><?= Loc::getMessage('SPOL_ORDER_SHIPMENT_STATUS'); ?>:</span>
                                <span class="sale-order-list-shipment-status-block"><?= htmlspecialcharsbx($shipment['DELIVERY_STATUS_NAME']) ?></span>
                            </div>

                            <?
                            if (!empty($shipment['DELIVERY_ID'])) {
                                ?>
                                <div class="sale-order-list-shipment-item">
                                    <?= Loc::getMessage('SPOL_TPL_DELIVERY_SERVICE') ?>:
                                    <?= $arResult['INFO']['DELIVERY'][$shipment['DELIVERY_ID']]['NAME'] ?>
                                </div>
                                <?
                            }

                            if (!empty($shipment['TRACKING_NUMBER'])) {
                                ?>
                                <div class="sale-order-list-shipment-item">
                                    <span class="sale-order-list-shipment-id-name"><?= Loc::getMessage('SPOL_TPL_POSTID') ?>:</span>
                                    <span class="sale-order-list-shipment-id"><?= htmlspecialcharsbx($shipment['TRACKING_NUMBER']) ?></span>
                                    <span class="sale-order-list-shipment-id-icon"></span>
                                </div>
                                <?
                            }
                            ?>
                        </div>
                        <?
                        if (strlen($shipment['TRACKING_URL']) > 0) {
                            ?>
                            <div class="col-md-2 col-md-offset-1 col-sm-12 sale-order-list-shipment-button-container">
                                <a class="sale-order-list-shipment-button" target="_blank"
                                   href="<?= $shipment['TRACKING_URL'] ?>">
                                    <?= Loc::getMessage('SPOL_TPL_CHECK_POSTID') ?>
                                </a>
                            </div>
                            <?
                        }
                        ?>
                    </div>
                    <?
                }
                ?>
                <div class="row sale-order-list-inner-row">
                    <div class="sale-order-list-top-border"></div>
                    <div class="col-md-<?= ($order['ORDER']['CAN_CANCEL'] !== 'N') ? 8 : 10 ?>  col-sm-12 sale-order-list-about-container">
                        <a class="sale-order-list-about-link"
                           href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"]) ?>"><?= Loc::getMessage('SPOL_TPL_MORE_ON_ORDER') ?></a>
                    </div>
                    <div class="col-md-2 col-sm-12 sale-order-list-repeat-container">
                        <a class="sale-order-list-repeat-link"
                           href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_COPY"]) ?>"><?= Loc::getMessage('SPOL_TPL_REPEAT_ORDER') ?></a>
                    </div>
                    <?
                    if ($order['ORDER']['CAN_CANCEL'] !== 'N') {
                        ?>
                        <div class="col-md-2 col-sm-12 sale-order-list-cancel-container">
                            <a class="sale-order-list-cancel-link"
                               href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_CANCEL"]) ?>"><?= Loc::getMessage('SPOL_TPL_CANCEL_ORDER') ?></a>
                        </div>
                        <?
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?
}
?>
<div class="clearfix"></div>
<?
echo $arResult["NAV_STRING"];

if ($_REQUEST["filter_history"] !== 'Y') {
    $javascriptParams = array(
        "url" => CUtil::JSEscape($this->__component->GetPath() . '/ajax.php'),
        "templateFolder" => CUtil::JSEscape($templateFolder),
        "templateName" => $this->__component->GetTemplateName(),
        "paymentList" => $paymentChangeData
    );
    $javascriptParams = CUtil::PhpToJSObject($javascriptParams);
    ?>
    <script>
        BX.Sale.PersonalOrderComponent.PersonalOrderList.init(<?=$javascriptParams?>);
    </script>
    <?
}
?>
