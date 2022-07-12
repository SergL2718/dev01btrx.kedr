<?php
/*
 * Изменено: 24 January 2022, Monday
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

/**
 * @var array $arResult - from template.php
 * @var array $item     - from template.php
 */
?>
<article id="<?= $this->GetEditAreaId($item['ID']) ?>" class="article-list-item-wrapper">
    <div class="article-list-item-image">
        <a href="<?= $item['DETAIL_PAGE_URL'] ?>">
            <img src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>" alt="<?= $item['NAME'] ?>" title="<?= $item['NAME'] ?>">
        </a>
    </div>
    <div class="article-list-item-section">
        <?php if ($item['IBLOCK_SECTION_ID'] && $arResult['SECTIONS'][$item['IBLOCK_SECTION_ID']]): ?>
            <a href="<?= $arResult['SECTIONS'][$item['IBLOCK_SECTION_ID']]['DETAIL_PAGE_URL'] ?>">
                <img src="<?= $arResult['SECTIONS'][$item['IBLOCK_SECTION_ID']]['PICTURE'] ?>"
                     alt="<?= $arResult['SECTIONS'][$item['IBLOCK_SECTION_ID']]['NAME'] ?>">
                <span><?= $arResult['SECTIONS'][$item['IBLOCK_SECTION_ID']]['NAME'] ?></span>
            </a>
        <?php else: ?>
            <a href="/blog/blog/<?//= $arResult['LIST_PAGE_URL'] ?>">
                <img src="<?= $this->getFolder() ?>/images/list.svg" alt="Все статьи">
                <span>Все статьи</span>
            </a>
        <?php endif ?>
    </div>
    <h2 class="article-list-item-title"><a href="<?= $item['DETAIL_PAGE_URL'] ?>"><?= $item['NAME'] ?></a></h2>
    <div class="article-list-item-description"><?= $item['PREVIEW_TEXT'] ?></div>
    <div class="article-detail">
        <a href="<?= $item['DETAIL_PAGE_URL'] ?>"><span>Читать</span></a>
    </div>
</article>