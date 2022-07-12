<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$arSkuTemplate = array();
if (!empty($arElementParams['SKU_PROPS']))
{
        foreach ($arElementParams['SKU_PROPS'] as &$arProp)
        {
                $templateRow = '';
                if ('TEXT' == $arProp['SHOW_MODE'])
                {
                        if (5 < $arProp['VALUES_COUNT'])
                        {
                                $strClass = 'bx_item_detail_size full';
                                $strWidth = ($arProp['VALUES_COUNT']*20).'%';
                                $strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
                                $strSlideStyle = '';
                        }
                        else
                        {
                                $strClass = 'bx_item_detail_size';
                                $strWidth = '100%';
                                $strOneWidth = '20%';
                                $strSlideStyle = 'display: none;';
                        }
                        $templateRow .= '<div class="'.$strClass.'" id="#ITEM#_prop_'.$arProp['ID'].'_cont">'.
'<span class="bx_item_section_name_gray">'.htmlspecialcharsex($arProp['NAME']).'</span>'.
'<div class="bx_size_scroller_container"><div class="bx_size"><ul id="#ITEM#_prop_'.$arProp['ID'].'_list" style="width: '.$strWidth.';">';
                        foreach ($arProp['VALUES'] as $arOneValue)
                        {
                                $arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
                                $templateRow .= '<li data-treevalue="'.$arProp['ID'].'_'.$arOneValue['ID'].'" data-onevalue="'.$arOneValue['ID'].'" style="width: '.$strOneWidth.';" title="'.$arOneValue['NAME'].'" data-prop-name="'.htmlspecialcharsex($arProp['NAME']).'"><i></i><span class="cnt">'.$arOneValue['NAME'].'</span></li>';
                        }
                        $templateRow .= '<div class="clearfix"></div></ul></div>'.
'<div class="bx_slide_left" id="#ITEM#_prop_'.$arProp['ID'].'_left" data-treevalue="'.$arProp['ID'].'" style="'.$strSlideStyle.'"></div>'.
'<div class="bx_slide_right" id="#ITEM#_prop_'.$arProp['ID'].'_right" data-treevalue="'.$arProp['ID'].'" style="'.$strSlideStyle.'"></div>'.
'</div></div>';
                }
                elseif ('PICT' == $arProp['SHOW_MODE'])
                {
                        if (5 < $arProp['VALUES_COUNT'])
                        {
                                $strClass = 'bx_item_detail_scu full';
                                $strWidth = ($arProp['VALUES_COUNT']*20).'%';
                                $strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
                                $strSlideStyle = '';
                        }
                        else
                        {
                                $strClass = 'bx_item_detail_scu';
                                $strWidth = '100%';
                                $strOneWidth = '20%';
                                $strSlideStyle = 'display: none;';
                        }
                        $templateRow .= '<div class="'.$strClass.'" id="#ITEM#_prop_'.$arProp['ID'].'_cont">'.
'<span class="bx_item_section_name_gray">'.htmlspecialcharsex($arProp['NAME']).'</span>'.
'<div class="bx_scu_scroller_container"><div class="bx_scu"><ul id="#ITEM#_prop_'.$arProp['ID'].'_list" style="width: '.$strWidth.';">';
                        foreach ($arProp['VALUES'] as $arOneValue)
                        {
                                $arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
                                $templateRow .= '<li data-treevalue="'.$arProp['ID'].'_'.$arOneValue['ID'].'" data-onevalue="'.$arOneValue['ID'].'" style="width: '.$strOneWidth.'; padding-top: '.$strOneWidth.';" title="'.$arOneValue['NAME'].'" data-prop-name="'.htmlspecialcharsex($arProp['NAME']).'"><i title="'.$arOneValue['NAME'].'"></i>'.
'<span class="cnt"><span class="cnt_item" style="background-image:url(\''.$arOneValue['PICT']['SRC'].'\');" title="'.$arOneValue['NAME'].'"></span></span></li>';
                        }
                        $templateRow .= '<div class="clearfix"></div></ul></div>'.
'<div class="bx_slide_left" id="#ITEM#_prop_'.$arProp['ID'].'_left" data-treevalue="'.$arProp['ID'].'" style="'.$strSlideStyle.'"></div>'.
'<div class="bx_slide_right" id="#ITEM#_prop_'.$arProp['ID'].'_right" data-treevalue="'.$arProp['ID'].'" style="'.$strSlideStyle.'"></div>'.
'</div></div>';
                }
                $arSkuTemplate[$arProp['CODE']] = $templateRow;
        }
        unset($templateRow, $arProp);
}

