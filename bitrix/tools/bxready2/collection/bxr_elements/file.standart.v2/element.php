<?
use Alexkova\Bxready2\Draw;
IncludeModuleLangFile(__FILE__);
$elementDraw = Draw::getInstance($this);

$name = strlen($arElement['DESCRIPTION'])>0 ? $arElement['DESCRIPTION'] : $arElement['ORIGINAL_NAME'];
$arExt = explode('.', $arElement['FILE_NAME']);
$ext = $arExt[count($arExt)-1];
?>
<div class="bxr-file-st-v1">
    <a href="<?=$arElement['SRC']?>" class="bxr-font-color bxr-font-color-hover" title="<?=$name?>">
        <div class="bx-file-icon-container-medium">
            <div class="bx-file-icon-cover">
                <div class="bx-file-icon-corner">
                    <div class="bx-file-icon-corner-fix"></div>
                </div>
                <div class="bx-file-icon-images"></div>
            </div>
            <div class="bx-file-icon-label"><?=$ext?></div>
        </div>
        <?=$name?>
    </a>
<p><?=GetMessage("BXR_SIZE");?>: <?=$elementDraw->GetStrFileSize($arElement['FILE_SIZE'])?></p>
</div>
<?include 'epilog.php';?>