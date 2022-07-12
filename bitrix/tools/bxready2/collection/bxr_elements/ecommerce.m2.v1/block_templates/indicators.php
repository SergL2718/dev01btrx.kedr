<div class="bxr-cart-basket-indicator">
    <div class="bxr-indicator-item bxr-indicator-item-basket" data-item="<?=$arElement["ID"]?>">
        <span class="fa fa-shopping-basket"></span>
        <span class="bxr-counter-item bxr-counter-item-basket" data-item="<?=$arElement["ID"]?>">0</span>
    </div>
</div>
<div class="bxr-sale-indicator">
    <div class="bxr-basket-group">
        <form class="bxr-basket-action bxr-basket-group" action="">
            <button class="bxr-indicator-item bxr-indicator-item-favor bxr-basket-favor" data-item="<?=$arElement["ID"]?>" tabindex="0" title="<?=GetMessage("BXR_FAVOR")?>">
                <span class="fa fa-heart-o"></span>
            </button>
            <input type="hidden" name="item" value="<?=$arElement["ID"]?>" tabindex="0"/>
            <input type="hidden" name="action" value="favor" tabindex="0"/>
            <input type="hidden" name="favor" value="yes"/>
        </form>
    </div><?
    if ($useCompare)
    {
        ?><div class="bxr-basket-group">
        <button class="bxr-indicator-item bxr-indicator-item-compare bxr-compare-button" value="" data-item="<?=$arElement["ID"]?>" title="<?=GetMessage("BXR_COMPARE")?>">
            <span class="fa fa-bar-chart " aria-hidden="true"></span>
        </button>
        </div><?
    }?>
</div>