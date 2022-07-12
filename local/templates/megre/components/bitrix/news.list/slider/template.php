<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
//echo "<pre>"; print_r($arParams["SECTIONS_ARR"]); echo "</pre>";
?>


    <div class="blog-slider">
        <div class="container">
            <div class="block-title">БЛОГ</div>
            <div class="swiper">
                <div class="swiper-arrow-prev"></div>
                <div class="swiper-arrow-next"></div>
                <div class="swiper-wrapper">
                    <?foreach($arResult["ITEMS"] as $arItem){?>
                    <div class="swiper-slide"><a class="blog-card" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                            <div class="blog-card__image"><img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
                                                               alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"></div>
                            <div class="blog-card__type blog-type">
                                <div class="blog-type__image"><?if($arParams["SECTIONS_ARR"][$arItem["IBLOCK_SECTION_ID"]]["PICTURE"]){?><img
                                            src="<?echo $arParams["SECTIONS_ARR"][$arItem["IBLOCK_SECTION_ID"]]["PICTURE"]?>"
                                            alt=""><?}?></div>
                                <div class="blog-type__name"><?=$arItem["IBLOCK_SECTION_NAME"]?></div>
                            </div>
                            <div class="blog-card__name"><?=$arItem["NAME"]?></div>
                            <div class="blog-card__text"><?=$arItem["PREVIEW_TEXT"]?></div>
                            <div class="blog-card__more">
                                <div class="link-more">ЧИТАТЬ</div>
                            </div>
                        </a></div>
                    <?}?>

                </div>
            </div>
        </div>
    </div>

