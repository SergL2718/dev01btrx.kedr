<?php
/*
 * Изменено: 06 декабря 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var array $arResult
 */

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
if (empty($arResult['ITEMS'])) {
	return;
}
$this->setFrameMode(true);
?>
<section class="manufacturer-list slider-manufacturers">
    <div class="slider-wrapper" data-slider-id="<?= $arResult['UNIQUE_ID'] ?>" data-slider-code="manufacturer-list">
        <h3 class="mb-5 text-center">
            <a href="/manufacturers/">Наши производители</a>
        </h3>
        <div class="swiper swiper-arrow-center swiper-pagination-bottom">
            <div class="swiper-wrapper">
                <?php foreach ($arResult['ITEMS'] as $item): ?>
                    <?php
                    $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item['IBLOCK_ID'], 'ELEMENT_EDIT'));
                    $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item['IBLOCK_ID'], 'ELEMENT_DELETE'), ['CONFIRM' => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
                    ?>
                    <div class="swiper-slide">
                        <article id="<?= $this->GetEditAreaId($item['ID']) ?>" class="manufacturer-wrapper">
                            <?php if ($item['PROPERTIES']['URL']['VALUE']): ?>
                            <a href="<?= $item['PROPERTIES']['URL']['VALUE'] ?>" target="_blank">
                                <?php else: ?>
                                <a href="<?= $item['DETAIL_PAGE_URL'] ?>">
                                    <?php endif ?>
                                    <?php if ($item['PREVIEW_PICTURE']['SRC']): ?>
                                        <div class="manufacturer-image">
                                            <img
                                                 src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>"
                                                 title="<?= $item['NAME'] ? : '' ?>"
                                                 alt="<?= $item['NAME'] ? : '' ?>">
                                        </div>
                                    <?php endif ?>
                                    <span class="manufacturer-full-title">
                                    <?php if ($item['PROPERTIES']['NAME']['VALUE']): ?>
                                        <span class="manufacturer-name"><?= $item['PROPERTIES']['NAME']['VALUE'] ?></span>
                                    <?php endif ?>
                                        <?php if ($item['PROPERTIES']['LAST_NAME']['VALUE']): ?>
                                            <span class="manufacturer-last-name"><?= $item['PROPERTIES']['LAST_NAME']['VALUE'] ?></span><?php endif ?>
                                        <?php if ($item['PROPERTIES']['LAST_NAME']['VALUE'] && $item['PROPERTIES']['TITLE']['VALUE']): ?> / <?php endif ?>
                                        <?php if ($item['PROPERTIES']['TITLE']['VALUE']): ?>
                                            <span class="manufacturer-title"><?= $item['PROPERTIES']['TITLE']['VALUE'] ?></span><?php endif ?>
                                        </span>
                                </a>
                        </article>
                    </div>
                <?php endforeach ?>
            </div>
            <div class="swiper-arrow-prev"></div>
            <div class="swiper-arrow-next"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    <script>
		const swiperManufacturers = new Swiper('.slider-manufacturers .swiper', {
			loop: false,
			navigation: {
				nextEl: '.swiper-arrow-next',
				prevEl: '.swiper-arrow-prev',
			},
			pagination: {
				el: ".swiper-pagination",
				dynamicBullets: true,
			},
			spaceBetween: 30,
			breakpoints: {
				300: {
					slidesPerView: '2',
					grid: {
						rows: 2,
					},
				},
				767: {
					slidesPerView: '3',
					grid: {
						rows: 1,
					},
				},
				999: {
					slidesPerView: '4',
					spaceBetween: 30,
				}
			}
		});
    </script>
</section>
