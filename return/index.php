<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Возврат денежных средств");
CJSCore::Init(['phone_number']);
?>
<link href="style.css" type="text/css" rel="stylesheet"/>
<section class="return-money">
    <div class="container">
        <div class="page-title">ФОРМА ВОЗВРАТА ДЕНЕЖНЫХ СРЕДСТВ</div>
        <div class="content-side">
            <div class="column-content">
                <div class="form-wrapper">
                    <form class="return-form">
                        <div class="block-title mb-4 mb-md-5">КОНТАКТЫ</div>
                        <div class="form-content">
                            <div class="form-item-row">
                                <div class="form-item-label">Фамилия <i>*</i></div>
                                <div class="form-item-input">
                                    <input type="text" name="LAST_NAME" class="inputtext" value="" required>
                                </div>
                            </div>
                            <div class="form-item-row">
                                <div class="form-item-label">Имя <i>*</i></div>
                                <div class="form-item-input">
                                    <input type="text" name="NAME" class="inputtext" value="" required>
                                </div>
                            </div>
                            <div class="form-item-row">
                                <div class="form-item-label">Отчество <i>*</i></div>
                                <div class="form-item-input">
                                    <input type="text" name="SECOND_NAME" class="inputtext" value="" required>
                                </div>
                            </div>
                            <div class="form-item-row">
                                <div class="form-item-label">Телефон <i>*</i></div>
                                <div class="form-item-input">
                                    <div class="form-item-phone-country" id="phone_country_select">
                                        <div id="phone_country_flag"></div>
                                        <div id="phone_country_code">+7</div>
                                        <i class="fas fa-caret-down"></i>
                                    </div>
                                    <div class="form-item-phone-number">
                                        <input type="text" name="PHONE_NUMBER" id="phone_number" value="" required>

                                    </div>
                                </div>
                            </div>
                            <div class="form-item-row">
                                <div class="form-item-label">Номер заказа <i>*</i></div>
                                <div class="form-item-input">
                                    <input type="text" name="ORDER_NUMBER" class="inputtext" value="" required>
                                </div>
                            </div>
                            <div class="form-item-row">
                                <div class="form-item-label">Причина возврата <i>*</i></div>
                                <div class="form-item-input">
                                    <input type="text" name="CAUSE" class="inputtext" value="" required>
                                </div>
                            </div>
                        </div>
                        <div class="block-title mt-5 mb-3">БАНК</div>
                        <p class="mb-4 mb-md-5">Заполните, если вы оплачивали заказ не банковской картой</p>
                        <div class="form-content">
                            <div class="form-item-row">
                                <div class="form-item-label">БИК</div>
                                <div class="form-item-input">
                                    <input type="text" name="BANK_NUMBER" class="inputtext" value="">
                                </div>
                            </div>
                            <div class="form-item-row">
                                <div class="form-item-label">Наименование банка</div>
                                <div class="form-item-input">
                                    <input type="text" name="BANK_NAME" class="inputtext" value="">
                                </div>
                            </div>
                            <div class="form-item-row">
                                <div class="form-item-label">Номер Р/С</div>
                                <div class="form-item-input">
                                    <input type="text" name="BANK_RS" class="inputtext" value="">
                                </div>
                            </div>
                            <p class="mt-4" style="font-size: 12px;">Нажимая кнопку «Отправить заявление», я даю свое
                                согласие на обработку моих персональных данных, в соответствии с Федеральным законом от
                                27.07.2006 года №152-ФЗ «О персональных данных», на условиях и для целей, определенных в
                                Согласии на обработку персональных данных <span style="color: #CB8383;">*</span></p>
                        </div>
                        <div class="form-footer">
                            <div id="button-loader">
                                <span class="loader-dots-wrapper"><span
                                            class="loader-dots"><span></span><span></span><span></span></span></span>
                            </div>
                            <input type="submit" value="Отправить заявление">
                        </div>
                    </form>
                </div>
            </div>
            <div class="column-side">
                <div class="side-subscribe">
                    <div class="side-subscribe__title">Хотите подарок с&nbsp;таёжного производства?</div>
                    <div class="side-subscribe__sub">Дарим дорожный размер нашей любимой зубной пасты при подписке.
                    </div>
                    <form class="subscribe_form">
                        <div class="input">
                            <label>Ваш e-mail</label>
                            <input placeholder="Введите email" name="EMAIL" required/>
                        </div>
                        <button class="button button_primary">Подписаться</button>
                    </form>
                    <div class="side-subscribe__privacy">Нажимая кнопку «Подписаться», я даю свое согласие на обработку
                        моих персональных данных, в соответствии с Федеральным законом от 27.07.2006 года №152-ФЗ «О
                        персональных данных», на условиях и для целей, определенных в <a href='/privacy-policy/' target='_blank'>Согласии на обработку персональных данных</a>.
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<script>
    BX.ready(function() {
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
    });
</script>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
