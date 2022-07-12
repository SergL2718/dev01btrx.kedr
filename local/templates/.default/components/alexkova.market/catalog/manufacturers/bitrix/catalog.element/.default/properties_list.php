<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>
<h3 class="bxr-detail-tab-mobile-title  hidden-lg hidden-md hidden-sm"><?=GetMessage("PROPS_TEXT")?></h3>
<div class="bxr-detail-tab bxr-detail-props" data-tab="props">
    <table width="100%" class="bxr-props-table">
        <?foreach($arResult["DISPLAY_PROPERTIES"] as $key => $arProperty):?>
	    <?if (!in_array($key, $arParams["PREVIEW_DETAIL_PROPERTY_CODE"]) || $arParams["HIDE_PREVIEW_PROPS_INLIST"] == 'N'):?>
                <?if (!is_array($arProperty["DISPLAY_VALUE"]) && $arProperty["DISPLAY_VALUE"]){?>
                        <tr itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
                                <td class="bxr-props-name"><span itemprop="name"><?=$arProperty["NAME"]?></span></td>
                                <td class="bxr-props-data"><span itemprop="value"><?=$arProperty["DISPLAY_VALUE"]?></span></td>
                        </tr>
                <?}elseif (is_array($arProperty["DISPLAY_VALUE"]) && count($arProperty["DISPLAY_VALUE"]>0)){?>
                        <?
                        $withDesc = false;
                        foreach($arProperty["DESCRIPTION"] as $cell=>$val){
                            if ($val) {
                                $withDesc = true;
                                break;
                            }
                        }?>
                        <?if ($withDesc) {?>
                            <tr>
                                    <td colspan="2" class="bxr-props-data bxr-props-data-group">
                                            <b><?=$arProperty["NAME"]?></b></td>
                            </tr>
                            <?foreach($arProperty["DISPLAY_VALUE"] as $cell=>$val){?>
                                    <tr itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
                                            <td class="bxr-props-name no-bold"><span itemprop="name"><?=$arProperty["DESCRIPTION"][$cell]?></span></td>
                                            <td class="bxr-props-data"><span itemprop="value"><?=$val?></span></td>
                                    </tr>
                            <?}?>
                        <?} else {?>
                            <tr itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
                                <td class="bxr-props-name"><span itemprop="name"><?=$arProperty["NAME"]?></span></td>
                                <td class="bxr-props-data"><span itemprop="value"><?=  implode(', ', $arProperty["DISPLAY_VALUE"])?></span></td>
                            </tr>
                        <?}?>
                <?}?>
	    <?endif;?>
        <?endforeach;?>
    </table>
</div>
        