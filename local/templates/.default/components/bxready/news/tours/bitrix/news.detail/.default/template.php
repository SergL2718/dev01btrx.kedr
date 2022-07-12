<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true);
//echo "<pre>";print_r($arResult);echo "</pre>";
$excludeProps = array('PRICE', 'OLD_PRICE', "MORE_URLS", "VIDEO");

$QueryTitle = COption::GetOptionString('alexkova.corporate', 'query_button_title', GetMessage('QUERY_BUTTON_TITLE'));
$SaleTitle = COption::GetOptionString('alexkova.corporate', 'query_button_title', GetMessage('SALE_BUTTON_TITLE'));

?>
<? if (in_array("DATE_ACTIVE_FROM", $arParams["DETAIL_FIELD_CODE"]) || in_array("DATE_ACTIVE_TO", $arParams["DETAIL_FIELD_CODE"])): ?>
    <div class="date-news">
        <? if (in_array("DATE_ACTIVE_FROM", $arParams["DETAIL_FIELD_CODE"])): ?>

            <?= $arResult["ACTIVE_FROM"] ?>

        <? endif; ?>
        <? if (in_array("DATE_ACTIVE_TO", $arParams["DETAIL_FIELD_CODE"])): ?>

            / <?= $arResult["DATE_ACTIVE_TO"] ?>

        <? endif; ?>
    </div>
<? endif; ?>

<? if (is_array($arResult["DETAIL_PICTURE"])): ?>
    <div class="bxr-news-image">
        <img src="<?= $arResult["DETAIL_PICTURE"]["SRC"] ?>">
    </div>
<? endif; ?>

<? if (count($arResult["FILES"]) > 0
    || count($arResult["LINKS"]) > 0
    || count($arResult["VIDEO"]) > 0): ?>


    <ul class="nav nav-tabs" role="tablist" id="details">

        <li role="presentation" class="active"><a href="#description" aria-controls="description" role="tab"
                                                  data-toggle="tab"><?= GetMessage("DETAIL_TEXT_DESC") ?></a></li>

        <? if (count($arResult["VIDEO"]) > 0): ?>
            <li role="presentation"><a href="#video" aria-controls="video" role="tab"
                                       data-toggle="tab"><?= GetMessage("VIDEO_TAB_DESC") ?></a></li>
        <? endif; ?>
        <? if (count($arResult["FILES"]) > 0): ?>
            <li role="presentation"><a href="#files" aria-controls="files" role="tab"
                                       data-toggle="tab"><?= GetMessage("CATALOG_FILES") ?></a></li>
        <? endif; ?>
        <? if (count($arResult["LINKS"]) > 0): ?>
            <li role="presentation"><a href="#links" aria-controls="video" role="tab"
                                       data-toggle="tab"><?= GetMessage("LINKS_TAB_DESC") ?></a></li>
        <? endif; ?>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="description">
            <hr/><? echo $arResult["DETAIL_TEXT"]; ?>
        </div>

        <? if (count($arResult["FILES"]) > 0): ?>
            <div id="files" class="element-files tb20 tab-pane fade" role="tabpanel">
                <hr/>
                <? foreach ($arResult["FILES"] as $val): ?>

                    <? $template = "file_element";
                    $arElementDrawParams = array(
                        "DISPLAY_VARIANT" => $template,
                        "ELEMENT" => array(
                            "NAME" => $val["ORIGINAL_NAME"],
                            "LINK" => $val["SRC"],
                            "CLASS_NAME" => $val["EXTENTION"]
                        )
                    );
                    ?>
                    <?
                    $APPLICATION->IncludeComponent(
                        "alexkova.corporate:element.draw",
                        ".default",
                        $arElementDrawParams,
                        false
                    )
                    ?>

                <? endforeach; ?>

            </div>
            <div class="clearfix"></div>
        <? endif; ?>

        <? if (count($arResult["LINKS"]) > 0): ?>
            <div id="links" class="element-files tb20 tab-pane fade" role="tabpanel">
                <hr/>
                <? foreach ($arResult["LINKS"] as $val): ?>

                    <? $template = "glyph_links";
                    $arElementDrawParams = array(
                        "DISPLAY_VARIANT" => $template,
                        "ELEMENT" => array(
                            "NAME" => $val["TITLE"],
                            "LINK" => $val["LINK"],
                            "GLYPH" => array("GLYPH_CLASS" => "glyphicon-chevron-right"),
                            "TARGET" => "_blank"
                        )
                    );
                    ?>
                    <?
                    $APPLICATION->IncludeComponent(
                        "alexkova.corporate:element.draw",
                        ".default",
                        $arElementDrawParams,
                        false
                    )
                    ?>

                <? endforeach; ?>

            </div>
            <div class="clearfix"></div>
        <? endif; ?>

        <? if (count($arResult["VIDEO"]) > 0): ?>
            <div id="video" class="element-files tb20 tab-pane fade" role="tabpanel">
                <hr/>
                <? foreach ($arResult["VIDEO"] as $val): ?>

                    <? $template = "video_card";
                    $arElementDrawParams = array(
                        "DISPLAY_VARIANT" => $template,
                        "ELEMENT" => array(
                            "VIDEO" => $val["LINK"],                  //������ �� �����
                            "VIDEO_IMG" => '',               //������ �� ��������
                            "VIDEO_IMG_WIDTH" => '150',         //������ �������� ��� �����
                            "NAME" => $val["TITLE"]
                        )
                    );


                    ?>
                    <div class="col-lg-3">
                        <?
                        $APPLICATION->IncludeComponent(
                            "alexkova.corporate:element.draw",
                            ".default",
                            $arElementDrawParams,
                            false
                        )
                        ?>
                    </div>

                <? endforeach; ?>

            </div>
            <div class="clearfix"></div>
        <? endif; ?>
    </div>

    <script>
        $(function () {
            $('#details a').click(function (e) {
                e.preventDefault();
                $(this).tab('show')
            })
        })
    </script>
