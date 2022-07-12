<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */


if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
$arResult['ITEMS_COUNT'] = count($arResult['ITEMS']);
$this->__component->SetResultCacheKeys(['ITEMS_COUNT']);
if (empty($arResult['ITEMS'])) {
	return;
}
$this->addExternalCss(SITE_TEMPLATE_PATH . '/lib/owlcarousel/owl.carousel.min.css');
$this->addExternalJS(SITE_TEMPLATE_PATH . '/lib/owlcarousel/owl.carousel.min.js');
$arResult['UNIQUE_ID'] = md5($this->getName() . $this->randString());
$previewImageWidth = 255;
$previewImageHeight = 255;
foreach ($arResult['ITEMS'] as &$item) {
	if (empty($item['PREVIEW_PICTURE']) && !empty($item['DETAIL_PICTURE'])) {
		$item['PREVIEW_PICTURE'] = $item['DETAIL_PICTURE'];
	}
	if (!empty($item['PREVIEW_PICTURE']) && $item['PREVIEW_PICTURE']['WIDTH'] > $previewImageWidth) {
		$item['PREVIEW_PICTURE'] = [
			'ID'  => $item['PREVIEW_PICTURE']['ID'],
			'SRC' => \CFile::ResizeImageGet($item['PREVIEW_PICTURE']['ID'], ['width' => $previewImageWidth, 'height' => $previewImageHeight])['src'],
		];
	}
}