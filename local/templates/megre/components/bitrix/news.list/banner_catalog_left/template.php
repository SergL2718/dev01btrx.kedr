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
?>

<?foreach($arResult["ITEMS"] as $arItem):?>
    <?
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>
    <div class="catalog-banner catalog-banner__toggle" style="background-image: url(<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>);">
        <div class="catalog-banner__title">Эликсир Мегре</div>
        <div class="catalog-banner__text">Из целой шишки сибирского кедра</div>
        <a href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>" class="button button_dark">Купить</a>
    </div>
    <a href="#" class="catalog-banner-mobile" style="background-image: url(/local/templates/megre/images/temp/delivery-bg.jpg);">
        <div class="catalog-banner-mobile__title">Бесплатная доставка</div>
        <div class="catalog-banner-mobile__text">При заказе от 5000 рублей стандартная наземная доставка Почтой России и Boxberry (пункт выдачи) - бесплатно.</div>
    </a>
<?endforeach;?>

