<?
/*
 * Изменено: 09 декабря 2021, четверг
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Заказать сертификат");
$APPLICATION->SetTitle("Заказать сертификат");
?>Тут надо добавить описание<br>
	<br>
	<script id="bx24_form_inline" data-skip-moving="true">
		(function (w, d, u, b) {
			w['Bitrix24FormObject'] = b;
			w[b] = w[b] || function () {
				arguments[0].ref = u;
				(w[b].forms = w[b].forms || []).push(arguments[0])
			};
			if (w[b]['forms']) return;
			var s = d.createElement('script');
			s.async = 1;
			s.src = u + '?' + (1 * new Date());
			var h = d.getElementsByTagName('script')[0];
			h.parentNode.insertBefore(s, h);
		})(window, document, 'https://megre.bitrix24.ru/bitrix/js/crm/form_loader.js', 'b24form');

		b24form({"id": "11", "lang": "ru", "sec": "wmic1b", "type": "inline"});
	</script><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>