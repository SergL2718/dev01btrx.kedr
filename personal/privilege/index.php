<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Мои привилегии");
?>


<section class="cabinet cabinet_inner">
    <div class="container">
        <div class="page-title">Личный кабинет</div>
		<?
		$APPLICATION->IncludeFile("/local/include/personal/menu.php", array("ACTIVE" => 4), array(
			"MODE" => "html",
			"NAME" => "",
			"TEMPLATE" => ""
		));
		?>
        <div class="cabinet-container">
            <div class="page-title"><a class="link-more" href="cabinet.html">НАЗАД</a>МОИ ПРИВИЛЕГИИ</div>
            <div class="cabinet-privilege">
                <div class="cabinet-privilege-status">
                    <div class="cabinet-privilege-status__card">
                        <div class="cabinet-privilege-status__sub">Ваш уровень сейчас</div>
                        <div class="cabinet-privilege-status__title">Уровень 1</div>
                        <div class="cabinet-privilege-status__image"><img src="<?=SITE_TEMPLATE_PATH?>/images/icons/smile-1.png" alt=""></div>
                    </div>
                    <div class="cabinet-privilege-status__step"></div>
                    <div class="cabinet-privilege-status__card">
                        <div class="cabinet-privilege-status__sub">Осталось накопить&nbsp;
                            <div class="bonus-line">75 зкр
                                <div class="icon icon-pine-cone"></div>
                            </div>
                        </div>
                        <div class="cabinet-privilege-status__title">Уровень 2</div>
                        <div class="cabinet-privilege-status__image"><img src="<?=SITE_TEMPLATE_PATH?>/images/icons/smile-2.png" alt=""></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="privilege">
            <div class="page-title">УРОВНИ И ПРИВИЛЕГИИ</div>
            <table class="privilege-table">
                <tr>
                    <th></th>
                    <th>
                        <div class="privilege-table__lvl"><span>УРОВЕНЬ 1</span><img src="<?=SITE_TEMPLATE_PATH?>/images/icons/smile-1.png"
                                                                                     alt=""/></div>
                    </th>
                    <th>
                        <div class="privilege-table__lvl"><span>УРОВЕНЬ 2</span><img src="<?=SITE_TEMPLATE_PATH?>/images/icons/smile-2.png"
                                                                                     alt=""/></div>
                    </th>
                    <th>
                        <div class="privilege-table__lvl"><span>УРОВЕНЬ 3</span><img src="<?=SITE_TEMPLATE_PATH?>/images/icons/smile-3.png"
                                                                                     alt=""/></div>
                    </th>
                </tr>
                <tr>
                    <td>НАКОПЛЕННЫЕ БАЛЛЫ</td>
                    <td>
                        <div class="icon icon-pine-cone"></div>
                    </td>
                    <td>
                        <div class="icon icon-pine-cone"></div>
                    </td>
                    <td>
                        <div class="icon icon-pine-cone"></div>
                    </td>
                </tr>
                <tr>
                    <td>Подарок на день рождения</td>
                    <td>
                        <div class="icon icon-pine-cone"></div>
                    </td>
                    <td>
                        <div class="icon icon-pine-cone"></div>
                    </td>
                    <td>
                        <div class="icon icon-pine-cone"></div>
                    </td>
                </tr>
                <tr>
                    <td>Бесплатная доставка</td>
                    <td class="td-mob-pr"><span>от<br> 5 000<br> руб.</span></td>
                    <td class="td-mob-pr"><span>от<br> 4 000<br> руб.</span></td>
                    <td class="td-mob-pr"><span>от<br> 3 000<br> руб.</span></td>
                </tr>
                <tr>
                    <td>Возможность купить продукцию, которая продаётся только в закрытом клубе</td>
                    <td>
                        <div class="icon icon-pine-cone"></div>
                    </td>
                    <td>
                        <div class="icon icon-pine-cone"></div>
                    </td>
                    <td>
                        <div class="icon icon-pine-cone"></div>
                    </td>
                </tr>
                <tr>
                    <td>Доступ к закрытым распродажам и эксклюзивным акциям</td>
                    <td>
                        <hr/>
                    </td>
                    <td>
                        <div class="icon icon-pine-cone"></div>
                    </td>
                    <td>
                        <div class="icon icon-pine-cone"></div>
                    </td>
                </tr>
                <tr>
                    <td>Закрытый чат с диетологом и руководителем онлайн-магазина</td>
                    <td>
                        <hr/>
                    </td>
                    <td>
                        <hr/>
                    </td>
                    <td>
                        <div class="icon icon-pine-cone"></div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</section>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
