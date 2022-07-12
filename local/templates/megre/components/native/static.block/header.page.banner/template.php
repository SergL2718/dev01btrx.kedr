<?php
/*
 * Изменено: 10 декабря 2021, пятница
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @global CMain $APPLICATION
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
if (empty($APPLICATION->GetProperty('HEADER-BACKGROUND-IMAGE'))) {
	return;
}
?>
<div class="header-page-banner-wrapper">
	<img src="<?= $APPLICATION->GetProperty('HEADER-BACKGROUND-IMAGE') ?>"
		 alt="<?= $APPLICATION->GetProperty('HEADER-BACKGROUND-TITLE') ?>">
	<?php if (!empty($APPLICATION->GetProperty('HEADER-BACKGROUND-TITLE'))): ?>
		<div class="header-page-banner-title-wrapper">
			<div class="container">
				<div class="header-page-banner-title">
					<?= trim($APPLICATION->GetProperty('HEADER-BACKGROUND-TITLE')) ?>
				</div>
			</div>
		</div>
	<?php endif ?>
</div>
