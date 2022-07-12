<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?

use Alexkova\Bxready\Draw;

$elementDraw = \Alexkova\Bxready\Draw::getInstance($this);
$elementDraw->setCurrentTemplate($this);


$this->setFrameMode(true);

//echo '<pre>' . print_r($arResult["ITEMS"], 1) . '</pre>';

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

    <div class="row bxr-list">

        <? foreach ($arResult["ITEMS"] as $cell => $arItem): ?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            $strMainID = $this->GetEditAreaId($arItem['ID']);
            ?>

            <div id="<?= $strMainID ?>" class="t_<?= $unicumID ?> col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="bxr-classic-image-v2" data-uid="1" data-resize="1">
                    <div class="bxr-section-container">
                        <div class="bxr-element-image">
                            <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><img
                                        src="<?= $arItem['PICTURE'] ?>"></a>
                        </div>
                        <div class="bxr-element-content">
                            <div class="bxr-element-name">
                                <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= $arItem['NAME'] ?></a>
                            </div>
                            <div class="bxr-element-description"><?= $arItem['PREVIEW_TEXT'] ?></div>
                            <div class="bxr-element-action">
                                <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"
                                   class="bxr-border-color-button">Подробнее </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <? endforeach; ?>
    </div>

    <?
    if ($arParams["DISPLAY_BOTTOM_PAGER"]) {
        ?><? echo $arResult["NAV_STRING"]; ?><?
    }
    ?>

<? endif; ?>




