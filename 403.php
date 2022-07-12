<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");
$GLOBALS["HIDE_BREADCRUMB"] = true;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("404 Not Found");
?>
<section>
	<div class="container">
		<div class="page-error"><img src="<?=SITE_TEMPLATE_PATH?>/images/icons/404.svg" alt="">
			<div class="page-error__catalog">
				<div class="block-title">Зато есть много полезных и натуральных продуктов</div>
				<a class="button button_primary" href="/catalog/">В каталог</a>
			</div>
			<div class="page-error__text">ТАКОЙ СТРАНИЦЫ У НАС НЕТ</div>
		</div>
	</div>
</section>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
