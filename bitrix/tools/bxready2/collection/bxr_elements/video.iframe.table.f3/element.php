<?
    use Alexkova\Bxready2\Draw;
    $elementDraw = Draw::getInstance($this);

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
        $arElement["path"] = str_replace("https://", "", $arElement["path"]);
        $arElement["path"] = str_replace("http://", "", $arElement["path"]);
        $arElement["path"] = str_replace("www.", "", $arElement["path"]);
        $arElement["path"] = str_replace("embed/", "v/", $arElement["path"]);
        $arElement["path"] = str_replace("//youtu.be/", "youtube.com/v/", $arElement["path"]);
        $arElement["path"] = str_replace("watch?v=", "v/", $arElement["path"]);
        $arElement["path"] = str_replace("/v/", "/embed/", $arElement["path"]);
        $arElement["path"] = str_replace("//", "", $arElement["path"]);    
        $arElement["path"] = "//www.".$arElement["path"];
 
        $s = strpos($arElement["path"], "youtube.com/embed/");
        if($s !== false) {
            $arElement["img"] = substr($arElement["path"], $s+18, strlen($arElement["path"]));
            $arElement["img"] = '//img.youtube.com/vi/'.$arElement["img"].'/maxresdefault.jpg'; //maxresdefault 1 2 3               
        }        
    }
?>
<div data-resize-width="0" class="bxr-element-video-card-iframe-col <?if(!isset($arElementParams["fullSize"]) || !$arElementParams["fullSize"]) echo "col-lg-4 col-md-4 col-sm-6" ?> col-xs-12 "> 
    <div class="bxr-element-video-card-iframe">
        <img class='bxr-element-video-card-iframe-img' data-url="<?=$arElement["path"];?>" src="<?=$arElement["img"];?>" alt="video">
    </div>
    <div class="element-video-card-iframe-content">
        <p class="element-video-card-title" title="<?=TxtToHTML($arElement["title"], false);?>"><a data-url="<?=$arElement["path"];?>" href="#"><?=TxtToHTML($arElement["title"]);?></a></p>
    </div>
</div>
<?
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/video.iframe.table/include/style.css", false);
$elementDraw->setAdditionalFile("JS", "/bitrix/tools/bxready2/collection/bxr_elements/video.iframe.table/include/script.js", false);
?>