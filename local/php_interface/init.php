<?php
/*
 * Изменено: 08 сентября 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;

// устаревший модуль с 17.02.2020
// пока еще используется, но все доработки надо переносить в новый модуль native.app
try {
    Loader::includeModule('zk.main');
} catch (\Bitrix\Main\LoaderException $e) {
}
// новый модуль приложения
try {
    Loader::includeModule('native.app');
} catch (\Bitrix\Main\LoaderException $e) {
}

// !!! Все события нужно добавлять/переносить в local/modules/zk.main/lib/event.php - устарело
// !!! Все события нужно добавлять/переносить в local/modules/native.app/lib/event.php


/**
 * Регистрация пользователя
 *
 * №1
 * Проверка пользователя по базе спама, если он присутствует в ней, отклоняем регистрацию
 *
 * №2
 * Если пользователь зарегистрировался, высылаем ему емаил с его учетными данными
 */
EventManager::getInstance()->addEventHandler('main', 'OnBeforeUserRegister', ['\\Zk\\Main\\Main\\EventHandler', 'OnBeforeUserRegister']);
EventManager::getInstance()->addEventHandler('main', 'OnAfterUserRegister', ['\\Zk\\Main\\Main\\EventHandler', 'OnAfterUserRegister']);

/**
 * Обработка товаров
 *
 * №1
 * Запишем товару минимальную цену в свойство MINIMUM_PRICE
 * Чтобы была возможность сортировать по цене
 *
 * №2
 * Если у товара имеются множественные свойства
 * Тогда разобьём их на отдельные свойства для удобства
 *
 * №3
 * Если у товара имеются кастомные множественные свойства, пример: 123^456^789
 * Тогда преобразуем их во множественные свойства
 *
 * №4 - Не актуально - работает на CRONе
 * Если пользователь подписывался на уведомление о поступлении товара
 * Тогда, когда товар появится в магазине, отошлем ему емаил
 */

// Возникает какая-то нездоровая ситуация
// Массово обновляются элементы инфоблоков
// В связи с этим, на данный момент (12.11.20) текущую функцию отключаю
// https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/8203/

// EventManager::getInstance()->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', ['\\Zk\\Main\\IBlock\\EventHandler', 'OnAfterIBlockElementUpdate']);
// EventManager::getInstance()->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', ['\\Zk\\Main\\IBlock\\EventHandler', 'OnBeforeIBlockElementUpdate']);
// EventManager::getInstance()->addEventHandler('iblock', 'OnIBlockElementSetPropertyValues', ['\\Zk\\Main\\IBlock\\EventHandler', 'OnIBlockElementSetPropertyValues']);

/**
 * Обработка товаров
 *
 * №5
 * Обновим вес товара (торгового предложения)
 */
EventManager::getInstance()->addEventHandler('catalog', 'OnBeforeProductUpdate', ['\\Zk\\Main\\Catalog\\EventHandler', 'OnBeforeProductUpdate']);


// deprecated since 2020-11-12
// EventManager::getInstance()->addEventHandler('sale', 'OnBeforeBasketAdd', ['\\Zk\\Main\\Sale\\EventHandler', 'OnBeforeBasketAdd']);


/**
 * Часто бывает, что на сайт выгружаются каталоги товаров, и например,
 * в анонсе или детальном представлении какого-то товара дублируется информация.
 * Штатно модуль поиска всё честно добавляет в поисковый индекс, не обращая информации на дубли.
 * Исправляем
 */
EventManager::getInstance()->addEventHandler('search', 'BeforeIndex', ['\\Zk\\Main\\Search\\EventHandler', 'BeforeIndex']);

/**
 * Валится очень много спама через формы
 * Будем сохранять ip-адреса тех, кто шлет спам, чтобы блокировать их
 */
// deprecated since 2020-11-12
// EventManager::getInstance()->addEventHandler('iblock', 'OnBeforeIBlockElementAdd', ['\\Zk\\Main\\IBlock\\EventHandler', 'OnBeforeIBlockElementAdd']);

function pluralForm($n, $form1, $form2, $form5){
	$n = abs($n) % 100;
	$n1 = $n % 10;
	if ($n > 10 && $n < 20) return $form5;
	if ($n1 > 1 && $n1 < 5) return $form2;
	if ($n1 == 1) return $form1;
	return $form5;
}
