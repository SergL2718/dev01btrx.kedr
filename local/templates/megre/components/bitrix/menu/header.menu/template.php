<?php
/*
 * Изменено: 30 ноября 2021, вторник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
if (empty($arResult)) {
	return;
}
?>
<nav>
	<div class="header-menu">
		<?php foreach ($arResult as $item): ?>
			<div class="header-menu-item">
				<a href="<?= $item['UF_DETAIL_PAGE_URL'] ?>"><?= $item['NAME'] ?></a>
				<div class="header-menu-data">
					<?php if (!empty($item['CHILDREN'])): ?>
						<div class="header-menu-data-left">
							<ul>
								<?php foreach ($item['CHILDREN'] as $child): ?>
									<li>
										<a <?php if ($child['SEE_ALL_URL'] === 'Y'): ?>class="header-menu-item-detail-url" <?php endif ?>
										   href="<?= $child['UF_DETAIL_PAGE_URL'] ?>"><?= $child['NAME'] ?></a></li>
								<?php endforeach ?>
							</ul>
						</div>
						<?php if ($item['PICTURE']['SRC'] && !$item['DESCRIPTION']): ?>
							<div class="header-menu-data-right" style="border: none; padding-left: 0">
								<div class="header-menu-data-full-image"
									 style="background-image: url('<?= $item['PICTURE']['SRC'] ?>')"></div>
							</div>
						<?php else: ?>
							<?php if ($item['UF_PRODUCT']['ID']): ?>
								<div class="header-menu-data-right">
									<div class="header-menu-data-right-image">
										<?php if (
												$item['UF_PRODUCT']['PROPERTY_NEWPRODUCT_VALUE']
												|| $item['UF_PRODUCT']['PROPERTY_SPECIALOFFER_VALUE']
												|| $item['UF_PRODUCT']['PROPERTY_RECOMMENDED_VALUE']
												|| $item['UF_PRODUCT']['PROPERTY_SALELEADER_VALUE']
												|| $item['UF_PRODUCT']['PROPERTY_OFFER_WEEK_VALUE']
										): ?>
											<div class="header-menu-data-badges">
												<?php if ($item['UF_PRODUCT']['PROPERTY_OFFER_WEEK_VALUE']): ?>
													<div>Товар недели</div>
												<?php endif ?>
												<?php if ($item['UF_PRODUCT']['PROPERTY_NEWPRODUCT_VALUE']): ?>
													<div>Новинки</div>
												<?php endif ?>
												<?php if ($item['UF_PRODUCT']['PROPERTY_SPECIALOFFER_VALUE']): ?>
													<div>Акция</div>
												<?php endif ?>
												<?php if ($item['UF_PRODUCT']['PROPERTY_SALELEADER_VALUE']): ?>
													<div>Бестселлеры</div>
												<?php endif ?>
												<?php if ($item['UF_PRODUCT']['PROPERTY_RECOMMENDED_VALUE']): ?>
													<div>Рекомендуем</div>
												<?php endif ?>
											</div>
										<?php endif ?>
										<a href="<?= $item['UF_PRODUCT_DETAIL_PAGE_URL'] ?>"><img src="<?= $item['UF_PRODUCT']['PREVIEW_PICTURE']['SRC'] ?>"
																								  alt=""></a>
									</div>
									<div class="header-menu-data-right-description">
										<?= $item['UF_PRODUCT_PREVIEW_TEXT'] ?>
									</div>
									<div class="header-menu-data-right-detail">
										<a class="header-menu-item-detail-url"
										   href="<?= $item['UF_PRODUCT_DETAIL_PAGE_URL'] ?>"><?= $item['UF_PRODUCT_DETAIL_PAGE_URL_TITLE'] ?></a>
									</div>
								</div>
							<?php else: ?>
								<div class="header-menu-data-right">
									<div class="header-menu-data-right-image">
										<a href="<?= $item['UF_DETAIL_PAGE_URL'] ?>"><img src="<?= $item['PICTURE']['SRC'] ?>"
																						  alt=""></a>
									</div>
									<div class="header-menu-data-right-description">
										<?= $item['DESCRIPTION'] ?>
									</div>
									<div class="header-menu-data-right-detail">
										<a class="header-menu-item-detail-url"
										   href="<?= $item['UF_DETAIL_PAGE_URL'] ?>">Посмотреть всё</a>
									</div>
								</div>
							<?php endif ?>
						<?php endif ?>
					<?php else: ?>
						<div class="header-menu-data-single">
							<a href="<?= $item['UF_DETAIL_PAGE_URL'] ?>">
								<img src="<?= $item['PICTURE']['SRC'] ?>" alt="">
							</a>
							<div class="header-menu-data-single-footer">
								<div class="header-menu-data-single-description"><?= $item['DESCRIPTION'] ?></div>
								<div class="header-menu-data-single-detail">
									<a class="header-menu-item-detail-url"
									   href="<?= $item['UF_DETAIL_PAGE_URL'] ?>">Посмотреть всё</a>
								</div>
							</div>
						</div>
					<?php endif ?>
				</div>
			</div>
		<?php endforeach ?>
	</div>
</nav>