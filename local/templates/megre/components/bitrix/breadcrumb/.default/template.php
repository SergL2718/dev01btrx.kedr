<?php
/*
 * Изменено: 06 декабря 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @global CMain $APPLICATION
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

if (empty($arResult)) {
	return '';
}
$breadcrumb = '';
$count = count($arResult);
$breadcrumb = '<div class="breadcrumbs '.$arParams["CLASS"].'" itemprop="http://schema.org/breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';
$breadcrumb .= '<div class="container">';
$breadcrumb .= '<div class="breadcrumbs-container">';
for ($index = 0; $index < $count; $index++):
	$separator =  '';
	if ($arResult[$index]['LINK'] <> '' && $index != $count - 1):
		$breadcrumb .= '<li class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
		$breadcrumb .= $separator . '<a href="' . $arResult[$index]['LINK'] . '" title="' . $arResult[$index]['TITLE'] . '" itemprop="item">';
		$breadcrumb .= '' . $arResult[$index]['TITLE'] . '';
		$breadcrumb .= '</a>';
		$breadcrumb .= '<meta itemprop="position" content="' . ($index + 1) . '">';
		$breadcrumb .= '<meta itemprop="name" content="' . $arResult[$index]['TITLE']. '">';
		$breadcrumb .= '</li>';
	else:
		$breadcrumb .= '<li class="breadcrumbs__item">' . $separator . '<span>' . $arResult[$index]['TITLE'] . '</span></li>';
	endif;
endfor;
$breadcrumb .= '</div>';
$breadcrumb .= '</div>';
$breadcrumb .= '</div>';
return $breadcrumb;