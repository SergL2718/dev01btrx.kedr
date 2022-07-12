<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="bx-auth">

    <?
    ShowMessage($arParams["~AUTH_RESULT"]);
    ?>

    <? if ($arParams['AUTH_RESULT']['TYPE'] === 'OK'): ?>
<?
if (isset($_POST['USER_LOGIN']) && strlen($_POST['USER_CONFIRM_PASSWORD']) > 0) {
    $login_password_correct = false;
    $rsUser = CUser::GetByLogin($_POST['USER_LOGIN']);
    if ($arUser = $rsUser->Fetch()) {
        if (strlen($arUser["PASSWORD"]) > 32) {
            $salt = substr($arUser["PASSWORD"], 0, strlen($arUser["PASSWORD"]) - 32);
            $db_password = substr($arUser["PASSWORD"], -32);
        } else {
            $salt = "";
            $db_password = $arUser["PASSWORD"];
        }
        $user_password = md5($salt . $_POST['USER_CONFIRM_PASSWORD']);
        if ($user_password == $db_password) {
            $login_password_correct = true;
            $GLOBALS['USER']->Authorize($arUser['ID']);
            header('Location: /');
        }
    }
}
?>
    <div style="display: none">
        <? endif ?>
        <form method="post" action="<?= $arResult["AUTH_FORM"] ?>" name="bform">
            <? if (strlen($arResult["BACKURL"]) > 0): ?>
                <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
            <? endif ?>
            <input type="hidden" name="AUTH_FORM" value="Y">
            <input type="hidden" name="TYPE" value="CHANGE_PWD">
            <table class="data-table bx-changepass-table">
                <thead>
                <tr>
                    <td colspan="2"><b><?= GetMessage("AUTH_CHANGE_PASSWORD") ?></b></td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><span class="starrequired">*</span><?= GetMessage("AUTH_LOGIN") ?></td>
                    <td><input type="text" name="USER_LOGIN" maxlength="50" value="<?= $arResult["LAST_LOGIN"] ?>"
                               class="bx-auth-input"/></td>
                </tr>
                <tr>
                    <td><span class="starrequired">*</span><?= GetMessage("AUTH_CHECKWORD") ?></td>
                    <td><input type="text" name="USER_CHECKWORD" maxlength="50"
                               value="<?= $arResult["USER_CHECKWORD"] ?>"
                               class="bx-auth-input"/></td>
                </tr>
                <tr>
                    <td><span class="starrequired">*</span><?= GetMessage("AUTH_NEW_PASSWORD_REQ") ?></td>
                    <td><input type="password" name="USER_PASSWORD" maxlength="50"
                               value="<?= $arResult["USER_PASSWORD"] ?>"
                               class="bx-auth-input" autocomplete="off"/>
                        <? if ($arResult["SECURE_AUTH"]): ?>
                            <span class="bx-auth-secure" id="bx_auth_secure"
                                  title="<? echo GetMessage("AUTH_SECURE_NOTE") ?>" style="display:none">
					<div class="bx-auth-secure-icon"></div>
				</span>
                            <noscript>
				<span class="bx-auth-secure" title="<? echo GetMessage("AUTH_NONSECURE_NOTE") ?>">
					<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
				</span>
                            </noscript>
                            <script type="text/javascript">
                                document.getElementById('bx_auth_secure').style.display = 'inline-block';
                            </script>
                        <? endif ?>
                    </td>
                </tr>
                <tr>
                    <td><span class="starrequired">*</span><?= GetMessage("AUTH_NEW_PASSWORD_CONFIRM") ?></td>
                    <td><input type="password" name="USER_CONFIRM_PASSWORD" maxlength="50"
                               value="<?= $arResult["USER_CONFIRM_PASSWORD"] ?>" class="bx-auth-input"
                               autocomplete="off"/>
                    </td>
                </tr>
                <? if ($arResult["USE_CAPTCHA"]): ?>
                    <tr>
                        <td></td>
                        <td>
                            <input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
                            <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>"
                                 width="180"
                                 height="40" alt="CAPTCHA"/>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="starrequired">*</span><? echo GetMessage("system_auth_captcha") ?></td>
                        <td><input type="text" name="captcha_word" maxlength="50" value=""/></td>
                    </tr>
                <? endif ?>
                </tbody>
                <tfoot>
                <tr>
                    <td></td>
                    <td><input type="submit" name="change_pwd" value="<?= GetMessage("AUTH_CHANGE") ?>"/></td>
                </tr>
                </tfoot>
            </table>

            <p><? echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"]; ?></p>
            <p><span class="starrequired">*</span><?= GetMessage("AUTH_REQ") ?></p>
            <p>
                <a href="<?= $arResult["AUTH_AUTH_URL"] ?>"><b><?= GetMessage("AUTH_AUTH") ?></b></a>
            </p>

        </form>

        <? if ($arParams['AUTH_RESULT']['TYPE'] === 'OK'): ?>
    </div>
<? endif ?>
    <script type="text/javascript">
        document.bform.USER_LOGIN.focus();
    </script>
</div>
