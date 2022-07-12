<?
use Alexkova\Bxready\Draw;
$elementDraw = \Alexkova\Bxready\Draw::getInstance();

?>

<div class="bxr-news-short-list-v1" data-uid="<?=$arElementParams["UNICUM_ID"]?>">
	<div class="bxr-element-container">
		<?if (($arElement['DATE_ACTIVE_FROM'] || $arElement['DATE_ACTIVE_TO'])) {?> 
                            <div class="bxr-element-date"> 
                                <div class="bxr-color"> 
                                    <?if ($arElement['DATE_ACTIVE_FROM']) {?> 
                                        <?=date($arElementParams['ACTIVE_DATE_FORMAT'],  strtotime($arElement['DATE_ACTIVE_FROM']))?> 
                                    <?}?> 
                                    <?if ($arElement['DATE_ACTIVE_FROM'] && $arElement['DATE_ACTIVE_TO']) {?> 
                                     -  
                                    <?}?> 
                                    <?if ($arElement['DATE_ACTIVE_TO']) {?> 
                                        <?=date($arElementParams['ACTIVE_DATE_FORMAT'],  strtotime($arElement['DATE_ACTIVE_TO']))?> 
                                     <?}?> 
                                </div> 
                            </div> 
                        <?}?> 
		<div class="bxr-element-name">
			<a class="bxr-gray-content" href="<?=$arElement['DETAIL_PAGE_URL']?>"><?=$arElement['NAME']?></a>
		</div>
	</div>
</div>
<?
/*
* Pri neobhodimosti podkluchaem nuzhnye biblioteki
*/

$dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));
//$elementDraw->setAdditionalFile("JS", $dirName."/include/script.js", true);
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready/library/elements/news.short.list.v1/include/style.css", false);?>