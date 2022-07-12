<?php
/*
 * Изменено: 15 декабря 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die; ?>
<?php if (!empty($arResult['CATEGORIES']) && $arResult['CATEGORIES_ITEMS_EXISTS']): ?>
	<div class="container">
		<?php foreach ($arResult['CATEGORIES'] as $category_id => $arCategory): ?>
			<?php foreach ($arCategory['ITEMS'] as $i => $arItem): ?>
				<?php if ($category_id === 'all'): ?>
					<div class="text-center"><a href="<?= $arItem['URL'] ?>"
												class="title-search-result-more">ПОКАЗАТЬ БОЛЬШЕ</a></div>
				<?php elseif (isset($arResult['ELEMENTS'][$arItem['ITEM_ID']])): ?>
					<?php $item =& $arResult['ELEMENTS'][$arItem['ITEM_ID']] ?>
					<div class="title-search-result-item">
						<a href="<?= $arItem['URL'] ?>">
							<?php if (is_array($item['PICTURE'])): ?>
								<span class="title-search-result-item-image">
									<img src="<?= $item['PICTURE']['src'] ?>">
								</span>
							<?php endif ?>
							<span><?= $arItem['NAME'] ?></span>
						</a>
					</div>
				<?php endif ?>
			<?php endforeach ?>
		<?php endforeach ?>
	</div>
<?php endif ?>