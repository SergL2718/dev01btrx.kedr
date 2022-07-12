<?php
/*
 * Изменено: 26 августа 2021, четверг
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

use Alexkova\Bxready\Draw;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arElement
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

Loc::loadMessages(__FILE__);

$arMarkers = $arElement;
?>
	<div class="bxr-ribbon-marker-vertical">
		<?php if (isset($arMarkers["WEEK"]) && $arMarkers["WEEK"] == true): ?>
			<span title="<?= GetMessage('BXR_MARKER_WEEK') ?>"
				  class="bxr-marker-week"><i class="fa fa-heart-o"></i></span>
		<?php endif; ?>
		<?php if (isset($arMarkers["NEW"]) && $arMarkers["NEW"] == true): ?>
			<span title="<?= GetMessage('BXR_MARKER_NEW') ?>"
				  class="bxr-marker-new"><i><?= GetMessage('BXR_MARKER_NEW') ?></i></span>
		<?php endif; ?>
		<?php if (isset($arMarkers["DISCOUNT"]) && $arMarkers["DISCOUNT"] > 0): ?>
			<span title="<?= GetMessage('BXR_MARKER_DISCOUNT') ?>"
				  class="bxr-marker-discount"><i><?= $arMarkers["DISCOUNT"] ?>%</i></span>
		<?php endif; ?>
		<?php if (isset($arMarkers["REC"]) && $arMarkers["REC"] == true): ?>
			<span title="<?= GetMessage('BXR_MARKER_REC') ?>"
				  class="bxr-marker-rec"><i class="fa fa-thumbs-o-up"></i></span>
		<?php endif; ?>
		<?php if (isset($arMarkers["HIT"]) && $arMarkers["HIT"] == true): ?>
			<span title="<?= GetMessage('BXR_MARKER_HIT') ?>"
				  class="bxr-marker-hit"><i><?= GetMessage('BXR_MARKER_HIT') ?></i></span>
		<?php endif; ?>
		<?php if (isset($arMarkers["SALE"]) && $arMarkers["SALE"] == true): ?>
			<span title="<?= GetMessage('BXR_MARKER_SALE') ?>"
				  class="bxr-marker-sale"><i><?= GetMessage('BXR_MARKER_SALE') ?></i></span>
		<?php endif; ?>
	</div>
<?php
$elementDraw = Draw::getInstance();
$dirName = str_replace($_SERVER["DOCUMENT_ROOT"], '', dirname(__FILE__));
$elementDraw->setAdditionalFile("CSS", "/bitrix/tools/bxready/library/markers/ribbon_vertical/include/style.css", false);
?>