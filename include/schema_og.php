<?
$module_id = "alexkova.market";
if (strlen(COption::GetOptionString($module_id, "bxr_org_name")) > 0):
    if (COption::GetOptionString($module_id, "bxr_org_opengraph") == "Y"):?>
        <meta property="og:title" content="<?= $GLOBALS['APPLICATION']->ShowTitle() ?>"/>
        <meta property="og:description" content="<?= $GLOBALS['APPLICATION']->ShowProperty('description') ?>"/>
        <meta property="og:image" content="https://<?= SITE_SERVER_NAME . CFile::GetPath(COption::GetOptionString($module_id, "bxr_org_logo")) ?>">
        <meta property="og:type" content="website"/>
        <meta property="og:url" content="https://<?= SITE_SERVER_NAME . $GLOBALS['APPLICATION']->GetCurDir() ?>"/>
    <?endif;
endif; ?>
