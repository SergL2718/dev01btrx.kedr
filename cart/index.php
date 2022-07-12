<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

    $APPLICATION->SetTitle('Корзина');
    $APPLICATION->SetPageProperty('title', 'Корзина');
    $APPLICATION->SetPageProperty('description', '');
    $APPLICATION->SetPageProperty('keywords', '');
    $APPLICATION->SetPageProperty('robots', 'noindex,nofollow');

    use Bitrix\Sale;
    use Bitrix\Catalog\ProductTable;
    use Native\App\Catalog\Product;
    use Native\App\Sale\Store;
    $arResult = Array();
    $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
    $price = $basket->getPrice();
    if ($price) {
        $basketItems = $basket->getBasketItems();
        $POINTS_SUM = 0;

        $PRICE_FULL = $basket->getBasePrice();
        $PRICE_SUM = $basket->getPrice();

        //Выборка всех активных складов:
        $rsStore = \Bitrix\Catalog\StoreTable::getList(array(
            'filter' => array('ACTIVE'>='Y'),
        ));

        while($arStore=$rsStore->fetch()){
            $arResult['STORES'][$arStore['ID']]=$arStore;
        }
        //echo "<pre>"; print_r($arResult); echo "</pre>";
        ?>

        <form id="create_order">
            <input type="hidden" name="DELIVERY_ADDRESS" value="">
            <section class="order">
                <div class="container">
                    <div class="page-title">ОФОРМЛЕНИЕ ЗАКАЗА</div>
                    <div class="order-container">
                        <div class="order-content">
                            <? if ($USER->IsAuthorized()) {
                                ?>
                                <div class="order-enter"><span class="link" data-modal="modal-enter"><b>Войдите на сайт</b></span>, чтобы воспользоваться бонусной программой</div>
                                <?
                            } ?>
                            <div class="order-content-list">
                                <div class="order-content-list__title">Товары</div>
                                <div class="order-content-list__container">
                                    <? foreach ($basket as $basketItem) {
                                        $PRODUCT_ID = $basketItem->getProductId();
                                        $res = CIBlockElement::GetByID($PRODUCT_ID);
                                        if ($ar_res = $res->GetNext()) {


                                            //echo "<pre>"; print_r($basketItem); echo "</pre>";
                                            ?>
                                            <div class="order-card">
                                                <div class="order-card__remove">
                                                    <div class="icon icon-close" data-id="<?echo $basketItem->getId();;?>"></div>
                                                </div>
                                                <div class="order-card__image">
                                                    <?if($ar_res["PREVIEW_PICTURE"]){?>
                                                        <img src="<?echo CFile::GetPath($ar_res["PREVIEW_PICTURE"]);?>" alt="">
                                                    <?}?>
                                                </div>
                                                <div class="order-card__info"><a class="order-card__info-name"
                                                                                 href="<? echo $basketItem->getField('DETAIL_PAGE_URL'); ?>"><? echo $basketItem->getField('NAME'); ?></a>
                                                    <div class="order-card__info-stat">
                                                        <!--<span>250 мл</span>-->
                                                        <div class="bonus-line">
                                                            +<? echo $POINTS = Product::getNumberBonuses($PRODUCT_ID)*$basketItem->getQuantity(); ?> зкр на счёт
                                                            <div class="icon icon-pine-cone"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="order-card__quantity">
                                                    <div class="order-card__label">Количество</div>
                                                    <div class="block-quantity">
                                                        <div class="block-quantity__minus icon icon-minus"></div>
                                                        <div class="block-quantity__num" data-id="<?echo $basketItem->getId();;?>"><? echo $basketItem->getQuantity(); ?></div>
                                                        <div class="block-quantity__plus icon icon-plus"></div>
                                                    </div>
                                                </div>
                                                <div class="order-card__price">
                                                    <div class="order-card__label">Цена</div>
                                                    <? if ($basketItem->getPrice() != $basketItem->getField('BASE_PRICE')) {
                                                        ?>
                                                        <div class="order-card__price-old"><? echo $basketItem->getField('BASE_PRICE'); ?>
                                                            руб.
                                                        </div>
                                                        <?
                                                    } ?>
                                                    <div class="order-card__price-std"><? echo $basketItem->getPrice(); ?>
                                                        руб.
                                                    </div>
                                                </div>
                                            </div>
                                            <?
                                            $POINTS_SUM += $POINTS;
                                        }
                                    } ?>
                                </div>
                            </div>
                            <div class="order-delivery">
                                <div class="order-delivery-country">
                                    <div class="custom-select">
                                        <label class="custom-select__label">ДОСТАВКА В</label>
                                        <select id="COUNTRY_NAME">
                                            <option value="ru">россию</option>
                                            <option value="bl">беларусь</option>
                                            <option value="kz">казахстан</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="order-delivery-city">
                                    <div class="input">
                                        <label>ГОРОД, РЕГИОН<i>*</i>
                                        </label>
                                        <input placeholder="Москва" name="LOCATION" list="LOCATION_LIST" value=""/>
                                        <!--<datalist id="LOCATION_LIST"></datalist>-->
                                    </div>
                                </div>
                                <div class="order-delivery-type">
                                    <label class="radio" for="delivery-1">
                                        <input class="radio__input" type="radio" id="delivery-1" name="DELIVERY"
                                               value="133"/>
                                        <div class="radio__container">
                                            <div class="radio__icon"></div>
                                            <div class="radio__label">Самовывоз<br> из&nbsp;магазина</div>
                                        </div>
                                    </label>
                                    <label class="radio" for="delivery-2">
                                        <input class="radio__input" type="radio" id="delivery-2" name="DELIVERY"
                                               checked="checked"
                                               value="148"/>
                                        <div class="radio__container">
                                            <div class="radio__icon"></div>
                                            <div class="radio__label">Курьерская<br> доставка</div>
                                        </div>
                                    </label>
                                    <label class="radio" for="delivery-3">
                                        <input class="radio__input" type="radio" id="delivery-3" name="DELIVERY"
                                               value="150"/>
                                        <div class="radio__container">
                                            <div class="radio__icon"></div>
                                            <div class="radio__label">Пункты выдачи<br> курьерских служб</div>
                                        </div>
                                    </label>
                                </div>
                                <div class="order-delivery-address">
                                    <div class="order-delivery-address__card" data-delivery="delivery-1">
                                        <div class="block-title">АДРЕС МАГАЗИНА</div>
                                        <div class="shop-map">
                                            <script src="https://api-maps.yandex.ru/2.1/?apikey=f4c6d400-38da-4793-b567-0ee1388f6703&amp;lang=ru_RU"></script>
                                            <script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@latest/dist/js/jquery.suggestions.min.js"></script>
                                            <script>
												let points = [
                                                        <?foreach($arResult['STORES'] as $STORE){?>{
														id: 1,
														name: '<?echo $STORE["TITLE"];?>\n',
														address: '<?echo $STORE["ADDRESS"];?>',
														time: '<?echo $STORE["SCHEDULE"];?>',
														price: '<?echo $STORE["DESCRIPTION"];?>',
														coords: [<?echo $STORE["GPS_N"];?>, <?echo $STORE["GPS_S"];?>]
													},<?}?>
												];
                                            </script>
                                            <div class="shop-map__map">
                                                <div id="map"></div>
                                            </div>
                                            <div class="shop-map__list"></div>
                                        </div>
                                    </div>
                                    <div class="order-delivery-address__card active" data-delivery="delivery-2">
                                        <div class="block-title">УКАЖИТЕ АДРЕС</div>
                                        <div class="order-delivery-address__list">
                                            <div class="order-delivery-address__form">
                                                <div class="input">
                                                    <label>ИНДЕКС<i>*</i>
                                                    </label>
                                                    <input placeholder="" name="ZIP" value="" id="postal_code"/>
                                                </div>
                                            </div>
                                            <div class="order-delivery-address__form">
                                                <div class="input">
                                                    <label>УЛИЦА<i>*</i>
                                                    </label>
                                                    <input placeholder="" name="STREET" value="" id="street"/>
                                                </div>
                                            </div>
                                            <div class="order-delivery-address__form">
                                                <div class="input">
                                                    <label>ДОМ<i>*</i>
                                                    </label>
                                                    <input placeholder="" name="HOUSE" value="" id="house"/>
                                                </div>
                                            </div>
                                            <div class="order-delivery-address__form">
                                                <div class="input">
                                                    <label>КВАРТИРА (ОПЦИОНАЛЬНО)<i>*</i>
                                                    </label>
                                                    <input placeholder="" name="APARTMENT" value="" id="flat"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="order-delivery-address__card" data-delivery="delivery-3">
                                        <div class="block-title">Укажите удобный способ самовывоза товара</div>
                                    </div>
                                </div>
                                <div class="order-delivery-client">
                                    <div class="page-title">ПОЛУЧАТЕЛЬ</div>
                                    <div class="order-delivery-client__list">
                                        <div class="order-delivery-client__form">
                                            <div class="input">
                                                <label>ТЕЛЕФОН
                                                </label>
                                                <input placeholder="" name="PHONE" value=""/>
                                            </div>
                                        </div>
                                        <div class="order-delivery-client__form">
                                            <div class="input">
                                                <label>ЭЛ. ПОЧТА
                                                </label>
                                                <input placeholder="" name="EMAIL" value=""/>
                                            </div>
                                        </div>
                                        <div class="order-delivery-client__form">
                                            <div class="input">
                                                <label>ФАМИЛИЯ
                                                </label>
                                                <input placeholder="" name="FIRST_NAME" value=""/>
                                            </div>
                                        </div>
                                        <div class="order-delivery-client__form">
                                            <div class="input">
                                                <label>ИМЯ
                                                </label>
                                                <input placeholder="" name="NAME" value=""/>
                                            </div>
                                        </div>
                                        <div class="order-delivery-client__form">
                                            <div class="input">
                                                <label>ОТЧЕСТВО
                                                </label>
                                                <input placeholder="" name="SECOND_NAME" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="order-delivery-legal">
                                        <label class="checkbox">
                                            <input class="checkbox__input" type="checkbox"/><span class="checkbox__label">Я хочу оформить заказ как юридическое лицо</span>
                                        </label>
                                        <div class="order-delivery-legal__container">
                                            <div class="order-delivery-legal__container-inner">
                                                <div class="input">
                                                    <label>НАИМЕНОВАНИЕ ОРГАНИЗАЦИИ
                                                    </label>
                                                    <input placeholder="" name="COMPANY_NAME" value=""/>
                                                </div>
                                                <div class="input">
                                                    <label>АДРЕС
                                                    </label>
                                                    <input placeholder="" name="COMPANY_ADR" value=""/>
                                                </div>
                                                <div class="input">
                                                    <label>ИНН
                                                    </label>
                                                    <input placeholder="" name="INN" value=""/>
                                                </div>
                                                <div class="input">
                                                    <label>КПП
                                                    </label>
                                                    <input placeholder="" name="KPP" value=""/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="order-delivery-commentary">
                                        <div class="textarea">
                                            <label>Комментарий к заказу (ОПЦИОНАЛЬНо)</label>
                                            <textarea name="COMMENTS"></textarea>
                                        </div>
                                    </div>
                                    <div class="order-delivery-personal">
                                        <label class="checkbox">
                                            <input class="checkbox__input" type="checkbox" checked="checked"/><span
                                                class="checkbox__label">Нажимая Далее вы соглашаетесь на обработку персональных данных</span>
                                        </label>
                                    </div>
                                    <div class="order-delivery-spam">
                                        <label class="checkbox">
                                            <input class="checkbox__input" type="checkbox"/><span class="checkbox__label">Я хочу подписаться на рассылку, чтобы получать рекомендации диетолога и&nbsp;информацию об акциях</span>
                                        </label>
                                    </div>
                                    <div class="order-delivery-pay">
                                        <div class="block-title">оПЛАТА</div>
                                        <div class="order-delivery-pay__list">
                                            <label class="radio">
                                                <input class="radio__input" type="radio" name="PAY_SYSTEM" value="27">
                                                <span class="radio__container">
                                                    <span class="radio__icon"></span>
                                                    <span class="radio__label">
                                                        <span class="sberpay"></span>
                                                    </span>
                                                </span>
                                            </label>
                                            <div class="order-delivery-pay__label">Оплатить<br/> на сайте</div>
                                            <label class="radio">
                                                <input class="radio__input" type="radio" name="PAY_SYSTEM" value="9">
                                                <span class="radio__container">
                                                    <span class="radio__icon"></span>
                                                    <span class="radio__label">
                                                        <span class="order-delivery-pay__check">
                                                            <span class="order-delivery-pay__check-text">
                                                                <span>Запросить<br/> счёт</span><i>?</i>
                                                            </span>
                                                            <span class="order-delivery-pay__check-tip">Мы пришлем вам письмо со счетом на
                                                                оплату
                                                                по реквизитам в банке. Обычно этот способ выбирают юридические лица.<br/>
                                                                Отгрузим ваш заказ после поступления оплаты, это может занять от 1 до 3
                                                                рабочих
                                                                дней.
                                                            </span>
                                                        </span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="order-total">
                            <div class="order-total__move"></div>
                            <div class="order-total__title"><span>ИТОГО</span><span class="order-total__title_value"><?echo $PRICE_SUM;?> руб.</span></div>
                            <div class="order-total__content">
                                <div class="order-total__content-inner">
                                    <div class="order-total__row price__sum"><span>Товары</span><b><?echo $PRICE_SUM;?> руб.</b></div>
                                    <div class="order-total__row price__delivery"><span>Доставка</span><b>0 руб.</b></div>
                                    <?if($PRICE_FULL != $PRICE_SUM){?>
                                        <div class="order-total__row price__sale"><span>Скидка</span><b>-<?echo ($PRICE_FULL - $PRICE_SUM)?> руб.</b></div>
                                    <?}?>
                                </div>
                            </div>
                            <div class="button button_white">Оформить заказ</div>
                            <div class="order-total__bonus">
                                <div class="order-total__bonus-inner">Начислим <b><?=$POINTS_SUM?> зкр</b> <span
                                        class="icon icon-pine-cone"></span></div>
                            </div>
                            <div class="order-promo">
                                <div class="order-promo__title">ЕСТЬ ПРОМОКОД?</div>
                                <div class="order-promo__content">
                                    <input placeholder="Введите промокод" name="PROMO_CODE">
                                    <button class="button">ОК</button>
                                </div>
                                <div class="order-promo__error" style="display: none">Неверный или неактуальный промокод</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
        <style>
            .suggestions-suggestions svg{
                opacity: 0 !important;
                display: none !important;
                width: 0px !important;
                height: 0px !important;
            }
        </style>
        <?
    } ?>

