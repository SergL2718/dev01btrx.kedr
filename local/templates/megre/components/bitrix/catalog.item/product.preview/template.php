<?php

/*
 * Изменено: 29 декабря 2021, среда
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */


//if ($USER->IsAdmin()){echo "<pre>"; print_r($arResult['ITEM']); echo "</pre>";}
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
$this->setFrameMode(true);
if (empty($arResult['ITEM'])) {
	return;
} else {
	$item = $arResult['ITEM'];
	$productTitle = isset($item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != '' ? $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $item['NAME'];
	$imgTitle = isset($item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != '' ? $item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] : $item['NAME'];
	$haveOffers = !empty($item['OFFERS']);
	//$arResult['AREA_ID'] = "card_".$item['ID'];
    ?>
<?if(!$arParams["ONLY_JS"]){?>
    <article id="<?= $arResult['AREA_ID'] ?>" data-id="<?= $item['ID'] ?>" class="product-preview-wrapper" data-page="1">
        <a href="<?= $item['DETAIL_PAGE_URL'] ?>">
            <div class="product-image">
				<?php if ($item['PREVIEW_PICTURE']['SRC']): ?>
					<?php if ($arParams['LAZY_LOAD'] === 'N'): ?>
                        <img src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>"
                             alt="<?= $item['NAME'] ?>"
                             title="<?= $imgTitle ?>">
					<?php else: ?>
                        <img src="<?= SITE_TEMPLATE_PATH ?>/images/placeholder/placeholder-300x300.gif" alt="">
                        <img class="owl-lazy"
                             src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>"
                             data-src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>"
                             alt="<?= $item['NAME'] ?>"
                             title="<?= $imgTitle ?>">
					<?php endif ?>
				<?php else: ?>
                    <img src="<?= SITE_TEMPLATE_PATH ?>/images/placeholder/placeholder-300x300.gif" alt="">
				<?php endif ?>
            </div>
            <div class="product-title">
				<?= $productTitle ?>
            </div>
            <div class="product-price-wrapper">
				<?php if ($haveOffers && $item['ITEM_PRICES']['PRINT_RATIO_PRICE']): ?>
                    <div class="product-current-price-from">от</div>
				<?php endif ?>
                <div class="product-current-price"><?echo ($item['ITEM_PRICES']['PRINT_RATIO_PRICE'])?$item['ITEM_PRICES']['PRINT_RATIO_PRICE']:CurrencyFormat($item['PROPERTIES']['MINIMUM_PRICE']['VALUE'], "RUB"); ?></div>
				<?php if ($item['ITEM_PRICES']['BASE_PRICE'] !== $item['ITEM_PRICES']['PRICE']): ?>
                    <div class="product-old-price"><?= $item['ITEM_PRICES']['PRINT_RATIO_BASE_PRICE'] ?></div>
				<?php endif ?>
            </div>
            <div class="product-bonuses">
                <?if($item['PROPERTIES']['NUMBER_BONUSES']['VALUE'] > 0){?>
                +<?= $item['PROPERTIES']['NUMBER_BONUSES']['VALUE'] > 0 ? $item['PROPERTIES']['NUMBER_BONUSES']['VALUE'] : 0 ?>
                зкр на счёт<i
                        class="pine-cone"></i>
                <?}?>
            </div>
        </a>
        <div class="product-button-wrapper">
			<?php if ($haveOffers): ?>
                <a class="product-button" href="<?= $item['DETAIL_PAGE_URL'] ?>"><?= $arParams['MESS_BTN_DETAIL'] ?></a>
			<?php else: ?>
				<?php if ($item['CATALOG_CAN_BUY_ZERO'] === 'Y' || $item['CATALOG_QUANTITY'] > 0): ?>
                    <a class="product-button"
                       data-controller="addToBasket"
                       href="javascript:void(0)"><?= $arParams['MESS_BTN_ADD_TO_BASKET'] ?></a>
				<?php else: ?>
                    <a class="product-button-not-available"
                       href="javascript:void(0)"><?= $arParams['MESS_NOT_AVAILABLE'] ?></a>
				<?php endif ?>
			<?php endif ?>
        </div>
		<?php if (!$haveOffers): ?>
            <div class="product-added-wrapper">
                <div>Добавлено</div>
                <div>
                    <a data-controller="changeQuantity"
                       data-direction="reduce"
                       href="javascript:void(0)"><i class="fas fa-angle-left"></i></a>
                    <span>1</span>
                    <a data-controller="changeQuantity"
                       data-direction="increase"
                       href="javascript:void(0)"><i class="fas fa-angle-right"></i></a>
                </div>
            </div>
		<?php endif ?>
        <div class="product-add-to-favorites-button-wrapper">
            <a data-controller="addToFavorites"
               href="javascript:void(0)"><i class="heart"></i><i class="heart-filled"></i></a>
        </div>
		<?php if (!empty($item['BADGES']) || $item["FAMILY"]): ?>
            <div class="product-badge-list-wrapper">
				<?php foreach ($item['BADGES'] as $badge): ?>
                    <div class="product-badge" data-code="<?= $badge['CODE'] ?>"><?= $badge['TITLE'] ?></div>
				<?php endforeach ?>
                <?if($arResult["FAMILY"]){?>
                    <div class="product-family"><?echo $arResult["FAMILY"];?></div>
                <?}?>
            </div>
		<?php endif ?>

    </article>
    <?}?>
    <script>
        if (!window.hasOwnProperty('ProductsStorage') || typeof window.ProductsStorage === 'undefined') {
            window.ProductsStorage = {}
        }
        if (!window.ProductsStorage.hasOwnProperty('<?= $item['ID'] ?>')) {
            window.ProductsStorage['<?= $item['ID'] ?>'] = document.querySelectorAll('.product-preview-wrapper[data-id="' + <?= $item['ID'] ?> + '"]')
        }
        if (typeof product === 'undefined') {
            let product
        }
        product = document.getElementById('<?= $arResult['AREA_ID'] ?>')
        if (FAVORITES.hasOwnProperty('<?= $item['ID'] ?>')) {
            product.setAttribute('data-in-favorites', 'Y')
        }
        product.querySelector('[data-controller="addToFavorites"]').onclick = function () {
            const action = FAVORITES.hasOwnProperty('<?= $item['ID'] ?>') ? 'deleteFromFavorites' : 'addToFavorites'
            BX.ajax.post(
                SITE_TEMPLATE_PATH + '/ajax/product.php',
                {
                    action: action,
                    ID: <?= $item['ID'] ?>
                },
                function (response) {
                    response = JSON.parse(response)
                    if (response.status !== 'success') {
                        return
                    }

                    if (action === 'addToFavorites') {
                        if (window.ProductsStorage.hasOwnProperty('<?= $item['ID'] ?>')) {
                            for (let i = 0; i < window.ProductsStorage['<?= $item['ID'] ?>'].length; i++) {
                                window.ProductsStorage['<?= $item['ID'] ?>'][i].setAttribute('data-in-favorites', 'Y')
                            }
                        }
                        FAVORITES[<?= $item['ID'] ?>] = {
                            ID: <?= $item['ID'] ?>,
                            NAME: '<?= $item['NAME'] ?>',
                            PREVIEW_PICTURE: {
                                SRC: '<?= $item['PREVIEW_PICTURE']['SRC'] ?>'
                            },
                            TYPE: '<?= $item['PRODUCT']['TYPE'] ?>',
                            QUANTITY: '<?= $item['CATALOG_QUANTITY'] ?>',
                            PRINT_RATIO_PRICE: '<?= $item['ITEM_PRICES']['PRINT_RATIO_PRICE'] ?>',
                            DETAIL_PAGE_URL: '<?= $item['DETAIL_PAGE_URL'] ?>',
                        }
                    } else {
                        if (window.ProductsStorage.hasOwnProperty('<?= $item['ID'] ?>')) {
                            for (let i = 0; i < window.ProductsStorage['<?= $item['ID'] ?>'].length; i++) {
                                window.ProductsStorage['<?= $item['ID'] ?>'][i].removeAttribute('data-in-favorites')
                            }
                        }
                        delete FAVORITES[<?= $item['ID'] ?>]
                    }
                    UserBar.favorites.render()
                }
            )
        }
    </script>
	<?php if (!$haveOffers): ?>
        <script>
            if (BASKET.hasOwnProperty('<?= $item['ID'] ?>')) {
                product.setAttribute('data-in-basket', 'Y')
                product.querySelector('.product-added-wrapper span').innerText = BASKET[<?= $item['ID'] ?>]['QUANTITY']
            }
            if (typeof buttonAddToBasket === 'undefined') {
                let buttonAddToBasket
            }
            buttonAddToBasket = product.querySelector('[data-controller="addToBasket"]')
            if (buttonAddToBasket) {
                buttonAddToBasket.onclick = function () {
                    const button = this
                    button.innerHTML = '<span class="product-button-loader-wrapper"><span class="loader-dots-wrapper"><span class="loader-dots"><span></span><span></span><span></span></span></span></span>'
                    BX.ajax.post(
                        SITE_TEMPLATE_PATH + '/ajax/product.php',
                        {
                            action: 'addToBasket',
                            ID: <?= $item['ID'] ?>
                        },
                        function (response) {
                            response = JSON.parse(response)
                            if (response.status !== 'success') {
                                if (response.hasOwnProperty('error')) {
                                    for (let i = 0, l = response.error.length; i < l; i++) {
                                        BX.UI.Notification.Center.notify({
                                            content: response.error[i],
                                            autoHideDelay: 2000
                                        })
                                    }
                                }
                                setTimeout(function () {
                                    button.innerText = '<?= $arParams['MESS_BTN_ADD_TO_BASKET'] ?>'
                                }, 50)
                                return
                            }
                            BASKET[<?= $item['ID'] ?>] = {
                                ITEM_ID: response['itemId'],
                                ID: <?= $item['ID'] ?>,
                                QUANTITY: 1,
                            }
                            if (window.ProductsStorage.hasOwnProperty('<?= $item['ID'] ?>')) {
                                for (let i = 0; i < window.ProductsStorage['<?= $item['ID'] ?>'].length; i++) {
                                    window.ProductsStorage['<?= $item['ID'] ?>'][i].setAttribute('data-in-basket', 'Y')
                                }
                            }
                            button.innerText = '<?= $arParams['MESS_BTN_ADD_TO_BASKET'] ?>'
                            UserBar.basket.render()
                        }
                    )
                }
            }
            product.querySelector('[data-controller="changeQuantity"][data-direction="increase"]').onclick = function () {
                BX.ajax.post(
                    SITE_TEMPLATE_PATH + '/ajax/product.php',
                    {
                        action: 'addToBasket',
                        ID: <?= $item['ID'] ?>
                    },
                    function (response) {
                        response = JSON.parse(response)
                        if (response.status !== 'success') {
                            if (response.hasOwnProperty('error')) {
                                for (let i = 0, l = response.error.length; i < l; i++) {
                                    BX.UI.Notification.Center.notify({content: response.error[i], autoHideDelay: 2000})
                                }
                            }
                            return
                        }
                        BASKET[<?= $item['ID'] ?>]['QUANTITY']++
                        if (window.ProductsStorage.hasOwnProperty('<?= $item['ID'] ?>')) {
                            for (let i = 0; i < window.ProductsStorage['<?= $item['ID'] ?>'].length; i++) {
                                window.ProductsStorage['<?= $item['ID'] ?>'][i].querySelector('.product-added-wrapper span').innerText = BASKET[<?= $item['ID'] ?>]['QUANTITY']
                            }
                        }
                    }
                )
            }
            product.querySelector('[data-controller="changeQuantity"][data-direction="reduce"]').onclick = function () {
                BX.ajax.post(
                    SITE_TEMPLATE_PATH + '/ajax/product.php',
                    {
                        action: 'deleteFromBasket',
                        ID: <?= $item['ID'] ?>
                    },
                    function (response) {
                        response = JSON.parse(response)
                        if (response.status !== 'success') {
                            return
                        }
                        BASKET[<?= $item['ID'] ?>]['QUANTITY']--
                        if (BASKET[<?= $item['ID'] ?>]['QUANTITY'] > 0) {
                            if (window.ProductsStorage.hasOwnProperty('<?= $item['ID'] ?>')) {
                                for (let i = 0; i < window.ProductsStorage['<?= $item['ID'] ?>'].length; i++) {
                                    window.ProductsStorage['<?= $item['ID'] ?>'][i].querySelector('.product-added-wrapper span').innerText = BASKET[<?= $item['ID'] ?>]['QUANTITY']
                                }
                            }
                            return
                        }
                        delete BASKET[<?= $item['ID'] ?>]
                        document.getElementById('<?= $arResult['AREA_ID'] ?>').removeAttribute('data-in-basket')
                        if (window.ProductsStorage.hasOwnProperty('<?= $item['ID'] ?>')) {
                            for (let i = 0; i < window.ProductsStorage['<?= $item['ID'] ?>'].length; i++) {
                                window.ProductsStorage['<?= $item['ID'] ?>'][i].removeAttribute('data-in-basket')
                            }
                        }
                        UserBar.basket.render()
                    }
                )
            }
        </script>
	<?php endif ?>
	<?php unset($item);
}
