

<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?

  use Alexkova\Bxready\Draw;

  $elementDraw = Draw::getInstance($this);
  $UID = 0;
  global $APPLICATION;


//  echo "<pre>"; print_r($arResult); echo "</pre>";

  if (is_array($arElement["PREVIEW_PICTURE"])){
  	$picture = $elementDraw->prepareImage($arElement["PREVIEW_PICTURE"]["ID"], $arMatrix);
  }else{
  	if (is_array($arElement["DETAIL_PICTURE"])){
  		$picture = $elementDraw->prepareImage($arElement["DETAIL_PICTURE"]["ID"], $arMatrix);
  	}
  }

  if (!is_array($picture) || strlen($picture["src"])<=0){
  	$picture = array("src" => $elementDraw->getDefaultImage());
  }
?>



<div class="reviews_classic_image_left_va5">
        <div class="reviews_image">
            <img src="<?=$picture["src"]?>" class="img-responsive">
        </div>
        <div class="reviews_text">
            &ldquo;<?=$arElement['PREVIEW_TEXT']?>&rdquo;
            <div class="reviews_author"><?=$arResult['ELEMENT']['AUTHOR']['VALUE']?></div>
            <div class="reviews_company"><?=$arResult['ELEMENT']['COMPANY']['VALUE']?></div>
        </div>
</div>
<?$elementDraw->setAdditionalFile("CSS","/bitrix/tools/bxready/library/elements/reviews_classic_image_left_va5/style.css");?>