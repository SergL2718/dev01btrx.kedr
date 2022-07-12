<?php
/*
 * Изменено: 17 декабря 2021, пятница
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

/** @var array $arParams */

/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */

/** @var PageNavigationComponent $component */
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
$component = $this->getComponent();
$this->setFrameMode(true);
?>
<div class="list-pagination-wrapper">
	<div class="list-pagination-container">
		<ul>
			<?php if ($arResult["REVERSED_PAGES"] === true): ?>

				<?php if ($arResult["CURRENT_PAGE"] < $arResult["PAGE_COUNT"]): ?>
					<?php if (($arResult["CURRENT_PAGE"] + 1) == $arResult["PAGE_COUNT"]): ?>
						<li class="list-pagination-pag-prev">
							<a href="<?= htmlspecialcharsbx($arResult["URL"]) ?>"><span><i class="pagination-arrow-left"></i></span></a>
						</li>
					<?php else: ?>
						<li class="list-pagination-pag-prev">
							<a href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"] + 1)) ?>"><span><i
											class="pagination-arrow-left"></i></span></a>
						</li>
					<?php endif ?>
					<li class=""><a href="<?= htmlspecialcharsbx($arResult["URL"]) ?>"><span>1</span></a></li>
				<?php else: ?>
					<li class="list-pagination-pag-prev"><span><i class="pagination-arrow-left"></i></span></li>
					<li class="list-pagination-active"><span>1</span></li>
				<?php endif ?>

				<?php
				$page = $arResult["START_PAGE"] - 1;
				while ($page >= $arResult["END_PAGE"] + 1):
					?>
					<?php if ($page == $arResult["CURRENT_PAGE"]): ?>
					<li class="list-pagination-active"><span><?= ($arResult["PAGE_COUNT"] - $page + 1) ?></span></li>
				<?php else: ?>
					<li class="">
						<a href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($page)) ?>"><span><?= ($arResult["PAGE_COUNT"] - $page + 1) ?></span></a>
					</li>
				<?php endif ?>

					<?php $page-- ?>
				<?php endwhile ?>

				<?php if ($arResult["CURRENT_PAGE"] > 1): ?>
					<?php if ($arResult["PAGE_COUNT"] > 1): ?>
						<li class="">
							<a href="<?= htmlspecialcharsbx($component->replaceUrlTemplate(1)) ?>"><span><?= $arResult["PAGE_COUNT"] ?></span></a>
						</li>
					<?php endif ?>
					<li class="list-pagination-pag-next">
						<a href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"] - 1)) ?>"><span><i
										class="pagination-arrow-right"></i></span></a>
					</li>
				<?php else: ?>
					<?php if ($arResult["PAGE_COUNT"] > 1): ?>
						<li class="list-pagination-active"><span><?= $arResult["PAGE_COUNT"] ?></span></li>
					<?php endif ?>
					<li class="list-pagination-pag-next"><span><i class="pagination-arrow-right"></i></span></li>
				<?php endif ?>

			<?php else: ?>

				<?php if ($arResult["CURRENT_PAGE"] > 1): ?>
					<?php if ($arResult["CURRENT_PAGE"] > 2): ?>
						<li class="list-pagination-pag-prev">
							<a href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"] - 1)) ?>"><span><i
											class="pagination-arrow-left"></i></span></a>
						</li>
					<?php else: ?>
						<li class="list-pagination-pag-prev">
							<a href="<?= htmlspecialcharsbx($arResult["URL"]) ?>"><span><i class="pagination-arrow-left"></i></span></a>
						</li>
					<?php endif ?>
					<li class=""><a href="<?= htmlspecialcharsbx($arResult["URL"]) ?>"><span>1</span></a></li>
				<?php else: ?>
					<li class="list-pagination-pag-prev"><span><i class="pagination-arrow-left"></i></span></li>
					<li class="list-pagination-active"><span>1</span></li>
				<?php endif ?>

				<?php
				$page = $arResult["START_PAGE"] + 1;
				while ($page <= $arResult["END_PAGE"] - 1):
					?>
					<?php if ($page == $arResult["CURRENT_PAGE"]): ?>
					<li class="list-pagination-active"><span><?= $page ?></span></li>
				<?php else: ?>
					<li class="">
						<a href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($page)) ?>"><span><?= $page ?></span></a>
					</li>
				<?php endif ?>
					<?php $page++ ?>
				<?php endwhile ?>

				<?php if ($arResult["CURRENT_PAGE"] < $arResult["PAGE_COUNT"]): ?>
					<?php if ($arResult["PAGE_COUNT"] > 1): ?>
						<li class="">
							<a href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($arResult["PAGE_COUNT"])) ?>"><span><?= $arResult["PAGE_COUNT"] ?></span></a>
						</li>
					<?php endif ?>
					<li class="list-pagination-pag-next">
						<a href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"] + 1)) ?>"><span><i
										class="pagination-arrow-right"></i></span></a>
					</li>
				<?php else: ?>
					<?php if ($arResult["PAGE_COUNT"] > 1): ?>
						<li class="list-pagination-active"><span><?= $arResult["PAGE_COUNT"] ?></span></li>
					<?php endif ?>
					<li class="list-pagination-pag-next"><span><i class="pagination-arrow-right"></i></span></li>
				<?php endif ?>
			<?php endif ?>
		</ul>
		<div style="clear:both"></div>
	</div>
</div>