<? else: ?>
    <? echo $arResult["DETAIL_TEXT"]; ?>

    <h3>Заказать тур</h3>
    <? $APPLICATION->IncludeComponent(
        "altasib:feedback.form",
        ".default",
        Array(
            "ACTIVE_ELEMENT" => "Y",
            "ADD_HREF_LINK" => "Y",
            "ALX_LINK_POPUP" => "N",
            "BBC_MAIL" => $arResult["PROPERTIES"]["EMAIL"]["VALUE"],
            "CAPTCHA_TYPE" => "default",
            "CATEGORY_SELECT_NAME" => "Выберите категорию",
            "CHANGE_CAPTCHA" => "N",
            "CHECKBOX_TYPE" => "CHECKBOX",
            "CHECK_ERROR" => "Y",
            "COLOR_OTHER" => "#009688",
            "COLOR_SCHEME" => "BRIGHT",
            "COLOR_THEME" => "c4",
            "COMPONENT_TEMPLATE" => ".default",
            "EVENT_TYPE" => "ALX_FEEDBACK_FORM",
            "FB_TEXT_NAME" => "Комментарий",
            "FB_TEXT_SOURCE" => "PREVIEW_TEXT",
            "FORM_ID" => "61",
            "IBLOCK_ID" => "61",
            "IBLOCK_TYPE" => "altasib_feedback",
            "INPUT_APPEARENCE" => array(0 => "DEFAULT",),
            "JQUERY_EN" => "jquery",
            "LINK_SEND_MORE_TEXT" => "Отправить ещё один запрос",
            "LOCAL_REDIRECT_ENABLE" => "N",
            "MASKED_INPUT_PHONE" => array(0 => "PHONE",),
            "MESSAGE_OK" => "Мы свяжемся с Вами в ближайшее время.",
            "NAME_ELEMENT" => "ALX_DATE",
            "NOT_CAPTCHA_AUTH" => "Y",
            "PROPERTY_FIELDS" => ["FIO", "PHONE", "EMAIL", "NUMBER_MEMBERS", "FEEDBACK_TEXT", "TOUR"],
            "PROPERTY_FIELDS_REQUIRED" => ["FIO", "PHONE", "EMAIL", "NUMBER_MEMBERS"],
            "PROPS_AUTOCOMPLETE_EMAIL" => array(0 => "EMAIL",),
            "PROPS_AUTOCOMPLETE_NAME" => array(0 => "FIO",),
            "PROPS_AUTOCOMPLETE_PERSONAL_PHONE" => array(0 => "PHONE",),
            "PROPS_AUTOCOMPLETE_VETO" => "N",
            "SECTION_FIELDS_ENABLE" => "N",
            "SECTION_MAIL_ALL" => "office@megre.ru",
            "SEND_IMMEDIATE" => "Y",
            "SEND_MAIL" => "N",
            "SHOW_LINK_TO_SEND_MORE" => "N",
            "SHOW_MESSAGE_LINK" => "Y",
            "USERMAIL_FROM" => "N",
            "USER_CONSENT" => "Y",
            "USER_CONSENT_ID" => "1",
            "USER_CONSENT_INPUT_LABEL" => "",
            "USER_CONSENT_IS_CHECKED" => "N",
            "USER_CONSENT_IS_LOADED" => "N",
            "USE_CAPTCHA" => "Y",
            "WIDTH_FORM" => "50%"
        )
    ); ?>

    <script>
        var tour = document.getElementById('TOUR_FID611');
        tour.value = "<?=$arResult['NAME']?>";
    </script>
<? endif; ?>