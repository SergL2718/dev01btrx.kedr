<?
/*
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @updated 01.09.2020, 17:51
 * @copyright 2011-2020
 */

/**
 * @var $arResult
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Alexkova\Bxready\Draw;

$elementDraw = \Alexkova\Bxready\Draw::getInstance($this);
$elementDraw->setCurrentTemplate($this);


$this->setFrameMode(true);


$elementTemplate = ".default";

global $unicumID;
if ($unicumID <= 0) {
    $unicumID = 1;
} else {
    $unicumID++;
}

$arParams["UNICUM_ID"] = $unicumID;

$colToElem = array();
$bootstrapGridCount = $arParams["BXREADY_LIST_BOOTSTRAP_GRID_STYLE"];
if ($bootstrapGridCount > 0) {
    for ($i = 1; $i <= $bootstrapGridCount; $i++) {
        if (($bootstrapGridCount % $i) == 0) {
            $colToElem[$bootstrapGridCount / $i] = $i;
        }
    }
}

?>

<? if (count($arResult["ITEMS"]) > 0): ?>

    <div class="row bxr-list" id="articles-main-page-<?= $unicumID ?>">

        <? if (strlen($arParams["BXREADY_LIST_PAGE_BLOCK_TITLE"]) > 0): ?>
            <div class="col-xs-12">
                <? if ($arParams["SHOW_LINK_MAIN_PAGE_IBLOCK"] == 'Y'): ?>
                <div class="bxr-list-title">
                    <? endif; ?>

                    <div class="block-list-title">
                        <? if (strlen($arParams["BXREADY_LIST_PAGE_BLOCK_TITLE_GLYPHICON"]) > 0): ?>
                            <i class="<?= $arParams["BXREADY_LIST_PAGE_BLOCK_TITLE_GLYPHICON"] ?>"></i>
                        <? endif; ?>
                        <?= $arParams["BXREADY_LIST_PAGE_BLOCK_TITLE"] ?>
                    </div>

                    <? if ($arParams["SHOW_LINK_MAIN_PAGE_IBLOCK"] == 'Y' && strlen($arParams["SHOWN_MAIN_PAGE_LINK"]) > 0 && strlen($arParams["SHOW_LINK_MAIN_PAGE_NAME"]) > 0): ?>
                        <a href="<?= $arParams["SHOWN_MAIN_PAGE_LINK"] ?>" class="bxr-list-title-link">
                            <?= $arParams["SHOW_LINK_MAIN_PAGE_NAME"] ?>
                        </a>
                    <? endif; ?>

                    <? if ($arParams["SHOW_LINK_MAIN_PAGE_IBLOCK"] == 'Y'): ?>
                </div>
            <? endif; ?>
            </div>
        <? endif; ?>
        <div class="clearfix"></div>

        <? if (strlen($arParams["PAGE_BLOCK_TITLE"]) > 0):
            $addClass = '';
            if (strlen($arParams["PAGE_BLOCK_TITLE_GLYPHICON"]) > 0) {
                $addClass = 'glyphicon glyphicon-pad ' . $arParams["PAGE_BLOCK_TITLE_GLYPHICON"];
            }
            ?>
            <h2 class="<?= $addClass ?>"><?= $arParams["PAGE_BLOCK_TITLE"] ?></h2>
        <? endif; ?>

        <? if ($arParams["BXREADY_LIST_SLIDER"] == "Y") { ?>
        <div class="slick-list" id="sl_<?= $unicumID ?>">
            <?
            }

            foreach ($arResult["ITEMS"] as $cell => $arItem):?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                $strMainID = $this->GetEditAreaId($arItem['ID']);
                ?>


                <div id="<?= $strMainID ?>"
                     class="t_<?= $unicumID ?> col-lg-<?= $arParams["BXREADY_LIST_LG_CNT"] ?> col-md-<?= $arParams["BXREADY_LIST_MD_CNT"] ?> col-sm-<?= $arParams["BXREADY_LIST_SM_CNT"] ?> col-xs-<?= $arParams["BXREADY_LIST_XS_CNT"] ?>">
                    <div class="bxr-classic-image-v1" data-uid="5">
                        <div class="bxr-element-container">
                            <div class="bxr-element-image">
                                <img v-bx-lazyload
                                     data-lazyload-dont-hide
                                     data-lazyload-src="<?= $arItem['PREVIEW_PICTURE']['SRC'] ?>"
                                     alt="<?= $arItem['NAME'] ?>"
                                     title="<?= $arItem['NAME'] ?>"
                                     src="<?= SITE_TEMPLATE_PATH ?>/images/ajax_loader.gif">
                            </div>
                            <div class="bxr-element-name">
                                <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= $arItem['NAME'] ?></a>
                            </div>
                            <div class="bxr-element-description"><?= $arItem['PREVIEW_TEXT'] ?></div>
                        </div>
                    </div>
                </div>


            <? endforeach; ?>
        </div>


        <? if ($arParams["BXREADY_LIST_SLIDER"] == "Y") { ?>
    </div>

    <?
} else {
    if ($arParams["DISPLAY_BOTTOM_PAGER"]) {
        ?><? echo $arResult["NAV_STRING"]; ?><?
    }
}
    ?>

    <script>
        BX.Vue.create({
            el: '#articles-main-page-<?= $unicumID ?>'
        });
    </script>

<? endif; ?>
