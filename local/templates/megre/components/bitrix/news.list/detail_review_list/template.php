<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

//echo "<pre>"; print_r($arResult); echo "</pre>";
$FULL_RATE = 0;
$STAR_RATE = 0;
$STAR_QUALITY = 0;
$STAR_USE = 0;
$STAR_BENEFIT = 0;
$STAR_RATE_CNT = 0;
$STAR_QUALITY_CNT = 0;
$STAR_USE_CNT = 0;
$STAR_BENEFIT_CNT = 0;

$FULL_RATE_SUMM = 0;
$FULL_RATE_CNT = 0;
foreach($arResult["ITEMS"] as $arItem) {

	if ($arItem["PROPERTIES"]["STAR_RATE"]["VALUE"]) {
		$FULL_RATE_SUMM = $FULL_RATE_SUMM + $arItem["PROPERTIES"]["STAR_RATE"]["VALUE"];
		$STAR_RATE = $STAR_RATE + $arItem["PROPERTIES"]["STAR_RATE"]["VALUE"];
		$FULL_RATE_CNT++;
		$STAR_RATE_CNT++;
	}
	if ($arItem["PROPERTIES"]["STAR_QUALITY"]["VALUE"]) {
		$FULL_RATE_SUMM = $FULL_RATE_SUMM + $arItem["PROPERTIES"]["STAR_QUALITY"]["VALUE"];
		$STAR_QUALITY = $STAR_QUALITY + $arItem["PROPERTIES"]["STAR_QUALITY"]["VALUE"];
		$FULL_RATE_CNT++;
		$STAR_QUALITY_CNT++;
	}
	if ($arItem["PROPERTIES"]["STAR_USE"]["VALUE"]) {
		$FULL_RATE_SUMM = $FULL_RATE_SUMM + $arItem["PROPERTIES"]["STAR_USE"]["VALUE"];
		$STAR_USE = $STAR_USE + $arItem["PROPERTIES"]["STAR_USE"]["VALUE"];
		$FULL_RATE_CNT++;
		$STAR_USE_CNT++;
	}
	if ($arItem["PROPERTIES"]["STAR_BENEFIT"]["VALUE"]) {
		$FULL_RATE_SUMM = $FULL_RATE_SUMM + $arItem["PROPERTIES"]["STAR_BENEFIT"]["VALUE"];
		$STAR_BENEFIT = $STAR_BENEFIT + $arItem["PROPERTIES"]["STAR_BENEFIT"]["VALUE"];
		$FULL_RATE_CNT++;
		$STAR_BENEFIT_CNT++;
	}
}
if ($FULL_RATE_SUMM) $FULL_RATE = round($FULL_RATE_SUMM / $FULL_RATE_CNT);

if ($STAR_RATE) {
	$STAR_RATE = round($STAR_RATE / $STAR_RATE_CNT);
}
if ($STAR_QUALITY) {
	$STAR_QUALITY = round($STAR_QUALITY / $STAR_QUALITY_CNT);
    $STAR_QUALITY = round($STAR_QUALITY*100/5);
}
if ($STAR_USE) {
	$STAR_USE = round($STAR_USE / $STAR_USE_CNT);
    $STAR_USE = round($STAR_USE*100/5);
}
if ($STAR_BENEFIT) {
	$STAR_BENEFIT = round($STAR_BENEFIT / $STAR_BENEFIT_CNT);
    $STAR_BENEFIT = round($STAR_BENEFIT*100/5);
}
?>


<div class="product-review__total">
    <div class="product-review__total-info">
        <div class="product-review__total-image"><img
                    src="<?= $arParams["PRODUCT_PICTURE_SRC"] ?>"
                    alt="<?= $arParams["PRODUCT_PICTURE_ALT"] ?>">
        </div>
        <div class="product-review__total-content">
            <div class="product-review__total-label">Общая оценка</div>
            <div class="product-review__total-stars">
                <?if($FULL_RATE)echo str_repeat('<img src="'.SITE_TEMPLATE_PATH.'/images/icons/star-filled.svg" alt=""/>', $FULL_RATE);
                echo str_repeat('<img src="'.SITE_TEMPLATE_PATH.'/images/icons/star.svg" alt=""/>', 5-$FULL_RATE);?>
            </div>
            <div class="product-review__total-from">На основе <b><?echo count($arResult["ITEMS"]);?></b> <?echo pluralForm(count($arResult["ITEMS"]), "отзыв", "отзыва", "отзывов")?></div>
        </div>
    </div>
    <div class="product-review__total-stats">
        <div class="product-review__total-row">
            <div class="product-review__total-name">Качество</div>
            <div class="product-review__total-progress">
                <div class="product-review__total-progress-line" style="width: <?=$STAR_QUALITY?>%"></div>
            </div>
        </div>
        <div class="product-review__total-row">
            <div class="product-review__total-name">Простота применения</div>
            <div class="product-review__total-progress">
                <div class="product-review__total-progress-line" style="width: <?=$STAR_USE?>%"></div>
            </div>
        </div>
        <div class="product-review__total-row">
            <div class="product-review__total-name">Польза</div>
            <div class="product-review__total-progress">
                <div class="product-review__total-progress-line" style="width: <?=$STAR_BENEFIT?>%"></div>
            </div>
        </div>
    </div>
