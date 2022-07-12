<?php
/*
 * Изменено: 29 сентября 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var array $arParams
 * @var array $arResult
 */

use Native\App\Sale\Basket;
use Native\App\Sale\Favorites;
global $USER;
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
if (empty($arResult)) {
	return;
}
?>
<div class="header-user-bar-wrapper">
	<div class="header-user-bar">
		<?php foreach ($arResult as $i => $item): ?>
			<?php if ($i === 'FAVORITES') continue ?>
            <?if($item['PARAMS']['CODE'] == "user-account" && !$USER->IsAuthorized()){?>
                <a href="#" data-code="<?= $item['PARAMS']['CODE'] ?>" title="<?= $item['TEXT'] ?>" class="open-pre-auth">
                    <img src="<?= $this->getFolder() ?>/images/<?= $item['PARAMS']['CODE'] ?>.svg" alt="">
                </a>
            <?}else{?>
                <a href="<?= $item['LINK'] ?>" data-code="<?= $item['PARAMS']['CODE'] ?>" title="<?= $item['TEXT'] ?>">
                    <img src="<?= $this->getFolder() ?>/images/<?= $item['PARAMS']['CODE'] ?>.svg" alt="">
                    <?php
                    if ($item['PARAMS']['CODE'] === 'shopping-bag') {
                        ?>
                        <span class="header-user-bar-count"
                              <?php if (Basket::count() == 0): ?>style="display: none"<?php endif ?>><span><?= Basket::count() ?></span></span>
                        <?php
                    } else if ($item['PARAMS']['CODE'] === 'favorites') {
                        ?>
                        <span class="header-user-bar-count"
                              <?php if (Favorites::count() == 0): ?>style="display: none"<?php endif ?>><span><?= Favorites::count() ?></span></span>
                        <?php
                    }
                    ?>
                </a>
            <?}?>
		<?php endforeach ?>
	</div>
    <div class="header-user-auth">
        <div class="header-user-auth-title">Войдите, чтобы делать покупки, отслеживать заказы и пользоваться персональными скидками</div>
        <div class="button button_primary" data-modal="modal-enter">Вход или регистрация</div>
    </div>
	<div class="user-bar-popup" data-code="favorites">
		<div class="user-bar-popup-header">
			<div class="user-bar-popup-header-title">Избранное</div>
			<div class="user-bar-popup-header-close"><i class="cross-white"></i></div>
		</div>
		<div class="user-bar-popup-content">
			<?php foreach ($arResult['FAVORITES'] as $item) {
				include 'favorite-item-template.php';
			} ?>
		</div>
	</div>

    <div class="mobile-user-bar">
        <?
		if ($USER->IsAuthorized()){?>
            <a href="/user/" class="mobile-user-bar__card">
                <img src="/local/templates/megre/images/icons/user.svg">
                <span>Профиль</span>
            </a>
        <?}
        else{?>
            <a href="#" class="mobile-user-bar__card" data-modal="modal-enter">
                <img src="/local/templates/megre/images/icons/user.svg">
                <span>Профиль</span>
            </a>
        <?}?>

        <a href="/catalog/" class="mobile-user-bar__card">
            <img src="/local/templates/megre/images/icons/catalog.svg">
            <span>Каталог</span>
        </a>
        <a href="javascript:void(0)" class="mobile-user-bar__card" data-code="favorites">
            <img src="/local/templates/megre/images/icons/heart-green.svg">
            <span>Избранное</span>
        </a>
        <a href="/cart/" class="mobile-user-bar__card">
            <img src="/local/templates/megre/images/icons/basket.svg">
            <span>Корзина</span>
        </a>
    </div>
</div>

<script>
	UserBar.run(<?= CUtil::PhpToJSObject([
			'TEMPLATE_PATH' => $this->getFolder(),
	]) ?>)
</script>
