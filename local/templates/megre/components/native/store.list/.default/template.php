<?php
/*
 * Изменено: 14 декабря 2021, вторник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
?>
<div class="form-wrapper form-find-store">
	<form class="form" name="StoreList">
		<input type="hidden" name="sessionId" value="<?= session_id() ?>">

		<div class="form-content">
			<div class="form-item-row">
				<div class="form-item-input">
					<input type="text" name="searchText" placeholder="Страна/Город" maxlength="40">
				</div>
				<div class="form-footer">
					<div id="button-loader">
						<span class="loader-dots-wrapper"><span class="loader-dots"><span></span><span></span><span></span></span></span>
					</div>
					<input type="submit" name="search" value="Найти отдел">
				</div>
			</div>
		</div>
		<div class="form-result mt-3">
			<div id="hint-start-search">
				Чтобы начать поиск, пожалуйста, введите в поле запроса название Страны или Города.
			</div>
			<div id="not-found">
				К сожалению, по вашему запросу ничего не найдено. Но Вы всегда можете оформить заказ на нашем сайте, выбрав
				продукцию
				<a href="/catalog/" class="link">в каталоге</a>.
			</div>
			<div id="found">
				По вашему запросу найдены фирменные отделы:
				<ul class="store-list"></ul>
			</div>
		</div>
	</form>
</div>
<script>
	StoreListComponent.construct(<?= $arResult['JS'] ?>)
</script>
