<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?

use Alexkova\Bxready\Draw;

$addClass = '';
$UID = 0;
$picture = false;

global $APPLICATION;

//echo "<pre>"; print_r($arElementParams); echo "</pre>";

$elementDraw = \Alexkova\Bxready\Draw::getInstance();

$arMatrix = array('width' => 300, 'height' => 200);

if (is_array($arElement["PREVIEW_PICTURE"])) {
    $picture = $elementDraw->prepareImage($arElement["PREVIEW_PICTURE"]["ID"], $arMatrix);
} else {
    if (is_array($arElement["DETAIL_PICTURE"])) {
        $picture = $elementDraw->prepareImage($arElement["DETAIL_PICTURE"]["ID"], $arMatrix);
    }
}

if (!is_array($picture) || strlen($picture["src"]) <= 0) {
    $picture = array("src" => $elementDraw->getDefaultImage());
}


if (intval($arElementParams["UNICUM_ID"]) > 0) {
    $UID = $arElementParams["UNICUM_ID"] > 0 ? $arElementParams["UNICUM_ID"] > 0 : 1;
}
?>

    <div class="bxr-classic-image-v2" data-uid="<?= $UID ?>" data-resize="1">
        <div class="bxr-section-container">

            <div class="bxr-element-image">
                <a href="<?= $arElement["DETAIL_PAGE_URL"] ?>"><img src="<?= $picture["src"] ?>"></a>
            </div>

            <div class="bxr-element-content">
                <div class="bxr-element-name">
                    <a href="<?= $arElement["DETAIL_PAGE_URL"] ?>">
                        <?= $arElement["NAME"] ?>
                        <? if ($arElementParams["COUNT_ELEMENTS"]): ?>
                            (<?= $arElement["ELEMENT_CNT"] ?>)
                        <? endif; ?>
                    </a>
                </div>
                <? if (strlen($arElement["PREVIEW_TEXT"]) > 0): ?>
                    <div class="bxr-element-description">
                        <!--noindex-->
                        <?= $arElement["PREVIEW_TEXT"] ?>
                        <!--/noindex-->
                    </div>
                <? endif; ?>
                <? if ($arElementParams["DISPLAY_DATE"] == "Y" && ($arElement['DATE_ACTIVE_FROM'] || $arElement['DATE_ACTIVE_TO'])) { ?>
                    <div class="bxr-element-date">
                        <div class="bxr-color">
                            <? if ($arElement['DATE_ACTIVE_FROM']) { ?>
                                <?= date($arElementParams['ACTIVE_DATE_FORMAT'], strtotime($arElement['DATE_ACTIVE_FROM'])) ?>
                            <? } ?>
                            <? if ($arElement['DATE_ACTIVE_FROM'] && $arElement['DATE_ACTIVE_TO']) { ?>
                                -
                            <? } ?>
                            <? if ($arElement['DATE_ACTIVE_TO']) { ?>
                                <?= date($arElementParams['ACTIVE_DATE_FORMAT'], strtotime($arElement['DATE_ACTIVE_TO'])) ?>
                            <? } ?>
                        </div>
                    </div>
                <? } ?>
                <? if (strlen($arElementParams["DETAIL_PAGE_TITLE"]) > 0): ?>
                    <div class="bxr-element-action">
                        <a href="<?= $arElement["DETAIL_PAGE_URL"] ?>" class="bxr-border-color-button">
                            <?= $arElementParams["DETAIL_PAGE_TITLE"] ?>
                        </a>
                    </div>
                <? endif; ?>

            </div>


        </div>
    </div>

<?
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready/library/elements/classic.image.v2/include/style.css", false);
?>