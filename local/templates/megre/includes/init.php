<?php
/*
 * Изменено: 24 сентября 2021, пятница
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

use Native\App\Sale\Basket;
use Native\App\Sale\Favorites;

$_SESSION['BASKET'] = [];               // корзина покупателя
$_SESSION['FAVORITES'] = [];            // избранное покупателя
$_SESSION['FAVORITES_STORAGE'] = false; // класс для работы с хайлоад блоком избранного
if (Basket::init()) {
	?>
	<script>
		const BASKET = <?= CUtil::PhpToJSObject(Basket::getList()) ?>
	</script>
	<?php
}
if (Favorites::init()) {
	?>
	<script>
		const FAVORITES = <?= CUtil::PhpToJSObject(Favorites::getList()) ?>
	</script>
	<?php
}
?>
<script>
	const SITE_TEMPLATE_PATH = '<?= SITE_TEMPLATE_PATH ?>'
</script>
