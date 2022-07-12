<?php
    /*
     * Изменено: 24 January 2022, Monday
     * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
     * copyright (c) 2022
     */

    /**
     * @@global CMain $APPLICATION
     * @var array $arParams
     * @var array $arResult
     */

    if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
    if (count($arResult['ITEMS']) > 0) {
        $arResult['ITEMS'] = array_chunk($arResult['ITEMS'], 3);
    }
?>
<div class="article-list-wrapper">
    <div class="container">
        <h1 class="page-title"><?= $APPLICATION->GetTitle() ?></h1>
        <div class="blog-filter">
            <div class="blog-filter__search">
                <form class="input-search" action="/blog?">
                    <input placeholder="Поиск в блоге..." name="q"/>
                    <button type="submit"></button>
                </form>
            </div>
            <div class="blog-filter__type">
                <div class="filter-select">
                    <div class="filter-select__selected">КАТЕГОРИЯ <i></i></div>
                    <div class="filter-select__options">
                        <div class="filter-select__options-wrap">

                            <?php foreach ($arResult['SECTIONS'] as $item): ?>
                                <a href="<?= $item['DETAIL_PAGE_URL'] ?>" class="filter-select__option blog-type">

                                    <div class="blog-type__image"><img src="<?= $item['PICTURE'] ?>"
                                                                       alt="<?= $item['NAME'] ?>"></div>
                                    <div class="blog-type__name"><?= $item['NAME'] ?></div>
                                </a>

                            <?php endforeach ?>
                            <a href="/blog/" class="filter-select__option blog-type">

                                <div class="blog-type__image"><img
                                        src="<?= SITE_TEMPLATE_PATH ?>/images/icons/note-sheet.svg" alt=""></div>
                                <div class="blog-type__name">Все статьи</div>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($arParams['DISPLAY_TOP_PAGER'] && $arResult['NAV_STRING']): ?>
            <div class="article-list-pagination mt-5 mb-5">
                <?= $arResult['NAV_STRING'] ?>
            </div>
        <?php endif ?>
    </div>

    <?php if (count($arResult['ITEMS']) === 0): ?>
        <div class="container">
            <div class="mb-5">Статей пока нет ...</div>
        </div>
    <?php else: ?>
        <?php if (count($arResult['ITEMS']) < 3): ?>
            <div class="container">
                <?php foreach ($arResult['ITEMS'] as $chunk): ?>
                    <div class="article-list-row">
                        <?php foreach ($chunk as $item): ?>
                            <?php include 'item.php' ?>
                        <?php endforeach ?>
                    </div>
                <?php endforeach ?>
            </div>
            <?php if ($arResult['NAV'] && $arResult['NAV']->getRecordCount() > 0): ?>
                <div class="container">
                    <div class="article-list-pagination">
                        <?php $APPLICATION->IncludeComponent(
                            'bitrix:main.pagenavigation',
                            '',
                            [
                                'NAV_OBJECT' => $arResult['NAV'],
                                'SEF_MODE' => 'Y',
                            ],
                            false,
                            ['HIDE_ICONS' => 'Y']
                        ) ?>
                    </div>
                </div>
            <?php endif ?>
            <div class="mt-5 pt-4">
                <?php include 'subscribe.php' ?>
            </div>
        <?php else: ?>
            <div class="container">
                <?php for ($i = 0; $i < 2; $i++): ?>
                    <div class="article-list-row">
                        <?php foreach ($arResult['ITEMS'][$i] as $item): ?>
                            <?php include 'item.php' ?>
                        <?php endforeach ?>
                    </div>
                <?php endfor ?>
            </div>
            <div class="mb-5 pt-2 pb-5">
                <?php include 'subscribe.php' ?>
            </div>
            <div class="container">
                <div class="article-list-row">
                    <?php foreach ($arResult['ITEMS'][2] as $item): ?>
                        <?php include 'item.php' ?>
                    <?php endforeach ?>
                </div>
            </div>
            <?php if ($arParams['DISPLAY_BOTTOM_PAGER'] && $arResult['NAV_STRING']): ?>
                <div class="container">
                    <div class="article-list-pagination mb-5 pb-3">
                        <?= $arResult['NAV_STRING'] ?>
                    </div>
                </div>
            <?php endif ?>
        <?php endif ?>
    <?php endif ?>
    <style>
        .block-subscribe .page-title {
            color: #636363;
        }
        .block-subscribe .button {
            background: #B19E86;
            border-color: #B19E86;
            color: #fff;
        }
        .block-subscribe .button:hover {
            background: transparent;
        }
        .block-subscribe .text-small, .block-subscribe .text-small a {
            color: #fff;
        }
        .block-subscribe input {
            border-color: #ADADAD;
        }
        @media (max-width: 767px) {
            .block-subscribe .text-small, .block-subscribe .text-small a {
                color: #9A9A9A;
            }
        }
    </style>
</div>