<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;


if (strlen($arParams["MAIN_CHAIN_NAME"]) > 0) {
	$APPLICATION->AddChainItem(htmlspecialcharsbx($arParams["MAIN_CHAIN_NAME"]), $arResult['SEF_FOLDER']);
}

$availablePages = array();

if ($arParams['SHOW_ORDER_PAGE'] === 'Y') {
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_ORDERS'],
		"name" => Loc::getMessage("SPS_ORDER_PAGE_NAME"),
		"icon" => '<i class="fa fa-calculator"></i>'
	);
}

if ($arParams['SHOW_ACCOUNT_PAGE'] === 'Y') {
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_ACCOUNT'],
		"name" => Loc::getMessage("SPS_ACCOUNT_PAGE_NAME"),
		"icon" => '<i class="fa fa-credit-card"></i>'
	);
}

if ($arParams['SHOW_PRIVATE_PAGE'] === 'Y') {
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_PRIVATE'],
		"name" => Loc::getMessage("SPS_PERSONAL_PAGE_NAME"),
		"icon" => '<i class="fa fa-user"></i>'
	);
}

if ($arParams['SHOW_ORDER_PAGE'] === 'Y') {

	$delimeter = ($arParams['SEF_MODE'] === 'Y') ? "?" : "&";
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_ORDERS'] . $delimeter . "filter_history=Y",
		"name" => Loc::getMessage("SPS_ORDER_PAGE_HISTORY"),
		"icon" => '<i class="fa fa-list-alt"></i>'
	);
}

if ($arParams['SHOW_PROFILE_PAGE'] === 'Y') {
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_PROFILE'],
		"name" => Loc::getMessage("SPS_PROFILE_PAGE_NAME"),
		"icon" => '<i class="fa fa-users"></i>'
	);
}

if ($arParams['SHOW_BASKET_PAGE'] === 'Y') {
	$availablePages[] = array(
		"path" => $arParams['PATH_TO_BASKET'],
		"name" => Loc::getMessage("SPS_BASKET_PAGE_NAME"),
		"icon" => '<i class="fa fa-shopping-cart"></i>'
	);
}

if ($arParams['SHOW_SUBSCRIBE_PAGE'] === 'Y') {
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_SUBSCRIBE'],
		"name" => Loc::getMessage("SPS_SUBSCRIBE_PAGE_NAME"),
		"icon" => '<i class="fa fa-envelope-o"></i>'
	);
}

if ($arParams['SHOW_CONTACT_PAGE'] === 'Y') {
	$availablePages[] = array(
		"path" => $arParams['PATH_TO_CONTACT'],
		"name" => Loc::getMessage("SPS_CONTACT_PAGE_NAME"),
		"icon" => '<i class="fa fa-info"></i>'
	);
}

$customPagesList = CUtil::JsObjectToPhp($arParams['~CUSTOM_PAGES']);
if ($customPagesList) {
	foreach ($customPagesList as $page) {
		$availablePages[] = array(
			"path" => $page[0],
			"name" => $page[1],
			"icon" => (strlen($page[2])) ? '<i class="fa ' . htmlspecialcharsbx($page[2]) . '"></i>' : ""
		);
	}
}

if (empty($availablePages)) {
	ShowError(Loc::getMessage("SPS_ERROR_NOT_CHOSEN_ELEMENT"));
} else {
	//echo "<pre>"; print_r($availablePages); echo "</pre>";
	?>
    <section class="cabinet">
        <div class="container">
            <div class="page-title">Личный кабинет</div>
			<?
			$APPLICATION->IncludeFile("/local/include/personal/menu.php", array("ACTIVE" => false), array(
				"MODE" => "html",
				"NAME" => "",
				"TEMPLATE" => "section_include_template.php"
			));
			?>
            <div class="cabinet-logout">
                <a href="/?logout=yes" class="button button_primary">Выход</a>
            </div>
        </div>
    </section>


	<?php
	/*
	?>
	<div class="row">
		<div class="col-md-12 sale-personal-section-index">
			<div class="row sale-personal-section-row-flex">
				<?
				foreach ($availablePages as $blockElement)
				{
					?>
					<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
						<div class="sale-personal-section-index-block bxr-border-color-hover">
							<a class="sale-personal-section-index-block-link" href="<?=htmlspecialcharsbx($blockElement['path'])?>">
								<span class="sale-personal-section-index-block-ico bxr-font-color">
									<?=$blockElement['icon']?>
								</span>
								<h2 class="sale-personal-section-index-block-name">
									<?=htmlspecialcharsbx($blockElement['name'])?>
								</h2>
							</a>
						</div>
					</div>
					<?
				}
				?>
			</div>
		</div>
	</div>
	<?*/
}
?>
