<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<div class="row brand-list">
    <? foreach ($arResult['SECTIONS'] as $ar): ?>
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4 brand-cart">
            <a href="<?= $ar['SECTION_PAGE_URL'] ?>" class="brand-image">
                <img src="<?= $ar['PICTURE'] ?>" alt="<?= $ar['NAME'] ?>" title="<?= $ar['NAME'] ?>">
            </a>
        </div>
    <? endforeach; ?>
</div>
<div class="clearfix"></div>