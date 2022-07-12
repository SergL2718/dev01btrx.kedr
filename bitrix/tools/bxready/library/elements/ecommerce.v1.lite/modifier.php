<?
if (!function_exists('getRealSectionId'))
{
    function getRealSectionId($requestSid = false, $requestScode = false, $elId, $ibSid) {
        if (!intval($elId) || !intval($ibSid)) return false;
        
        $realSid = false;
        
        $elGroups = CIBlockElement::GetElementGroups($elId, true);
        while($arGroup = $elGroups->Fetch())
            $groups[$arGroup["ID"]] = $arGroup["CODE"];
        
        if (intval($requestSid) && array_key_exists($requestSid, $groups))
            return $requestSid;
        elseif (strlen($requestScode) && in_array($requestScode, $groups))
            return array_search($requestScode, $groups);
        elseif (intval($ibSid) && array_key_exists($ibSid, $groups))
            return $ibSid;
        else
            return key($groups);
    }
}

if (!function_exists('getSectionBasketProps'))
{
    function getSectionBasketProps($sid, $ibId) {
        if (!intval($sid) || !intval($ibId)) return false;
        
        $arBasketProps = array();
        
        $nav = CIBlockSection::GetNavChain($ibId, $sid, array("ID", "LEFT_MARGIN"));
        while($arSectionPath = $nav->GetNext()) {
            $arSPathId[$arSectionPath["LEFT_MARGIN"]] = $arSectionPath["ID"];
        }
        krsort($arSPathId);
        
        foreach ($arSPathId as $cSid) {
            $filter = array("IBLOCK_ID" => $ibId, "ID" => $cSid);
            $select = array("ID", "UF_REQUIRED_BP", "UF_OPTIONAL_BP");
            $res = CIBlockSection::GetList(Array($by=>$order), $filter, false, $select);
            if ($arFields = $res->GetNext())
            {
                if ( (is_array($arFields["UF_REQUIRED_BP"]) && count($arFields["UF_REQUIRED_BP"])) 
                    || (is_array($arFields["UF_OPTIONAL_BP"]) && count($arFields["UF_OPTIONAL_BP"])) ) {
                    $arBasketProps = array(
                        "REQUIRED" => $arFields["UF_REQUIRED_BP"],
                        "OPTIONAL" => $arFields["UF_OPTIONAL_BP"],
                    );
                    break;
                }
            }
        }
        
        return $arBasketProps;
    }
}

/*start basket props*/
$arElement["REAL_SECTION_ID"] = getRealSectionId($arElementParams["SECTION_ID"],$arElementParams["SECTION_CODE"],$arElement["ID"], $arElement["IBLOCK_SECTION_ID"]);
$arElement["BASKET_PROPS"] = getSectionBasketProps($arElement["REAL_SECTION_ID"], $arElement["IBLOCK_ID"]);
foreach ($arElement["BASKET_PROPS"]["REQUIRED"] as $pCode) {
    $arProp = $arElement["PROPERTIES"][$pCode];
    if ($arProp["MULTIPLE"] == "Y" && $arProp["VALUE"])
        $arElement["BASKET_PROPS"]["REQUIRED_CHECK"][$pCode] = array(
            "ID" => $arProp["ID"],
            "CODE" => $arProp["CODE"],
            "NAME" => $arProp["NAME"],
            "SORT" => $arProp["SORT"],
            "VALUE" => $arProp["VALUE"]
        );
}
foreach ($arElement["BASKET_PROPS"]["OPTIONAL"] as $pCode) {
    $arProp = $arElement["PROPERTIES"][$pCode];
    if ($arProp["MULTIPLE"] == "Y" && $arProp["VALUE"])
        $arElement["BASKET_PROPS"]["OPTIONAL_CHECK"][$pCode] = array(
            "ID" => $arProp["ID"],
            "CODE" => $arProp["CODE"],
            "NAME" => $arProp["NAME"],
            "SORT" => $arProp["SORT"],
            "VALUE" => $arProp["VALUE"]
        );
}
/*end basket props*/