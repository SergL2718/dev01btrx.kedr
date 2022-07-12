<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if ($arParams["OFFERS_VIEW"] == "CHOISE") {?>
    <div class="sku-choise-wrap">
        <?foreach ($arResult["SKU_PROPS_LIST"] as $code => $prop) {?>
            <div class="sku-prop" data-prop-id="<?=$prop["ID"]?>" data-prop-code="<?=$prop["CODE"]?>" data-prop-name="<?=$prop["NAME"]?>" data-prop-type="<?=$prop["PROPERTY_TYPE"]?>">
                <div class="sku-prop-name">
                    <?=GetMessage('CHOOSE')?> <?=mb_strtolower($prop["NAME"])?>:
                </div>
                <ul class="sku-prop-values-list">
                    <?foreach ($prop["VALUES"] as $valId => $propVal) {
                        $valueType = ($propVal["FILE"]) ? "prop-img-wrap" : "prop-text-wrap";
                        $addClass = ($arParams["SKU_PROPS_SHOW_TYPE"] == "rounded") ? "rounded" : "";?>
                        <li class="sku-prop-value <?=$valueType?> <?=$addClass?>" data-prop-code="<?=$code?>" data-prop-type="<?=$prop["PROPERTY_TYPE"]?>" data-val-id="<?=$propVal["ID"]?>" data-val-code="<?=$propVal["XML_ID"]?>" data-val-name="<?=$propVal["NAME"]?>">
                            <?if ($propVal["FILE"]) {?>
                                <!--<div class="prop-img-wrap">-->
                                    <img src="<?=$propVal["FILE"]?>">
                                <!--</div>-->
                            <?} else {?>
                            <!--<div class="prop-text-wrap">-->
                                <?=$propVal["NAME"]?>
                            <!--</div>-->
                            <?}?>
                        </li>
                    <?}?>
                </ul>
            </div>
            <div class="clearfix"></div>
        <?}?>
        <div  class="offers-cnt"></div>
    </div>
<?}?>  
<?if ($arParams["OFFERS_VIEW"] == "SELECT") {?>
    <div class="bxr-sku-select-wrap" data-pid="<?=$chosenColor["ID"]?>">
        <i class="fa fa-chevron-down"></i>
        <div class="bxr-sku-select-chosen-inner">
            <?=GetMessage('CHOISE_OFFER')?>
        </div>
        <hr>
        <ul class="bxr-sku-select-items">
            <?foreach($arResult["OFFERS"] as $offer):
                if (is_array($offer["DETAIL_PICTURE"])){
                    $img = $offer["DETAIL_PICTURE"]["SRC"];
                }
                else{
                    $img = $arResult['DEFAULT_PICTURE']["SRC"];
                }?>
                <li class="bxr-sku-select-item" data-pid="<?=$offer['ID']?>">
                    <div class="bxr-offers-ico">
                        <img src="<?=$img?>">
                    </div>
                    <div class="bxr-offers-props">
                        <?$propsStr = "";
                        foreach($offer["PROPERTIES"] as $propCode => $arProp):
                            if (array_key_exists($propCode, $arResult["OFFERS_PROP"])): 
                                $sPropId = $arResult["SKU_PROPS"][$propCode]["XML_MAP"][$arProp["VALUE"]];
                                if ($arProp["PROPERTY_TYPE"] == "E") {
                                    $printValue = $arProp["NAME"].": ".$arResult["SKU_PROPS"][$propCode]["VALUES"][$arProp["VALUE"]]["NAME"];
                                } else if ($arProp["PROPERTY_TYPE"] == "S") {
                                    $printValue = $arProp["NAME"].": ".$arResult["SKU_PROPS"][$propCode]["VALUES"][$sPropId]["NAME"];
                                } else {
                                    $printValue = $arProp["NAME"].": ".$arProp["VALUE"];
                                }
                                
                                $propsStr .= $printValue.", ";
                            endif;
                        endforeach;
			$propStr = rtrim($propsStr, ', ');
			if (strlen($propStr) > 0) :
				$propStr = ' (<span class="bxr-sku-prop-brackets">'.$propStr.'</span>)';
			endif;
			?>
                        <span class="bxr-offer-props-name"><?=$offer["NAME"]?></span><?=$propStr?>
                    </div>
                    <div class="clearfix"></div>
                </li>
            <?endforeach;?>
        </ul>
    </div>
<?}?>
<?if ($arParams["OFFERS_VIEW"] == "ICONS") {?>
    <div class="bxr-sku-icons-items-block">
        <ul class="bxr-sku-icons-items">
            <?foreach($arResult["OFFERS"] as $offer):
                if (is_array($offer["DETAIL_PICTURE"])){
                    $img = $offer["DETAIL_PICTURE"]["SRC"];
                } else if (is_array($offer["PREVIEW_PICTURE"])){
                    $img = $offer["PREVIEW_PICTURE"]["SRC"];
                } else{
                    $img = $arResult['DEFAULT_PICTURE']["SRC"];
                }?>
                <li class="bxr-sku-icons-item" data-pid="<?=$offer['ID']?>">
                    <div class="bxr-offers-ico">
                        <img src="<?=$img?>" alt="<?=$offer['NAME']?>" title="<?=$offer['NAME']?>">
                    </div>
                    <input type="hidden" value="<?=$offer['NAME']?>" class="bxr-icons-offer-name">
                </li>
            <?endforeach;?>
            <div class="clearfix"></div>
        </ul>
        <div  class="offers-cnt"></div>
    </div>
<? } ?>