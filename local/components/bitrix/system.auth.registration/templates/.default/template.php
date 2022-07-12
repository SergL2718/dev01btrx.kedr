<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$elementsResult = CUser::GetList(($by="ID"), ($order="DESC"));
$counter = 0;
while($ob = $elementsResult->Fetch()){
    //print_r($ob);
    $userInfo["LOGIN"][$counter] = $ob["LOGIN"];
    $userInfo["NAME"][$counter] = $ob["NAME"];
    $userInfo["LAST_NAME"][$counter] = $ob["LAST_NAME"];
    $userInfo["EMAIL"][$counter] = $ob["EMAIL"][0].$ob["EMAIL"][1].$ob["EMAIL"][2].$ob["EMAIL"][3].$ob["EMAIL"][4].$ob["EMAIL"][5]."****";
    $counter++;
}
?>
<div class="bx-auth">
<?
ShowMessage($arParams["~AUTH_RESULT"]);
?>
<?if($arResult["USE_EMAIL_CONFIRMATION"] === "Y" && is_array($arParams["AUTH_RESULT"]) &&  $arParams["AUTH_RESULT"]["TYPE"] === "OK"):?>
<p><?echo GetMessage("AUTH_EMAIL_SENT")?></p>
<?else:?>

<?if($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):?>
	<p><?echo GetMessage("AUTH_EMAIL_WILL_BE_SENT")?></p>
<?endif?>
<noindex>
<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform" enctype="multipart/form-data">
<?
if (strlen($arResult["BACKURL"]) > 0)
{
?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
}
?>
	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="REGISTRATION" />

<table class="data-table bx-registration-table">
	<thead>
		<tr>
			<td colspan="2"><b><?=GetMessage("AUTH_REGISTER")?></b></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><span class="starrequired">*</span><?=GetMessage("AUTH_NAME")?></td>
			<td><input type="text" onchange="checkUser();" id="USER_NAME" name="USER_NAME" maxlength="50" required="required" value="<?=$arResult["USER_NAME"]?>" class="bx-auth-input" /></td>
		</tr>
		<tr>
			<td><span class="starrequired">*</span><?=GetMessage("AUTH_LAST_NAME")?></td>
			<td><input type="text" onchange="checkUser();" name="USER_LAST_NAME" id="USER_LAST_NAME" maxlength="50" required="required" value="<?=$arResult["USER_LAST_NAME"]?>" class="bx-auth-input" /></td>
		</tr>
		<tr>
			<td><span class="starrequired">*</span><?=GetMessage("AUTH_LOGIN_MIN")?></td>
			<td><input type="text" onchange="checkUser();" id="USER_LOGIN" name="USER_LOGIN" maxlength="50" required="required" value="<?=$arResult["USER_LOGIN"]?>" class="bx-auth-input" /></td>
		</tr>
		<tr>
			<td><span class="starrequired">*</span><?=GetMessage("AUTH_PASSWORD_REQ")?></td>
			<td><input type="password" onchange="checkPass();" id="USER_PASSWORD" name="USER_PASSWORD" maxlength="50" required="required" value="<?=$arResult["USER_PASSWORD"]?>" class="bx-auth-input" autocomplete="off" />
<?if($arResult["SECURE_AUTH"]):?>
				<span class="bx-auth-secure" id="bx_auth_secure" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
					<div class="bx-auth-secure-icon"></div>
				</span>
				<noscript>
				<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
					<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
				</span>
				</noscript>
<script type="text/javascript">
document.getElementById('bx_auth_secure').style.display = 'inline-block';
</script>
<?endif?>
			</td>
		</tr>
		<tr>
			<td><span class="starrequired">*</span><?=GetMessage("AUTH_CONFIRM")?></td>
			<td><input type="password" onchange="checkPass();" id="USER_CONFIRM_PASSWORD" name="USER_CONFIRM_PASSWORD" maxlength="50" required="required" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" class="bx-auth-input" autocomplete="off" /></td>
		</tr>
		<tr>
			<td><?if($arResult["EMAIL_REQUIRED"]):?><span class="starrequired">*</span><?endif?><?=GetMessage("AUTH_EMAIL")?></td>
			<td><input type="email" id="USER_EMAIL" name="USER_EMAIL" maxlength="255" required="required" value="<?=$arResult["USER_EMAIL"]?>" class="bx-auth-input" /></td>
		</tr>
