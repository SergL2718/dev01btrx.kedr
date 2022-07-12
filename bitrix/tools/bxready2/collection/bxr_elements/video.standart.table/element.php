<?
use Alexkova\Bxready2\Draw;
$elementDraw = Draw::getInstance($this);

$dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));
?>
<?
    $video_type = "video/mp4";

    if(strripos($arElement["path"], "youtu"))
        $video_type = "video/youtube";

    if(strripos($arElement["path"], ".webm"))
        $video_type = "video/webm";  

    if(strripos($arElement["path"], ".ogv"))
        $video_type = "video/ogg";
    
    if(strripos($arElement["path"], ".mp4"))
        $video_type = "video/mp4";
    
    if(strripos($arElement["path"], ".flv"))
        $video_type = "video/x-flv";
    
?>
<div data-resize-width="0" class="bxr-element-video-card-mej-col <?if(!isset($arElementParams["fullSize"]) || !$arElementParams["fullSize"]) echo "col-lg-4 col-md-4 col-sm-6" ?> col-xs-12 "> 
    <div class="bxr-element-video-card-mej video-fullscreen">
        <video height="100%" width="100%" style="width:100%;height:100%;" preload="none"  controls>
            <source src="<?=$arElement["path"];?>" type="<?=$video_type;?>">
        </video>
    </div>
    <div class="element-video-card-mej-content">
        <p class="element-video-card-title" title="<?=TxtToHTML($arElement["title"], false);?>"><?=TxtToHTML($arElement["title"]);?></p>
         <?/*?><span class="element-video-card-description"><?=TxtToHTML($arElement["desc"], true, 0 , "N", "Y", "N", "Y");?></span><?*/?>
    </div>
</div>
<?
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/video.standart.table/include/style.css", false);
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/video.standart.table/include/mediaelementplayer.min.css", false);
$elementDraw->setAdditionalFile("JS", "/bitrix/tools/bxready2/collection/bxr_elements/video.standart.table/include/mediaelement-and-player.min.js", false);
$elementDraw->setAdditionalFile("JS", "/bitrix/tools/bxready2/collection/bxr_elements/video.standart.table/include/script.js", false);
?>