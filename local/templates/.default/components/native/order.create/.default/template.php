<?php
/*
 * Изменено: 29 июня 2021, вторник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @var $APPLICATION
 * @var $USER
 * @var $arResult
 */

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

$arResult['JS']['path']['image']['ruble'] = $this->getFolder() . '/images/ruble.png';
?>
<div class="order-component mb-5">
    <div id="order-component-data" style="overflow: hidden">
        <div class="col-sm-12 mb-5">
            <div class="order-title">
                <h1><?= $APPLICATION->GetTitle() ?></h1>
            </div>
        </div>
        <div class="col-sm-12 mt-0 mt-lg-3 mb-0 mb-lg-5 pb-0 pb-lg-4">
            <div class="order-steps">
                <?php $counter = 1 ?>
                <?php foreach ($arResult['STEP']['list'] as $step): ?>
                    <a href="javascript:void(0)"
                       class="order-step<?php if ($arResult['STEP']['current'] === $step): ?> active<?php endif ?>"
                       data-code="<?= $step ?>" data-controller="setStep">
                        <div class="order-step-icon"></div>
                        <div class="order-step-marker"></div>
                        <div class="order-step-label"><?= $counter . '. ' . Loc::getMessage('step_' . $step) ?></div>
                    </a>
                    <?php $counter++ ?>
                <?php endforeach ?>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="order-content">
                <div class="order-content-left-column">
                    <?php foreach ($arResult['STEP']['list'] as $step): ?>
                        <div class="order-data<?php if ($arResult['STEP']['current'] === $step): ?> active<?php endif ?>"
                             data-code="<?= $step ?>">
                            <?php require $step . '.php' ?>
                        </div>
                    <?php endforeach ?>
                </div>
                <div class="order-content-right-column">
                    <?php require 'sidebar.php' ?>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="mobile-panel">
                <?php require 'mobile.php' ?>
            </div>
        </div>
    </div>

    <div id="order-component-complete" style="display: none">
        <div class="col-sm-12">
            <div class="order-complete">
                <?php require 'complete.php' ?>
            </div>
        </div>
    </div>
</div>

<script>
    OrderComponent.init.component(<?= CUtil::PhpToJSObject($arResult['JS']) ?>);
</script>
