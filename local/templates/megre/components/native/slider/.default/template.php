<?php
/*
 * Изменено: 17 сентября 2021, пятница
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @global CMain $APPLICATION
 * @var array    $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
if (empty($arResult['ITEMS'])) {
	return;
}
$this->setFrameMode(true);
?>
<div class="slider-wrapper" data-slider-id="<?= $arResult['UNIQUE_ID'] ?>" data-slider-code="large-slider-main-page">
	<a id="placeholder-<?= $arResult['UNIQUE_ID'] ?>" href="javascript:void(0)" rel="nofollow">
		<img data-size="375" src="<?= $this->getFolder() ?>/images/placeholder-375.gif">
		<img data-size="768" src="<?= $this->getFolder() ?>/images/placeholder-768.gif">
		<img data-size="992" src="<?= $this->getFolder() ?>/images/placeholder-992.gif">
		<img data-size="1600" src="<?= $this->getFolder() ?>/images/placeholder-1600.gif">
		<img data-size="1920" src="<?= $this->getFolder() ?>/images/placeholder-1920.gif">
	</a>
	<div class="owl-carousel">
		<?php foreach ($arResult['ITEMS'] as $item): ?>
			<a href="<?= $item['URL'] ?>" target="<?= $item['TARGET'] ?>">
				<img class="owl-lazy" data-src="<?= $item['IMAGE']['375'] ?>" data-size="375">
				<img class="owl-lazy" data-src="<?= $item['IMAGE']['768'] ?>" data-size="768">
				<img class="owl-lazy" data-src="<?= $item['IMAGE']['992'] ?>" data-size="992">
				<img class="owl-lazy" data-src="<?= $item['IMAGE']['1600'] ?>" data-size="1600">
				<img class="owl-lazy" data-src="<?= $item['IMAGE']['1920'] ?>" data-size="1920">
			</a>
		<?php endforeach ?>
	</div>
</div>
<script>
	if (!window.hasOwnProperty('SliderStorage') || typeof window.SliderStorage === 'undefined') {
		window.SliderStorage = {}
	}
	if (!window.SliderStorage.hasOwnProperty('<?= $arResult['UNIQUE_ID'] ?>')) {
		window.SliderStorage['<?= $arResult['UNIQUE_ID'] ?>'] = $('[data-slider-id="<?= $arResult['UNIQUE_ID'] ?>"] .owl-carousel')
	}
	window.SliderStorage['<?= $arResult['UNIQUE_ID'] ?>'].on('initialize.owl.carousel', function () {
		document.getElementById('placeholder-<?= $arResult['UNIQUE_ID'] ?>').remove()
	})
	window.SliderStorage['<?= $arResult['UNIQUE_ID'] ?>'].owlCarousel({
		// https://owlcarousel2.github.io/OwlCarousel2/docs/api-options.html
		checkVisibility: false,
		items: 1,
		rewind: true,
		autoplay: true,
		autoplayHoverPause: true,
		lazyLoad: true,
		lazyLoadEager: 1,
		autoplayTimeout: 3000,
	})
</script>