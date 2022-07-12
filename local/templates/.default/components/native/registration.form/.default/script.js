/*
 * Copyright (c) 2019 Артамонов Денис
 * Дата создания: 10/25/19 10:27 PM
 * Email: artamonov.ceo@gmail.com
 */

'use strict';

const RegistrationForm = {

    component: 'native:registration.form',

    btnRegister: document.forms.registration.elements.register,

    construct: function () {
        this.btnRegister.addEventListener('click', this.register, false);
    },

    register: function (event) {
        event.preventDefault();
        event.stopPropagation();
        let form = {
            name: document.forms.registration.elements.name.value,
            lastName: document.forms.registration.elements.lastName.value,
            email: document.forms.registration.elements.email.value,
            login: document.forms.registration.elements.login.value,
            password: document.forms.registration.elements.password.value,
            confirmPassword: document.forms.registration.elements.confirmPassword.value,
        };

        BX.UI.Notification.Center.notify({
            content: 'Проверяем данные',
            autoHideDelay: 3000
        });

        BX.ajax.runComponentAction(
            RegistrationForm.component,
            'registration',
            {
                mode: 'class',
                data: {
                    request: form
                }
            })
            .then(function (response) {

                if (response.status !== 'success') {
                    BX.UI.Notification.Center.notify({
                        content: 'Что-то пошло не так',
                        autoHideDelay: 3000
                    });
                    return;
                }

                response = response.data;

                if (response.error) {
                    if (response.type === 'USER_FOUND') {
                        BX.UI.Notification.Center.notify({
                            content: response.error,
                            autoHide: true,
                            autoHideDelay: 8000,
                            actions: [
                                {
                                    title: 'Авторизация',
                                    events: {
                                        click: function () {
                                            window.location.href = '/login/';
                                        }
                                    }
                                }
                            ]
                        });
                        return;
                    }
                    if (Array.isArray(response.error) === true) {
                        for (let i = 0; i < response.error.length; i++) {
                            BX.UI.Notification.Center.notify({
                                content: response.error[i],
                                autoHideDelay: 3000
                            });
                        }
                    } else {
                        BX.UI.Notification.Center.notify({
                            content: response.error,
                            autoHideDelay: 3000
                        });
                    }
                    return;
                }

                let elements = document.forms.registration.querySelector('.elements');
                let content = document.forms.registration.querySelector('.content');
                elements.style.display = 'none';
                content.innerHTML = response.message;
                content.style.display = 'block';
            });
    }
};

RegistrationForm.construct();
