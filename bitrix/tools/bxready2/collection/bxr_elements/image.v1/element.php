<?
use Alexkova\Bxready2\Draw;
$elementDraw = Draw::getInstance($this);

$dirName = str_replace($_SERVER["DOCUMENT_ROOT"],'', dirname(__FILE__));
$elementDraw->setAdditionalFile("CSS", $dirName."/include/style.css", false);

if(!isset($arElementParams["BXR_PRST_ELEMENT_HEIGHT"]) || empty($arElementParams["BXR_PRST_ELEMENT_HEIGHT"]))
    $arElementParams["BXR_PRST_ELEMENT_HEIGHT"] = 140;

if(!isset($arElementParams["BXR_PRST_ELEMENT_WIDTH"]) || empty($arElementParams["BXR_PRST_ELEMENT_WIDTH"]))
    $arElementParams["BXR_PRST_ELEMENT_WIDTH"] = 100;

$arMatrix = array('width' => 999, 'height' => $arElementParams["BXR_PRST_ELEMENT_HEIGHT"]);

if (is_array($arElement["PREVIEW_PICTURE"]))
	$picture = $elementDraw->prepareImage($arElement["PREVIEW_PICTURE"]["ID"], $arMatrix);
else {
	if (is_array($arElement["DETAIL_PICTURE"]))
		$picture = $elementDraw->prepareImage($arElement["DETAIL_PICTURE"]["ID"], $arMatrix);
}

if (is_array($arElement["DETAIL_PICTURE"]))
	$big_picture = $arElement["DETAIL_PICTURE"];
else {
	if (is_array($arElement["PREVIEW_PICTURE"]))
		$big_picture = $arElement["PREVIEW_PICTURE"];
}

if (!is_array($picture) || strlen($picture["src"])<=0)
	$picture = array("src" => $elementDraw->getDefaultImage());
?>
<div class="bxr-image-v1 <?if(isset($arElementParams["BXR_PRST_GRAYSCALE"]) && $arElementParams["BXR_PRST_GRAYSCALE"]=="Y") echo " bxr-grayscale ";?>  <?if(!isset($arElementParams["BXR_PRST_USE_BACKGROUND"]) || $arElementParams["BXR_PRST_USE_BACKGROUND"]=="Y") echo " bxr-use-background ";?> <?if(!isset($arElementParams["BXR_PRST_SHOW_BORDER"]) || $arElementParams["BXR_PRST_SHOW_BORDER"]=="Y") echo " bxr-border-color bxr-border-color-hover ";?>" data-uid="<?=$arElementParams["UNICUM_ID"]?>">
	<div class="bxr-element-container">
		<div class="bxr-element-image" style="width:<?=$arElementParams["BXR_PRST_ELEMENT_WIDTH"];?>%; height: <?=$arMatrix['height']?>px">
            <?if(isset($arElementParams["BXR_PRST_ELEMENT_REGIME"]) && $arElementParams["BXR_PRST_ELEMENT_REGIME"]=="fancybox"):?>
                <a href="<?=$big_picture["SRC"]?>" class="bxr-fancy <?=$arElementParams['ADD_CLASS']?>" rel="<?=$arElementParams['ADD_REL']?>" data-fancybox="<?=$arElementParams['ADD_REL']?>">
                    <img class="bxr-fancybox" style="max-height: <?=$arMatrix['height']?>px" src="<?=$picture["src"]?>" alt="<?=$arElement['NAME']?>" title="<?=$arElement['NAME']?>">
                </a>
            <?else:?>
                <a href="<?=$arElement['DETAIL_PAGE_URL']?>" class="<?=$arElementParams['ADD_CLASS']?>" rel="<?=$arElementParams['ADD_REL']?>" data-fancybox="<?=$arElementParams['ADD_REL']?>">
                    <img class="bxr-fancybox" style="max-height: <?=$arMatrix['height']?>px" src="<?=$picture["src"]?>" alt="<?=$arElement['NAME']?>" title="<?=$arElement['NAME']?>">
                </a>
            <?endif;?>
		</div>
            <?if(!isset($arElementParams["BXR_PRST_SHOW_NAME"]) || $arElementParams["BXR_PRST_SHOW_NAME"]=="Y"):?>
                <div class="bxr-element-name" style="width:<?=$arElementParams["BXR_PRST_ELEMENT_WIDTH"];?>%">
                    <?if(!isset($arElementParams["BXR_PRST_ELEMENT_REGIME"]) || $arElementParams["BXR_PRST_ELEMENT_REGIME"]=="normal"):?>
                         <a href="<?=$arElement['DETAIL_PAGE_URL']?>"><?=$arElement['NAME']?></a>
                    <?else:?>
                        <?=$arElement['NAME']?>
                    <?endif;?>
                </div>
            <?endif;?>
	</div>
</div>
<?include 'epilog.php';?>