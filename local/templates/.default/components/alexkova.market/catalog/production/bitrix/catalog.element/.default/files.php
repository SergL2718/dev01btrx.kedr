<h3 class="bxr-detail-tab-mobile-title  hidden-lg hidden-md hidden-sm"><?=$arResult["PROPERTIES"]["FILES"]["NAME"]?></h3>
<div class="bxr-detail-tab bxr-detail-files container-fluid" data-tab="element-files"><div class="row">
    <?
        if(!empty($arResult["PROPERTIES"]["FILES"]["VALUE"]))
            $arResult["PROPERTIES"]["FILES"]["VALUE"] = array_merge($arResult["PROPERTIES"]["UF_FILES"], $arResult["PROPERTIES"]["FILES"]["VALUE"]);
        else
           $arResult["PROPERTIES"]["FILES"]["VALUE"] =  $arResult["PROPERTIES"]["UF_FILES"];
        
        function GetStrFileSize($size, $round=2)
        {
            $arSize = GetMessage("FILE_SIZE_S");
            $sizes = array($arSize["b"], $arSize["Kb"], $arSize["Mb"], $arSize["Gb"], $arSize["Tb"], $arSize["Pb"], $arSize["Eb"], $arSize["Zb"], $arSize["Yb"]);
            for ($i=0; $size > 1024 && $i < count($sizes) - 1; $i++) $size /= 1024;
            return round($size,$round)." ".$sizes[$i];
        }
    ?>
    <?
    foreach ($arResult["PROPERTIES"]["FILES"]["VALUE"] as $val):
        $rsFile = CFile::GetByID($val);
        $arFile = $rsFile->Fetch();
        $arFile["PATH"] = CFile::GetPath($val);
        
        $resolution = "";
        $resolution = explode(".", $arFile["FILE_NAME"]);  
        
        if(isset($resolution[1]))
            $resolution = $resolution[1];
        
        if(!empty($arFile["DESCRIPTION"]))
            $arFile["ORIGINAL_NAME"] = $arFile["DESCRIPTION"];
    ?>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 element-file-card cols <?=$resolution;?>">
            <a download href="<?=$arFile["PATH"]?>"><span class="bxr-color-flat f-ico"></span></a>
            <a download href="<?=$arFile["PATH"]?>"><span><?=$arFile["ORIGINAL_NAME"]?></span></a><br>
                <span class="element-file-card-size"><?=GetMessage("FILE_SIZE");?>: <?=GetStrFileSize($arFile["FILE_SIZE"])?></span>              
        </div>
    <?endforeach;?>
</div></div>