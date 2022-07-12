<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$arSelect = Array("ID", "NAME", "DETAIL_PICTURE", "DATE_ACTIVE_FROM", "CODE");
$arFilter = Array("IBLOCK_ID"=>30, "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>100), $arSelect);
?>

<div class="container">
    <div class="wrapper">
        <div class="jcarousel-wrapper">
            <div id="jcarousel-brands" class="jcarousel">
                <ul>
                    <?
                    while($ob = $res->GetNextElement()) {
                        $arFields = $ob->GetFields();
                        ?>
                        <li><a href="/brands/<?=$arFields["CODE"]?>/"><img src="<?=CFile::GetPath($arFields["DETAIL_PICTURE"])?>" title="<?=$arFields["NAME"]?>" alt="<?=$arFields["PREVIEW_TEXT"]?>"></a></li>
                        <?
                    }
                    ?>
                </ul>
            </div>

            <a href="#" class="jcarousel-control-prev bxr-color-button slick-prev hidden-arrow slick-arrow">&nbsp;</a>
            <a href="#" class="jcarousel-control-next bxr-color-button slick-next hidden-arrow slick-arrow">&nbsp;</a>

            <p class="jcarousel-pagination" style="display: none;"></p>
        </div>
    </div>
</div>

<script>
    (function($) {
        $(function() {
            var jcarousel = $('#jcarousel-brands');
            jcarousel
                .on('jcarousel:reload jcarousel:create', function () {
                    var width = jcarousel.innerWidth();
                    if($(window).width() >= 1080){
                        width = width / 6;
                    } else if($(window).width() >= 768 && $(window).width() < 992){
                        width = width / 3;
                    }
                    jcarousel.jcarousel('items').css('width', width + 'px');
                })
                .jcarousel({
                    wrap: 'circular'
                });

            $('.jcarousel-control-prev')
                .jcarouselControl({
                    target: '-=1'
                });

            $('.jcarousel-control-next')
                .jcarouselControl({
                    target: '+=1'
                });

            $('.jcarousel-pagination')
                .on('jcarouselpagination:active', 'a', function() {
                    $(this).addClass('active');
                })
                .on('jcarouselpagination:inactive', 'a', function() {
                    $(this).removeClass('active');
                })
                .on('click', function(e) {
                    e.preventDefault();
                })
                .jcarouselPagination({
                    perPage: 1,
                    item: function(page) {
                        return '<a href="#' + page + '">' + page + '</a>';
                    }
                });
        });
    })(jQuery);
</script>
<?
