<?php
/*
 * Изменено: 29 сентября 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var array $item
 */

use Bitrix\Catalog\ProductTable;
if($item['ID']){
?>
<div class="user-bar-popup-product-wrapper" data-id="<?= $item['ID'] ?>">
	<div class="user-bar-popup-product-delete" onclick="UserBar.favorites.delete(<?= $item['ID'] ?>)">
		<i class="cross"></i></div>
	<div class="user-bar-popup-product-content">
		<div class="user-bar-popup-product-image">
			<a href="<?= $item['DETAIL_PAGE_URL'] ?>"><img src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>"
														   alt="<?= $item['NAME'] ?>"></a>
		</div>
		<div class="user-bar-popup-product-description">
			<a href="<?= $item['DETAIL_PAGE_URL'] ?>">
				<div class="user-bar-popup-product-title"><?= $item['NAME'] ?></div>
				<div class="user-bar-popup-product-price">
					<?php if ($item['TYPE'] == ProductTable::TYPE_SKU): ?>от <?php endif ?><?= $item['PRICE_FORMATTED'] ?>
				</div>
			</a>
		</div>
		<div class="user-bar-popup-product-button-wrapper">
			<?php if ($item['QUANTITY'] <= 0): ?>
				<a href="<?= $item['DETAIL_PAGE_URL'] ?>"
				   class="user-bar-popup-product-add-to-basket not-available">Нет в наличи</a>
			<?php else: ?>
				<?php if ($item['TYPE'] != ProductTable::TYPE_SKU): ?>
					<a href="javascript:void(0)"
					   class="user-bar-popup-product-add-to-basket"
					   onclick="UserBar.basket.add(<?= $item['ID'] ?>)"><i class="cart"></i></a>
					<div class="user-bar-popup-product-change-quantity">
						<a href="javascript:void(0)"
						   class="user-bar-popup-product-quantity"
						   onclick="UserBar.basket.changeQuantity(<?= $item['ID'] ?>, 'reduce')"><i class="minus"></i></a>
						<span>1</span>
						<a href="javascript:void(0)"
						   class="user-bar-popup-product-quantity"
						   onclick="UserBar.basket.changeQuantity(<?= $item['ID'] ?>, 'increase')"><i class="plus"></i></a>
					</div>
				<?php else: ?>
					<a href="<?= $item['DETAIL_PAGE_URL'] ?>"
					   class="user-bar-popup-product-add-to-basket">Подробнее</a>
				<?php endif ?>
			<?php endif ?>
		</div>
	</div>
</div>
<?}
/*else{
    unset ($_SESSION["FAVORITES"][137008]);
    echo "<pre>"; print_r ($_SESSION["FAVORITES"]); echo "</pre>";
}*/?>
