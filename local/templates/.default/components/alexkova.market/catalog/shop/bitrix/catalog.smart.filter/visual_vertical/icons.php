<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @updated 28.05.2020, 10:02
 * @copyright 2011-2020
 */

$iconPropCodes = array(
	"SPECIALOFFER",
	"NEWPRODUCT",
	"SALELEADER",
	"RECOMMENDED"
);
$icons = array();
foreach ($arResult["ITEMS"] as $key => $arItem):
	if(!in_array($arItem["CODE"],$iconPropCodes))
		continue;
	$icons[] = $arItem;
endforeach;

if(empty($icons))
	return;
?>
<div class="bx_filter_container icon">
	<span class="bx_filter_container_modef"></span>
<?foreach ($icons as $arItem):
		if(empty($arItem["VALUES"]) || isset($arItem["PRICE"]))
			continue;

		switch($arItem["CODE"])
		{
			case 'SPECIALOFFER':
				$iconCode = 'sale';
				break;
			case 'NEWPRODUCT':
				$iconCode = 'new';
				break;
			case 'SALELEADER':
				$iconCode = 'hit';
				break;
			case 'RECOMMENDED':
				$iconCode = 'rec';
				break;
		}

		foreach($arItem["VALUES"] as $val => $ar):?>
				<div class="emarket-media-label-area pull-left" id="filter_icon_<?=$ar["CONTROL_ID"]?>">
					<div
						class="emarket-label emarket-label-<?=$iconCode?> <?=$ar["CHECKED"]?'active':''?>"
						title="<?=GetMessage("FILTER_MESSAGE_".$arItem["CODE"]."_TITLE")?>"
						onclick="smartFilter.iconClick(this)"
						></div>
					<input
						type="checkbox"
						value="<?echo $ar["HTML_VALUE"]?>"
						name="<?echo $ar["CONTROL_NAME"]?>"
						id="<?echo $ar["CONTROL_ID"]?>"
						<?echo $ar["CHECKED"]? 'checked="checked"': ''?>
						onclick="smartFilter.click(this)"
						/>
				</div>
			<?endforeach;
	endforeach;
	?>
	<div class="clear"></div>
	<div class="filter-separator" style="margin-top: 10px"></div>
</div>
