<?
$BONUS = 0;
global $USER;
if ($USER->IsAuthorized()) {
	$USER_ID = $USER->GetID();
	$rsUsers = CUser::GetList(($by = "personal_country"), ($order = "desc"), array("ID" => $USER_ID), Array("SELECT"=>Array("UF_*")));
	while ($ar_fields = $rsUsers->GetNext()) {
        //echo "<pre>"; print_r($ar_fields); echo "</pre>";
        $BONUS = $ar_fields["UF_BONUS"];
	}
}
$GLOBALS["BONUS"]["VALUE"] = $BONUS;
$GLOBALS["BONUS"]["MEASUREMENT"] = pluralForm($BONUS, "бонус", "бонуса", "бонусов");
?>

<div class="cabinet-list">
    <a class="cabinet-card<? if ($arParams["ACTIVE"] == 1) {
		echo " active";
	    $pic = "package-filled";
    }else{
		$pic = "package";
    } ?>" href="/personal/order/">
        <div class="cabinet-card__image"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icons/<?=$pic?>.svg" alt=""></div>
        <div class="cabinet-card__title">ЗАКАЗЫ</div>
    </a>
    <a class="cabinet-card<? if ($arParams["ACTIVE"] == 2) {
		echo " active";
	    $pic = "pine-cone.svg";
    }else{
		$pic = "pine-cone-e.png";
    } ?>" href="/personal/bonus/">
        <div class="cabinet-card__image"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icons/<?=$pic?>" alt=""></div>
        <div class="cabinet-card__title">БОНУСЫ</div>
        <div class="cabinet-card__sub">у вас <?=$GLOBALS["BONUS"]["VALUE"]?> <?=$GLOBALS["BONUS"]["MEASUREMENT"]?></div>
    </a>
    <a class="cabinet-card<? if ($arParams["ACTIVE"] == 3) {
		echo " active";
	    $pic = "profile-filled";
    }else{
		$pic = "profile";
    } ?>" href="/personal/profile/">
        <div class="cabinet-card__image"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icons/<?=$pic?>.svg" alt=""></div>
        <div class="cabinet-card__title">ЛИЧНЫЕ ДАННЫЕ</div>
    </a>
    <a class="cabinet-card<? if ($arParams["ACTIVE"] == 4) {
		echo " active";
	    $pic = "gift-filled";
    }else{
		$pic = "gift";
    } ?>" href="/personal/privilege/">
        <div class="cabinet-card__image"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icons/<?=$pic?>.svg" alt=""></div>
        <div class="cabinet-card__title">МОИ ПРИВИЛЕГИИ</div>
    </a>
</div>

