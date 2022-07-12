<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*
 * Изменено: 26 августа 2021, четверг
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

$useBrands = ('Y' == $arParams['BRAND_USE']);
$elementDraw = \Alexkova\Bxready\Draw::getInstance($this);
$elementDraw->setMarkerCollection('circle_vertical');

$isDiscount = false;
if ($arParams["USE_PRICE_COUNT"]):
    foreach ($arResult['PRICE_MATRIX']['MATRIX'] as $matrix):
	if ($matrix[0]['DISCOUNT_PRICE']):
		$isDiscount = intval(100-$matrix[0]['DISCOUNT_PRICE']*100/$matrix[0]['PRICE']);
	endif;
    endforeach;
elseif (count($arResult["OFFERS"]) > 0) :
    $isDiscount = intval($arResult['MIN_PRICE'][$arParams['PRICE_CODE'][0]]['DISCOUNT_DIFF_PERCENT']);
else:
    $isDiscount = intval($arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT']);
endif;
$markerGroup = array();
if ($arResult["PROPERTIES"]["RECOMMENDED"]["VALUE_XML_ID"] == "Y")
    $markerGroup["REC"] = true;
if ($arResult["PROPERTIES"]["NEWPRODUCT"]["VALUE_XML_ID"] == "Y")
    $markerGroup["NEW"] = true;
if ($arResult["PROPERTIES"]["SALELEADER"]["VALUE_XML_ID"] == "Y")
    $markerGroup["HIT"] = true;
if ($arResult["PROPERTIES"]["SPECIALOFFER"]["VALUE_XML_ID"] == "Y")
	$markerGroup["SALE"] = true;
if ($arResult["PROPERTIES"]["OFFER_WEEK"]["VALUE_XML_ID"] === "Y")
	$markerGroup["WEEK"] = true;
if ($isDiscount != 0) {
	$markerGroup["SALE"] = true;
	$markerGroup["DISCOUNT"] = $isDiscount;
}


if (strlen(COption::GetOptionString('alexkova.market', 'list_marker_type')) > 0){
    $bxreadyMarkers = COption::GetOptionString('alexkova.market', 'list_marker_type');
} else {
    $bxreadyMarkers = $arParams["BXREADY_LIST_MARKER_TYPE"];
}

$elementDraw->setMarkerCollection($bxreadyMarkers);
?>
<div class="ax-element-slider">
    <?$elementDraw->showMarkerGroup($markerGroup);?>
    <div class="ax-element-slider-main">
        <?if (count($arResult["MORE_PHOTO"]) > 0 && $arResult["MORE_PHOTO"][0]):?>
            <?foreach ($arResult["MORE_PHOTO"] as $key => $val):
                $title = ($arResult["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) ? $arResult["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arResult["NAME"];
                $alt = ($arResult["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"]) ? $arResult["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arResult["NAME"];
                $imgTitle = ($key > 0) ? $title.'. '.GetMessage("PHOTO").' �'.($key+1) : $title;
                $imgAlt = ($key > 0) ? $alt.'. '.GetMessage("PHOTO").' �'.($key+1) : $alt;
                ?>
                <a href="<?=$val["SRC"]?>" class="fancybox" rel="product-gallery" <?if ($key == 0) echo 'id="main-photo"'?>>
                    <img src="<?=$val["SRC"]?>" class="zoom-img" title="<?=$imgTitle?>" alt="<?=$imgAlt?>"
                         data-state="show" data-large="<?=$val["SRC"]?>" data-text-bottom="<?=$imgTitle?>" itemprop="image">
                </a>
            <?endforeach;?>
            <?if ($useBrands && $arResult["PROPERTIES"]["MANUFACTURER"]["VALUE"]) {?>
            <div class="brand-detail">
                <?$APPLICATION->IncludeComponent("bitrix:catalog.brandblock", "element_detail", array(
                    "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
                    "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                    "ELEMENT_ID" => $arResult['ID'],
                    "ELEMENT_CODE" => "",
                    "PROP_CODE" => $arParams['BRAND_PROP_CODE'],
                    "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                    "CACHE_TIME" => $arParams['CACHE_TIME'],
                    "CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
                    "WIDTH" => "176",
                    "HEIGHT" => "76"
                    ),
                    $component,
                    array("HIDE_ICONS" => "Y")
                );?>
            </div>
            <?}?>
        <?else:?>
            <div class="bxr-no-image-detail-wrap" id="main-photo">
                <img src="<?=$elementDraw->getDefaultImage()?>">
            </div>
        <?endif;?>
    </div>
    <?if (count($arResult["MORE_PHOTO"])>1):?>
        <div class="ax-element-slider-nav hidden-xs">
            <?foreach ($arResult["MORE_PHOTO"] as $key => $val):
                $title = ($arResult["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) ? $arResult["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arResult["NAME"];
                $alt = ($arResult["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"]) ? $arResult["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arResult["NAME"];
                $imgTitle = ($key > 0) ? $title.'. '.GetMessage("PHOTO").' �'.($key+1) : $title;
                $imgAlt = ($key > 0) ? $alt.'. '.GetMessage("PHOTO").' �'.($key+1) : $alt;?>
            <div>
                <div class="slide-wrap <?if ($key == 0) echo 'first-slide'?>" data-item="<?=$val["ITEM_ID"]?>" <?if ($key == 0) echo 'id="main-photo-small"'?>>
                    <img src="<?=$val["SRC"]?>" title="<?=$imgTitle?>" alt="<?=$imgAlt?>" itemprop="image">
                </div>
            </div>
            <?endforeach;?>
        </div>
    <?endif;?>
</div>

<?if (count($arResult["MORE_PHOTO"]) > 0 && $arResult["MORE_PHOTO"][0]):?>
    <script>
    //fancy init    
    $("a.fancybox").fancybox();

    //slick sliders init
    $('.ax-element-slider-main').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        speed: 500,
        arrows: true,
        prevArrow: '<button type="button" class="bxr-color-button slick-prev"></button>',
        nextArrow: '<button type="button" class="bxr-color-button slick-next"></button>',
        fade: true,
        dots: false,
        infinite: true,
        cssEase: 'linear',
        asNavFor: '.ax-element-slider-nav',
        slide: 'a'
    });
    $('.ax-element-slider-nav').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        speed: 500,
        asNavFor: '.ax-element-slider-main',
        arrows: true,
        prevArrow: '<button type="button" class="bxr-color-button slick-prev"></button>',
        nextArrow: '<button type="button" class="bxr-color-button slick-next"></button>',
        dots: true,
        infinite: true,
        centerMode: false,
        focusOnSelect: true,
        slide: 'div'
    });

    $('.ax-element-slider-nav').on('afterChange', function(event, slick, currentSlide, nextSlide){
      $($('.ax-element-slider-nav .slick-track').children('.slick-slide')).css("border", "1px solid #f6f6f6");
      $($($('.ax-element-slider-nav .slick-track').children('.slick-current'))[0]).css("border", "1px solid #000");
    });

    $('.ax-element-slider-nav').find('.slick-slide').on('click', function(){
      $($('.ax-element-slider-nav .slick-track').children('.slick-slide')).css("border", "1px solid #f6f6f6");
      $($($('.ax-element-slider-nav .slick-track').children('.slick-current'))[0]).css("border", "1px solid #000");  
    });

    $('.ax-element-slider-main').on('afterChange', function(event, slick, currentSlide, nextSlide){
      data = $('.ax-element-slider-main').find('.slick-active').data('slick-index');
      $($('.ax-element-slider-nav .slick-track').children('.slick-slide')).css("border", "1px solid #f6f6f6");
      $($('.ax-element-slider-nav .slick-track').children('.slick-slide[data-slick-index='+data+']')).css("border", "1px solid #000");  
    });

    $(document).ready(function() {
        $($($('.ax-element-slider-nav .slick-track').children('.slick-current'))[0]).css("border", "1px solid #000");
        $(".ax-element-slider-nav").children(".slick-prev").css("display", "none");
        $(".ax-element-slider-nav").children(".slick-next").css("display", "none");
        $(".ax-element-slider-main").children(".slick-prev").css("display", "none");
        $(".ax-element-slider-main").children(".slick-next").css("display", "none");
        setSlideSizes();
    });

    $(document).on("mouseover", ".ax-element-slider-nav", function() {
        hideArrows(this, "block");
    });

    $(document).on("mouseover", ".ax-element-slider-main", function() {
        hideArrows(this, "block");
    });

    $(document).on("mouseout", ".ax-element-slider-nav", function() {
        hideArrows(this, "none");
    });

    $(document).on("mouseout", ".ax-element-slider-main", function() {
        hideArrows(this, "none");
    });

    $(window).resize(function() {
        setSlideSizes();
    });

    function hideArrows(elem, display) {
        $(elem).children(".slick-prev").css("display", display);
        $(elem).children(".slick-next").css("display", display);
    }

    function setSlideSizes() {
        border = 4;
        width = $('.ax-element-slider-nav .slick-list .slick-track .slick-slide').width();
        height = width + border;
        imgSize = width - 10;
        $('.ax-element-slider-nav .slick-list .slick-track .slick-slide').height(height);
        $('.ax-element-slider-nav .slick-list .slick-track .slick-slide .slide-wrap').css({'width': height, 'height': height, 'line-height': height+'px'})
    }

    //zoom plagin init
    <?if ($arParams["ZOOM_ON"] == "Y") {?>
        jQuery(function(){

            $(".zoom-img").imagezoomsl({
              zoomrange: [2.12, 12],
              magnifiersize: [300, 300],
              scrollspeedanimate: 10,
              loopspeedanimate: 5,
              cursorshadeborder: "1px solid black",
              magnifiereffectanimate: "slideIn",	
              magnifierborder: "1px solid #eee",
              zindex: 1000,
            });

            $(document).on("click", ".tracker", function() {
                $('.zoom-img').closest('a.slick-active').trigger("click");
            })
        });   
    <?}?>
    </script>
<?endif;?>