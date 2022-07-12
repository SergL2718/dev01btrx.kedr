<?php

    $arParams['DISPLAY_VARIANT'] = 'modern_card_vertical';
    $arParams['~DISPLAY_VARIANT'] = 'modern_card_vertical';
    
    $arElement = array (
        'CARD_BORDER' => 'Y',
        'TYPE_CARD' => 'white',
        'IMAGE_BORDER' => 'img-none',
        'BORDER_COLOR' => 'border-transparent',
        'TYPE_IMG' => 'glyph',
        'IMAGE_SIZE_HEIGHT' => '50',
        //'IMAGE_MAX_WIDTH' => '35',
        'USER_VIEW_MODE' => 'modern_card_vertical',
        'IMAGE_MAX_WIDTH' => '45',
        'CARD_ALIGN' => 'center',
        'SHOW_DESCRIPTION' => 'Y',
        'HIDE_LINKS' => 'N',
        'FANCYBOX_TYPE' => 'N',
        'SHOW_ELEMENT_BUTTONS' => 'N',
        'SHOW_ELEMENT_URL' => 'N',
        'IMAGE_COLOR' => 'image-white',
        'IMAGE_SIZE_WIDTH' => '25',
        '~CARD_BORDER' => 'Y',
        '~TYPE_CARD' => 'white',
        '~IMAGE_BORDER' => 'img-none',
        '~TYPE_IMG' => 'glyph',
        '~IMAGE_SIZE_HEIGHT' => '50',
        '~CARD_ALIGN' => 'center',
        '~SHOW_DESCRIPTION' => 'N',
        '~HIDE_LINKS' => 'N',
        '~FANCYBOX_TYPE' => 'N',
        '~SHOW_ELEMENT_BUTTONS' => 'N',
        '~SHOW_ELEMENT_URL' => 'N',
        '~IMAGE_COLOR' => 'image-white',
        '~IMAGE_SIZE_WIDTH' => '25',
        '~~CARD_BORDER' => 'Y',
        '~~TYPE_CARD' => 'white',
        '~~IMAGE_BORDER' => 'img-none',
        '~~TYPE_IMG' => 'glyph',
        '~~IMAGE_SIZE_HEIGHT' => '50',

        '~~CARD_ALIGN' => 'center',
        '~~SHOW_DESCRIPTION' => 'N',
        '~~HIDE_LINKS' => 'N',
        '~~FANCYBOX_TYPE' => 'N',
        '~~SHOW_ELEMENT_BUTTONS' => 'N',
        '~~SHOW_ELEMENT_URL' => 'N',
        '~~IMAGE_COLOR' => 'image-white',
        '~~IMAGE_SIZE_WIDTH' => '25',
        'SHOW_ELEMENT_URL_TITLE' => '',
        'CLASS_NAME' => 'flat_vertical_va1 colored_block glyph-white',
    );
    
    foreach($arElement as $cell => $val) {
        $arParams['ELEMENT'][$cell] = $val;
    }
    
?>
