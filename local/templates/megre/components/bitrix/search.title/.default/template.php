<?php
/*
 * Изменено: 17 декабря 2021, пятница
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
$this->setFrameMode(true); ?>
<?php if ($arParams['SHOW_INPUT'] !== 'N'): ?>
	<div id="search" class="search-wrapper">
		<div id="<?= $arParams['~CONTAINER_ID'] ?>">
			<div class="container">
				<div class="search-content">
					<form name="search" action="<?= $arResult['FORM_ACTION'] ?>" method="get">
						<input type="text"
							   name="q"
							   value=""
							   placeholder="Поиск по товарам ..."
							   id="<?= $arParams['~INPUT_ID'] ?>">
					</form>
					<div id="searchClose" class="search-close"><span><i class="cross"></i></span></div>
				</div>
			</div>
		</div>
	</div>
	<script>
		BX.ready(function () {
			new JCTitleSearch({
				'AJAX_PAGE': '<?= CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
				'CONTAINER_ID': '<?= $arParams['~CONTAINER_ID']?>',
				'INPUT_ID': '<?= $arParams['~INPUT_ID']?>',
				'MIN_QUERY_LEN': 2
			})
		})
	</script>
<?php endif ?>
