<?
use Alexkova\Bxready2\Draw;
$elementDraw = Draw::getInstance($this);
global $APPLICATION;
?>
<div data-resize-width="0" class="bxr-element-video-card-bitrix-col <?if(!isset($arElementParams["fullSize"]) || !$arElementParams["fullSize"]) echo "col-lg-4 col-md-4 col-sm-6" ?> col-xs-12 "> 
    <div class="bxr-element-video-card-bitrix">
        <?$APPLICATION->IncludeComponent(
            "bitrix:player", 
            ".default", 
            array(
                    "ADDITIONAL_FLASHVARS" => "",
                    "ADVANCED_MODE_SETTINGS" => "N",
                    "ALLOW_SWF" => "N",
                    "AUTOSTART" => "N",
                    "BUFFER_LENGTH" => "10",
                    "CONTROLBAR" => "bottom",
                    "LOGO" => "",
                    "LOGO_LINK" => "",
                    "LOGO_POSITION" => "none",
                    "MUTE" => "N",
                    "PATH" => $arElement["path"],
                    "PLAYBACK_RATE" => "1",
                    "PLAYER_ID" => "",
                    "PLAYER_TYPE" => "videojs",
                    "PLAYLIST" => "none",
                    "PLAYLIST_DIALOG" => "",
                    "PLAYLIST_SIZE" => "180",
                    "PLUGINS" => "",
                    "PRELOAD" => "N",
                    "PREVIEW" => "",
                    "PROVIDER" => "http",
                    "REPEAT" => "none",
                    "SHOW_CONTROLS" => "Y",
                    "SHUFFLE" => "N",
                    "SIZE_TYPE" => "fluid",
                    "SKIN" => "",
                    "SKIN_PATH" => "/bitrix/components/bitrix/player/videojs/skins",
                    "START_ITEM" => "0",
                    "START_TIME" => "0",
                    "STREAMER" => "",
                    "USE_PLAYLIST" => "N",
                    "VOLUME" => "90",
                    "WMODE" => "opaque",
                    "COMPONENT_TEMPLATE" => ".default"
            ),
            false
        );?> 
    </div>
    <div class="element-video-card-bitrix-content">
        <p class="element-video-card-title bxr-font-color" title="<?=TxtToHTML($arElement["title"], false);?>"><?=TxtToHTML($arElement["title"]);?></p>
    </div>
</div>
<?$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready2/collection/bxr_elements/video.bitrix.table/include/style.css", false);?>