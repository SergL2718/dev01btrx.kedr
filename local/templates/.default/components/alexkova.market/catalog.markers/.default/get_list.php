<?
/*
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @updated 01.09.2020, 19:08
 * @copyright 2011-2020
 */

/**
 * @var $APPLICATION
 * @var $component
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

define("BX_AJAX_PARAM_ID", "ID, IBLOCK_ID");

if (is_array($_SESSION["BXR_MARKERS_SETTINGS"])
    && !empty($_SESSION["BXR_MARKERS_SETTINGS"])):

    if (isset($_REQUEST["ID"])) {

        $arFilter = array();

        switch (strval($_REQUEST["ID"])) {
            case 'NEWPRODUCT':
                $arFilter = array("!PROPERTY_NEWPRODUCT" => false);
                break;
            case 'RECOMMENDED':
                $arFilter = array("!PROPERTY_RECOMMENDED" => false);
                break;
            case 'SPECIALOFFER':
                $arFilter = array("!PROPERTY_SPECIALOFFER" => false);
                break;
            case 'SALELEADER':
                $arFilter = array("!PROPERTY_SALELEADER" => false);
                break;
        }

        if (is_array($arFilter) && !empty($arFilter)) {
            if ($arParams['FILTER_NAME']) {
                $GLOBALS[$arParams['FILTER_NAME']] = $arFilter;
                $GLOBALS[$arParams['FILTER_NAME']]['PRODUCT_DISPLAY_MODE'] = 'Y';
                // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/10803/
                $GLOBALS[$arParams['FILTER_NAME']]['PROPERTY_HIDDEN'] = false;
                // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/21639/
                $GLOBALS[$arParams['FILTER_NAME']]['PROPERTY_DUPLICATED'] = false;
            }
            $APPLICATION->IncludeComponent(
                "bitrix:catalog.section",
                "",
                $arParams,
                $component,
                array('HIDE_ICONS' => 'Y')
            );
            ?>

            <script>
                const CatalogMarkersComponent = {

                    nodes: {
                        buttons: {
                            addToBasket: document.querySelectorAll('button.bxr-basket-add')
                        }
                    },

                    checkedProducts: {}, // Складываем товары, которые уже были проверены через ajax

                    run: function () {
                        //console.log(CatalogMarkersComponent.nodes.buttons);
                        this.init.buttons();

                        BX.Vue.create({
                            el: '#mark-panel-<?= $_REQUEST["ID"] ?>'
                        });
                    },

                    init: {
                        buttons: function () {
                            if (CatalogMarkersComponent.nodes.buttons.length === 0) return;

                            for (let code in CatalogMarkersComponent.nodes.buttons) {

                                let buttons = CatalogMarkersComponent.nodes.buttons[code];

                                if (buttons.length === 0) continue;

                                for (let i = 0; i < buttons.length; i++) {
                                    let button = buttons[i];
                                    button.addEventListener('click', CatalogMarkersComponent.action.button[code], false);
                                }
                            }
                        }
                    },

                    action: {
                        button: {
                            addToBasket: function (event) {
                                let _self = this;
                                let productId = _self.parentNode.querySelector('input[data-item]').dataset.item;
                                if (!productId) return;

                                // Проверим, имеется ли товар в уже проверенных товарах

                                if (CatalogMarkersComponent.checkedProducts.hasOwnProperty(productId)) {
                                    let product = CatalogMarkersComponent.checkedProducts[productId];

                                    if (product.canAdd === false) {
                                        event.preventDefault();
                                        event.stopPropagation();

                                        if (product.message) {
                                            BX.UI.Notification.Center.notify({
                                                content: product.message,
                                                autoHideDelay: 600000
                                            });
                                        }
                                    }

                                } else {
                                    // Останавливаем стандартные действия
                                    event.preventDefault();
                                    event.stopPropagation();

                                    // Проверим возможность добавления товара в корзину
                                    BX.ajax.runComponentAction(
                                        'native:order.create',
                                        'checkAbilityAddToBasket',
                                        {
                                            mode: 'class',
                                            data: {
                                                productId: productId
                                            }
                                        })
                                        .then(function (response) {
                                            response = response.data;
                                            CatalogMarkersComponent.checkedProducts[productId] = response;
                                            if (response.canAdd === false) {
                                                if (response.message) {
                                                    BX.UI.Notification.Center.notify({
                                                        content: response.message,
                                                        autoHideDelay: 600000
                                                    });
                                                }

                                            } else {
                                                _self.click();
                                            }
                                        });
                                }
                            }
                        }
                    }
                };

                CatalogMarkersComponent.run();
            </script>
            <?
        }
    }

endif;
