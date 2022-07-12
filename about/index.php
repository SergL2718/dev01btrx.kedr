<?php
/*
 * Изменено: 15 декабря 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

/**
 * @global CMain $APPLICATION
 */

use Bitrix\Main\Page\Asset;

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

// Чтобы пока еще радел не готов, то чтобы никто не мог зайти, через адресную строку

//LocalRedirect('/company/');
if (!\Native\App\Template::isNewVersion()) {

}

Asset::getInstance()->addCss('/' . basename(__DIR__) . '/style.css');
$APPLICATION->SetTitle('Наша история');
$APPLICATION->SetPageProperty('title', 'Наша история');
$APPLICATION->SetPageProperty('description', 'История компании Звенящие Кедры');
$APPLICATION->SetPageProperty('keywords', 'ООО Звенящие Кедры, о компании, история');
?>
	<div class="container">
		<h1 class="page-title"><?= $APPLICATION->GetTitle() ?></h1>
		<div class="greeting-wrapper">
			<div class="greeting-image">
				<img src="./images/greeting-nina.jpeg" alt="">
			</div>
			<div class="greeting-content">
				<div class="greeting-title mb-4 pb-2"><span>Всем привет!</span></div>
				<p>
					Я <b>Нина Мегре</b>, внучка <b>Владимира Мегре</b>, автора книг <b>«Звенящие Кедры России»</b>.<br>
					Всё, что вы видите на этом сайте – это воплощение идей и ценностей, заложенных в книгах. А я счастлива быть продолжателем этой традиции.
				</p>
				<p>
					<b>Звенящие Кедры</b> – это знак качества, объединивший десятки семейных производств из родовых поместий и частных подворий. Наша семья Мегре – один из таких производителей. Мы не гонимся за объемами, мы делаем продукт для себя и своих друзей, для вас.
				</p>
				<p>Хочу поделиться с вами историей нашего производства,
					<span>сейчас больше известного как <b>Кедровый Дом Мегре</b>.</span></p>
			</div>
		</div>
		<div class="page-title my-5 py-2">ХРОНОЛОГИЯ</div>
		<div class="chronology-wrapper">
			<div class="chronology-item">
				<div class="chronology-year">1996</div>
				<div class="chronology-content">
					<p>
						<b>Вышла первая книга Владимира Мегре «Анастасия». Она и стала отправной точкой всего. К нам домой тысячами приходили письма с благодарностями от читателей.</b> Тогда и стало ясно, что тема экологичной жизни, поднятая в книге, откликается и очень нужна людям.
					</p>
					<div class="chronology-content-arrow-down">
						<img src="./images/arrow-down.png" alt="">
					</div>
				</div>
			</div>
			<div class="chronology-item">
				<div class="chronology-year">1997</div>
				<div class="chronology-content">
					<p>
						Мой отец Сергей Мегре вдохновился идеями книг и начал путь воссоздания старинной сибирской традиции изготовления настоящего кедрового масла. Сначала в Новосибирске, на Заводе Медпрепаратов,
						<b>но очень быстро стало понятно, то самое масло из книг можно сделать только в родной среде, в тайге</b>.
					</p>
					<div class="chronology-content-arrow-down">
						<img src="./images/arrow-down.png" alt="">
					</div>
				</div>
			</div>
			<div class="chronology-item">
				<div class="chronology-year">2000</div>
				<div class="chronology-content">
					<p>
						После выхода третьей книги «Пространство любви» стали появляться родовые поместья, где люди стремились создавать продукты с чистой доброй энергетикой, без химии и промышленных технологий. В большинстве своем эти продукты тоже созданы по восстановленным традициям предков.
						<b>Со временем их стало много, и мы решили объединить их усилия под единым знаком «Звенящие кедры России»</b>.
					</p>
					<p>
						Это огромный ассортимент из природных даров, и он ещё расширяется. Подушки из можжевельника, косметика из масел и пчелиного воска, деревянная посуда, травяные сборы и чаи, живые злаки, экологически чистый мёд ...
					</p>
					<div class="chronology-content-arrow-down">
						<img src="./images/arrow-down.png" alt="">
					</div>
				</div>
			</div>
			<div class="chronology-item">
				<div class="chronology-year">2003</div>
				<div class="chronology-content">
					<p>
						<b>Наше кедровое производство перебралось в таёжную деревню</b>. Это в 100 километрах от Новосибирска, на границе с Томской областью. Кстати, первым продуктом, который был выпущен, стало не масло, а кедровая шишка для посадки с инструкцией, упакованная в коробочку.
					</p>
					<p>
						<b>Это и сейчас небольшое домашнее производство</b>, где все делается вручную или с применением деревянного оборудования. Работают здесь деревенские жители, размерено, непринуждённо и как-то по-семейному тепло.
					</p>
					<div class="chronology-content-arrow-down">
						<img src="./images/arrow-down.png" alt="">
					</div>
				</div>
			</div>
			<div class="chronology-item">
				<div class="chronology-year">2006</div>
				<div class="chronology-content">
					<p>
						У нас появилась собственная тайга.
						<b>Таёжный квартал площадью 127 гектаров был вверен нам государством в долгосрочную аренду. Несмотря на то, что далось это непросто, этот шаг открыл нам новые кедровые тайны и вывел на новый уровень взаимоотношений с тайгой.</b> Теперь мы ухаживаем за частью тайги, оберегаем от вредителей, защищаем от охотников и пожаров. Здесь мы собираем шишку и живицу для масла и остальной продукции.
					</p>
					<div class="chronology-content-arrow-down">
						<img src="./images/arrow-down.png" alt="">
					</div>
				</div>
			</div>
			<div class="chronology-item">
				<div class="chronology-year">2011</div>
				<div class="chronology-content">
					<p>
						<b>Построен Кедровый дом, или дом для гостей</b>. Так папа воплотил идею, чтобы каждый желающий познакомиться с сибирской тайгой, мог приехать сюда, проникнуться этой необыкновенной энергетикой и увидеть вживую, как делается кедровое масло по древней традиции. К тому моменту мы уже восстановили эту технологию до мельчайших подробностей.
					</p>
					<div class="chronology-content-arrow-down">
						<img src="./images/arrow-down.png" alt="">
					</div>
				</div>
			</div>
			<div class="chronology-item">
				<div class="chronology-year">2016</div>
				<div class="chronology-content">
					<p>
						<b>Родился Эликсир Кедра, который позже мы запатентовали под именем</b>
						<a href="/catalog/eliksir/">MEGRE Elixir</a>. Он уникальный во всех проявлениях, такого в мире точно нет, ни по силе воздействия, ни по эффекту.
						<b>Многие врачи традиционной медицины недоумевают, как он излечивает то, что было признано неизлечимым</b>. Кстати, именно благодаря появлению Эликсира удалось создать духи, о которых говорила Анастасия в книгах. Он стал ключом к разгадке их состава.
					</p>
					<div class="chronology-content-arrow-down">
						<img src="./images/arrow-down.png" alt="">
					</div>
				</div>
			</div>
			<div class="chronology-item">
				<div class="chronology-year">2017</div>
				<div class="chronology-content">
					<p>
						<b>Построен Дом для шишки</b>. Прежде, чем реализовать эту задумку, папа много лет собирал знания о том, как в старину хранили шишку, как сохраняли кедровую энергию, чтобы масло, да и всё остальное из кедра, несло заложенные природой целебные свойства. Так мы смогли максимально приблизиться к тем традициям.
					</p>
				</div>
			</div>
		</div>
		<div class="my-5 pt-3">
			<div><img src="./images/family.jpeg" alt="" width="100%"></div>
			<p class="mt-5" style="max-width: 840px;">
				Конечно, все члены нашей семьи Мегре пользуются продуктами собственного производства, потому что делали их, прежде всего, для себя. У каждого есть свои фавориты, если хотите узнать из первых рук, читайте в
				<a href="/articles/blog/">Блоге</a> статьи:
			</p>
		</div>
		<div class="articles-wrapper my-5">
			<div class="article-wrapper">
				<div class="article-image"><img src="./images/nina.jpeg?v=2" alt="Нина Мегре"></div>
				<div class="article-title">Топ-10 продуктов Нины Мегре</div>
				<p>Это мои любимые продукты.</p>
				<div class="article-detail"><a href="/articles/blog/top-10-niny-megre/"><span>ЧИТАТЬ</span></a></div>
			</div>
			<div class="article-wrapper">
				<div class="article-image"><img src="./images/vladimir.jpeg" alt="Владимир Мегре"></div>
				<div class="article-title">День рождения Владимира Мегре</div>
				<p>Дедушкин топ. Хотя он говорит, что всё любит одинаково.</p>
				<div class="article-detail">
					<a href="/articles/blog/23-iyulya-den-zemli-i-den-rozhdeniya-vladimira-megre/"><span>ЧИТАТЬ</span></a>
				</div>
			</div>
			<div class="article-wrapper">
				<div class="article-image"><img src="./images/polina.jpeg" alt="Полина Мегре"></div>
				<div class="article-title">Полина Мегре<br>Топ-5 любимчиков</div>
				<p>Здесь мамины секреты.</p>
				<div class="article-detail">
					<a href="/articles/blog/polina-megre-top-5-lyubimchikov/"><span>ЧИТАТЬ</span></a></div>
			</div>
			<div class="article-wrapper">
				<div class="article-image"><img src="./images/sergey.jpeg" alt="Сергей Мегре"></div>
				<div class="article-title">Сергей Мегре – 5 любимых продуктов</div>
				<p>Мужской взгляд, то, что любит папа.</p>
				<div class="article-detail">
					<a href="/articles/blog/sergey-megre-5-lyubimykh-produktov/"><span>ЧИТАТЬ</span></a>
				</div>
			</div>
		</div>
		<div class="greeting-footer my-5 pt-4">
			<p>
				Я рада продолжать дело своего дедушки и родителей, и хочу сделать всё, чтобы эта преемственность ушла далеко в будущее, а нам удалось сохранить чистоту изначальных идей.
			</p>
			<p><span>Нина Мегре</span></p>
		</div>
	</div>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
