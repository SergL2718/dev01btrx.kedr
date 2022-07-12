<?
if (strlen(COption::GetOptionString($module_id, "bxr_org_name")) > 0):?>
    <div itemscope itemtype="http://schema.org/Organization" style="visibility:hidden;">
        <meta itemprop="name" content="<?= COption::GetOptionString($module_id, "bxr_org_name") ?>">
        <meta itemprop="logo"
              content="<?= SITE_SERVER_NAME . CFile::GetPath(COption::GetOptionString($module_id, "bxr_org_logo")) ?>">
        <meta itemprop="telephone" content="<?= COption::GetOptionString($module_id, "bxr_org_phone") ?>">
        <meta itemprop="faxNumber" content="<?= COption::GetOptionString($module_id, "bxr_org_fax") ?>">
        <meta itemprop="email" content="<?= COption::GetOptionString($module_id, "bxr_org_email") ?>">
        <meta itemprop="description" content="<?= COption::GetOptionString($module_id, "bxr_org_description") ?>">
        <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress" style="visibility:hidden;">
            <meta itemprop="streetAddress"
                  content="<?= COption::GetOptionString($module_id, "bxr_org_address_street") ?>">
            <meta itemprop="postalCode" content="<?= COption::GetOptionString($module_id, "bxr_org_address_zip") ?>">
            <meta itemprop="addressLocality"
                  content="<?= COption::GetOptionString($module_id, "bxr_org_address_city") ?>">
        </div>
    </div>
<? endif; ?>
