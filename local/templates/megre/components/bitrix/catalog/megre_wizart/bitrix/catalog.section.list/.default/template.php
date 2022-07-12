<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/*
 * Изменено: 13 сентября 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$arViewModeList = $arResult['VIEW_MODE_LIST'];

$arViewStyles = [
		'LIST' => [
				'CONT'  => 'bx_sitemap',
				'TITLE' => 'bx_sitemap_title',
				'LIST'  => 'bx_sitemap_ul',
		],
		'LINE' => [
				'CONT'      => 'bx_catalog_line',
				'TITLE'     => 'bx_catalog_line_category_title',
				'LIST'      => 'bx_catalog_line_ul',
				'EMPTY_IMG' => $this->GetFolder() . '/images/line-empty.png',
		],
		'TEXT' => [
				'CONT'  => 'bx_catalog_text',
				'TITLE' => 'bx_catalog_text_category_title',
				'LIST'  => 'bx_catalog_text_ul',
		],
		'TILE' => [
				'CONT'      => 'bx_catalog_tile',
				'TITLE'     => 'bx_catalog_tile_category_title',
				'LIST'      => 'bx_catalog_tile_ul',
				'EMPTY_IMG' => $this->GetFolder() . '/images/tile-empty.png',
		],
];
$arCurView = $arViewStyles[$arParams['VIEW_MODE']];

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = ["CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')];


//echo "<pre>"; print_r($arResult); echo "</pre>";
$PICTURE_SRC = SITE_TEMPLATE_PATH."/images/temp/catalog.jpg";
if($arResult["SECTION"]["DETAIL_PICTURE"]){
$PICTURE_SRC = CFile::GetPath($arResult["SECTION"]["DETAIL_PICTURE"]);
}
?><div class="page-banner" style="background-image: url('<?=$PICTURE_SRC?>')">
    <div class="container">
        <div class="page-banner__title" style="text-align: right;"><?echo $arResult["SECTION"]["NAME"];?></div>
    </div>
</div><?

/*
?>
<div class="<? echo $arCurView['CONT']; ?>"><?
	if ('Y' == $arParams['SHOW_PARENT_NAME'] && 0 < $arResult['SECTION']['ID']) {
		$this->AddEditAction($arResult['SECTION']['ID'], $arResult['SECTION']['EDIT_LINK'], $strSectionEdit);
		$this->AddDeleteAction($arResult['SECTION']['ID'], $arResult['SECTION']['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

		?><h1
		class="<? echo $arCurView['TITLE']; ?>"
		id="<? echo $this->GetEditAreaId($arResult['SECTION']['ID']); ?>"
		><a href="<? echo $arResult['SECTION']['SECTION_PAGE_URL']; ?>"><?
			echo(
			isset($arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]) && $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != ""
					? $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]
					: $arResult['SECTION']['NAME']
			);
			?></a></h1><?
	}
	if (0 < $arResult["SECTIONS_COUNT"]) {
		?>
		<ul class="<? echo $arCurView['LIST']; ?>">
			<?
			switch ($arParams['VIEW_MODE'])
			{
			case 'LINE':
				foreach ($arResult['SECTIONS'] as &$arSection) {
					$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
					$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

					if (false === $arSection['PICTURE'])
						$arSection['PICTURE'] = [
								'SRC'   => $arCurView['EMPTY_IMG'],
								'ALT'   => (
								'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
										? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
										: $arSection["NAME"]
								),
								'TITLE' => (
								'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
										? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
										: $arSection["NAME"]
								),
						];
					?>
					<li id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
					<a
							href="<? echo $arSection['SECTION_PAGE_URL']; ?>"
							class="bx_catalog_line_img"
							style="background-image: url('<? echo $arSection['PICTURE']['SRC']; ?>');"
							title="<? echo $arSection['PICTURE']['TITLE']; ?>"
					></a>
					<h2 class="bx_catalog_line_title">
						<a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
						if ($arParams["COUNT_ELEMENTS"] && $arSection['ELEMENT_CNT'] !== null) {
							?> <span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span><?
						}
						?></h2><?
					if ('' != $arSection['DESCRIPTION']) {
						?><p class="bx_catalog_line_description"><? echo $arSection['DESCRIPTION']; ?></p><?
					}
					?>
					<div style="clear: both;"></div>
					</li><?
				}
				unset($arSection);
				break;
			case 'TEXT':
				foreach ($arResult['SECTIONS'] as &$arSection) {
					$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
					$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

					?>
					<li id="<? echo $this->GetEditAreaId($arSection['ID']); ?>"><h2 class="bx_catalog_text_title"><a
								href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
						if ($arParams["COUNT_ELEMENTS"] && $arSection['ELEMENT_CNT'] !== null) {
							?> <span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span><?
						}
						?></h2></li><?
				}
				unset($arSection);
				break;
			case 'TILE':
				foreach ($arResult['SECTIONS'] as &$arSection) {
					$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
					$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

					if (false === $arSection['PICTURE'])
						$arSection['PICTURE'] = [
								'SRC'   => $arCurView['EMPTY_IMG'],
								'ALT'   => (
								'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
										? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
										: $arSection["NAME"]
								),
								'TITLE' => (
								'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
										? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
										: $arSection["NAME"]
								),
						];
					?>
					<li id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
				<a
						href="<? echo $arSection['SECTION_PAGE_URL']; ?>"
						class="bx_catalog_tile_img"
						style="background-image:url('<? echo $arSection['PICTURE']['SRC']; ?>');"
						title="<? echo $arSection['PICTURE']['TITLE']; ?>"
				> </a><?
					if ('Y' != $arParams['HIDE_SECTION_NAME']) {
						?><h2 class="bx_catalog_tile_title">
						<a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
						if ($arParams["COUNT_ELEMENTS"] && $arSection['ELEMENT_CNT'] !== null) {
							?> <span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span><?
						}
						?></h2><?
					}
					?></li><?
				}
				unset($arSection);
				break;
			case 'LIST':
			$intCurrentDepth = 1;
			$boolFirst = true;
			foreach ($arResult['SECTIONS'] as &$arSection)
			{
			$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
			$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

			if ($intCurrentDepth < $arSection['RELATIVE_DEPTH_LEVEL']) {
				if (0 < $intCurrentDepth)
					echo "\n", str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']), '<ul>';
			} else if ($intCurrentDepth == $arSection['RELATIVE_DEPTH_LEVEL']) {
				if (!$boolFirst)
					echo '</li>';
			} else {
				while ($intCurrentDepth > $arSection['RELATIVE_DEPTH_LEVEL']) {
					echo '</li>', "\n", str_repeat("\t", $intCurrentDepth), '</ul>', "\n", str_repeat("\t", $intCurrentDepth - 1);
					$intCurrentDepth--;
				}
				echo str_repeat("\t", $intCurrentDepth - 1), '</li>';
			}

			echo(!$boolFirst ? "\n" : ''), str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']);
			?>
			<li id="<?= $this->GetEditAreaId($arSection['ID']); ?>"><h2 class="bx_sitemap_li_title">
					<a href="<? echo $arSection["SECTION_PAGE_URL"]; ?>"><? echo $arSection["NAME"]; ?><?
						if ($arParams["COUNT_ELEMENTS"] && $arSection['ELEMENT_CNT'] !== null) {
							?> <span>(<? echo $arSection["ELEMENT_CNT"]; ?>)</span><?
						}
						?></a></h2><?

				$intCurrentDepth = $arSection['RELATIVE_DEPTH_LEVEL'];
				$boolFirst = false;
				}
				unset($arSection);
				while ($intCurrentDepth > 1) {
					echo '</li>', "\n", str_repeat("\t", $intCurrentDepth), '</ul>', "\n", str_repeat("\t", $intCurrentDepth - 1);
					$intCurrentDepth--;
				}
				if ($intCurrentDepth > 0) {
					echo '</li>', "\n";
				}
				break;
				}
				?>
		</ul>
		<?
		echo('LINE' != $arParams['VIEW_MODE'] ? '<div style="clear: both;"></div>' : '');
	}
	?></div>