/*
 * Изменено: 08 сентября 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

'use strict';

const App = {
    util: {}
};

/**
 * Метод для работы с объектами
 */
App.object = {
    get: {
        key: {
            /**
             * Метод возвращает первый элемент объекта
             * @param object
             * @returns {{value: *, key: string}}
             */
            first: function (object) {
                for (let key in object) {
                    return {
                        key: key,
                        value: object[key],
                    }
                }
            }
        }
    }
};

/**
 * Более простая функция для вывода данных в консоль
 * @param data
 */
function pr(data) {
    console.log(data);
}

/**
 * Форматирование различных данных
 * @type {{price: {withoutCurrency: (function(*=): string), withCurrency: (function(*=): string)}}}
 */
App.util.format = {
    price: {
        withCurrency: function (amount) {
            if (parseInt(amount) !== amount) {
                amount = new Intl.NumberFormat('ru-RU', {minimumFractionDigits: 0}).format(amount);
                amount = amount.replace(',', '.');
            } else {
                amount = new Intl.NumberFormat('ru-RU').format(amount);
            }
            amount = amount + ' руб.';
            return amount;
        },
        withoutCurrency: function (amount) {
            if (parseInt(amount) !== amount) {
                amount = new Intl.NumberFormat('ru-RU', {minimumFractionDigits: 2}).format(amount);
                amount = amount.replace(',', '.');
            } else {
                amount = new Intl.NumberFormat('ru-RU').format(amount);
            }
            return amount;
        },
    },
};

/**
 * Получение положения элемента на странице
 * @param node
 */
App.util.getOffset = function (node) {
    const rect = node.getBoundingClientRect(),
        scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
        scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    return {
        top: rect.top + scrollTop,
        left: rect.left + scrollLeft
    }
}

/**
 * Склонение существительных
 * Правильная форма существительного рядом с числом (счетная форма).
 *
 * @example declension("файл", "файлов", "файла", 0);//returns "файлов"
 * @example declension("файл", "файлов", "файла", 1);//returns "файл"
 * @example declension("файл", "файлов", "файла", 2);//returns "файла"
 *
 * @param {(string|number)} number количество
 * @param {string} oneNominative единственное число (именительный падеж)
 * @param {string} severalGenitive множественное число (родительный падеж)
 * @param {string} severalNominative множественное число (именительный падеж)
 * @returns {string}
 */
App.util.declension = function (number, oneNominative, severalGenitive, severalNominative) {
    number = number % 100;
    return (number <= 14 && number >= 11)
        ? severalGenitive
        : (number %= 10) < 5
            ? number > 2
                ? severalNominative
                : number === 1
                    ? oneNominative
                    : number === 0
                        ? severalGenitive
                        : severalNominative//number === 2
            : severalGenitive
        ;
}