<?php
    /*$APPLICATION->IncludeComponent(
    "bitrix:sale.basket.basket",
    "native",
    array(
        "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
        "COLUMNS_LIST" => array(
            0 => "NAME",
            1 => "DISCOUNT",
            2 => "PROPS",
            3 => "DELETE",
            4 => "DELAY",
            5 => "PRICE",
            6 => "QUANTITY",
            7 => "SUM",
        ),
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "PATH_TO_ORDER" => SITE_DIR."personal/order/create/",
        "HIDE_COUPON" => "N",
        "QUANTITY_FLOAT" => "N",
        "PRICE_VAT_SHOW_VALUE" => "N",
        "SET_TITLE" => "Y",
        "AJAX_OPTION_ADDITIONAL" => "",
        "OFFERS_PROPS" => "",
        "COMPONENT_TEMPLATE" => "native",
        "USE_PREPAYMENT" => "N",
        "ACTION_VARIABLE" => "action",
        "COLUMNS_LIST_EXT" => array(
            0 => "DISCOUNT",
            1 => "DELETE",
            2 => "DELAY",
            3 => "SUM",
        ),
        "CORRECT_RATIO" => "Y",
        "AUTO_CALCULATION" => "Y",
        "COMPATIBLE_MODE" => "Y",
        "GIFTS_TEXT_LABEL_GIFT" => "",
        "GIFTS_PRODUCT_PROPS_VARIABLE" => "",
        "GIFTS_SHOW_OLD_PRICE" => "N",
        "GIFTS_SHOW_DISCOUNT_PERCENT" => "N",
        "GIFTS_SHOW_NAME" => "N",
        "GIFTS_SHOW_IMAGE" => "N",
        "GIFTS_CONVERT_CURRENCY" => "N",
        "ADDITIONAL_PICT_PROP_37" => "-",
        "ADDITIONAL_PICT_PROP_38" => "-",
        "ADDITIONAL_PICT_PROP_39" => "-",
        "ADDITIONAL_PICT_PROP_40" => "-",
        "ADDITIONAL_PICT_PROP_41" => "-",
        "ADDITIONAL_PICT_PROP_42" => "-",
        "ADDITIONAL_PICT_PROP_51" => "-",
        "ADDITIONAL_PICT_PROP_52" => "-",
        "BASKET_IMAGES_SCALING" => "adaptive",
        "USE_GIFTS" => "Y",
        "GIFTS_PLACE" => "TOP",
        "GIFTS_BLOCK_TITLE" => "Выберите подарок",
        "GIFTS_HIDE_BLOCK_TITLE" => "N",
        "GIFTS_PRODUCT_QUANTITY_VARIABLE" => "quantity",
        "GIFTS_MESS_BTN_BUY" => "Выбрать",
        "GIFTS_MESS_BTN_DETAIL" => "Подробнее",
        "GIFTS_PAGE_ELEMENT_COUNT" => "8",
        "GIFTS_HIDE_NOT_AVAILABLE" => "N",
        "TEMPLATE_THEME" => "blue",
        "USE_ENHANCED_ECOMMERCE" => "N",
        "DEFERRED_REFRESH" => "N",
        "USE_DYNAMIC_SCROLL" => "Y",
        "SHOW_FILTER" => "Y",
        "SHOW_RESTORE" => "Y",
        "COLUMNS_LIST_MOBILE" => array(
            0 => "DISCOUNT",
            1 => "SUM",
        ),
        "TOTAL_BLOCK_DISPLAY" => array(
            0 => "top",
        ),
        "DISPLAY_MODE" => "extended",
        "PRICE_DISPLAY_MODE" => "Y",
        "SHOW_DISCOUNT_PERCENT" => "Y",
        "DISCOUNT_PERCENT_POSITION" => "bottom-right",
        "PRODUCT_BLOCKS_ORDER" => "props,sku,columns",
        "USE_PRICE_ANIMATION" => "Y",
        "LABEL_PROP" => "",
        "LABEL_PROP_MOBILE" => "",
        "LABEL_PROP_POSITION" => "",
        "ADDITIONAL_PICT_PROP_57" => "-",
        "ADDITIONAL_PICT_PROP_58" => "-"
    ),
    false
    );*/
    require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
