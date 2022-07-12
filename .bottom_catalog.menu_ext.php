<?
use Alexkova\Market\Data;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(CModule::IncludeModule('iblock') && CModule::IncludeModule('alexkova.market'))
{
	$aMenuLinksExt = array();
	//проверить кеш
	$obCache = new CPHPCache();
	$cacheLifetime = 864000*3;
	$cacheID = 'bottomCatalogMenuExt';
	$cachePath = '/'.$cacheID;
	if( $obCache->InitCache($cacheLifetime, $cacheID, $cachePath) )
	{
		extract($obCache->GetVars());
	}
	else
	{
		$filter = array(
			"IBLOCK_ID" => 37,
			"ACTIVE" => "Y",
			"!UF_SHOW_IN_FOOTER" => false
		);
		$select = array(
			"ID", "NAME", "IBLOCK_ID", "CODE", "SECTION_PAGE_URL"
		);
		$res = CIBlockSection::GetList(
			array('SORT' => 'ASC', 'LEFT_MARGIN' => 'ASC', 'NAME' => 'ASC'),
			$filter,
			false,
			$select
		);
		while ($item = $res->GetNext(true, false))
		{
			$aMenuLinksExt[] = array(
				$item["NAME"],
				$item["SECTION_PAGE_URL"],
			);
		}
		$res = $obCache->EndDataCache(array('aMenuLinksExt' => $aMenuLinksExt));
	}
	$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
}