<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Page\Asset;

if ($arParams['GUEST_MODE'] !== 'Y') {
	Asset::getInstance()->addJs("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/script.js");
	Asset::getInstance()->addCss("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/style.css");
}
//$this->addExternalCss("/bitrix/css/main/bootstrap.css");

CJSCore::Init(array('clipboard', 'fx'));

$APPLICATION->SetTitle("");
$APPLICATION->SetPageProperty('title', Loc::getMessage('SPOD_LIST_MY_ORDER', array(
	'#ACCOUNT_NUMBER#' => htmlspecialcharsbx($arResult["ACCOUNT_NUMBER"]),
	'#DATE_ORDER_CREATE#' => $arResult["DATE_INSERT_FORMATED"]
)));

if (!empty($arResult['ERRORS']['FATAL'])) {
	foreach ($arResult['ERRORS']['FATAL'] as $error) {
		ShowError($error);
	}

	$component = $this->__component;

	if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED])) {
		$APPLICATION->AuthForm('', false, false, 'N', false);
	}
} else {
	if (!empty($arResult['ERRORS']['NONFATAL'])) {
		foreach ($arResult['ERRORS']['NONFATAL'] as $error) {
			ShowError($error);
		}
	}
	//echo "<pre>"; print_r ($arResult); echo "</pre>";
	?>

    <section class="order-view mb-5">
        <div class="container">
            <div class="page-title">
                <p>ЗАКАЗ <span>№ <?= $arResult["ACCOUNT_NUMBER"] ?></span> от
                    <span><?= $arResult["DATE_INSERT_FORMATED"] ?></span></p>
                <div class="button button_outline"><?= $arResult["STATUS"]["NAME"] ?></div>
            </div>
            <div class="order-container">
                <div class="order-content">
                    <div class="order-content-list">
                        <div class="order-content-list__title">Товары</div>
                        <div class="order-content-list__container">
							<? foreach ($arResult["BASKET"] as $BASKET) { ?>
                                <div class="order-card">
                                    <div class="order-card__content">
                                        <div class="order-card__image"><?if($BASKET["PICTURE"]["SRC"]){?><img src="<?= $BASKET["PICTURE"]["SRC"] ?>"
                                                                            alt=""><?}?></div>
                                        <div class="order-card__info">
                                            <a class="order-card__info-name" href="<?= $BASKET["DETAIL_PAGE_URL"] ?>">
												<?= $BASKET["NAME"] ?>
                                            </a>
											<?/*?><div class="order-card__info-stat"><span>250 мл</span></div><?*/ ?>
                                        </div>
                                        <div class="order-card__quantity">
                                            <div class="order-card__label">Количество</div>
                                            <div class="order-card__quantity-num"><?= $BASKET["QUANTITY"] ?> шт.</div>
                                        </div>
                                        <div class="order-card__price">
                                            <div class="order-card__label">Цена</div>
                                            <div class="order-card__price-std"><?= $BASKET["PRICE_FORMATED"] ?></div>
                                        </div>
                                    </div>
                                    <div class="order-card__footer">
                                        <a href="<?= $BASKET["DETAIL_PAGE_URL"] ?>" class="link-more">НАПИСАТЬ ОТЗЫВ</a>
                                    </div>
                                </div>
							<?
							} ?>
                        </div>
                    </div>
                    <div class="order-view-call">
                        <div class="block-title">КОНТАКТЫ</div>
                        <div class="order-view-call__wrap" id="order-call">
                            <div class="order-view-call__formed">
                                <p><?= $arResult["USER"]["PERSONAL_PHONE"] ?></p>
                                <div class="button button_primary" id="call-me-button">Связаться со мной</div>
                            </div>
                            <div class="link-more change-phone__detail_order">ИЗМЕНИТЬ</div>
                            <div class="order-view-call__help" style="display:none">Вам скоро позвонит наш менеджер.
                                <br/>Наши рабочие часы: пн-пт с 6 до 16 по московскому времени ☺️
                            </div>
                        </div>
                        <div class="order-view-call__wrap" id="order-call-phone" style="display:none">
                            <div class="order-view-call__form">
                                <div class="input">
                                    <input name="PHONE" value="<?= $arResult["USER"]["PERSONAL_PHONE"] ?>" type="tel"/>
                                </div>
                                <div class="button button_primary change-phone__detail_order">ОК</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-view-side">
                    <div class="order-side-card">
                        <div class="order-side-card__title">СПОСОБ ДОСТАВКИ</div>
                        <div class="order-side-card__body">
                            <div class="order-side-card__type"><?= $arResult["DELIVERY"]["NAME"] ?></div>
							<?/*?><div class="order-side-card__text">
                                <p>Россия, г. Москва</p>
                                <p>102738</p>
                                <p>ул. 1905 года, д.5, кв. 15</p>
                            </div><?*/ ?>
                        </div>
                    </div>
					<?
					$order = \Bitrix\Sale\Order::load($arResult["ID"]);

					/** @var \Bitrix\Sale\ShipmentCollection $shipmentCollection */
					$shipmentCollection = $order->getShipmentCollection();

					/** @var \Bitrix\Sale\Shipment $shipment */
					foreach ($shipmentCollection as $shipment) {
						$track = $shipment->getField("TRACKING_NUMBER");
						if (!$track) continue;
						?>
                        <div class="order-side-card">
                            <div class="order-side-card__title">Трек-Номер</div>
                            <div class="order-side-card__body">
                                <div class="order-side-card__type"><?= $track ?></div>
                                <div class="order-side-card__text">
                                    <div class="link-more">ОТСЛЕДИТЬ</div>
                                </div>
                            </div>
                        </div>

						<?
					}
					?>
					<? $GLOBALS["arrFilter"] = array("PROPERTY_ORDER_ID" => $arResult["ID"]); ?>
					<? $APPLICATION->IncludeComponent("bitrix:news.list", "order_detail_bonus_list", array(
						"DISPLAY_DATE" => "Y",    // Выводить дату элемента
						"DISPLAY_NAME" => "Y",    // Выводить название элемента
						"DISPLAY_PICTURE" => "Y",    // Выводить изображение для анонса
						"DISPLAY_PREVIEW_TEXT" => "Y",    // Выводить текст анонса
						"AJAX_MODE" => "N",    // Включить режим AJAX
						"IBLOCK_TYPE" => "service",    // Тип информационного блока (используется только для проверки)
						"IBLOCK_ID" => "68",    // Код информационного блока
						"NEWS_COUNT" => "2",    // Количество новостей на странице
						"SORT_BY1" => "ACTIVE_FROM",    // Поле для первой сортировки новостей
						"SORT_ORDER1" => "DESC",    // Направление для первой сортировки новостей
						"SORT_BY2" => "SORT",    // Поле для второй сортировки новостей
						"SORT_ORDER2" => "ASC",    // Направление для второй сортировки новостей
						"FILTER_NAME" => "arrFilter",    // Фильтр
						"FIELD_CODE" => array(    // Поля
							0 => "ID",
							1 => "",
						),
						"PROPERTY_CODE" => array(    // Свойства
							0 => "BONUS",
							1 => "ORDER_ID",
							2 => "USER_ID",
							3 => "SUMM",
							4 => "TYPE",
							5 => "",
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
						"INCLUDE_SUBSECTIONS" => "N",    // Показывать элементы подразделов раздела
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
						"PAGER_SHOW_ALL" => "N",    // Показывать ссылку "Все"
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

					<? ?>
                    <div class="order-view-side__button">
                        <a href="/personal/order/?COPY_ORDER=Y&ID=<?= $arResult["ACCOUNT_NUMBER"] ?>"
                           class="button button_primary">Повторить заказ</a>
                        <?if($arResult["PAYED"] == "N"){?><a href="/personal/order/?ID=<?= $arResult["ACCOUNT_NUMBER"] ?>&CANCEL=Y"
                           class="button button_outline">Отменить заказ</a><?}?>
                    </div>
                </div>
            </div>
        </div>
    </section>

	<?php


	/*
	?>
	<div class="container-fluid sale-order-detail">
		<div class="sale-order-detail-title-container">
			<h1 class="sale-order-detail-title-element">
				<?= Loc::getMessage('SPOD_LIST_MY_ORDER', array(
					'#ACCOUNT_NUMBER#' => htmlspecialcharsbx($arResult["ACCOUNT_NUMBER"]),
					'#DATE_ORDER_CREATE#' => $arResult["DATE_INSERT_FORMATED"]
				)) ?>
			</h1>
		</div>
		<?
		if ($arParams['GUEST_MODE'] !== 'Y') {
			?>
			<a class="sale-order-detail-back-to-list-link-up"
			   href="<?= htmlspecialcharsbx($arResult["URL_TO_LIST"]) ?>">
				&larr; <?= Loc::getMessage('SPOD_RETURN_LIST_ORDERS') ?>
			</a>
			<?
		}
		?>
		<div class="col-md-12 col-sm-12 col-xs-12 sale-order-detail-general">
			<div class="row">
				<div class="col-md-12 cols-sm-12 col-xs-12 sale-order-detail-general-head">
					<span class="sale-order-detail-general-item">
						<?= Loc::getMessage('SPOD_SUB_ORDER_TITLE', array(
							"#ACCOUNT_NUMBER#" => htmlspecialcharsbx($arResult["ACCOUNT_NUMBER"]),
							"#DATE_ORDER_CREATE#" => $arResult["DATE_INSERT_FORMATED"]
						)) ?>
						<?= count($arResult['BASKET']); ?>
						<?
						$count = count($arResult['BASKET']) % 10;
						if ($count == '1') {
							echo Loc::getMessage('SPOD_TPL_GOOD');
						} elseif ($count >= '2' && $count <= '4') {
							echo Loc::getMessage('SPOD_TPL_TWO_GOODS');
						} else {
							echo Loc::getMessage('SPOD_TPL_GOODS');
						}
						?>
						<?= Loc::getMessage('SPOD_TPL_SUMOF') ?>
						<?= $arResult["PRICE_FORMATED"] ?>
					</span>
				</div>
			</div>

			<div class="row sale-order-detail-about-order">

				<div class="col-md-12 col-sm-12 col-xs-12 sale-order-detail-about-order-container">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12 sale-order-detail-about-order-title">
							<h3 class="sale-order-detail-about-order-title-element">
								<?= Loc::getMessage('SPOD_LIST_ORDER_INFO') ?>
							</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12 sale-order-detail-about-order-inner-container">
							<div class="row">
								<div class="col-md-4 col-sm-6 sale-order-detail-about-order-inner-container-name">
									<div class="sale-order-detail-about-order-inner-container-name-title">
										<?
										$userName = $arResult["USER_NAME"];
										if (strlen($userName) || strlen($arResult['FIO'])) {
											echo Loc::getMessage('SPOD_LIST_FIO') . ':';
										} else {
											echo Loc::getMessage('SPOD_LOGIN') . ':';
										}
										?>
									</div>
									<div class="sale-order-detail-about-order-inner-container-name-detail">
										<?
										if (strlen($userName)) {
											echo htmlspecialcharsbx($userName);
										} elseif (strlen($arResult['FIO'])) {
											echo htmlspecialcharsbx($arResult['FIO']);
										} else {
											echo htmlspecialcharsbx($arResult["USER"]['LOGIN']);
										}
										?>
									</div>
									<a class="sale-order-detail-about-order-inner-container-name-read-less">
										<?= Loc::getMessage('SPOD_LIST_LESS') ?>
									</a>
									<a class="sale-order-detail-about-order-inner-container-name-read-more">
										<?= Loc::getMessage('SPOD_LIST_MORE') ?>
									</a>
								</div>

								<div class="col-md-4 col-sm-6 sale-order-detail-about-order-inner-container-status">
									<div class="sale-order-detail-about-order-inner-container-status-title">
										<?= Loc::getMessage('SPOD_LIST_CURRENT_STATUS_DATE', array(
											'#DATE_STATUS#' => $arResult["DATE_STATUS_FORMATED"]
										)) ?>
									</div>
									<div class="sale-order-detail-about-order-inner-container-status-detail">
										<?
										if ($arResult['CANCELED'] !== 'Y') {
											echo htmlspecialcharsbx($arResult["STATUS"]["NAME"]);
										} else {
											echo Loc::getMessage('SPOD_ORDER_CANCELED');
										}
										?>
									</div>
								</div>

								<div class="col-md-<?= ($arParams['GUEST_MODE'] !== 'Y') ? 2 : 4 ?> col-sm-6 sale-order-detail-about-order-inner-container-price">
									<div class="sale-order-detail-about-order-inner-container-price-title">
										<?= Loc::getMessage('SPOD_ORDER_PRICE') ?>:
									</div>
									<div class="sale-order-detail-about-order-inner-container-price-detail">
										<?= $arResult["PRICE_FORMATED"] ?>
									</div>
								</div>
								<?
								if ($arParams['GUEST_MODE'] !== 'Y') {
									?>
									<div class="col-md-2 col-sm-6 sale-order-detail-about-order-inner-container-repeat">
										<a href="<?= $arResult["URL_TO_COPY"] ?>"
										   class="sale-order-detail-about-order-inner-container-repeat-button">
											<?= Loc::getMessage('SPOD_ORDER_REPEAT') ?>
										</a>
										<?
										if ($arResult["CAN_CANCEL"] === "Y") {
											?>
											<a href="<?= $arResult["URL_TO_CANCEL"] ?>"
											   class="sale-order-detail-about-order-inner-container-repeat-cancel">
												<?= Loc::getMessage('SPOD_ORDER_CANCEL') ?>
											</a>
											<?
										}
										?>
									</div>
									<?
								}
								?>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12 sale-order-detail-about-order-inner-container-details">
								<h4 class="sale-order-detail-about-order-inner-container-details-title">
									<?= Loc::getMessage('SPOD_USER_INFORMATION') ?>
								</h4>
								<ul class="sale-order-detail-about-order-inner-container-details-list">
									<?
									if (strlen($arResult["USER"]["LOGIN"]) && !in_array("LOGIN", $arParams['HIDE_USER_INFO'])) {
										?>
										<li class="sale-order-detail-about-order-inner-container-list-item">
											<?= Loc::getMessage('SPOD_LOGIN') ?>:
											<div class="sale-order-detail-about-order-inner-container-list-item-element">
												<?= htmlspecialcharsbx($arResult["USER"]["LOGIN"]) ?>
											</div>
										</li>
										<?
									}
									if (strlen($arResult["USER"]["EMAIL"]) && !in_array("EMAIL", $arParams['HIDE_USER_INFO'])) {
										?>
										<li class="sale-order-detail-about-order-inner-container-list-item">
											<?= Loc::getMessage('SPOD_EMAIL') ?>:
											<a class="sale-order-detail-about-order-inner-container-list-item-link"
											   href="mailto:<?= htmlspecialcharsbx($arResult["USER"]["EMAIL"]) ?>"><?= htmlspecialcharsbx($arResult["USER"]["EMAIL"]) ?></a>
										</li>
										<?
									}
									if (strlen($arResult["USER"]["PERSON_TYPE_NAME"]) && !in_array("PERSON_TYPE_NAME", $arParams['HIDE_USER_INFO'])) {
										?>
										<li class="sale-order-detail-about-order-inner-container-list-item">
											<?= Loc::getMessage('SPOD_PERSON_TYPE_NAME') ?>:
											<div class="sale-order-detail-about-order-inner-container-list-item-element">
												<?= htmlspecialcharsbx($arResult["USER"]["PERSON_TYPE_NAME"]) ?>
											</div>
										</li>
										<?
									}
									if (isset($arResult["ORDER_PROPS"])) {

										global $USER;

										foreach ($arResult["ORDER_PROPS"] as $property) {
											?>
											<li class="sale-order-detail-about-order-inner-container-list-item">
												<?= htmlspecialcharsbx($property['NAME']) ?>:
												<div class="sale-order-detail-about-order-inner-container-list-item-element">
													<?
													if ($property["TYPE"] == "Y/N") {
														echo Loc::getMessage('SPOD_' . ($property["VALUE"] == "Y" ? 'YES' : 'NO'));
													} else {
														if ($property['MULTIPLE'] == 'Y'
															&& $property['TYPE'] !== 'FILE'
															&& $property['TYPE'] !== 'LOCATION') {
															$propertyList = unserialize($property["VALUE"]);
															if ($property['CODE'] === 'LIST_LINKS') {
																$counter = 1;
																foreach ($propertyList as $productId => $link) {
																	$link = (Application::getInstance()->getContext()->getRequest()->isHttps() ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . '/' . trim($link, '/');
																	?>
																	<div style="margin: 5px 0 7px">
																		<div><?= $counter ?>. <a href="<?= $link ?>"
																								 target="_blank"><?= $arResult['PRODUCTS'][$productId]['NAME'] ?></a>
																		</div>
																		<div style="font-weight: normal;color: #999;margin-top: 6px;font-size: 13px;padding: 0 15px;"><?= $link ?></div>
																	</div>
																	<?
																	$counter++;
																}
															} else {
																foreach ($propertyList as $propertyElement) {
																	echo $propertyElement . '</br>';
																}
															}
														} elseif ($property['TYPE'] == 'FILE') {
															echo $property["VALUE"];
														} else {
															echo htmlspecialcharsbx($property["VALUE"]);
														}
													}
													?>
												</div>
											</li>
											<?
										}
									}
									?>
								</ul>
								<?
								if (strlen($arResult["USER_DESCRIPTION"])) {
									?>
									<h4 class="sale-order-detail-about-order-inner-container-details-title sale-order-detail-about-order-inner-container-comments">
										<?= Loc::getMessage('SPOD_ORDER_DESC') ?>
									</h4>
									<div class="col-xs-12 sale-order-detail-about-order-inner-container-list-item-element">
										<?= nl2br(htmlspecialcharsbx($arResult["USER_DESCRIPTION"])) ?>
									</div>
									<?
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row sale-order-detail-payment-options">

				<div class="col-md-12 col-sm-12 col-xs-12 sale-order-detail-payment-options-container">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12 sale-order-detail-payment-options-title">
							<h3 class="sale-order-detail-payment-options-title-element">
								<?= Loc::getMessage('SPOD_ORDER_PAYMENT') ?>
							</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12 sale-order-detail-payment-options-inner-container">
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12 sale-order-detail-payment-options-info">
									<div class="row">
										<div class="col-md-1 col-sm-2 col-xs-2 sale-order-detail-payment-options-info-image"></div>
										<div class="col-md-11 col-sm-10 col-xs-10 sale-order-detail-payment-options-info-container">
											<div class="sale-order-detail-payment-options-info-order-number">
												<?= Loc::getMessage('SPOD_SUB_ORDER_TITLE', array(
													"#ACCOUNT_NUMBER#" => htmlspecialcharsbx($arResult["ACCOUNT_NUMBER"]),
													"#DATE_ORDER_CREATE#" => $arResult["DATE_INSERT_FORMATED"]
												)) ?>
												<?
												if ($arResult['CANCELED'] !== 'Y') {
													echo htmlspecialcharsbx($arResult["STATUS"]["NAME"]);
												} else {
													echo Loc::getMessage('SPOD_ORDER_CANCELED');
												}
												?>
											</div>
											<div class="sale-order-detail-payment-options-info-total-price">
												<?= Loc::getMessage('SPOD_ORDER_PRICE_FULL') ?>:
												<span><?= $arResult["PRICE_FORMATED"] ?></span>
											</div>
											<?
											if (!empty($arResult["SUM_REST"]) && !empty($arResult["SUM_PAID"])) {
												?>
												<div class="sale-order-detail-payment-options-info-total-price">
													<?= Loc::getMessage('SPOD_ORDER_SUM_PAID') ?>:
													<span><?= $arResult["SUM_PAID_FORMATED"] ?></span>
												</div>
												<div class="sale-order-detail-payment-options-info-total-price">
													<?= Loc::getMessage('SPOD_ORDER_SUM_REST') ?>:
													<span><?= $arResult["SUM_REST_FORMATED"] ?></span>
												</div>
												<?
											}
											?>
										</div>
									</div>
								</div><!--sale-order-detail-payment-options-info-->
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12 sale-order-detail-payment-options-methods-container">
									<?
									foreach ($arResult['PAYMENT'] as $payment) {
										?>
										<div class="row payment-options-methods-row">
											<div class="col-md-12 col-sm-12 col-xs-12 sale-order-detail-payment-options-methods">
												<div class="row sale-order-detail-payment-options-methods-information-block">
													<div class="col-md-2 col-sm-5 col-xs-12 sale-order-detail-payment-options-methods-image-container">
													<span class="sale-order-detail-payment-options-methods-image-element"
														  style="background-image: url('<?= strlen($payment['PAY_SYSTEM']["SRC_LOGOTIP"]) ? htmlspecialcharsbx($payment['PAY_SYSTEM']["SRC_LOGOTIP"]) : '/bitrix/images/sale/nopaysystem.gif' ?>');"></span>
													</div>
													<div class="col-md-8 col-sm-7 col-xs-10 sale-order-detail-payment-options-methods-info">
														<div class="sale-order-detail-payment-options-methods-info-title">
															<div class="sale-order-detail-methods-title">
																<?
																$paymentData[$payment['ACCOUNT_NUMBER']] = array(
																	"payment" => $payment['ACCOUNT_NUMBER'],
																	"order" => $arResult['ACCOUNT_NUMBER'],
																	"allow_inner" => $arParams['ALLOW_INNER'],
																	"only_inner_full" => $arParams['ONLY_INNER_FULL'],
																	"refresh_prices" => $arParams['REFRESH_PRICES'],
																	"path_to_payment" => $arParams['PATH_TO_PAYMENT']
																);
																$paymentSubTitle = Loc::getMessage('SPOD_TPL_BILL') . " " . Loc::getMessage('SPOD_NUM_SIGN') . $payment['ACCOUNT_NUMBER'];
																if (isset($payment['DATE_BILL'])) {
																	$paymentSubTitle .= " " . Loc::getMessage('SPOD_FROM') . " " . $payment['DATE_BILL']->format($arParams['ACTIVE_DATE_FORMAT']);
																}
																$paymentSubTitle .= ",";
																echo htmlspecialcharsbx($paymentSubTitle);
																?>
																<span class="sale-order-list-payment-title-element"><?= $payment['PAY_SYSTEM_NAME'] ?></span>
																<?
																if ($payment['PAID'] === 'Y') {
																	?>
																	<span class="sale-order-detail-payment-options-methods-info-title-status-success">
																	<?= Loc::getMessage('SPOD_PAYMENT_PAID') ?></span>
																	<?
																} elseif ($arResult['IS_ALLOW_PAY'] == 'N') {
																	?>
																	<span class="sale-order-detail-payment-options-methods-info-title-status-restricted">
																	<?= Loc::getMessage('SPOD_TPL_RESTRICTED_PAID') ?></span>
																	<?
																} else {
																	?>
																	<span class="sale-order-detail-payment-options-methods-info-title-status-alert">
																	<?= Loc::getMessage('SPOD_PAYMENT_UNPAID') ?></span>
																	<?
																}
																?>
															</div>
														</div>
														<div class="sale-order-detail-payment-options-methods-info-total-price">
															<span class="sale-order-detail-sum-name"><?= Loc::getMessage('SPOD_ORDER_PRICE_BILL') ?>:</span>
															<span class="sale-order-detail-sum-number"><?= $payment['PRICE_FORMATED'] ?></span>
														</div>
														<?
														if (!empty($payment['CHECK_DATA'])) {
															$listCheckLinks = "";
															foreach ($payment['CHECK_DATA'] as $checkInfo) {
																$title = Loc::getMessage('SPOD_CHECK_NUM', array('#CHECK_NUMBER#' => $checkInfo['ID'])) . " - " . htmlspecialcharsbx($checkInfo['TYPE_NAME']);
																if (strlen($checkInfo['LINK']) > 0) {
																	$link = $checkInfo['LINK'];
																	$listCheckLinks .= "<div><a href='$link' target='_blank'>$title</a></div>";
																}
															}
															if (strlen($listCheckLinks) > 0) {
																?>
																<div class="sale-order-detail-payment-options-methods-info-total-check">
																	<div class="sale-order-detail-sum-check-left"><?= Loc::getMessage('SPOD_CHECK_TITLE') ?>
																		:
																	</div>
																	<div class="sale-order-detail-sum-check-left">
																		<?= $listCheckLinks ?>
																	</div>
																</div>
																<?
															}
														}
														if (
															$payment['PAID'] !== 'Y'
															&& $arResult['CANCELED'] !== 'Y'
															&& $arParams['GUEST_MODE'] !== 'Y'
															&& $arResult['LOCK_CHANGE_PAYSYSTEM'] !== 'Y'
														) {
															?>
															<a href="#" id="<?= $payment['ACCOUNT_NUMBER'] ?>"
															   class="sale-order-detail-payment-options-methods-info-change-link"><?= Loc::getMessage('SPOD_CHANGE_PAYMENT_TYPE') ?></a>
															<?
														}
														?>
														<?
														if ($arResult['IS_ALLOW_PAY'] === 'N' && $payment['PAID'] !== 'Y') {
															?>
															<div class="sale-order-detail-status-restricted-message-block">
																<span class="sale-order-detail-status-restricted-message"><?= Loc::getMessage('SOPD_TPL_RESTRICTED_PAID_MESSAGE') ?></span>
															</div>
															<?
														}
														?>
													</div>
													<?
													if ($payment['PAY_SYSTEM']['IS_CASH'] !== 'Y' && $payment['PAY_SYSTEM']['ACTION_FILE'] !== 'cash') {
														?>
														<div class="col-md-2 col-sm-12 col-xs-12 sale-order-detail-payment-options-methods-button-container">
															<?
															if ($payment['PAY_SYSTEM']['PSA_NEW_WINDOW'] === 'Y' && $arResult["IS_ALLOW_PAY"] !== "N") {
																?>
																<a class="btn-theme sale-order-detail-payment-options-methods-button-element-new-window"
																   target="_blank"
																   href="<?= htmlspecialcharsbx($payment['PAY_SYSTEM']['PSA_ACTION_FILE']) ?>">
																	<?= Loc::getMessage('SPOD_ORDER_PAY') ?>
																</a>
																<?
															} else {
																if ($payment["PAID"] === "Y" || $arResult["CANCELED"] === "Y" || $arResult["IS_ALLOW_PAY"] === "N") {
																	?>
																	<a class="btn-theme sale-order-detail-payment-options-methods-button-element inactive-button"><?= Loc::getMessage('SPOD_ORDER_PAY') ?></a>
																	<?
																} else {
																	?>
																	<a class="btn-theme sale-order-detail-payment-options-methods-button-element active-button"><?= Loc::getMessage('SPOD_ORDER_PAY') ?></a>
																	<?
																}
															}
															?>
														</div>
														<?
													}
													?>
													<div class="sale-order-detail-payment-inner-row-template col-md-offset-3 col-sm-offset-5 col-md-5 col-sm-10 col-xs-12">
														<a class="sale-order-list-cancel-payment">
															<i class="fa fa-long-arrow-left"></i> <?= Loc::getMessage('SPOD_CANCEL_PAYMENT') ?>
														</a>
													</div>
												</div>
												<?
												if ($payment["PAID"] !== "Y"
													&& $payment['PAY_SYSTEM']["IS_CASH"] !== "Y"
													&& $payment['PAY_SYSTEM']['ACTION_FILE'] !== 'cash'
													&& $payment['PAY_SYSTEM']['PSA_NEW_WINDOW'] !== 'Y'
													&& $arResult['CANCELED'] !== 'Y'
													&& $arResult["IS_ALLOW_PAY"] !== "N") {
													?>
													<div class="row sale-order-detail-payment-options-methods-template col-md-12 col-sm-12 col-xs-12">
														<span class="sale-paysystem-close active-button">
															<span class="sale-paysystem-close-item sale-order-payment-cancel"></span>
															<!--sale-paysystem-close-item-->
														</span><!--sale-paysystem-close-->
														<?= $payment['BUFFERED_OUTPUT'] ?>
														<!--<a class="sale-order-payment-cancel">-->
														<?//= Loc::getMessage('SPOD_CANCEL_PAY')
														?><!--</a>-->
													</div>
													<?
												}
												?>
											</div>
										</div>
										<?
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?
			if (count($arResult['SHIPMENT'])) {
				?>
				<div class="row sale-order-detail-payment-options">
					<div class="col-md-12 col-sm-12 col-xs-12 sale-order-detail-payment-options-container">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12 sale-order-detail-payment-options-title">
								<h3 class="sale-order-detail-payment-options-title-element">
									<?= Loc::getMessage('SPOD_ORDER_SHIPMENT') ?>
								</h3>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12 sale-order-detail-payment-options-inner-container">
								<?
								foreach ($arResult['SHIPMENT'] as $shipment) {
									?>
									<div class="row">
										<div class="col-md-12 col-md-12 col-sm-12 sale-order-detail-payment-options-shipment-container">
											<div class="row">
												<div class="col-md-12 col-md-12 col-sm-12 sale-order-detail-payment-options-shipment">
													<div>
														<div class="col-md-3 col-sm-5 sale-order-detail-payment-options-shipment-image-container">
															<?
															if (strlen($shipment['DELIVERY']["SRC_LOGOTIP"])) {
																?>
																<span class="sale-order-detail-payment-options-shipment-image-element"
																	  style="background-image: url('<?= htmlspecialcharsbx($shipment['DELIVERY']["SRC_LOGOTIP"]) ?>')"></span>
																<?
															}
															?>
														</div>
														<div class="col-md-7 col-sm-7 sale-order-detail-payment-options-methods-shipment-list">
															<div class="sale-order-detail-payment-options-methods-shipment-list-item-title">
																<?
																//change date
																if (!strlen($shipment['PRICE_DELIVERY_FORMATED'])) {
																	$shipment['PRICE_DELIVERY_FORMATED'] = 0;
																}
																$shipmentRow = Loc::getMessage('SPOD_SUB_ORDER_SHIPMENT') . " " . Loc::getMessage('SPOD_NUM_SIGN') . $shipment["ACCOUNT_NUMBER"];
																if ($shipment["DATE_DEDUCTED"]) {
																	$shipmentRow .= " " . Loc::getMessage('SPOD_FROM') . " " . $shipment["DATE_DEDUCTED"]->format($arParams['ACTIVE_DATE_FORMAT']);
																}
																$shipmentRow = htmlspecialcharsbx($shipmentRow);
																$shipmentRow .= ", " . Loc::getMessage('SPOD_SUB_PRICE_DELIVERY', array(
																		'#PRICE_DELIVERY#' => $shipment['PRICE_DELIVERY_FORMATED']
																	));
																echo $shipmentRow;
																?>
															</div>
															<?
															if (strlen($shipment["DELIVERY_NAME"])) {
																?>
																<div class="sale-order-detail-payment-options-methods-shipment-list-item">
																	<?= Loc::getMessage('SPOD_ORDER_DELIVERY') ?>
																	: <?= htmlspecialcharsbx($shipment["DELIVERY_NAME"]) ?>
																</div>
																<?
															}
															?>
															<div class="sale-order-detail-payment-options-methods-shipment-list-item">
																<?= Loc::getMessage('SPOD_ORDER_SHIPMENT_STATUS') ?>:
																<?= htmlspecialcharsbx($shipment['STATUS_NAME']) ?>
															</div>
															<?
															if (strlen($shipment['TRACKING_NUMBER'])) {
																?>
																<div class="sale-order-detail-payment-options-methods-shipment-list-item">
																	<span class="sale-order-list-shipment-id-name"><?= Loc::getMessage('SPOD_ORDER_TRACKING_NUMBER') ?>:</span>
																	<span class="sale-order-detail-shipment-id"><?= htmlspecialcharsbx($shipment['TRACKING_NUMBER']) ?></span>
																	<span class="sale-order-detail-shipment-id-icon"></span>
																</div>
																<?
															}
															?>
															<div class="sale-order-detail-payment-options-methods-shipment-list-item-link">
																<a class="sale-order-detail-show-link"><?= Loc::getMessage('SPOD_LIST_SHOW_ALL') ?></a>
																<a class="sale-order-detail-hide-link"><?= Loc::getMessage('SPOD_LIST_LESS') ?></a>
															</div>
														</div>
														<?
														if (strlen($shipment['TRACKING_URL'])) {
															?>
															<div class="col-md-2 col-sm-12 sale-order-detail-payment-options-shipment-button-container">
																<a class="sale-order-detail-payment-options-shipment-button-element"
																   href="<?= $shipment['TRACKING_URL'] ?>">
																	<?= Loc::getMessage('SPOD_ORDER_CHECK_TRACKING') ?>
																</a>
															</div>
															<?
														}
														?>
													</div><!--row-->
													<div class="col-md-9 col-md-offset-3 col-sm-12 sale-order-detail-payment-options-shipment-composition-map">
														<?
														$store = $arResult['DELIVERY']['STORE_LIST'][$shipment['STORE_ID']];
														if (isset($store)) {
															?>
															<div class="row">
																<div class="col-md-12 col-sm-12 sale-order-detail-map-container">
																	<div class="row">
																		<h4 class="sale-order-detail-payment-options-shipment-composition-map-title">
																			<?= Loc::getMessage('SPOD_SHIPMENT_STORE') ?>
																		</h4>
																		<?
																		$APPLICATION->IncludeComponent(
																			"bitrix:map.yandex.view",
																			"",
																			array(
																				"INIT_MAP_TYPE" => "COORDINATES",
																				"MAP_DATA" => serialize(
																					array(
																						'yandex_lon' => $store['GPS_S'],
																						'yandex_lat' => $store['GPS_N'],
																						'PLACEMARKS' => array(
																							array(
																								"LON" => $store['GPS_S'],
																								"LAT" => $store['GPS_N'],
																								"TEXT" => htmlspecialcharsbx($store['TITLE'])
																							)
																						)
																					)
																				),
																				"MAP_WIDTH" => "100%",
																				"MAP_HEIGHT" => "300",
																				"CONTROLS" => array("ZOOM", "SMALLZOOM", "SCALELINE"),
																				"OPTIONS" => array(
																					"ENABLE_DRAGGING",
																					"ENABLE_SCROLL_ZOOM",
																					"ENABLE_DBLCLICK_ZOOM"
																				),
																				"MAP_ID" => ""
																			)
																		);
																		?>
																	</div>
																</div>
															</div>
															<?
															if (strlen($store['ADDRESS'])) {
																?>
																<div class="row">
																	<div class="col-md-12 col-sm-12 sale-order-detail-payment-options-shipment-map-address">
																		<div class="row">
																<span class="col-md-2 sale-order-detail-payment-options-shipment-map-address-title">
																	<?= Loc::getMessage('SPOD_STORE_ADDRESS') ?>:</span>
																			<span class="col-md-10 sale-order-detail-payment-options-shipment-map-address-element">
																	<?= htmlspecialcharsbx($store['ADDRESS']) ?></span>
																		</div>
																	</div>
																</div>
																<?
															}
														}
														?>
														<div class="row">
															<div class="col-md-12 col-sm-12 sale-order-detail-payment-options-shipment-composition-container">
																<div class="row">
																	<div class="col-md-12 col-sm-12 col-xs-12 sale-order-detail-payment-options-shipment-composition-title">
																		<h3 class="sale-order-detail-payment-options-shipment-composition-title-element"><?= Loc::getMessage('SPOD_ORDER_SHIPMENT_BASKET') ?></h3>
																	</div>
																</div>
																<div class="row">
																	<div class="sale-order-detail-order-section bx-active">
																		<div class="sale-order-detail-order-section-content container-fluid">
																			<div class="sale-order-detail-order-table-fade sale-order-detail-order-table-fade-right">
																				<div style="width: 100%; overflow-x: auto; overflow-y: hidden;">
																					<div class="sale-order-detail-order-item-table">
																						<div class="sale-order-detail-order-item-tr hidden-sm hidden-xs">
																							<div class="sale-order-detail-order-item-td"
																								 style="padding-bottom: 5px;">
																								<div class="sale-order-detail-order-item-td-title">
																									<?= Loc::getMessage('SPOD_NAME') ?>
																								</div>
																							</div>
																							<div class="sale-order-detail-order-item-nth-4p1"></div>
																							<div class="sale-order-detail-order-item-td sale-order-detail-order-item-properties bx-text-right"
																								 style="padding-bottom: 5px;">
																								<div class="sale-order-detail-order-item-td-title">
																									<?= Loc::getMessage('SPOD_QUANTITY') ?>
																								</div>
																							</div>
																						</div>
																						<?
																						foreach ($shipment['ITEMS'] as $item) {
																							$basketItem = $arResult['BASKET'][$item['BASKET_ID']];
																							?>
																							<div class="sale-order-detail-order-item-tr sale-order-detail-order-basket-info sale-order-detail-order-item-tr-first">
																								<div class="sale-order-detail-order-item-td"
																									 style="min-width: 300px;">
																									<div class="sale-order-detail-order-item-block">
																										<div class="sale-order-detail-order-item-img-block">
																											<a href="<?= htmlspecialcharsbx($basketItem['DETAIL_PAGE_URL']) ?>">
																												<?
																												if (strlen($basketItem['PICTURE']['SRC'])) {
																													$imageSrc = htmlspecialcharsbx($basketItem['PICTURE']['SRC']);
																												} else {
																													$imageSrc = $this->GetFolder() . '/images/no_photo.png';
																												}
																												?>
																												<div class="sale-order-detail-order-item-imgcontainer"
																													 style="background-image: url(<?= $imageSrc ?>);
																															 background-image:
																															 -webkit-image-set(url(<?= $imageSrc ?>) 1x,
																															 url(<?= $imageSrc ?>) 2x)">
																												</div>
																											</a>
																										</div>
																										<div class="sale-order-detail-order-item-content">
																											<div class="sale-order-detail-order-item-title">
																												<a href="<?= htmlspecialcharsbx($basketItem['DETAIL_PAGE_URL']) ?>"><?= htmlspecialcharsbx($basketItem['NAME']) ?></a>
																											</div>
																											<?
																											if (isset($basketItem['PROPS']) && is_array($basketItem['PROPS'])) {
																												foreach ($basketItem['PROPS'] as $itemProps) {
																													?>
																													<div class="sale-order-detail-order-item-color">
																												<span class="sale-order-detail-order-item-color-name">
																													<?= htmlspecialcharsbx($itemProps['NAME']) ?>:</span>
																														<span class="sale-order-detail-order-item-color-type"><?= htmlspecialcharsbx($itemProps['VALUE']) ?></span>
																													</div>
																													<?
																												}
																											}
																											?>
																										</div>
																									</div>
																								</div>
																								<div class="sale-order-detail-order-item-nth-4p1"></div>
																								<div class="sale-order-detail-order-item-td sale-order-detail-order-item-properties bx-text-right">
																									<div class="sale-order-detail-order-item-td-title col-xs-7  col-sm-7 col-dm-7 visible-xs visible-sm">
																										<?= Loc::getMessage('SPOD_QUANTITY') ?>
																									</div>
																									<div class="sale-order-detail-order-item-td-text">
																										<span><?= $item['QUANTITY'] ?>&nbsp;<?= htmlspecialcharsbx($item['MEASURE_NAME']) ?></span>
																									</div>
																								</div>
																							</div>
																							<?
																						}
																						?>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<?
								}
								?>
							</div>
						</div>
					</div>
				</div>
				<?
			}
			?>


			<div class="row sale-order-detail-payment-options-order-content">

				<div class="col-md-12 col-sm-12 col-xs-12 sale-order-detail-payment-options-order-content-container">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12 sale-order-detail-payment-options-order-content-title">
							<h3 class="sale-order-detail-payment-options-order-content-title-element">
								<?= Loc::getMessage('SPOD_ORDER_BASKET') ?>
							</h3>
						</div>
						<div class="sale-order-detail-order-section bx-active">
							<div class="sale-order-detail-order-section-content container-fluid">
								<div class="sale-order-detail-order-table-fade sale-order-detail-order-table-fade-right">
									<div style="width: 100%; overflow-x: auto; overflow-y: hidden;">
										<div class="sale-order-detail-order-item-table">
											<div class="sale-order-detail-order-item-tr hidden-sm hidden-xs">
												<div class="sale-order-detail-order-item-td"
													 style="padding-bottom: 5px;">
													<div class="sale-order-detail-order-item-td-title">
														<?= Loc::getMessage('SPOD_NAME') ?>
													</div>
												</div>
												<div class="sale-order-detail-order-item-td sale-order-detail-order-item-properties bx-text-right"
													 style="padding-bottom: 5px;">
													<div class="sale-order-detail-order-item-td-title">
														<?= Loc::getMessage('SPOD_PRICE') ?>
													</div>
												</div>
												<?
												if (strlen($arResult["SHOW_DISCOUNT_TAB"])) {
													?>
													<div class="sale-order-detail-order-item-td sale-order-detail-order-item-properties bx-text-right"
														 style="padding-bottom: 5px;">
														<div class="sale-order-detail-order-item-td-title">
															<?= Loc::getMessage('SPOD_DISCOUNT') ?>
														</div>
													</div>
													<?
												}
												?>
												<div class="sale-order-detail-order-item-nth-4p1"></div>
												<div class="sale-order-detail-order-item-td sale-order-detail-order-item-properties bx-text-right"
													 style="padding-bottom: 5px;">
													<div class="sale-order-detail-order-item-td-title">
														<?= Loc::getMessage('SPOD_QUANTITY') ?>
													</div>
												</div>
												<div class="sale-order-detail-order-item-td sale-order-detail-order-item-properties bx-text-right"
													 style="padding-bottom: 5px;">
													<div class="sale-order-detail-order-item-td-title">
														<?= Loc::getMessage('SPOD_ORDER_PRICE') ?>
													</div>
												</div>
											</div>
											<?
											foreach ($arResult['BASKET'] as $basketItem) {
												?>
												<div class="sale-order-detail-order-item-tr sale-order-detail-order-basket-info sale-order-detail-order-item-tr-first">
													<div class="sale-order-detail-order-item-td"
														 style="min-width: 300px;">
														<div class="sale-order-detail-order-item-block">
															<div class="sale-order-detail-order-item-img-block">
																<a href="<?= $basketItem['DETAIL_PAGE_URL'] ?>">
																	<?
																	if (strlen($basketItem['PICTURE']['SRC'])) {
																		$imageSrc = $basketItem['PICTURE']['SRC'];
																	} else {
																		$imageSrc = $this->GetFolder() . '/images/no_photo.png';
																	}
																	?>
																	<div class="sale-order-detail-order-item-imgcontainer"
																		 style="background-image: url(<?= $imageSrc ?>);
																				 background-image: -webkit-image-set(url(<?= $imageSrc ?>) 1x,
																				 url(<?= $imageSrc ?>) 2x)">
																	</div>
																</a>
															</div>
															<div class="sale-order-detail-order-item-content">
																<div class="sale-order-detail-order-item-title">
																	<a href="<?= $basketItem['DETAIL_PAGE_URL'] ?>">
																		<?= htmlspecialcharsbx($basketItem['NAME']) ?>
																	</a>
																</div>
																<?
																if (isset($basketItem['PROPS']) && is_array($basketItem['PROPS'])) {
																	foreach ($basketItem['PROPS'] as $itemProps) {
																		?>
																		<div class="sale-order-detail-order-item-color">
																		<span class="sale-order-detail-order-item-color-name">
																			<?= htmlspecialcharsbx($itemProps['NAME']) ?>:</span>
																			<span class="sale-order-detail-order-item-color-type">
																			<?= htmlspecialcharsbx($itemProps['VALUE']) ?></span>
																		</div>
																		<?
																	}
																}
																?>
															</div>
														</div>
													</div>
													<div class="sale-order-detail-order-item-td sale-order-detail-order-item-properties bx-text-right">
														<div class="sale-order-detail-order-item-td-title col-xs-7 col-sm-5 visible-xs visible-sm">
															<?= Loc::getMessage('SPOD_PRICE') ?>
														</div>
														<div class="sale-order-detail-order-item-td-text">
															<strong class="bx-price"><?= $basketItem['BASE_PRICE_FORMATED'] ?></strong>
														</div>
													</div>
													<?
													if (strlen($basketItem["DISCOUNT_PRICE_PERCENT_FORMATED"])) {
														?>
														<div class="sale-order-detail-order-item-td sale-order-detail-order-item-properties bx-text-right">
															<div class="sale-order-detail-order-item-td-title col-xs-7 col-sm-5 visible-xs visible-sm">
																<?= Loc::getMessage('SPOD_DISCOUNT') ?>
															</div>
															<div class="sale-order-detail-order-item-td-text">
																<strong class="bx-price"><?= $basketItem['DISCOUNT_PRICE_PERCENT_FORMATED'] ?></strong>
															</div>
														</div>
														<?
													} elseif (strlen($arResult["SHOW_DISCOUNT_TAB"])) {
														?>
														<div class="sale-order-detail-order-item-td sale-order-detail-order-item-properties bx-text-right">
															<div class="sale-order-detail-order-item-td-title col-xs-7 col-sm-5 visible-xs visible-sm">
																<?= Loc::getMessage('SPOD_DISCOUNT') ?>
															</div>
															<div class="sale-order-detail-order-item-td-text">
																<strong class="bx-price"></strong>
															</div>
														</div>
														<?
													}
													?>
													<div class="sale-order-detail-order-item-nth-4p1"></div>
													<div class="sale-order-detail-order-item-td sale-order-detail-order-item-properties bx-text-right">
														<div class="sale-order-detail-order-item-td-title col-xs-7 col-sm-5 visible-xs visible-sm">
															<?= Loc::getMessage('SPOD_QUANTITY') ?>
														</div>
														<div class="sale-order-detail-order-item-td-text">
														<span><?= $basketItem['QUANTITY'] ?>&nbsp;
															<?
															if (strlen($basketItem['MEASURE_NAME'])) {
																echo htmlspecialcharsbx($basketItem['MEASURE_NAME']);
															} else {
																echo Loc::getMessage('SPOD_DEFAULT_MEASURE');
															}
															?></span>
														</div>
													</div>
													<div class="sale-order-detail-order-item-td sale-order-detail-order-item-properties bx-text-right">
														<div class="sale-order-detail-order-item-td-title col-xs-7 col-sm-5 visible-xs visible-sm"><?= Loc::getMessage('SPOD_ORDER_PRICE') ?></div>
														<div class="sale-order-detail-order-item-td-text">
															<strong class="bx-price all"><?= $basketItem['FORMATED_SUM'] ?></strong>
														</div>
													</div>
												</div>
												<?
											}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row sale-order-detail-total-payment">
				<div class="col-md-7 col-md-offset-5 col-sm-12 col-xs-12 sale-order-detail-total-payment-container">
					<div class="row">
						<ul class="col-md-8 col-sm-6 col-xs-6 sale-order-detail-total-payment-list-left">
							<?
							if (floatval($arResult["ORDER_WEIGHT"])) {
								?>
								<li class="sale-order-detail-total-payment-list-left-item">
									<?= Loc::getMessage('SPOD_TOTAL_WEIGHT') ?>:
								</li>
								<?
							}

							if ($arResult['PRODUCT_SUM_FORMATED'] != $arResult['PRICE_FORMATED'] && !empty($arResult['PRODUCT_SUM_FORMATED'])) {
								?>
								<li class="sale-order-detail-total-payment-list-left-item">
									<?= Loc::getMessage('SPOD_COMMON_SUM') ?>:
								</li>
								<?
							}

							if (strlen($arResult["PRICE_DELIVERY_FORMATED"])) {
								?>
								<li class="sale-order-detail-total-payment-list-left-item">
									<?= Loc::getMessage('SPOD_DELIVERY') ?>:
								</li>
								<?
							}

							if ((float)$arResult["TAX_VALUE"] > 0) {
								?>
								<li class="sale-order-detail-total-payment-list-left-item">
									<?= Loc::getMessage('SPOD_TAX') ?>:
								</li>
								<?
							}
							?>
							<li class="sale-order-detail-total-payment-list-left-item"><?= Loc::getMessage('SPOD_SUMMARY') ?>
								:
							</li>
						</ul>
						<ul class="col-md-4 col-sm-6 col-xs-6 sale-order-detail-total-payment-list-right">
							<?
							if (floatval($arResult["ORDER_WEIGHT"])) {
								?>
								<li class="sale-order-detail-total-payment-list-right-item"><?= $arResult['ORDER_WEIGHT_FORMATED'] ?></li>
								<?
							}

							if ($arResult['PRODUCT_SUM_FORMATED'] != $arResult['PRICE_FORMATED'] && !empty($arResult['PRODUCT_SUM_FORMATED'])) {
								?>
								<li class="sale-order-detail-total-payment-list-right-item"><?= $arResult['PRODUCT_SUM_FORMATED'] ?></li>
								<?
							}

							if (strlen($arResult["PRICE_DELIVERY_FORMATED"])) {
								?>
								<li class="sale-order-detail-total-payment-list-right-item"><?= $arResult["PRICE_DELIVERY_FORMATED"] ?></li>
								<?
							}

							if ((float)$arResult["TAX_VALUE"] > 0) {
								?>
								<li class="sale-order-detail-total-payment-list-right-item"><?= $arResult["TAX_VALUE_FORMATED"] ?></li>
								<?
							}
							?>
							<li class="sale-order-detail-total-payment-list-right-item"><?= $arResult['PRICE_FORMATED'] ?></li>
						</ul>
					</div>
				</div>
			</div>
		</div><!--sale-order-detail-general-->
		<?
		if ($arParams['GUEST_MODE'] !== 'Y' && $arResult['LOCK_CHANGE_PAYSYSTEM'] !== 'Y') {
			?>
			<a class="sale-order-detail-back-to-list-link-down"
			   href="<?= $arResult["URL_TO_LIST"] ?>">&larr; <?= Loc::getMessage('SPOD_RETURN_LIST_ORDERS') ?></a>
			<?
		}
		?>
	</div>
	<?
	*/


	$javascriptParams = array(
		"url" => CUtil::JSEscape($this->__component->GetPath() . '/ajax.php'),
		"templateFolder" => CUtil::JSEscape($templateFolder),
		"templateName" => $this->__component->GetTemplateName(),
		"paymentList" => $paymentData
	);
	$javascriptParams = CUtil::PhpToJSObject($javascriptParams);
	?>
    <script>
        BX.Sale.PersonalOrderComponent.PersonalOrderDetail.init(<?=$javascriptParams?>);
    </script>
	<?
}
?>

