<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if (count($arElement["OFFERS"]) > 0) {?>
    <div class="bxr-element-offers">
    <?
    $module_id = "alexkova.market2";

    $bxr_use_links_sku = COption::GetOptionString($module_id, "bxr_use_links_sku", "N");

    $bxr_use_links_sku_sef_section = COption::GetOptionString($module_id, "bxr_use_links_sku_sef_section", "");
    $offerMask = ($bxr_use_links_sku_sef_section != "") ? $bxr_use_links_sku_sef_section : 'offer';

    $bxr_use_links_sku_sef_request = COption::GetOptionString($module_id, "bxr_use_links_sku_sef_request", "");
    $offerRequestMask = ($bxr_use_links_sku_sef_request != "") ? $bxr_use_links_sku_sef_request : 'offer_id';

    $bxr_use_links_sku_sef = COption::GetOptionString($module_id, "bxr_use_links_sku_sef", "N");
    $bxr_use_links_sku_sef_code = COption::GetOptionString($module_id, "bxr_use_links_sku_sef_code", "N");

    $arSkuList = array();
    $rounded = ($arElementParams["BXR_PRESENT_SETTINGS"]["SKU_PROPS_SHOW_TYPE"] == "rounded") ? "rounded" : "";

    if (!empty($arElementParams['SKU_PROPS']))
    {
            foreach ($arElementParams['SKU_PROPS'] as &$arProp)
            {
                    $propId = $arProp['ID'];
                    $arSkuList[$propId] = array(
                        "TEMPLATE" => array(
                            "START" => "",
                            "END" => ""
                        ),
                        "ITEMS" => array(

                        )
                    );
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
                            $arSkuList[$propId]["TEMPLATE"]["START"] = '<div class="'.$strClass.'" id="#ITEM#_prop_'.$arProp['ID'].'_cont">'.
    //'<span class="bx_item_section_name_gray">'.htmlspecialcharsex($arProp['NAME']).'</span>'.
    '<div class="bx_size_scroller_container"><div class="bx_size '.$rounded.'"><ul id="#ITEM#_prop_'.$arProp['ID'].'_list" style="width: '.$strWidth.';">';
                            foreach ($arProp['VALUES'] as $arOneValue)
                            {
                                    if ($arOneValue['ID'] == 0) continue;
                                    $arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
                                    $arSkuList[$propId]['ITEMS'][$arOneValue['ID']] = '<li data-treevalue="'.$arProp['ID'].'_'.$arOneValue['ID'].'" data-onevalue="'.$arOneValue['ID'].'" title="'.$arOneValue['NAME'].'" data-prop-name="'.htmlspecialcharsex($arProp['NAME']).'" class="bxr-border-color-hover"><i></i><span class="cnt">'.$arOneValue['NAME'].'</span></li>';
                            }
                            $arSkuList[$propId]["TEMPLATE"]["END"] = '<div class="clearfix"></div></ul></div>'.
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
                            $arSkuList[$propId]["TEMPLATE"]["START"] = '<div class="'.$strClass.'" id="#ITEM#_prop_'.$arProp['ID'].'_cont">'.
    //'<span class="bx_item_section_name_gray">'.htmlspecialcharsex($arProp['NAME']).'</span>'.
    '<div class="bx_scu_scroller_container"><div class="bx_scu '.$rounded.'"><ul id="#ITEM#_prop_'.$arProp['ID'].'_list" style="width: '.$strWidth.';">';
                            foreach ($arProp['VALUES'] as $arOneValue)
                            {
                                    if ($arOneValue['ID'] == 0) continue;
                                    $arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
                                    $templateRow .= '<li data-treevalue="'.$arProp['ID'].'_'.$arOneValue['ID'].'" data-onevalue="'.$arOneValue['ID'].'" style="padding-top: '.$strOneWidth.';" title="'.$arOneValue['NAME'].'" data-prop-name="'.htmlspecialcharsex($arProp['NAME']).'" class="bxr-border-color-hover"><i title="'.$arOneValue['NAME'].'"></i>'.
    '<span class="cnt"><span class="cnt_item" style="background-image:url(\''.$arOneValue['PICT']['SRC'].'\');" title="'.$arOneValue['NAME'].'"></span></span></li>';
                                    $arSkuList[$propId]['ITEMS'][$arOneValue['ID']] = '<li data-treevalue="'.$arProp['ID'].'_'.$arOneValue['ID'].'" data-onevalue="'.$arOneValue['ID'].'" style="padding-top: '.$strOneWidth.';" title="'.$arOneValue['NAME'].'" data-prop-name="'.htmlspecialcharsex($arProp['NAME']).'" class="bxr-border-color-hover"><i title="'.$arOneValue['NAME'].'"></i>'.
    '<span class="cnt"><span class="cnt_item" style="background-image:url(\''.$arOneValue['PICT']['SRC'].'\');" title="'.$arOneValue['NAME'].'"></span></span></li>';
                            }
                            $arSkuList[$propId]["TEMPLATE"]["END"] = '<div class="clearfix"></div></ul></div>'.
    '<div class="bx_slide_left" id="#ITEM#_prop_'.$arProp['ID'].'_left" data-treevalue="'.$arProp['ID'].'" style="'.$strSlideStyle.'"></div>'.
    '<div class="bx_slide_right" id="#ITEM#_prop_'.$arProp['ID'].'_right" data-treevalue="'.$arProp['ID'].'" style="'.$strSlideStyle.'"></div>'.
    '</div></div>';
                    }
            }
            unset($arProp);
    }

    if ('Y' == $arElementParams['PRODUCT_DISPLAY_MODE'])
    {
        foreach ($arElement["OFFERS"] as $key => $offer) {
            $propsStr = "";
            foreach($offer["PROPERTIES"] as $propCode => $arProp):
                $printValue = "";
                if (array_key_exists($propCode, $arElement["OFFERS_PROP"]) || in_array($arProp["CODE"], $arElementParams["~OFFERS_PROPERTY_CODE"])):
                    $sPropId = $arElementParams["SKU_PROPS"][$propCode]["XML_MAP"][$arProp["VALUE"]];
                    if ($arProp["PROPERTY_TYPE"] == "E" && strlen($arElementParams["SKU_PROPS"][$propCode]["VALUES"][$arProp["VALUE"]]["NAME"]) > 0) {
                        $printValue = $arProp["NAME"].": ".$arElementParams["SKU_PROPS"][$propCode]["VALUES"][$arProp["VALUE"]]["NAME"];
                    } else if ($arProp["PROPERTY_TYPE"] == "S" && strlen($arElementParams["SKU_PROPS"][$propCode]["VALUES"][$sPropId]["NAME"]) > 0) {
                        $printValue = $arProp["NAME"].": ".$arElementParams["SKU_PROPS"][$propCode]["VALUES"][$sPropId]["NAME"];
                    } else if ($arProp["PROPERTY_TYPE"] == "L" && $arProp["MULTIPLE"] == "Y" && $arProp["VALUE"]) {
                            $printValue = $arProp["NAME"].": ";
                            $valueCount = count($arProp["VALUE"])-1;
                            foreach ($arProp["VALUE"] as $key => $value)
                            {
                                $printValue .= $value;
                                if ($key!=$valueCount) $printValue .= ',';
                            }
                    } else if (strlen($arProp["VALUE"]) > 0) {
                            $printValue = $arProp["NAME"].": ".$arProp["VALUE"];
                    }

                        if(!empty($printValue))
                            $propsStr .= $printValue.", ";

                endif;
            endforeach;
            $propsStr = rtrim($propsStr, ", ");
            $offer["OFFER_PROPS_TEXT"] = $propsStr;
            $offer["MSG"] = str_replace("#TRADE_NAME#", htmlspecialchars($offer["NAME"],ENT_QUOTES, SITE_CHARSET), GetMessage('OFFER_REQUEST_MSG'));
            $offer["MSG"] = str_replace("#PARAMS#", htmlspecialchars($propsStr,ENT_QUOTES, SITE_CHARSET), $offer["MSG"]);

            $arElement['JS_OFFERS'][$key]["BASKET_VALUES"] = array(
                "ID" => $arElement["ID"],
                "OFFER_ID" => $offer["ID"],
                "NAME" => $offer["NAME"],
                "LINK" => ($bxr_use_links_sku_sef == "Y") ? (($bxr_use_links_sku_sef_code == "Y") ? $arElement["DETAIL_PAGE_URL"].$offerMask."/".$offer["CODE"]."/" : $arElement["DETAIL_PAGE_URL"].$offerMask."/".$offer["ID"]."/") : $arElement["DETAIL_PAGE_URL"]."?".$offerRequestMask."=".$offer["ID"],
                "IMG" => $offer["PREVIEW_PICTURE"]["SRC"],
                "MSG" => $offer['MSG'],
                "HAS_PRICE" => (!empty($offer['MIN_PRICE'])) ? 'Y' : 'N',
                "CATALOG_QUANTITY" => $offer['CATALOG_QUANTITY'],
                "CATALOG_CAN_BUY_ZERO" => $offer['CATALOG_CAN_BUY_ZERO'],
                "MAX_QTY" => ($offer['CATALOG_CAN_BUY_ZERO'] == 'Y') ? 0 : $offer['CATALOG_QUANTITY'],
                "CATALOG_SUBSCRIBE" => $offer['CATALOG_SUBSCRIBE'],
                "QTY_MAX" => $offer['QTY_MAX'],
                "RATIO" => $offer['RATIO'],
                "START_QTY" => $offer['START_QTY']
            );
    //        echo "<pre>2 ";
    //        print_r($arElement['JS_OFFERS'][$key]["BASKET_VALUES"]);
    //        echo "</pre>";
        }
        if (!empty($arElement['OFFERS_PROP']))
        {
                $arSkuProps = array();
                ?><div class="bx_catalog_item_scu" id="<? echo $arItemIDs['PROP_DIV']; ?>"><?
                foreach ($arSkuList as $propId => $strTemplate)
                {
                        if (!isset($arElement['SKU_TREE_VALUES'][$propId]))
                            continue;

                        echo '<div>', str_replace('#ITEM#_prop_', $arItemIDs['PROP'], $strTemplate["TEMPLATE"]["START"]);
                        foreach ($strTemplate['ITEMS'] as $value => $valueItem)
                        {
                                if (!isset($arElement['SKU_TREE_VALUES'][$propId][$value]))
                                        continue;
                                echo str_replace('#ITEM#_prop_', $arItemIDs['PROP'], $valueItem);
                        }
                        echo str_replace('#ITEM#_prop_', $arItemIDs['PROP'], $strTemplate["TEMPLATE"]["END"]), '</div>';
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
                    'SLIDER_INTERVAL' => ($arElementParams['BXR_PRESENT_SETTINGS']['BXR_SLIDER_INTERVAL']) ?: 0,
                    'VISUAL' => array(
                        'ID' => $arItemIDs['ID'],
                        'NAME' => $arItemIDs['NAME'],
                        'PICT_ID' => $arItemIDs['PICT'],
                        'AVAIL_ID' => $arItemIDs['AVAIL'],
                        'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
                        'PICT_SLIDER_ID' => $arItemIDs['PICT_SLIDER'],
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
                        'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK'],
                        'FAST_VIEW_ID' => $arItemIDs['FAST_VIEW']
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
                        'NAME' => $arElement['NAME'],
                        'DETAIL_PAGE_URL' => $arElement['DETAIL_PAGE_URL'],
                        'MORE_PHOTO' => $arElement['MORE_PHOTO'],
                        'MORE_PHOTO_COUNT' => $arElement['MORE_PHOTO_COUNT']
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
        var useSkuLinks = '<?=$bxr_use_links_sku?>';
        var <? echo $strObName; ?> = new JCCatalogSection(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
        </script>
                    <?
            }
        }
        ?>
    </div>
<?}?>
