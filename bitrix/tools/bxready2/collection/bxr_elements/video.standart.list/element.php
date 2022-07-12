<?
use Alexkova\Bxready2\Draw;
$elementDraw = Draw::getInstance($this);

$dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));
$elementDraw->setAdditionalFile("CSS", $dirName."/include/style.css", false);?>
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
<div class="row bxr-element-video-card-mej-list">
    <div data-resize-width="0" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 "> 
        <div class="bxr-element-video-card-mej-e video-fullscreen">
            <video height="100%" width="100%" style="width:100%;height:100%;" preload="none"  controls>
                <source src="<?=$arElement["path"];?>" type="<?=$video_type;?>">
            </video>
        </div>
    </div>
    <div class="element-video-card-mej-content col-lg-8 col-md-8 col-sm-6 col-xs-12 ">
        <p class="element-video-card-title" title="<?=TxtToHTML($arElement["title"], false);?>"><?=TxtToHTML($arElement["title"]);?></p>
        <span class="element-video-card-description"><?=TxtToHTML($arElement["desc"], true, 0 , "N", "Y", "N", "Y");?></span>
    </div>
</div>
<?
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/video.standart.list/include/style.css", false);
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/video.standart.list/include/mediaelementplayer.min.css", false);
$elementDraw->setAdditionalFile("JS", "/bitrix/tools/bxready2/collection/bxr_elements/video.standart.list/include/mediaelement-and-player.min.js", false);
$elementDraw->setAdditionalFile("JS", "/bitrix/tools/bxready2/collection/bxr_elements/video.standart.list/include/script.js", false);
?>