<?php
/*
 * Изменено: 29 December 2021, Wednesday
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
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
<section class="slider-blog mb-5" data-slider-id="<?= $arResult['UNIQUE_ID'] ?>">
    <h3 class="mb-5 text-center"><a href="/blog/">Блог</a></h3>
    <div class="swiper swiper-arrow-center swiper-pagination-bottom swiper-arrow-center_top">
        <div class="swiper-wrapper">
            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <?php
                $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item['IBLOCK_ID'], 'ELEMENT_EDIT'));
                $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item['IBLOCK_ID'], 'ELEMENT_DELETE'), ['CONFIRM' => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
                ?>
            <div class="swiper-slide">
                <article id="<?= $this->GetEditAreaId($item['ID']) ?>" class="blog-card">
                    <?php if ($item['PREVIEW_PICTURE']['SRC']): ?>
                        <a class="blog-card__image" href="<?= $item['DETAIL_PAGE_URL'] ?>">
                            <?php if ($item['DETAIL_PAGE_URL']): ?>
                            <?php endif ?>
                            <img class="swiper-lazy"
                                 data-src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>"
                                 title="<?= $item['NAME'] ?>"
                                 alt="<?= $item['NAME'] ?>">
                            <?php if ($item['DETAIL_PAGE_URL']): ?>
                            <div class="swiper-lazy-preloader"></div>
                        <?php endif ?>
                        </a>
                    <?php endif ?>
                    <?php if ($item['NAME']): ?>
                        <?php if ($item['DETAIL_PAGE_URL']): ?>
                        <a class="blog-card__name" href="<?= $item['DETAIL_PAGE_URL'] ?>">
                            <?php endif ?>
                            <?= $item['NAME'] ?>
                            <?php if ($item['DETAIL_PAGE_URL']): ?>
                        </a>
                    <?php endif ?>
                    <?php endif ?>
                    <?php if ($item['PREVIEW_TEXT']): ?>
                        <div class="blog-card__text">
                            <?= $item['PREVIEW_TEXT'] ?>
                        </div>
                    <?php endif ?>
                    <?php if ($item['DETAIL_PAGE_URL']): ?>
                        <div class="blog-card__more">
                            <a href="<?= $item['DETAIL_PAGE_URL'] ?>" class="link-more"><span>Читать</span></a>
                        </div>
                    <?php endif ?>
                </article>
            </div>
            <?php endforeach ?>
        </div>
        <div class="swiper-arrow-prev"></div>
        <div class="swiper-arrow-next"></div>
        <div class="swiper-pagination"></div>
    </div>
    <a href="/blog/" class="button button_primary mt-5 d-flex d-md-none">Смотреть все</a>
    <script>
		const swiperBlog = new Swiper('.slider-blog .swiper', {
			loop: false,
			lazy: true,
			navigation: {
				nextEl: '.swiper-arrow-next',
				prevEl: '.swiper-arrow-prev',
			},
			pagination: {
				el: ".swiper-pagination",
				dynamicBullets: false,
			},
			spaceBetween: 30,
			breakpoints: {
				300: {
					slidesPerView: '1',
					spaceBetween: 15,
				},
				767: {
					slidesPerView: '3',
					spaceBetween: 20,
				},
				999: {
					slidesPerView: '3',
					spaceBetween: 30,
				}
			}
		});
    </script>
</section>
