<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

<h1 class="text-center"><?=$APPLICATION->GetTitle().' ('.count($arResult['ITEMS']).')'?></h1>
<div class="text-center"><a href="/dealers/" class="show-list">Посмотреть список</a></div>

<? foreach ($arResult['ITEMS'] as $ar) {
    $tmp = $ar['PROPERTIES'];
    if (!$tmp['YANDEX_MAP']['VALUE']) {
        continue;
    }
    if ($tmp['WWW']['VALUE']) {
        $tmp['WWW']['VALUE'] = explode('://', $tmp['WWW']['VALUE']);
        $tmp['WWW']['VALUE'] = $tmp['WWW']['VALUE'][1] ? $tmp['WWW']['VALUE'][1] : $tmp['WWW']['VALUE'][0];
        $tmp['WWW']['VALUE'] = 'http://' . $tmp['WWW']['VALUE'];
    }
    $arResult['DEALERS'][] = [
        'SHOP' => $tmp['SHOP']['VALUE'],
        'CITY' => $tmp['CITY']['VALUE'],
        'ADDRESS' => $tmp['ADDRESS']['VALUE'],
        'TC' => $tmp['TC']['VALUE'],
        'TIME' => $tmp['TIME']['VALUE'],
        'PHONE' => $tmp['PHONE']['VALUE'],
        'PERSON' => $tmp['PERSON']['VALUE'],
        'WWW' => $tmp['WWW']['VALUE'],
        'EMAIL' => $tmp['EMAIL']['VALUE'],
        'COMMENT' => $tmp['COMMENT']['VALUE'],
        'COORDINATES' => $tmp['YANDEX_MAP']['VALUE'],
        'EN_NAME' => $tmp['EN_NAME']['VALUE'],
        'EN_ADDRESS' => $tmp['EN_ADDRESS']['VALUE']
    ];
} ?>
<h1 class="total-store"><?= GetMessage('TOTAL_STORE', ['#TOTAL_STORE#' => count($arResult['DEALERS'])]) ?></h1>
<div class="map">
    <div id="map"></div>
</div>
<script>
    ymaps.ready(function () {
        var dealers = <?=CUtil::PhpToJSObject($arResult['DEALERS'])?>;
        var myMap = new ymaps.Map('map', {
            center: [50.475980, 73.270331],
            zoom: 3,
            controls: [
                'zoomControl',
                'fullscreenControl'
            ]
        });
        for (var i in dealers) {
            var mark = dealers[i];
            var coordinates = mark.COORDINATES.split(',');
            var header = '';
            var body = '';
            var footer = '';
            if (mark.CITY) {
                header += mark.CITY;
            }
            if (mark.CITY && mark.ADDRESS) {
                header += ', ';
            }
            if (mark.ADDRESS) {
                header += mark.ADDRESS;
            }
            if (mark.TC) {
                body = mark.TC;
            }
            if (mark.TIME) {
                footer += 'Время работы: ' + mark.TIME + '<br>';
            }
            if (mark.PHONE) {
                footer += 'Телефон: ' + mark.PHONE + '<br>';
            }
            if (mark.EMAIL) {
                footer += 'Email: <a href="mailto:' + mark.EMAIL + '" target="_blank">' + mark.EMAIL + '</a><br>';
            }
            if (mark.WWW) {
                footer += 'Сайт: <a href="' + mark.WWW + '" target="_blank">' + mark.WWW + '</a><br>';
            }
            var myPlacemarkWithContent = new ymaps.Placemark(coordinates, {
                    balloonContentHeader: header,
                    balloonContentBody: body,
                    balloonContentFooter: footer,
                    hintContent: body
                },
                {
                    iconColor: '#035b2c'
                });
            myMap.geoObjects.add(myPlacemarkWithContent);
        }
    });
</script>
