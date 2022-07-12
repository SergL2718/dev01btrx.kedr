<?php
/*
 * Изменено: 03 ноября 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die;

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixBasketComponent $component */

if ($arResult['EMPTY_BASKET'] !== true) {
    require 'popup.php';
}
?>

<div class="basket-component mb-5">

    <?php if ($arResult['EMPTY_BASKET']): ?>
        <div class="col-md-12">
			<?php
            require 'empty.php';
            ?>
        </div>
        <?php return // Дальше шаблон не выполняем ?>
    <?php endif ?>

	<?php
    if ($arResult['ERROR_MESSAGE'] /*&& $arResult['HAS_EXCLUDED_PRODUCTS'] === 'N'*/) {
        ShowError($arResult['ERROR_MESSAGE']);
        return;
    }
    ?>

    <div class="col-12">
        <?php require 'gifts.php' ?>
    </div>

    <form method="post" action="<?= POST_FORM_ACTION_URI ?>" name="basket_form" id="basket_form">
        <input type="hidden" name="BasketOrder" value="BasketOrder">

        <div class="col-md-6 mb-5">
            <div class="basket-title">
                <h1><?= $APPLICATION->GetTitle() ?></h1>
            </div>
        </div>

        <div class="col-md-6 mb-5">
            <div class="text-right">
                <a href="/catalog/" class="go-to-catalog">Продолжить покупки</a>
            </div>
        </div>

		<?php if (!empty($arResult['WARNING_MESSAGE']) && is_array($arResult['WARNING_MESSAGE'])): ?>
			<div class="col-md-12 mb-5">
				<div id="warning_message" style="color: red;">
					<?php foreach ($arResult['WARNING_MESSAGE'] as $message): ?>
						<?php ShowError($message) ?>
					<?php endforeach ?>
				</div>
			</div>
		<?php endif ?>

		<?php if (time() > strtotime('29.12.2020 00:00:00') && time() < strtotime('11.01.2021 00:00:00')): ?>
			<div class="col-sm-12 mb-5">
				<div style="background-color: #f5fce7; font-size: 17px; padding: 15px 15px 13px; border-radius: 5px;">
					Заказы, оплаченные после 29 декабря 23:59 по Мск будут отправлены после новогодних выходных, начиная
					с 11 января 2021 года
				</div>
			</div>
		<?php endif ?>

		<?php /*
        <div class="col-md-12">
            <?php require 'discount-informer.php' ?>
        </div>
 		*/ ?>

		<?php if ($arResult['HAS_OFFLINE_PRODUCT'] === 'Y'): ?>
			<div class="col-md-12">
				<div id="has-offline-product" class="mb-5">
					Внимание! В вашей корзине есть товары, доступные только в магазине "Мегре" в Новосибирске. Они
					помечены зеленым цветом. Если вы хотите заказать товары с доставкой в другой город, пожалуйста
					удалите их из корзины.
				</div>
			</div>
		<?php endif ?>

		<div class="col-md-12">
			<div class="basket-content">
				<div class="basket-left-column">
					<?php require 'products.php' ?>
				</div>
				<div class="basket-right-column">
					<?php require 'sidebar.php' ?>
				</div>
			</div>
        </div>
    </form>
</div>

<script type="text/javascript">
    BasketComponent.init.component(<?= CUtil::PhpToJSObject($arResult['JS']) ?>);
</script>
