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





<section class="faq">
    <div class="container">
        <div class="faq-header">
            <div class="page-title">Часто задаваемые вопросы</div>
            <form class="input-search" action="/faq/">
                <input placeholder="Поиск по ответам..." name="q" value="<?if($_GET["q"])echo trim(htmlspecialcharsEx($_GET["q"]));?>"/>
                <button type="submit"></button>
            </form>
        </div>
        <div class="faq-container">
            <div class="faq-content">
				<?
				foreach ($arResult["SECTIONS"] as $SECTION) {
					$GLOBALS["arrFilter"] = array("SECTION_ID" => $SECTION["ID"]);
                    if($_GET["q"])$GLOBALS["arrFilter"]["SEARCHABLE_CONTENT"] = "%".$_GET["q"]."%";
					?>

                    <div class="faq-content__item">
                        <div class="faq-content__title"><?= $SECTION["NAME"] ?></div>
						<? $APPLICATION->IncludeComponent("bitrix:news.list", "faq", array(
							"DISPLAY_DATE" => "Y",    // Выводить дату элемента
							"DISPLAY_NAME" => "Y",    // Выводить название элемента
							"DISPLAY_PICTURE" => "Y",    // Выводить изображение для анонса
							"DISPLAY_PREVIEW_TEXT" => "Y",    // Выводить текст анонса
							"AJAX_MODE" => "N",    // Включить режим AJAX
							"IBLOCK_TYPE" => "content",    // Тип информационного блока (используется только для проверки)
							"IBLOCK_ID" => "67",    // Код информационного блока
							"NEWS_COUNT" => "200",    // Количество новостей на странице
							"SORT_BY1" => "ACTIVE_FROM",    // Поле для первой сортировки новостей
							"SORT_ORDER1" => "DESC",    // Направление для первой сортировки новостей
							"SORT_BY2" => "SORT",    // Поле для второй сортировки новостей
							"SORT_ORDER2" => "ASC",    // Направление для второй сортировки новостей
							"FILTER_NAME" => "arrFilter",    // Фильтр
							"FIELD_CODE" => array(    // Поля
								0 => "",
								1 => "",
							),
							"PROPERTY_CODE" => array(    // Свойства
								0 => "",
								1 => "",
								2 => "",
							),
							"CHECK_DATES" => "Y",    // Показывать только активные на данный момент элементы
							"DETAIL_URL" => "",    // URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
							"PREVIEW_TRUNCATE_LEN" => "",    // Максимальная длина анонса для вывода (только для типа текст)
							"ACTIVE_DATE_FORMAT" => "d.m.Y",    // Формат показа даты
							"SET_TITLE" => "N",    // Устанавливать заголовок страницы
							"SET_BROWSER_TITLE" => "N",    // Устанавливать заголовок окна браузера
							"SET_META_KEYWORDS" => "N",    // Устанавливать ключевые слова страницы
							"SET_META_DESCRIPTION" => "N",    // Устанавливать описание страницы
							"SET_LAST_MODIFIED" => "N",    // Устанавливать в заголовках ответа время модификации страницы
							"INCLUDE_IBLOCK_INTO_CHAIN" => "N",    // Включать инфоблок в цепочку навигации
							"ADD_SECTIONS_CHAIN" => "N",    // Включать раздел в цепочку навигации
							"HIDE_LINK_WHEN_NO_DETAIL" => "N",    // Скрывать ссылку, если нет детального описания
							"PARENT_SECTION" => "",    // ID раздела
							"PARENT_SECTION_CODE" => "",    // Код раздела
							"INCLUDE_SUBSECTIONS" => "Y",    // Показывать элементы подразделов раздела
							"CACHE_TYPE" => "A",    // Тип кеширования
							"CACHE_TIME" => "3600",    // Время кеширования (сек.)
							"CACHE_FILTER" => "N",    // Кешировать при установленном фильтре
							"CACHE_GROUPS" => "N",    // Учитывать права доступа
							"DISPLAY_TOP_PAGER" => "N",    // Выводить над списком
							"DISPLAY_BOTTOM_PAGER" => "N",    // Выводить под списком
							"PAGER_TITLE" => "Новости",    // Название категорий
							"PAGER_SHOW_ALWAYS" => "N",    // Выводить всегда
							"PAGER_TEMPLATE" => "",    // Шаблон постраничной навигации
							"PAGER_DESC_NUMBERING" => "N",    // Использовать обратную навигацию
							"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",    // Время кеширования страниц для обратной навигации
							"PAGER_SHOW_ALL" => "Y",    // Показывать ссылку "Все"
							"PAGER_BASE_LINK_ENABLE" => "N",    // Включить обработку ссылок
							"SET_STATUS_404" => "N",    // Устанавливать статус 404
							"SHOW_404" => "N",    // Показ специальной страницы
							"MESSAGE_404" => "",    // Сообщение для показа (по умолчанию из компонента)
							"PAGER_BASE_LINK" => "",
							"PAGER_PARAMS_NAME" => "arrPager",
							"AJAX_OPTION_JUMP" => "N",    // Включить прокрутку к началу компонента
							"AJAX_OPTION_STYLE" => "N",    // Включить подгрузку стилей
							"AJAX_OPTION_HISTORY" => "N",    // Включить эмуляцию навигации браузера
							"AJAX_OPTION_ADDITIONAL" => "",    // Дополнительный идентификатор
							"COMPONENT_TEMPLATE" => ".default",
							"STRICT_SECTION_CHECK" => "N",    // Строгая проверка раздела для показа списка
						),
							false
						); ?>
                    </div>

				<? } ?>

                <div class="block-question__container">
                    <div class="block-title">Не нашли ответ?<br/> Мы готовы ответить на любой ваш вопрос --- звоните!</div>
                    <a class="block-question__call" href="tel:88003500270">8-800-350-0270</a>
                    <div class="block-question__time">Часы работы: будние дни с 6:00 до 16:00 (время Московское)</div>
                    <div class="block-title">Не любите говорить по телефону?</div>
                    <div class="block-question__email">Тогда пишите нам на <a href='mailto:admin@megre.ru'>admin@megre.ru</a>
                    </div>
                </div>

            </div>
            <div class="faq-banner">
                <a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/images/elexir-2.jpg" alt=""></a>
                <div class="side-subscribe">
                    <div class="side-subscribe__title">Хотите подарок с&nbsp;таёжного производства?</div>
                    <div class="side-subscribe__sub">Дарим дорожный размер нашей любимой зубной пасты при подписке.</div>
                    <form class="subscribe_form">
                        <div class="input">
                            <label>Ваш e-mail</label>
                            <input placeholder="Введите email" name="EMAIL" required/>
                        </div>
                        <button class="button button_primary">Подписаться</button>
                    </form>
                    <div class="side-subscribe__privacy">Нажимая кнопку «Подписаться», я даю свое согласие на обработку моих персональных данных, в соответствии с Федеральным законом от 27.07.2006 года №152-ФЗ «О персональных данных», на условиях и для целей, определенных в <a href="/privacy-policy/" target="_blank">Согласии на обработку персональных данных</a>.</div>
                </div>
                <a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/images/temp/delivery.jpg" alt=""></a>
            </div>
        </div>
    </div>
</section>

<div class="pay-system">
    <div class="container">
        <div class="block-title">МЫ ПРИНИМАЕМ</div>
        <div class="pay-system__list"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icons/applepay.svg" alt=""/><img
                    src="<?= SITE_TEMPLATE_PATH ?>/images/icons/googlepay.svg"
                    alt=""/><img
                    src="<?= SITE_TEMPLATE_PATH ?>/images/icons/spay.svg" alt=""/><img
                    src="<?= SITE_TEMPLATE_PATH ?>/images/icons/visa.svg" alt=""/><img
                    src="<?= SITE_TEMPLATE_PATH ?>/images/icons/mastercard.svg" alt=""/><img
                    src="<?= SITE_TEMPLATE_PATH ?>/images/icons/jcb.svg" alt=""/></div>
        <div class="pay-system__text">Доставка осуществляется почтой, курьером или самовывозом, а также Boxberry.</div>
    </div>
</div>
<?php
if($_GET["q"]){?>
    <script>
        $(function(){
            $('.faq-content__item').each(function(i, obj){
                if(!$(obj).find('.faq-card').length)$(obj).remove();
            });
        })
    </script>
<?}?>
