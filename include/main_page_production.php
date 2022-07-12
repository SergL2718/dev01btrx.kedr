<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*
 * Изменено: 01 марта 2020, воскресенье
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

$arSelect = Array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "PROPERTY_*", "NAME", "DETAIL_PICTURE", "DATE_ACTIVE_FROM", "CODE");
$arFilter = Array("IBLOCK_ID"=>33, "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array("RAND"=>"ASC"), $arFilter, false, Array("nPageSize"=>100), $arSelect);
?>

    <div class="container">
        <div class="wrapper">
            <div class="jcarousel-wrapper">
                <div id="jcarousel-production" class="jcarousel">
                    <ul>
                        <?
                        while($ob = $res->GetNextElement()) {
                            $arFields = $ob->GetFields();
                            $arProps = $ob->GetProperties();
                            $sectRes = CIBlockSection::GetByID($arFields["IBLOCK_SECTION_ID"]);
                            if($ar_res = $sectRes->GetNext())
                                $sect_code = $ar_res['CODE'];
                            ?>

                            <li>
                                <div class="element-image">
                                    <?if($arProps["PRISUTSTVIE_V_INTERNET_MAGAZINE"]["VALUE"] == "Да"){ ?>
                                        <a href="/catalog/<?=$sect_code;?>/<?=$arFields["CODE"]?>/" title="Этот товар вы можете купить в интернет-магазине">
                                            <div class="internet-magazin-icon" title="Этот товар вы можете купить в интернет-магазине"></div>
                                        </a>
                                    <? }?>
                                    <?if($arProps["PRISUTSTVIE_V_INTERNET_MAGAZINE"]["VALUE"] == "Да" && 1==2){ ?>
                                        <a href="/production/<?=$sect_code;?>/<?=$arFields["CODE"]?>/" title="Этот товар Вы можете заказать в нашей дилерской сети">
                                            <div class="internet-magazin-icon" title="Этот товар Вы можете заказать в нашей дилерской сети"></div>
                                        </a>
                                    <? }?>
                                    <?if($arFields["DETAIL_PICTURE"] != ""){ ?>
                                        <a href="/production/<?=$sect_code;?>/<?=$arFields["CODE"];?>/"><img src="<?=CFile::GetPath($arFields["DETAIL_PICTURE"])?>" title="<?=$arFields["NAME"]?>" alt="<?=$arFields["PREVIEW_TEXT"]?>"></a>
                                    <? } else { ?>
                                        <a href="/production/<?=$sect_code;?>/<?=$arFields["CODE"]?>/"><img src="/bitrix/tools/bxready/.default/no-image.png" title="<?=$arFields["NAME"]?>" alt="<?=$arFields["PREVIEW_TEXT"]?>"></a>
                                    <? } ?>
                                </div>
                                <div class="element-name" id="bx_brands_<?=$arFields["ID"]?>_name">
                                    <a href="/production/<?=$sect_code;?>/<?=$arFields["CODE"]?>/" title="<?=$arFields["NAME"]?>">
                                        <?=$arFields["NAME"]?>
                                    </a>
                                </div>
                            </li>
                            <?
                        }
                        ?>
                    </ul>
                </div>

                <a href="#" class="main-carousel jcarousel-control-p bxr-color-button slick-prev hidden-arrow slick-arrow">&nbsp;</a>
                <a href="#" class="main-carousel jcarousel-control-n bxr-color-button slick-next hidden-arrow slick-arrow">&nbsp;</a>

            </div>
        </div>
    </div>

    <script>
        (function($) {
            $(function() {
                var jcarouselProd = $('#jcarousel-production');
                jcarouselProd
                    .on('jcarousel:reload jcarousel:create', function () {
                        var width = jcarouselProd.innerWidth();
                        if($(window).width() >= 1080){
                            width = width / 6;
                        } else if($(window).width() >= 768 && $(window).width() < 992){
                            width = width / 3;
                        }
                        jcarouselProd.jcarousel('items').css('width', width + 'px');
                    })
                    .jcarousel({
                        wrap: 'circular'
                    });

                $('.jcarousel-control-p')
                    .jcarouselControl({
                        target: '-=1'
                    });

                $('.jcarousel-control-n')
                    .jcarouselControl({
                        target: '+=1'
                    });
            });
        })(jQuery);
    </script>
<?
