<?php
/*
 * Изменено: 04 июля 2021, воскресенье
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var $APPLICATION
 */

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\Config\Option;

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

Bitrix\Main\Page\Asset::getInstance()->addCss('/personal/order/payment/style.min.css');

$APPLICATION->SetTitle('Ошибка оплаты заказа');

$lastOrder = [];
$cookie = '';
try {
    $cookie = Option::get('main', 'cookie_name') . '_LAST_ORDER';
    $lastOrder = unserialize($_COOKIE[$cookie]);
} catch (ArgumentNullException | ArgumentOutOfRangeException $e) {
}

/*$logRequests = $_SERVER['DOCUMENT_ROOT'] . '/personal/order/payment/logs/error/' . date('dmY') . '.log';
$log[] = 'Дата: ' . date('d.m.Y H:i:s');
$log[] = print_r($_COOKIE, true);
$log[] = '================================';
$log[] = PHP_EOL . PHP_EOL;
\Bitrix\Main\IO\File::putFileContents($logRequests, implode(PHP_EOL, $log), \Bitrix\Main\IO\File::APPEND);*/

$accountNumber = $lastOrder['ACCOUNT_NUMBER'];
if (!$accountNumber) {
    setcookie($cookie, '', time() + 8640000, '/'); // удалим куки
}

/*if (!$accountNumber || !is_string($accountNumber) || strlen($accountNumber) > 15) {
    LocalRedirect('/');
}*/
?>
    <div class="order-complete-content mb-5">
        <div class="order-complete-message mt-0 mt-lg-3">
            <div class="order-complete-message-text">
                <div id="order-payment-online">
                    К сожалению, Ваш заказ <?php if ($accountNumber): ?><a
                        href="/personal/order/?ID=<?= $accountNumber ?>"><?= $accountNumber ?></a><?php endif ?>оплатить
                    не удалось!
                    <br>
                    <br>
                    Если у вас возникнут вопросы по заказу или пожелания по нашей работе, пишите на почту admin@megre.ru
                    или
                    звоните
                    по бесплатному номеру 8-800-350-0270.
                </div>
            </div>
            <div class="order-complete-message-photo">
                <img src="../images/alina.jpg" alt="">
            </div>
            <div class="order-complete-message-photo-description">
                Алина, администратор megre.ru
            </div>
        </div>
        <div class="order-complete-button mt-4">
            <a href="/">На главную</a>
        </div>
    </div>
<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
