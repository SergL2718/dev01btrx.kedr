<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Zk\Main\IBlock;


use Zk\Main\Helper;
use Zk\Main\Spam\Protect;

class EventHandler
{
    /**
     * Событие вызывается в методе CIBlockElement::Update до изменения элемента информационного блока.
     * И может быть использовано для отмены изменения или для переопределения некоторых полей.
     *
     * @param $arFields
     */
    public function OnBeforeIBlockElementUpdate(&$arFields)
    {
        // code ...
    }

    /**
     * Событие вызывается после попытки изменения элемента информационного блока методом CIBlockElement::Update.
     * Работает вне зависимости от того были ли созданы/изменены элементы непосредственно, поэтому необходимо дополнительно проверять параметр: RESULT_MESSAGE.
     *
     * @param $arFields
     */
    public function OnAfterIBlockElementUpdate(&$arFields)
    {
        // Возникает какая-то нездоровая ситуация
        // Массово обновляются элементы инфоблоков
        // В связи с этим, на данный момент (12.11.20) текущую функцию отключаю
        // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/8203/
        return true;
        $element = new Element($arFields);

        // Если пользователь подписывался на уведомление о поступлении товара
        // Тогда, когда товар появится в магазине, отошлем ему емаил
        // Функция перенесена в агент
        //$element->checkRemainder();

        // Запишем товару минимальную цену в свойство MINIMUM_PRICE
        // Чтобы была возможность сортировать по цене
        $element->setMinimumPrice();

        // Разобьём множественные свойства на простые
        $element->splitProperties();

        $element->handleMultiplePropertiesValues1C();

        // Установим товарам из инфоблоков "Производители" и "Полный перечень"
        // Индекс сортировки такой же, как и у товаров из инфоблока "Интернет-магазин"
        $element->setSortIndex();
    }

    /**
     * Событие OnIBlockElementSetPropertyValues вызывается в момент сохранения значений свойств элемента инфоблока.
     * Событие вызывается в момент, когда уже отработали все обработчики, изменяющие данные, а также уже произошла проверка данных и идет запись в базу.
     * Изменять данные событие не позволяет.
     * Основной сценарий использования - выполнить некий код перед работой с базой, будучи уверенным, что данные в базе будут изменены.
     *
     * @param $elementId
     * @param $iblockId
     * @param $PROPERTY_VALUES
     * @param $PROPERTY_CODE
     * @param $ar_prop
     * @param $arDBProps
     */
    public function OnIBlockElementSetPropertyValues($elementId, $iblockId, $PROPERTY_VALUES, $PROPERTY_CODE, $ar_prop, $arDBProps)
    {
        // Возникает какая-то нездоровая ситуация
        // Массово обновляются элементы инфоблоков
        // В связи с этим, на данный момент (12.11.20) текущую функцию отключаю
        // https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/8203/
        return true;

        $element = new Element(['ID' => $elementId, 'IBLOCK_ID' => $iblockId, 'PROPERTY_VALUES' => $PROPERTY_VALUES]);
        // Если из 1С пришло множественное свойство
        // Тогда преобразуем его в ножественное свойство и на стороне сайта
        // Пример множественного свойства из 1С:
        // - Тип: Строка
        // - Значение: 123^456^789
        $element->handleMultipleProperties1C($ar_prop);
    }

    /**
     * Валится очень много спама через формы
     * Будем сохранять ip-адреса тех, кто шлет спам, чтобы блокировать их
     *
     * @param $arFields
     * @return bool
     *
     * @deprecated since 2020-11-12
     */
    public function OnBeforeIBlockElementAdd(&$arFields)
    {
        return true;
        if ($arFields['IBLOCK_ID'] == Helper::IBLOCK_FORM_CONTACT_ID) {
            $email = $arFields['PROPERTY_VALUES'][Helper::IBLOCK_FORM_CONTACT_PROPERTY_EMAIL]['n0']['VALUE'];
            if (!(new Protect())->check($_SERVER['REMOTE_ADDR'], $email)) {
                $GLOBALS['APPLICATION']->ThrowException('Обнаружен спам');
                return false;
            }
            $arFields['PROPERTY_VALUES'][Helper::IBLOCK_FORM_CONTACT_PROPERTY_IP] = $_SERVER['REMOTE_ADDR'];
        }
    }
}
