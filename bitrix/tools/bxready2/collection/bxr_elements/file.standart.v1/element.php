<?
use Alexkova\Bxready\Draw2;
$elementDraw = \Alexkova\Bxready2\Draw::getInstance($this);

$name = strlen($arElement['DESCRIPTION'])>0 ? $arElement['DESCRIPTION'] : $arElement['ORIGINAL_NAME'];
$arExt = explode('.', $arElement['FILE_NAME']);
$ext = $arExt[count($arExt)-1];
?>
<div class="bxr-file-st-v1">
<a href="<?=$arElement['SRC']?>" title="<?=$name?>">
	<span class="bxr-file-ico bxr-file-ico-<?=$ext?>"></span>
	<?=$name?>
</a><p>Размер: <?=$elementDraw->GetStrFileSize($arElement['FILE_SIZE'])?></p>
</div>
<?
$dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));
$elementDraw->setAdditionalFile("CSS", $dirName."/include/style.css", false);?>