<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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

$arViewStyles = array(
	'LIST' => array(
		'CONT' => 'bx_sitemap',
		'TITLE' => 'bx_sitemap_title',
		'LIST' => 'bx_sitemap_ul',
	),
	'LINE' => array(
		'CONT' => 'bx_catalog_line',
		'TITLE' => 'bx_catalog_line_category_title',
		'LIST' => 'bx_catalog_line_ul',
		'EMPTY_IMG' => $this->GetFolder() . '/images/line-empty.png'
	),
	'TEXT' => array(
		'CONT' => 'bx_catalog_text',
		'TITLE' => 'bx_catalog_text_category_title',
		'LIST' => 'bx_catalog_text_ul'
	),
	'TILE' => array(
		'CONT' => 'bx_catalog_tile',
		'TITLE' => 'bx_catalog_tile_category_title',
		'LIST' => 'bx_catalog_tile_ul',
		'EMPTY_IMG' => $this->GetFolder() . '/images/tile-empty.png'
	)
);
$arCurView = $arViewStyles[$arParams['VIEW_MODE']];

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
?>
    <section class="manufacturers">
        <div class="container">
            <div class="page-title">НАШИ ПРОИЗВОДИТЕЛИ</div>
            <div class="page-sub">Звенящие кедры объединяют небольшие семейные производства под своим брендом.<br/> Все
                продукты изготавливаются как для себя: с любовью и заботой из лучших ингридиентов
            </div>
            <div class="manufacturers-item">
                <div class="manufacturers-list">
					<?php
					$n = 0;
					$arResult['SECTIONS_SHOW_MORE'] = array();
					foreach ($arResult['SECTIONS'] as $arSection) {
						if ($arSection["UF_NOT_SHOW"]) continue;
						if ($n <= 5) {
							?>
                            <a class="manufacturer-card" href="<?= $arSection["SECTION_PAGE_URL"] ?>"
                               style="background-image: url('<?= $arSection["PICTURE"]['SRC'] ?>')">
                                <div class="manufacturer-card__content">
                                    <div class="block-title"><?= $arSection["NAME"] ?></div>
                                </div>
                            </a>
							<?
						} else {
							$arResult['SECTIONS_SHOW_MORE'][] = $arSection;
							?>
                            <a class="manufacturer-card hidden" href="<?= $arSection["SECTION_PAGE_URL"] ?>"
                               style="background-image: url('<?= $arSection["PICTURE"]['SRC'] ?>')">
                                <div class="manufacturer-card__content">
                                    <div class="block-title"><?= $arSection["NAME"] ?></div>
                                </div>
                            </a>
							<?
						}
						$n++;
					}
					?>
                </div>
                <div class="block-show-more">
                    <div class="link-more">ПОКАЗАТЬ БОЛЬШЕ</div>
                </div>
            </div>

            <div class="manufacturers-item">
                <div class="page-title">ПРОИЗВОДИТЕЛЯМ ИЗ РОДОВЫХ ПОМЕСТИЙ</div>
                <div class="manufacturers-content">
                    Идея компании «Звенящие Кедры» в том, чтобы объединить товары, сделанные с любовью к природе и
                    своему
                    делу в родовых поместьях России. Мы поддерживаем производителей, которые живут и творят на земле,
                    возрождая традиции предков и создавая свои. Силу, качество и действие таких продуктов покупатели
                    ценят
                    всё больше.<br/><br/>
                    Мы открыты новому сотрудничеству и готовы представить Ваш продукт под знаком «Звенящие кедры
                    России».
                    Товар может быть представлен для отдельных регионов или на всей территории России, на международном
                    рынке, в интернет-магазине megre.ru.<br/><br/>
                    Мы дорожим чистой репутацией, сложившейся за более 19 лет существования марки, и доверием клиентов,
                    потому представляем только продукцию, соответствующую нашим взглядам на производство, сознание и
                    образ
                    жизни производителя.
                </div>
                <div class="manufacturers-image">
                    <img src="<?= SITE_TEMPLATE_PATH ?>/images/temp/manuf-10.jpg" alt="">
                    <img src="<?= SITE_TEMPLATE_PATH ?>/images/temp/manuf-11.jpg" alt=""></div>
            </div>
            <div class="manufacturers-item">
                <div class="page-title">Какая продукция может продаваться под знаком<br/> «Звенящие кедры России»?</div>
                <div class="manufacturers-content">
                    Обязательные условия, на основании которых продукт попадает в наш ассортимент:<br/><br/>
                    --- производится в родовом поместье или личном подворье, в семьях или у мастеров, проживающих на
                    земле<br/><br/>
                    --- 100% экологичность, продукция изготовлена из природных материалов, без химических веществ и
                    консервантов в составе<br/><br/>
                    --- выращивание и сбор сырья осуществляется осознанно, с уважением к земле и природе, щадящими,
                    экологичными способами, это значит, что не используются гормоны и ускоряющие рост
                    препараты<br/><br/>
                    --- использование преимущественно ручного труда
                </div>
            </div>
            <div class="manufacturers-item">
                <div class="manufacturers-coop"><img src="<?= SITE_TEMPLATE_PATH ?>/images/temp/manuf-15.jpg" alt="">
                    <div class="manufacturers-coop__content">
                        <div class="page-title">Чтобы начать сотрудничество с&nbsp;нами, необходимо:</div>
                        <p>
                            --- зарегистрировать юридическое лицо или ИП<br/><br/>
                            --- иметь банковский расчетный счет<br/><br/>
                            --- сделать сертификаты и другую необходимую документацию на продукцию<br/><br/>
                            --- отправить предложение на почту <a class='link'
                                                                  href='mailto:zakup@megre.ru'>zakup@megre.ru</a> и
                            указать
                            Ваши контакты
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?


