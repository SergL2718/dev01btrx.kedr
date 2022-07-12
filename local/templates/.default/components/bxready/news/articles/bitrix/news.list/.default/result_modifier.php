<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

foreach ($arResult["ITEMS"] as &$val) {
    if (is_array($val["PREVIEW_PICTURE"])) {
        $tmb = CFile::ResizeImageGet($val["PREVIEW_PICTURE"]["ID"], array('width' => 700, 'height' => 700));
        $val["PICTURE"] = $tmb["src"];
        //$val["PICTURE"] = $val["PREVIEW_PICTURE"]['SRC'];
    } elseif (!is_array($val["PREVIEW_PICTURE"]) && is_array($val["DETAIL_PICTURE"])) {
        $tmb = CFile::ResizeImageGet($val["DETAIL_PICTURE"]["ID"], array('width' => 700, 'height' => 700));
        $val["PICTURE"] = $tmb["src"];
        //$val["PICTURE"] = $val["DETAIL_PICTURE"]['SRC'];
    }
}

$arResult["COUNT_ELS"] = count($arResult["ITEMS"]);

$this->__component->arResultCacheKeys[] = "COUNT_ELS";

?>