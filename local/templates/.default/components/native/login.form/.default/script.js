/*
 * Copyright (c) 2019 Артамонов Денис
 * Дата создания: 10/25/19 7:10 PM
 * Email: artamonov.ceo@gmail.com
 */

'use strict';

const LoginForm = {

    component: 'native:login.form',
    btnLoginByPassword: document.forms.login.elements.loginByPassword,
    btnLoginByEmail: document.forms.login.elements.loginByEmail,

    construct: function () {
        this.initButtons();
    },

    initButtons: function () {
        if (this.btnLoginByEmail) {
            this.btnLoginByEmail.addEventListener('click', this.login, false);
        }
        if (this.btnLoginByPassword) {
            this.btnLoginByPassword.addEventListener('click', this.login, false);
        }
    },

    login: function (event) {
        event.preventDefault();
        event.stopPropagation();

        let _self = this;
        let loginType = _self.name;

        BX.UI.Notification.Center.notify({
            content: 'Проверяем данные',
            autoHideDelay: 3000
        });

        BX.ajax.runComponentAction(
            LoginForm.component,
            loginType,
            {
                mode: 'class',
                data: {
                    request: {
                        login: document.forms.login.elements.login.value,
                        password: document.forms.login.elements.password.value,
                        email: document.forms.login.elements.email.value
                    }
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
                    if (response.type === 'USER_NOT_FOUND') {
                        BX.UI.Notification.Center.notify({
                            content: response.error,
                            autoHide: true,
                            autoHideDelay: 8000,
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

                if (response.redirect) {
                    window.location.href = response.redirect;
                    return;
                }

                let elements = document.forms.login.querySelector('.elements');
                let content = document.forms.login.querySelector('.content');

                elements.style.display = 'none';
                content.innerHTML = response.message;
                content.style.display = 'block';
            });
    }
};

LoginForm.construct();