</div>
<?$n = 0;?>
<?php
if(count($arResult["ITEMS"])){
?>
<div class="catalog-sorting">
    <div class="catalog-sorting__label">Сортировать по:</div>
    <div class="custom-select">
        <select id="sort_reviews">
            <option value="desc" selected>самые новые</option>
            <option value="asc">самые старые</option>
        </select>
    </div>
</div>
<div class="product-review__list">

    <?foreach($arResult["ITEMS"] as $arItem){?>
        <?
        $FULL_RATE = 0;
        $FULL_RATE_SUMM = 0;
        $FULL_RATE_CNT = 0;
        if($arItem["PROPERTIES"]["STAR_RATE"]["VALUE"]){
			$FULL_RATE_SUMM = $FULL_RATE_SUMM + $arItem["PROPERTIES"]["STAR_RATE"]["VALUE"];
			$FULL_RATE_CNT++;
        }
        if($arItem["PROPERTIES"]["STAR_QUALITY"]["VALUE"]){
			$FULL_RATE_SUMM = $FULL_RATE_SUMM + $arItem["PROPERTIES"]["STAR_QUALITY"]["VALUE"];
			$FULL_RATE_CNT++;
        }
        if($arItem["PROPERTIES"]["STAR_USE"]["VALUE"]){
			$FULL_RATE_SUMM = $FULL_RATE_SUMM + $arItem["PROPERTIES"]["STAR_USE"]["VALUE"];
			$FULL_RATE_CNT++;
        }
        if($arItem["PROPERTIES"]["STAR_BENEFIT"]["VALUE"]){
			$FULL_RATE_SUMM = $FULL_RATE_SUMM + $arItem["PROPERTIES"]["STAR_BENEFIT"]["VALUE"];
			$FULL_RATE_CNT++;
        }
		if($FULL_RATE_SUMM)$FULL_RATE = round($FULL_RATE_SUMM/$FULL_RATE_CNT);
        ?>
        <div class="review-card<?if($n>=3)echo " hidden";?>">
            <div class="review-card__info">
                <div class="review-card__info-stars">
                    <?
                    if($FULL_RATE)echo str_repeat('<img src="'.SITE_TEMPLATE_PATH.'/images/icons/star-filled.svg" alt=""/>', $FULL_RATE);
					echo str_repeat('<img src="'.SITE_TEMPLATE_PATH.'/images/icons/star.svg" alt=""/>', 5-$FULL_RATE);
                    ?>
                </div>
                <div class="review-card__info-user">
                    <b><?=$arItem["PROPERTIES"]["REVIEW_NAME"]["VALUE"]?></b><span><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span><span><?=$arItem["PROPERTIES"]["REVIEW_CITY"]["VALUE"]?></span></div>
            </div>
            <div class="review-card__content">
                <div class="block-title"><?=$arItem["PROPERTIES"]["REVIEW_TITLE"]["VALUE"]?></div>
                <div class="review-card__content-text">
                    <p><?echo ($arItem["PREVIEW_TEXT"])?$arItem["PREVIEW_TEXT"]:$arItem["NAME"];?></p>
                    <p><b>Плюсы<br/></b><?=$arItem["PROPERTIES"]["REVIEW_PLUS"]["VALUE"]?></p>
                    <p><b>Минусы<br/></b><?=$arItem["PROPERTIES"]["REVIEW_MINUS"]["VALUE"]?></p>
                </div>
            </div>
        </div>
        <?$n++;?>
    <?}?>

</div>
<?php
}
?>

<div class="product-review__more">
    <div class="button button_primary" data-modal="modal-feedback">Оставить отзыв</div>
    <?if($n>=3){?><div class="link-more">ПОКАЗАТЬ БОЛЬШЕ</div><?}?>
</div>


    <div class="modal" id="modal-feedback">
        <div class="modal-inner">
            <div class="modal-container">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-close" style="margin: 0 0 50px;" data-modal-close>
                            <div class="link">ВЕРНУТЬСЯ НАЗАД</div>
                        </div>
                    </div>
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>
    </div>

<?php
/*
?>
<div class="news-list">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<p class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
			<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img
						class="preview_picture"
						border="0"
						src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
						width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
						height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
						alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
						title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
						style="float:left"
						/></a>
			<?else:?>
				<img
					class="preview_picture"
					border="0"
					src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
					width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
					height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
					alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
					title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
					style="float:left"
					/>
			<?endif;?>
		<?endif?>
		<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
			<span class="news-date-time"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
		<?endif?>
		<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
			<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><b><?echo $arItem["NAME"]?></b></a><br />
			<?else:?>
				<b><?echo $arItem["NAME"]?></b><br />
			<?endif;?>
		<?endif;?>
		<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
			<?echo $arItem["PREVIEW_TEXT"];?>
		<?endif;?>
		<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
			<div style="clear:both"></div>
		<?endif?>
		<?foreach($arItem["FIELDS"] as $code=>$value):?>
			<small>
			<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
			</small><br />
		<?endforeach;?>
		<?foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
			<small>
			<?=$arProperty["NAME"]?>:&nbsp;
			<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
				<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
			<?else:?>
				<?=$arProperty["DISPLAY_VALUE"];?>
			<?endif?>
			</small><br />
		<?endforeach;?>
	</p>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>