if ('Y' == $arElementParams['PRODUCT_DISPLAY_MODE'])
{
    if (!empty($arElement['OFFERS_PROP']))
    {
            $arSkuProps = array();
            ?><div class="bx_catalog_item_scu" id="<? echo $arItemIDs['PROP_DIV']; ?>"><?
            foreach ($arSkuTemplate as $code => $strTemplate)
            {
                    if (!isset($arElement['OFFERS_PROP'][$code]))
                            continue;
                    echo '<div>', str_replace('#ITEM#_prop_', $arItemIDs['PROP'], $strTemplate), '</div>';
            }
            foreach ($arElementParams['SKU_PROPS'] as $arOneProp)
            {
                    if (!isset($arElement['OFFERS_PROP'][$arOneProp['CODE']]))
                            continue;
                    $arSkuProps[] = array(
                            'ID' => $arOneProp['ID'],
                            'SHOW_MODE' => $arOneProp['SHOW_MODE'],
                            'VALUES_COUNT' => $arOneProp['VALUES_COUNT']
                    );
            }
            foreach ($arElement['JS_OFFERS'] as &$arOneJs)
            {
                    if (0 < $arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'])
                    {
                            $arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'] = '-'.$arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'].'%';
                            $arOneJs['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'] = '-'.$arOneJs['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'].'%';
                    }
            }
            unset($arOneJs);
            ?></div><?
            if ($arElement['OFFERS_PROPS_DISPLAY'])
            {
                    foreach ($arElement['JS_OFFERS'] as $keyOffer => $arJSOffer)
                    {
                            $strProps = '';
                            if (!empty($arJSOffer['DISPLAY_PROPERTIES']))
                            {
                                    foreach ($arJSOffer['DISPLAY_PROPERTIES'] as $arOneProp)
                                    {
                                            $strProps .= '<br>'.$arOneProp['NAME'].' <strong>'.(
                                                    is_array($arOneProp['VALUE'])
                                                    ? implode(' / ', $arOneProp['VALUE'])
                                                    : $arOneProp['VALUE']
                                            ).'</strong>';
                                    }
                            }
                            $arElement['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES'] = $strProps;
                    }
            }
            $arJSParams = array(
                    'PRODUCT_TYPE' => $arElement['CATALOG_TYPE'],
                    'SHOW_QUANTITY' => ($arElementParams['USE_PRODUCT_QUANTITY'] == 'Y'),
                    'SHOW_ADD_BASKET_BTN' => false,
                    'SHOW_BUY_BTN' => true,
                    'SHOW_ABSENT' => true,
                    'SHOW_SKU_PROPS' => $arElement['OFFERS_PROPS_DISPLAY'],
                    'SECOND_PICT' => $arElement['SECOND_PICT'],
                    'SHOW_OLD_PRICE' => ('Y' == $arElementParams['SHOW_OLD_PRICE']),
                    'SHOW_DISCOUNT_PERCENT' => ('Y' == $arElementParams['SHOW_DISCOUNT_PERCENT']),
                    'ADD_TO_BASKET_ACTION' => $arElementParams['ADD_TO_BASKET_ACTION'],
                    'SHOW_CLOSE_POPUP' => ($arElementParams['SHOW_CLOSE_POPUP'] == 'Y'),
                    'DISPLAY_COMPARE' => $arElementParams['DISPLAY_COMPARE'],
                    'DEFAULT_PICTURE' => array(
                            'PICTURE' => $arElement['PRODUCT_PREVIEW'],
                            'PICTURE_SECOND' => $arElement['PRODUCT_PREVIEW_SECOND']
                    ),
                    'VISUAL' => array(
                            'ID' => $arItemIDs['ID'],
                            'PICT_ID' => $arItemIDs['PICT'],
                            'AVAIL_ID' => $arItemIDs['AVAIL'],
                            'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
                            'QUANTITY_ID' => $arItemIDs['QUANTITY'],
                            'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
                            'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
                            'QUANTITY_MEASURE' => $arItemIDs['QUANTITY_MEASURE'],
                            'PRICE_ID' => $arItemIDs['PRICE'],
                            'TREE_ID' => $arItemIDs['PROP_DIV'],
                            'TREE_ITEM_ID' => $arItemIDs['PROP'],
                            'BUY_ID' => $arItemIDs['BUY_LINK'],
                            'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
                            'DSC_PERC' => $arItemIDs['DSC_PERC'],
                            'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
                            'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
                            'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
                            'NOT_AVAILABLE_MESS' => $arItemIDs['NOT_AVAILABLE_MESS'],
                            'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK']
                    ),
                    'BASKET' => array(
                            'QUANTITY' => $arElementParams['PRODUCT_QUANTITY_VARIABLE'],
                            'PROPS' => $arElementParams['PRODUCT_PROPS_VARIABLE'],
                            'SKU_PROPS' => $arElement['OFFERS_PROP_CODES'],
                            'ADD_URL_TEMPLATE' => $arElementParams['~ADD_URL_TEMPLATE'],
                            'BUY_URL_TEMPLATE' => $arElementParams['~BUY_URL_TEMPLATE']
                    ),
                    'PRODUCT' => array(
                            'ID' => $arElement['ID'],
                            'NAME' => $arElement['NAME']
                    ),
                    'OFFERS' => $arElement['JS_OFFERS'],
                    'OFFER_SELECTED' => $arElement['OFFERS_SELECTED'],
                    'TREE_PROPS' => $arSkuProps,
                    'LAST_ELEMENT' => $arElement['LAST_ELEMENT']
            );
            if ($arElementParams['DISPLAY_COMPARE'])
            {
                    $arJSParams['COMPARE'] = array(
                            'COMPARE_URL_TEMPLATE' => $arElementParams['~COMPARE_URL_TEMPLATE'],
                            'COMPARE_PATH' => $arElementParams['COMPARE_PATH']
                    );
            }
            ?>
<script type="text/javascript">
var <? echo $strObName; ?> = new JCCatalogSection(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
</script>
            <?
    }
}
