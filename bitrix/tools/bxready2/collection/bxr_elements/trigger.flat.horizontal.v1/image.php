<?php
?>
<div class="bxr-element-image">
        <?if ( !empty($arElement["PROPERTIES"]["BXR_URL"]["VALUE"]) ):?>
            <a href="<?=$arElement["PROPERTIES"]["BXR_URL"]["VALUE"]?>">
                <img src="<?=$picture["src"]?>" alt="<?=$arElement['NAME']?>" >
            </a>
        <?else:?>
                <img src="<?=$picture["src"]?>" alt="<?=$arElement['NAME']?>">
        <?endif?>
</div>