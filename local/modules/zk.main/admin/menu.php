<?php
/*
 * Изменено: 29 декабря 2021, среда
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

Bitrix\Main\Loader::IncludeModule(GetModuleID(__FILE__));

return [
    [
        'parent_menu' => 'global_menu_marketing',
        'text' => 'Подарки для корзины',
        'section' => 'zk.main',
        'module_id' => 'zk.main',
        'items_id' => 'menu_zk.main',
        'icon' => 'sale_menu_icon_catalog',
        'page_icon' => 'sale_menu_icon_catalog',
        'sort' => 500,
        'items' => [

            /*[
                'items_id' => 'menu_' . $settings['module']['id'] . '_hr',
                'icon' => 'sonet_menu_icon',
                'page_icon' => 'sonet_menu_icon',
                'text' => Loc::getMessage('NativeAppMenuHR'),
                'url' => 'app-hr.php?lang=' . LANG
            ],*/

            [
                'items_id' => 'menu_zk.main_integration_start_exam',
                'icon' => 'bizproc_menu_icon',
                'page_icon' => 'bizproc_menu_icon',
                'text' => 'Правила',
                'url' => 'iblock_list_admin.php?IBLOCK_ID=62&type=discount&lang=' . LANG
            ]

        ]
    ]
];
