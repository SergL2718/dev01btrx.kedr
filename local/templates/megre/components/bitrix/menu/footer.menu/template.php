<?php
/*
 * Изменено: 09 сентября 2021, четверг
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
if (empty($arResult)) {
	return;
}
?>
<ul class="footer-menu">
	<?php foreach ($arResult as $item): ?>
		<li><a href="<?= $item['LINK'] ?>"><?= $item['TEXT'] ?></a></li>
	<?php endforeach ?>
</ul>