<script>

    var checkUser = function () {
        $("#USER_EMAIL").attr("placeholder", "");
        var Logins = <?php echo json_encode($userInfo["LOGIN"] );?>;
        var Names = <?php echo json_encode($userInfo["NAME"] );?>;
        var LastNames = <?php echo json_encode($userInfo["LAST_NAME"] );?>;
        var Emails = <?php echo json_encode($userInfo["EMAIL"] );?>;

        var ValueLogin = document.getElementById("USER_LOGIN").value;
        var ValueName = document.getElementById("USER_NAME").value;
        var ValueLastName = document.getElementById("USER_LAST_NAME").value;
        if(Logins.indexOf(ValueLogin) != "-1" && Names.indexOf(ValueName) != "-1" && LastNames.indexOf(ValueLastName) != "-1"){
            var id = Logins.indexOf(ValueLogin);
            var email = Emails[id];
            $("#USER_EMAIL").attr("placeholder", email);
        }
    };
    var checkPass = function () {
        var valueX = document.getElementById("USER_PASSWORD").value;
        var valueY = document.getElementById("USER_CONFIRM_PASSWORD").value;
        if (valueX != valueY) {
            jQuery("input[name='USER_PASSWORD']").addClass("error");
            jQuery("input[name='USER_CONFIRM_PASSWORD']").addClass("error");
        } else {
            jQuery("input[name='USER_PASSWORD']").removeClass("error");
            jQuery("input[name='USER_CONFIRM_PASSWORD']").removeClass("error");
        }
    };
</script>
<?// ********************* User properties ***************************************************?>
<?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
	<tr><td colspan="2"><?=strlen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB")?></td></tr>
	<?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>
	<tr><td><?if ($arUserField["MANDATORY"]=="Y"):?><span class="starrequired">*</span><?endif;
		?><?=$arUserField["EDIT_FORM_LABEL"]?>:</td><td>
			<?$APPLICATION->IncludeComponent(
				"bitrix:system.field.edit",
				$arUserField["USER_TYPE"]["USER_TYPE_ID"],
				array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField, "form_name" => "bform"), null, array("HIDE_ICONS"=>"Y"));?></td></tr>
	<?endforeach;?>
<?endif;?>
<?// ******************** /User properties ***************************************************

	/* CAPTCHA */
	if ($arResult["USE_CAPTCHA"] == "Y")
	{
		?>
		<tr>
			<td colspan="2"><b><?=GetMessage("CAPTCHA_REGF_TITLE")?></b></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
				<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
			</td>
		</tr>
		<tr>
			<td><span class="starrequired">*</span><?=GetMessage("CAPTCHA_REGF_PROMT")?>:</td>
			<td><input type="text" name="captcha_word" required="required" maxlength="50" value="" /></td>
		</tr>
		<?
	}
	/* CAPTCHA */
	?>
	</tbody>
	<tfoot>
		<tr>
			<td></td>
			<td><input type="submit" name="Register" value="<?=GetMessage("AUTH_REGISTER")?>" /></td>
		</tr>
	</tfoot>
</table>
<p><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
<p><span class="starrequired">*</span><?=GetMessage("AUTH_REQ")?></p>

<p>
<a href="<?=$arResult["AUTH_AUTH_URL"]?>" rel="nofollow"><b><?=GetMessage("AUTH_AUTH")?></b></a>
</p>

</form>
</noindex>
<script type="text/javascript">
document.bform.USER_NAME.focus();
</script>

<?endif?>
</div>