/*
?><div class="<? echo $arCurView['CONT']; ?>"><?
if ('Y' == $arParams['SHOW_PARENT_NAME'] && 0 < $arResult['SECTION']['ID'])
{
	$this->AddEditAction($arResult['SECTION']['ID'], $arResult['SECTION']['EDIT_LINK'], $strSectionEdit);
	$this->AddDeleteAction($arResult['SECTION']['ID'], $arResult['SECTION']['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

	?><h1
		class="<? echo $arCurView['TITLE']; ?>"
		id="<? echo $this->GetEditAreaId($arResult['SECTION']['ID']); ?>"
	><a href="<? echo $arResult['SECTION']['SECTION_PAGE_URL']; ?>"><?
		echo (
			isset($arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]) && $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != ""
			? $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]
			: $arResult['SECTION']['NAME']
		);
	?></a></h1><?
}
if (0 < $arResult["SECTIONS_COUNT"])
{
?>
<ul class="<? echo $arCurView['LIST']; ?>">
<?
	switch ($arParams['VIEW_MODE'])
	{
		case 'LINE':
			foreach ($arResult['SECTIONS'] as &$arSection)
			{
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

				if (false === $arSection['PICTURE'])
					$arSection['PICTURE'] = array(
						'SRC' => $arCurView['EMPTY_IMG'],
						'ALT' => (
							'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
							? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
							: $arSection["NAME"]
						),
						'TITLE' => (
							'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
							? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
							: $arSection["NAME"]
						)
					);
				?><li id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
				<a
					href="<? echo $arSection['SECTION_PAGE_URL']; ?>"
					class="bx_catalog_line_img"
					style="background-image: url('<? echo $arSection['PICTURE']['SRC']; ?>');"
					title="<? echo $arSection['PICTURE']['TITLE']; ?>"
				></a>
				<h2 class="bx_catalog_line_title"><a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
				if ($arParams["COUNT_ELEMENTS"] && $arSection['ELEMENT_CNT'] !== null)
				{
					?> <span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span><?
				}
				?></h2><?
				if ('' != $arSection['DESCRIPTION'])
				{
					?><p class="bx_catalog_line_description"><? echo $arSection['DESCRIPTION']; ?></p><?
				}
				?><div style="clear: both;"></div>
				</li><?
			}
			unset($arSection);
			break;
		case 'TEXT':
			foreach ($arResult['SECTIONS'] as &$arSection)
			{
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

				?><li id="<? echo $this->GetEditAreaId($arSection['ID']); ?>"><h2 class="bx_catalog_text_title"><a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
				if ($arParams["COUNT_ELEMENTS"] && $arSection['ELEMENT_CNT'] !== null)
				{
					?> <span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span><?
				}
				?></h2></li><?
			}
			unset($arSection);
			break;
		case 'TILE':
			foreach ($arResult['SECTIONS'] as &$arSection)
			{
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

				if (false === $arSection['PICTURE'])
					$arSection['PICTURE'] = array(
						'SRC' => $arCurView['EMPTY_IMG'],
						'ALT' => (
							'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
							? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
							: $arSection["NAME"]
						),
						'TITLE' => (
							'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
							? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
							: $arSection["NAME"]
						)
					);
				?><li id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
				<a
					href="<? echo $arSection['SECTION_PAGE_URL']; ?>"
					class="bx_catalog_tile_img"
					style="background-image:url('<? echo $arSection['PICTURE']['SRC']; ?>');"
					title="<? echo $arSection['PICTURE']['TITLE']; ?>"
					> </a><?
				if ('Y' != $arParams['HIDE_SECTION_NAME'])
				{
					?><h2 class="bx_catalog_tile_title"><a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
					if ($arParams["COUNT_ELEMENTS"] && $arSection['ELEMENT_CNT'] !== null)
					{
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

				if ($intCurrentDepth < $arSection['RELATIVE_DEPTH_LEVEL'])
				{
					if (0 < $intCurrentDepth)
						echo "\n",str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']),'<ul>';
				}
				elseif ($intCurrentDepth == $arSection['RELATIVE_DEPTH_LEVEL'])
				{
					if (!$boolFirst)
						echo '</li>';
				}
				else
				{
					while ($intCurrentDepth > $arSection['RELATIVE_DEPTH_LEVEL'])
					{
						echo '</li>',"\n",str_repeat("\t", $intCurrentDepth),'</ul>',"\n",str_repeat("\t", $intCurrentDepth-1);
						$intCurrentDepth--;
					}
					echo str_repeat("\t", $intCurrentDepth-1),'</li>';
				}

				echo (!$boolFirst ? "\n" : ''),str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']);
				?><li id="<?=$this->GetEditAreaId($arSection['ID']);?>"><h2 class="bx_sitemap_li_title"><a href="<? echo $arSection["SECTION_PAGE_URL"]; ?>"><? echo $arSection["NAME"];?><?
				if ($arParams["COUNT_ELEMENTS"] && $arSection['ELEMENT_CNT'] !== null)
				{
					?> <span>(<? echo $arSection["ELEMENT_CNT"]; ?>)</span><?
				}
				?></a></h2><?

				$intCurrentDepth = $arSection['RELATIVE_DEPTH_LEVEL'];
				$boolFirst = false;
			}
			unset($arSection);
			while ($intCurrentDepth > 1)
			{
				echo '</li>',"\n",str_repeat("\t", $intCurrentDepth),'</ul>',"\n",str_repeat("\t", $intCurrentDepth-1);
				$intCurrentDepth--;
			}
			if ($intCurrentDepth > 0)
			{
				echo '</li>',"\n";
			}
			break;
	}
?>
</ul>
<?
	echo ('LINE' != $arParams['VIEW_MODE'] ? '<div style="clear: both;"></div>' : '');
}
?></div>
