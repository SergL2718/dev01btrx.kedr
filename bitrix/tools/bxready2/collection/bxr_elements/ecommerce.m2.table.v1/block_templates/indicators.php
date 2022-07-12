<div class="bxr-sale-indicator">
    <!--<div class="bxr-basket-group">-->
    <form class="bxr-basket-action bxr-basket-group" action="">
        <button class="bxr-indicator-item bxr-indicator-item-favor bxr-basket-favor" data-item="<?=$arElement["ID"]?>" tabindex="0">
            <span class="fa fa-heart-o"></span>
        </button>
        <input type="hidden" name="item" value="<?=$arElement["ID"]?>" tabindex="0">
        <input type="hidden" name="action" value="favor" tabindex="0">
        <input type="hidden" name="favor" value="yes">
    </form>
    <!--</div>-->
    <?if ($useCompare):?>
        <button class="bxr-indicator-item bxr-indicator-item-compare bxr-compare-button" value="" data-item="<?=$arElement["ID"]?>">
            <span class="fa fa-bar-chart " aria-hidden="true"></span>
        </button>
    <?endif;?>
</div>