/*
 * Изменено: 29 декабря 2021, среда
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

'use strict';

const SearchSalePointComponent = {
    construct: function (params = {}) {
        SearchSalePointComponent.searchData = params
        SearchSalePointComponent.cache = {}
        SearchSalePointComponent.form = document.forms['SearchSalePoint']
        SearchSalePointComponent.searchNode = document.querySelector('.form__result .form__search')
        SearchSalePointComponent.notFoundNode = document.querySelector('.form__result .form__not__found')
        SearchSalePointComponent.foundNode = document.querySelector('.form__result .form__found')
        SearchSalePointComponent.btnSearch = document.querySelector('[data-type="search"]')
        SearchSalePointComponent.entityNode = document.querySelector('[name="entity"]')
        SearchSalePointComponent.entity = false
        SearchSalePointComponent.form.onsubmit = function (event) {
            event.preventDefault()
            event.stopPropagation()
            SearchSalePointComponent.search()
        }
        SearchSalePointComponent.btnSearch.onclick = function (event) {
            event.preventDefault()
            event.stopPropagation()
            SearchSalePointComponent.search()
        }
        SearchSalePointComponent.entityNode.addEventListener('input', function () {
            SearchSalePointComponent.clear()
        })
    },

    clear: function () {
        SearchSalePointComponent.entity = SearchSalePointComponent.entityNode.value.toLowerCase().trim();
        if (SearchSalePointComponent.entity.length <= 3) {
            SearchSalePointComponent.searchNode.style.display = 'block';
            SearchSalePointComponent.notFoundNode.style.display = 'none';
            SearchSalePointComponent.foundNode.style.display = 'none';
        }
    },

    search: function () {
        SearchSalePointComponent.entity = SearchSalePointComponent.entityNode.value.toLowerCase().trim()
        if (SearchSalePointComponent.entity.length <= 3) {
            SearchSalePointComponent.searchNode.style.display = 'block'
            SearchSalePointComponent.notFoundNode.style.display = 'none'
            SearchSalePointComponent.foundNode.style.display = 'none'
        } else {
            SearchSalePointComponent.findByEntity()
        }
    },

    findByEntity: function () {
        if (!SearchSalePointComponent.cache.hasOwnProperty(SearchSalePointComponent.entity)) {
            SearchSalePointComponent.cache[SearchSalePointComponent.entity] = SearchSalePointComponent.find('country') || SearchSalePointComponent.find('city') || false;
        }
        SearchSalePointComponent.display();
    },

    find: function (type) {
        pr(SearchSalePointComponent.searchData)
        if (!SearchSalePointComponent.searchData.hasOwnProperty(type)) return;
        for (let value in SearchSalePointComponent.searchData[type]) {
            if (value.indexOf(SearchSalePointComponent.entity) !== -1) {
                pr('type: ' + type)
                pr('value: ' + value)
                pr(SearchSalePointComponent.searchData[type][value])
                return SearchSalePointComponent.searchData[type][value];
            }
        }
        return false;
    },

    display: function () {
        let entities = SearchSalePointComponent.cache[SearchSalePointComponent.entity];
        let code = SearchSalePointComponent.entity;

        SearchSalePointComponent.searchNode.style.display = 'none';

        if (!entities) {
            SearchSalePointComponent.foundNode.style.display = 'none';
            SearchSalePointComponent.notFoundNode.style.display = 'block';
            return;
        }

        let list = document.querySelector('.form__result .form__list');
        let items = document.querySelectorAll('.form__result .form__list__item[data-code]');

        if (items.length > 0) {
            for (let i = 0, m = items.length; i < m; i++) {
                let item = items[i];
                item.style.display = 'none';
            }
        }

        for (let k in entities) {
            let entity = entities[k];

            code += '_' + entity['ID'];
            let item = document.querySelector('.form__list__item[data-code="' + code + '"]');

            if (item) {
                item.style.display = 'block';
                continue;
            }

            item = document.createElement('li');
            item.classList.add('form__list__item');

            item.setAttribute('data-code', code);

            if (entity.NAME) {
                let title = document.createElement('div');
                title.classList.add('form__list__item__title');
                title.innerText = entity.NAME;
                item.appendChild(title);
            }

            if (entity.CITY) {
                let city = document.createElement('div');
                city.innerHTML = '<span>Город:</span>' + entity.CITY;
                item.appendChild(city);
            }

            if (entity.ADDRESS) {
                let address = document.createElement('div');
                address.innerHTML = '<span>Адрес:</span>' + entity.ADDRESS;
                item.appendChild(address);
            }

            if (entity.DESCRIPTION) {
                let description = document.createElement('div');
                description.innerHTML = entity.DESCRIPTION;
                item.appendChild(description);
            }

            if (entity.SHOPPING_CENTER) {
                let shoppingCenter = document.createElement('div');
                shoppingCenter.innerHTML = '<span>Торговый центр:</span>' + entity.SHOPPING_CENTER;
                item.appendChild(shoppingCenter);
            }

            if (entity.SCHEDULE) {
                let schedule = document.createElement('div');
                schedule.innerHTML = '<span>Время работы:</span>' + entity.SCHEDULE;
                item.appendChild(schedule);
            }

            if (entity.EMAIL) {
                let email = document.createElement('div');
                email.innerHTML = '<i class="fas fa-envelope"></i><a href="mailto:' + entity.EMAIL + '">' + entity.EMAIL + '</a>';
                item.appendChild(email);
            }

            if (entity.PHONE) {
                for (let i = 0, m = entity.PHONE.length; i < m; i++) {
                    let phone = document.createElement('div');
                    phone.innerHTML = '<i class="fas fa-phone"></i>' + entity.PHONE[i];
                    item.appendChild(phone);
                }
            }

            if (entity.WWW) {
                let www = document.createElement('div');
                www.innerHTML = '<i class="fas fa-globe-americas"></i><a href="' + entity.WWW + '">' + entity.WWW + '</a>';
                item.appendChild(www);
            }

            list.appendChild(item);
        }

        SearchSalePointComponent.notFoundNode.style.display = 'none';
        SearchSalePointComponent.foundNode.style.display = 'block';
    }
}
