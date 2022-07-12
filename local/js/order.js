/*
 * @updated 18.02.2021, 17:15
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2021, Компания Webco <hello@wbc.cx>
 * @link https://wbc.cx
 */

window.onload = function () {
    addButtonForSendDataToCustomer();
};

function addButtonForSendDataToCustomer() {
    //const order = JSON.parse(BX.getCookie('jsOrder'));

    const buttons = document.querySelector('.adm-detail-toolbar-right');
    const buttonActions = buttons.querySelector('a:last-child');
    const buttonSend = buttonActions.cloneNode(true);
    const onclick = buttonSend.getAttribute('onclick');
    const start_pos = onclick.indexOf('[') + 1;
    const end_pos = onclick.indexOf(']', start_pos);
    const strItems = '[' + onclick.substring(start_pos, end_pos) + ']';
    // Новые пункты для кнопки
    const items = [
        {
            TEXT: 'Банковский счёт',
            ONCLICK: 'Send.bill()'
        },
        {
            TEXT: 'Ссылка на оплату картой',
            ONCLICK: 'Send.card()'
        }
    ];

    // Добавляем кнопку в правый тулбар
    buttonSend.innerText = 'Отправить на email';

    document.querySelector('.adm-detail-toolbar-right').appendChild(buttonSend);
    // Добавляем новые пункты в добавленную кнопку
    buttonSend.setAttribute('onclick', onclick.replace(strItems, JSON.stringify(items)));
}

const Send = {
    //url: '/local/ajax/order.php?ORDER_ID=#ORDER_ID#&ACCOUNT_NUMBER=#ACCOUNT_NUMBER#&USER_EMAIL=#USER_EMAIL#&TYPE=#TYPE#',
    url: '/local/ajax/order.php?action=Send',
    bill: function () {
        Send.request('bill');
    },
    card: function () {
        Send.request('cards');
    },
    request: function (type) {
        const xhr = new XMLHttpRequest();
        const params = getParams(window.location.href);
        BX.showWait();
        xhr.open('GET', this.url + '&type=' + type + '&orderId=' + params['ID'], true);
        xhr.onload = function () {
            const response = JSON.parse(xhr.response);
            //const response = xhr.response;

            //console.log(response);

            BX.closeWait();

            if (response.message) {
                alert(response.message);
            }
        };
        xhr.send();
    }
};

const getParams = function (url) {
    let params = {};
    let parser = document.createElement('a');
    parser.href = url;
    let query = parser.search.substring(1);
    let vars = query.split('&');
    for (let i = 0; i < vars.length; i++) {
        let pair = vars[i].split('=');
        params[pair[0]] = decodeURIComponent(pair[1]);
    }
    return params;
};
