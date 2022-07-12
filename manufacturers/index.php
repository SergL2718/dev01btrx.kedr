<?php
$GLOBALS["HIDE_BREADCRUMB"] = true;
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
$APPLICATION->SetTitle("Производители");
$APPLICATION->SetPageProperty("title", "Производители | Интернет-магазин Звенящие Кедры");
$APPLICATION->SetPageProperty("description", "Кто изготавливает продукцию под знаком «Звенящие Кедры России». Перечень всех владельцев семейных производств, родовых поместий и частных мастеров, с которыми мы сотрудничаем.");
$APPLICATION->SetPageProperty("keywords", "");
?>

<div class="page-banner page-banner_left"
     style="background-image: url('<?= SITE_TEMPLATE_PATH ?>/images/temp/manufacturers.jpg')">
    <div class="container">
        <div class="page-banner__title">ЧЕСТНЫЕ ПРОИЗВОДИТЕЛИ</div>
        <div class="page-banner__sub">Звенящие кедры объединяют небольшие семейные производства под своим брендом.
            Все
            продукты изготавливаются как для себя: с любовью и заботой из лучших ингридиентов
        </div>
    </div>
</div>
<?php $APPLICATION->IncludeComponent(
	'bitrix:breadcrumb',
	'',
	[
		'COMPONENT_TEMPLATE' => '',
		'START_FROM' => '0',
	],
	false,
	['HIDE_ICONS' => 'N']
) ?>

<? $APPLICATION->IncludeComponent("bitrix:catalog.section.list", "manufacturers", array(
	"VIEW_MODE" => "TEXT",    // Вид списка подразделов
	"SHOW_PARENT_NAME" => "Y",    // Показывать название раздела
	"IBLOCK_TYPE" => "1c_catalog",    // Тип инфоблока
	"IBLOCK_ID" => "41",    // Инфоблок
	"SECTION_ID" => "",    // ID раздела
	"SECTION_CODE" => "",    // Код раздела
	"SECTION_URL" => "",    // URL, ведущий на страницу с содержимым раздела
	"COUNT_ELEMENTS" => "Y",    // Показывать количество элементов в разделе
	"TOP_DEPTH" => "1",    // Максимальная отображаемая глубина разделов
	"SECTION_FIELDS" => array(    // Поля разделов
		0 => "",
		1 => "",
	),
	"SECTION_USER_FIELDS" => array(    // Свойства разделов
		0 => "UF_NOT_SHOW",
		1 => "",
	),
	"ADD_SECTIONS_CHAIN" => "N",    // Включать раздел в цепочку навигации
	"CACHE_TYPE" => "A",    // Тип кеширования
	"CACHE_TIME" => "36000000",    // Время кеширования (сек.)
	"CACHE_NOTES" => "",
	"CACHE_GROUPS" => "N",    // Учитывать права доступа
	"COMPONENT_TEMPLATE" => ".default",
	"COUNT_ELEMENTS_FILTER" => "CNT_ACTIVE",    // Показывать количество
	"FILTER_NAME" => "sectionsFilter",    // Имя массива со значениями фильтра разделов
	"CACHE_FILTER" => "N",    // Кешировать при установленном фильтре
),
	false
); ?>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
