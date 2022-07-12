<?php
/*
 * Изменено: 18 сентября 2021, суббота
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var array $arParams
 * @var array $arResult
 */

use Native\App\Sale\Location;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
$this->setFrameMode(true);
?>
<style>

	[data-popup-code="<?= $arResult['UNIQUE_ID'] ?>"] {
		width : 405px;
	}
</style>
<div class="user-region-wrapper">
	<div id="region-selection:<?= $arResult['UNIQUE_ID'] ?>" class="user-region">
		<i class="map-marker"></i><span><?= $arResult['CURRENT']['NAME'] ?></span>
	</div>
</div>
<div data-popup-code="<?= $arResult['UNIQUE_ID'] ?>">
	<div id="popup-close:<?= $arResult['UNIQUE_ID'] ?>" class="popup-window-close"><i class="cross"></i></div>
	<div class="popup-window-title">Выберите регион</div>
	<div class="user-region-list-wrapper">
		<div data-code="country-list:<?= $arResult['UNIQUE_ID'] ?>" class="user-region-country-list">
			<?php foreach ($arResult['LOCATION']['COUNTRY'] as $country): ?>
				<a href="javascript:void(0)"
				   data-country-code="<?= $country['ISO_CODE'] ?>"
				   <?php if ($country['ISO_CODE'] === $arResult['CURRENT']['COUNTRY']['CODE']): ?>class="current" <?php endif ?>><span><?= $country['NAME'] ?></span></a>
			<?php endforeach ?>
		</div>
		<div class="user-region-search">
			<input id="user-region-search:<?= $arResult['UNIQUE_ID'] ?>"
				   type="text"
				   name="user-region-search"
				   placeholder="Введите ваш город или область">
		</div>
		<div class="user-region-country-city-list">
			<?php foreach ($arResult['LOCATION']['COUNTRY'] as $country): ?>
				<div data-country-city-list="<?= $country['ISO_CODE'] ?>"
					 <?php if ($country['ISO_CODE'] === $arResult['CURRENT']['COUNTRY']['CODE']): ?>class="current"<?php endif ?>>
					<?php foreach ($country['TOP_CITY'] as $item): ?>
						<?php $region =& $arResult['LOCATION']['COUNTRY'][$item['COUNTRY_ID']]['REGION'][$item['REGION_ID']]['NAME'] ?>
						<a data-id="<?= $item['ID'] ?>"
						   href="javascript:void(0)"><?= $item['NAME'] ?><?php if ($item['REGION_ID']) echo ', ' . $region ?></a>
					<?php endforeach ?>
					<a href="javascript:void(0)"><?= $arResult[Location::OTHER]['NAME'] ?></a>
				</div>
			<?php endforeach ?>
			<div id="search-result:<?= $arResult['UNIQUE_ID'] ?>" class="user-region-search-result-wrapper">
				<div id="search-result-content:<?= $arResult['UNIQUE_ID'] ?>" class="user-region-search-result-content">
					Ничего не найдено
				</div>
				<div class="user-region-search-result-close-wrapper">
					<a id="search-result-close:<?= $arResult['UNIQUE_ID'] ?>"
					   href="javascript:void(0)"
					   class="user-region-search-result-close">Закрыть</a>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	UserRegionComponent.run(<?= CUtil::PhpToJSObject([
			'UNIQUE_ID'              => $arResult['UNIQUE_ID'],
			'COOKIE'                 => Location::getCookieName(),
			'LOCATION'               => $arResult['JS']['LOCATION'],
			'SEARCH'                 => $arResult['JS']['SEARCH'],
			'CURRENT_COUNTRY'        => $arResult['CURRENT']['COUNTRY']['CODE'],
			'OTHER'                  => Location::OTHER,
			'POPUP_TITLE'            => 'Выберите регион',
			'SEARCH_NOT_FOUND_TITLE' => 'Ничего не найдено',
	]) ?>)
</script>