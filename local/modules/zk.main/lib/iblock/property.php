<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Zk\Main\IBlock;


class Property
{
    const MINIMUM_PRICE = 'MINIMUM_PRICE';
    const CML2_TRAITS = 'CML2_TRAITS';
    const CML2_TRAITS_VES = 'CML2_TRAITS_VES';

    public static function set($elementId, $code, $value)
    {
        \CIBlockElement::SetPropertyValueCode($elementId, $code, $value);
    }
}
