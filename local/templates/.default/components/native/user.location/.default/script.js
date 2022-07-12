/*
 * @updated 09.03.2021, 13:37
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2021, Компания Webco <hello@wbc.cx>
 * @link https://wbc.cx
 */

const UserLocationComponent = {}

UserLocationComponent.run = function (params) {
    UserLocationComponent.set.params(params)
    UserLocationComponent.get.nodes()
    UserLocationComponent.set.controllers()
    if (!BX.getCookie(UserLocationComponent.get.param('COOKIE'))) {
        UserLocationComponent.get.node('showList').click()
    }
}

UserLocationComponent.controller = {
    location: {
        list: {
            show: function () {
                UserLocationComponent.get.node('list').classList.toggle('hidden')
            }
        },
        set: function () {
            if (!this.dataset.cityCode) {
                return
            }
            BX.showWait()
            const cityCode = this.dataset.cityCode.toUpperCase()
            UserLocationComponent.get.node('city').innerText = UserLocationComponent.get.city(cityCode).TITLE
            UserLocationComponent.get.node('list').classList.add('hidden')
            BX.setCookie(UserLocationComponent.get.param('COOKIE'), cityCode, {expires: 864000000, path: '/'})
            window.location.reload()
        },
    }
}

UserLocationComponent.get = {
    param: function (code) {
        return UserLocationComponent.params[code]
    },
    nodes: function () {
        UserLocationComponent.node = {
            showList: document.querySelector('[data-controller=showLocationList]'),
            setLocation: document.querySelectorAll('[data-controller=setLocation]'),
            list: document.querySelector('.user__location__list'),
            city: document.querySelector('.user__location .user__location__city__title'),
        }
    },
    node: function (code) {
        return UserLocationComponent.node[code]
    },
    city: function (code) {
        return UserLocationComponent.get.param('LOCATION')[code]
    },
}

UserLocationComponent.set = {
    params: function (params) {
        params = params || {}
        UserLocationComponent.params = params
    },
    controllers: function () {
        UserLocationComponent.get.node('showList').addEventListener('click', UserLocationComponent.controller.location.list.show)
        for (let i = 0; i < UserLocationComponent.get.node('setLocation').length; i++) {
            const node = UserLocationComponent.get.node('setLocation')[i]
            node.addEventListener('click', UserLocationComponent.controller.location.set)
        }
    },
}