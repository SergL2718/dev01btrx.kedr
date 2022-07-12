<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;
?>

<div class="window__popup__wrapper">
    <div class="window__popup">
        <div class="window__content">
            <div class="window__popup__header">
                <div class="window__popup__title"><?= GetMessage('title') ?></div>
                <div class="window__popup__close"><i class="fas fa-times-circle"></i></div>
            </div>
            <div class="window__popup__body">
                <div class="image">
                    <img src="<?= $templateFolder ?>/images/subscribe.jpg">
                </div>

                <div class="form">
                    <div class="description"><?= GetMessage('description') ?></div>
                    <? $APPLICATION->IncludeComponent(
                        'bitrix:subscribe.form',
                        'market_horizontal',
                        [
                            'COMPONENT_TEMPLATE' => 'market_horizontal',
                            'USE_PERSONALIZATION' => 'Y',
                            'SHOW_HIDDEN' => 'N',
                            'PAGE' => '/personal/subscribe/',
                            'CACHE_TYPE' => 'A',
                            'CACHE_TIME' => '3600000',
                            'SHOW_RUBRICS' => 'N'
                        ],
                        false, ['ACTIVE_COMPONENT' => 'Y']
                    ) ?>
                </div>
            </div>
            <div class="window__popup__footer">
                <input type="checkbox" id="checkbox__not__show__window__popup__welcome__subscribe"
                       name="welcome-subscribe">
                <label for="checkbox__not__show__window__popup__welcome__subscribe"><?= GetMessage('notShow') ?></label><i
                        class="fas fa-question-circle" title="<?= GetMessage('notShowHint') ?>"></i>
            </div>
        </div>
    </div>
</div>