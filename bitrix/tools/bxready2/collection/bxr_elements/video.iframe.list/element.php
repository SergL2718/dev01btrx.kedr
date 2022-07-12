<?
    use Alexkova\Bxready\Draw2;
    $elementDraw = \Alexkova\Bxready2\Draw::getInstance($this);

    $dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));
    $arElement["img"] = $dirName."/include/play.jpg";
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
    
    $s = strpos($arElement["path"], "youtu");
    if($s !== false) {
        $arElement["path"] = str_replace("https:", "", $arElement["path"]);
        $arElement["path"] = str_replace("http:", "", $arElement["path"]);
        $arElement["path"] = str_replace("\/\/youtu.be\/", "http://www.youtube.com/v/", $arElement["path"]);
        $arElement["path"] = str_replace("watch?v=", "v/", $arElement["path"]);
        
        $s = strpos($arElement["path"], "youtube.com/v/");
        if($s !== false) {
            $arElement["img"] = substr($arElement["path"], $s+14, strlen($arElement["path"]));
            $arElement["img"] = '//img.youtube.com/vi/'.$arElement["img"].'/maxresdefault.jpg';
        }        
    }
?>
<div class="row bxr-element-video-card-iframe-list">
    <div data-resize-width="0" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 "> 
        <div class="bxr-element-video-card-iframe-e">
            <img class='bxr-element-video-card-iframe-img' data-url="<?=$arElement["path"];?>" src="<?=$arElement["img"];?>">
        </div>
    </div>
    <div class="element-video-card-iframe-content col-lg-8 col-md-8 col-sm-6 col-xs-12 ">
        <p class="element-video-card-title" title="<?=TxtToHTML($arElement["title"], false);?>"><a data-url="<?=$arElement["path"];?>" href="#"><?=TxtToHTML($arElement["title"]);?></a></p>
        <span class="element-video-card-description"><?=TxtToHTML($arElement["desc"], true, 0 , "N", "Y", "N", "Y");?></span>
    </div>
</div>
<?
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/video.iframe.list/include/style.css", false);
$elementDraw->setAdditionalFile("JS", "/bitrix/tools/bxready2/collection/bxr_elements/video.iframe.list/include/script.js", false);
?>