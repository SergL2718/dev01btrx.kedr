<?
/*echo "<pre>";
print_r($arResult['ELEMENT']);
echo "</pre>";*/
?>
<div class="reviews_classic_image_va3">
    <div class="reviews_image">
        <img src="<?=$arResult['ELEMENT']['IMAGE']?>" alt="Станки для гибки и резки арматуры" class="img-responsive">
    </div>
    <div class="reviews_author"><?=$arResult['ELEMENT']['AUTHOR']['VALUE']?></div>
    <div class="reviews_company"><?=$arResult['ELEMENT']['COMPANY']['VALUE']?></div>
    <div class="reviews_text">&ldquo;<?=$arResult['ELEMENT']['TEXT']?>&rdquo;</div>
</div>