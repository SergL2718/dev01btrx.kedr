/*
 * Изменено: 06 августа 2021, пятница
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

'use strict';

const ManagerLoginForm = {

    btnSend: document.forms.login.elements.send,

    construct: function () {
        this.btnSend.addEventListener('click', this.send, false);
    },

    send: function (event) {
        event.preventDefault();
        event.stopPropagation();

        let form = {
            email: document.forms.login.elements.email.value,
            //sessionId: document.forms.login.elements.sessionId.value
        };

        BX.UI.Notification.Center.notify({
            content: 'Ожидайте, пожалуйста. Мы проверяем информацию.',
            autoHideDelay: 10000
        });

        BX.ajax.runComponentAction(
            'native:manager.login.form',
            'login',
            {
                mode: 'class',
                data: {
                    request: form
                }
            })
            .then(function (response) {
                if (response.status === 'success') {
                    if (response.data.type === 'EMAIL_NOT_FOUND') {
                        BX.UI.Notification.Center.notify({
                            content: response.data.message,
                            autoHide: false,
                            actions: [
                                {
                                    title: 'Регистрация',
                                    events: {
                                        click: function () {
                                            window.location.href = '/registration/';
                                        }
                                    }
                                }
                            ]
                        });
                        return;
                    }
                    if (!response.data.sent && response.data.message) {
                        BX.UI.Notification.Center.notify({content: response.data.message, autoHideDelay: 3000});
                        return;
                    }
                    if (response.data.sent && response.data.message) {
                        let elements = document.forms.login.querySelector('.elements');
                        let content = document.forms.login.querySelector('.content');
                        elements.style.display = 'none';
                        content.innerHTML = response.data.message;
                        content.style.display = 'block';
                    }
                }
            });
    }
};
ManagerLoginForm.construct();
