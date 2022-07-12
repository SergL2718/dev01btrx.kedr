<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

foreach ($arResult['TREE'] as &$item) {
    // manufacturers
    if (trim($item['LINK'], '/') == 'manufacturers') {
        unset($item['CHILDREN']);
    }
    // cooperation
    if (trim($item['LINK'], '/') == 'cooperation') {
        foreach ($item['CHILDREN'] as &$child) {

            $code = explode('/', $child['LINK'])[2];

            switch ($code) {
                case 'manufacturers-patrimonial-estates':
                    $imgId = 1205;
                    break;
                case 'dealers':
                    $imgId = 1204;
                    break;
                case 'wholesalers-retailers':
                    $imgId = 1206;
                    break;
            }

            $child['IMG'] = $imgId;
            $child['PARAMS'] = [
                'FROM_IBLOCK' => '',
                'IS_PARENT' => '',
                'PICTURE' => $imgId,
                'DETAIL_PICTURE' => '',
                'ico_light' => '',
                'ico_dark' => '',
                'ico_color' => '',
                'DEPTH_LEVEL' => 1
            ];
        }
    }
}