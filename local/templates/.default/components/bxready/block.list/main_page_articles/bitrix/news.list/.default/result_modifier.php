<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

foreach ($arResult["ITEMS"] as &$item) {
    unset($item['DETAIL_TEXT'], $item['~DETAIL_TEXT']);
    if (is_array($item["PREVIEW_PICTURE"])) {
        $tmb = CFile::ResizeImageGet($item["PREVIEW_PICTURE"]["ID"], array('width' => 800, 'height' => 800), BX_RESIZE_IMAGE_EXACT);
        $item["PREVIEW_PICTURE"]['SRC'] = $tmb["src"];
    }
}

$arResult["COUNT_ELS"] = count($arResult["ITEMS"]);

$this->__component->arResultCacheKeys[] = "COUNT_ELS";

?>