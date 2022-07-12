<?php
/*
 * Изменено: 10 декабря 2021, пятница
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @global CUser $USER
 * @var array    $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
?>
<div class="form-wrapper">
	<?php if ($arResult['isFormNote'] === 'Y'): ?>
		<div class="form-message mb-4">
			<i class="far fa-check-circle"></i> Спасибо! Ваше сообщение отправлено!
		</div>
	<?php else: ?>
	<?php CJSCore::Init(['phone_number']) ?>
	<?= $arResult['FORM_HEADER'] ?>
	<?php if ($arResult['isUseCaptcha'] == 'Y'): ?>
	<input type="hidden" name="captcha_sid" value="<?= htmlspecialcharsbx($arResult['CAPTCHACode']) ?>">
	<?php endif ?>
	<?php foreach ($arResult['QUESTIONS'] as $FIELD_SID => $arQuestion): ?>
	<?php if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] === 'hidden'): ?>
	<?php if ($FIELD_SID === 'SOURCE' && !$arQuestion['VALUE']) {
		$arQuestion['VALUE'] = 'WEB'; // Источник CRM - Сайт megre.ru
	} ?>
	<input type="hidden"
		   name="form_hidden_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
		   value="<?= $arQuestion['VALUE'] ?>">
	<?php endif ?>
	<?php endforeach ?>
		<div class="form-content">
			<?php foreach ($arResult['QUESTIONS'] as $FIELD_SID => $arQuestion): ?>
				<?php if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] !== 'hidden'): ?>
					<div class="form-item-row form-item-row-<?=$FIELD_SID?>">
						<div class="form-item-label">
							<?= $arQuestion['CAPTION'] ?>
						</div>
						<div <?php if (is_array($arResult['FORM_ERRORS']) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])): ?>class="form-item-input-error"
							 title="<?= htmlspecialcharsbx($arResult['FORM_ERRORS'][$FIELD_SID]) ?>"
							 <?php else: ?>class="form-item-input"<?php endif ?>>
							<?php if ($FIELD_SID === 'PHONE'): ?>
								<div class="form-item-phone-country" id="phone_country_select">
									<div id="phone_country_flag"></div>
									<div id="phone_country_code">+7</div>
									<i class="fas fa-caret-down"></i>
								</div>
								<div class="form-item-phone-number">
									<input type="text"
										   name="phone_number"
										   id="phone_number"
										   value="<?= $_POST['phone_number'] ?>">
									<input type="hidden"
										   name="form_<?= $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
										   value="<?= $arQuestion['VALUE'] ?>">
								</div>
							<?php else: ?>
								<?= $arQuestion['IS_INPUT_CAPTION_IMAGE'] === 'Y' ? '<br>' . $arQuestion['IMAGE']['HTML_CODE'] : '' ?>
								<?php if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] === 'dropdown'): ?>
									<div class="form-item-type-select">
										<?= $arQuestion['HTML_CODE'] ?>
										<i class="fas fa-caret-down"></i>
									</div>
								<?php else: ?>
									<?= $arQuestion['HTML_CODE'] ?>
								<?php endif ?>
							<?php endif ?>
						</div>
					</div>
				<?php endif ?>
			<?php endforeach ?>
			<?php if ($arResult['isUseCaptcha'] == 'Y'): ?>
				<div class="form-item-row">
					<div class="form-item-label">
						Введите код
					</div>
					<div <?php if (is_array($arResult['FORM_ERRORS']) && array_key_exists(0, $arResult['FORM_ERRORS'])): ?>class="form-item-input-error"
						 title="<?= htmlspecialcharsbx($arResult['FORM_ERRORS'][0]) ?>"
						 <?php else: ?>class="form-item-input"<?php endif ?>>
						<div class="form-item-captcha-image">
							<img src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($arResult['CAPTCHACode']) ?>">
						</div>
						<div class="form-item-captcha-word">
							<input type="text" name="captcha_word" size="30" maxlength="50" value="">
						</div>
					</div>
				</div>
			<?php endif ?>
		</div>
		<div class="form-footer">
			<div id="button-loader">
				<span class="loader-dots-wrapper"><span class="loader-dots"><span></span><span></span><span></span></span></span>
			</div>
			<input type="submit"
				   name="web_form_submit"
				   value="<?= trim($arResult['arForm']["BUTTON"]) == '' ? GetMessage('FORM_ADD') : $arResult['arForm']['BUTTON'] ?>">
		</div>
	<?= $arResult['FORM_FOOTER'] ?>
	<?php if ($USER->IsAuthorized() && empty($_POST)): ?>
	<?php if (isset($arResult['QUESTIONS']['NAME']) && empty($arResult['QUESTIONS']['NAME']['VALUE'])): ?>
	<?php $fieldCode = 'form_' . $arResult['QUESTIONS']['NAME']['STRUCTURE'][0]['FIELD_TYPE'] . '_' . $arResult['QUESTIONS']['NAME']['STRUCTURE'][0]['ID'] ?>
		<script>
			document.forms['<?= $arResult['arForm']['SID'] ?>'].elements['<?= $fieldCode ?>'].value = '<?= $USER->GetFirstName() ?? '' ?>'
		</script>
	<?php endif ?>
	<?php if (isset($arResult['QUESTIONS']['EMAIL']) && empty($arResult['QUESTIONS']['EMAIL']['VALUE'])): ?>
	<?php $fieldCode = 'form_' . $arResult['QUESTIONS']['EMAIL']['STRUCTURE'][0]['FIELD_TYPE'] . '_' . $arResult['QUESTIONS']['EMAIL']['STRUCTURE'][0]['ID'] ?>
		<script>
			document.forms['<?= $arResult['arForm']['SID'] ?>'].elements['<?= $fieldCode ?>'].value = '<?= $USER->GetEmail() ?? '' ?>'
		</script>
	<?php endif ?>
	<?php endif ?>
	<?php if (isset($arResult['QUESTIONS']['PHONE'])): ?>
	<?php $fieldCode = 'form_' . $arResult['QUESTIONS']['PHONE']['STRUCTURE'][0]['FIELD_TYPE'] . '_' . $arResult['QUESTIONS']['PHONE']['STRUCTURE'][0]['ID'] ?>
		<script>
			if (typeof formSubmit === 'undefined') {
				let formSubmit
			}
			if (typeof phoneNumberOriginal === 'undefined') {
				let phoneNumberOriginal
			}
			if (typeof phoneNumber === 'undefined') {
				let phoneNumber
			}
			if (typeof phoneCountrySelect === 'undefined') {
				let phoneCountrySelect
			}
			if (typeof phoneCountryFlag === 'undefined') {
				let phoneCountryFlag
			}
			if (typeof phoneCountryCode === 'undefined') {
				let phoneCountryCode
			}
			if (typeof PhoneNumberObject === 'undefined') {
				let PhoneNumberObject
			}
			formSubmit = document.forms['<?= $arResult['arForm']['SID'] ?>'].elements['web_form_submit']
			phoneNumberOriginal = document.forms['<?= $arResult['arForm']['SID'] ?>'].elements['<?= $fieldCode ?>']
			phoneNumber = document.getElementById('phone_number')
			phoneCountrySelect = document.getElementById('phone_country_select')
			phoneCountryFlag = document.getElementById('phone_country_flag')
			phoneCountryCode = document.getElementById('phone_country_code')
			PhoneNumberObject = new BX.PhoneNumber.Input({
				node: phoneNumber,
				flagNode: phoneCountryFlag,
				flagSize: 32,
				defaultCountry: 'ru',
				onCountryChange: function (e) {
					phoneCountryCode.innerText = '+' + e.countryCode
				},
				onInitialize: function () {
					if (PhoneNumberObject.initialized === true) {
						phoneCountryCode.innerText = '+' + PhoneNumberObject.getCountryCode()
						phoneCountrySelect.onclick = function () {
							phoneCountryFlag.click()
						}
						formSubmit.onclick = function (e) {
							this.style.display = 'none'
							document.getElementById('button-loader').style.display = 'flex'
							if (PhoneNumberObject.getValue().length > 8) {
								phoneNumberOriginal.value = PhoneNumberObject.getValue()
							}
						}
					}
				}
			})
		</script>
	<?php endif ?>
	<?php endif ?>
</div>
