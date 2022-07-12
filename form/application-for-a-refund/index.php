<?php
/*
 * Изменено: 28 декабря 2021, вторник
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2021
 */

global $APPLICATION, $USER;
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Форма возврата денежных средств");
$APPLICATION->SetTitle("Форма возврата денежных средств");
?>
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

	b24form({
		"id": "17",
		"lang": "ru",
		"sec": "cowutb",
		"type": "inline",
		'fields': {
			'values': {
				'LEAD_UF_CRM_1640717790': '<?= $USER->GetLastName() ?>',
				'LEAD_UF_CRM_1640717800': '<?= $USER->GetFirstName() ?>',
				'LEAD_UF_CRM_1640717809': '<?= $USER->GetSecondName() ?>',
			}
		},
	});
</script>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
