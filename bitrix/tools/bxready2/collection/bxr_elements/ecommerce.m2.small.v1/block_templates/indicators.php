<form class="bxr-basket-action" action="">
    <button class="bxr-indicator-item bxr-indicator-item-favor bxr-basket-favor bxr-hover-btn" data-item="<?=$arElement["ID"]?>" tabindex="0" title="<?=GetMessage("BXR_FAVOR")?>">
        <i class="fa fa-heart-o"></i>
    </button>
    <input type="hidden" name="item" value="<?=$arElement["ID"]?>" tabindex="0"/>
    <input type="hidden" name="action" value="favor" tabindex="0"/>
    <input type="hidden" name="favor" value="yes"/>
</form><?
if ($useCompare)
{
    ?><button class="bxr-indicator-item bxr-indicator-item-compare bxr-compare-button bxr-hover-btn" value="" data-item="<?=$arElement["ID"]?>" title="<?=($arElementParams["MESS_BTN_COMPARE"]) ?: GetMessage("BXR_COMPARE")?>">
        <i class="fa fa-bar-chart " aria-hidden="true"></i>
    </button><?
}?>