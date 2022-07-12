/*
 * @updated 09.12.2020, 21:02
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

const Article = {}

Article.run = function (params) {

    const productList = document.querySelectorAll('[data-code="product"][data-id]')

    if (productList.length > 0) {
        for (let i = 0; i < productList.length; i++) {
            const node = productList[i]
            const id = node.dataset.id
            BX.ajax.get(params.ajaxUrl + '?ID=' + id, function (response) {
                node.innerHTML = response
            })
        }
    }
}


