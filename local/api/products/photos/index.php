<?php
/*
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @updated 26.08.2020, 13:44
 * @copyright 2011-2020
 */

/**
 * Файл формирует zip-архив со всеми фото товаров
 */

require __DIR__ . '/../../index.php';

$request =& $_GET;

//define('LOG_FILENAME', __DIR__ . '/request.log');
//AddMessage2Log($request, 'api.megre.ru');


Bitrix\Main\Loader::includeModule('iblock');
Bitrix\Main\Loader::includeModule('catalog');

$zip = 'upload/export/products/photos-'.date('Ymd').'.zip'; // zip-архив для скачивания
$storage = 'export/products/photos'; // название папки хранения файлов - относительно /upload
$iblockProductsId = 37; // id инфоблока хранения товаров
$parents = []; // для хранения списка товаров у которых имеются торговые предложения
$list = []; // для хранения общего списка товаров


if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $zip)) {
    LocalRedirect('https://megre.ru/' . $zip);
    return;
}


$products = CIBlockElement::GetList(['NAME' => 'ASC'], ['IBLOCK_ID' => $iblockProductsId, 'ACTIVE' => 'Y'], false, false, ['ID', 'NAME', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'PROPERTY_MORE_PHOTO', 'TYPE']);

while ($product = $products->fetch()) {
    if ($product['TYPE'] === '3') {
        $parents[] = $product['ID'];
    }

    $morePhotoId = $product['PROPERTY_MORE_PHOTO_VALUE'];

    if (!isset($list[$product['ID']])) {
        $product['PHOTOS'][] = $morePhotoId;
        $list[$product['ID']] = $product;
    } else {
        $list[$product['ID']]['PHOTOS'][] = $morePhotoId;
    }
}

if (count($parents) > 0) {
    $products = CCatalogSKU::getOffersList($parents, 0, ['ACTIVE' => 'Y'], ['ID', 'NAME', 'PREVIEW_PICTURE', 'DETAIL_PICTURE'], []);
    foreach ($products as $parentId => $offers) {
        $list[$parentId]['OFFERS'] = $offers;
    }
}

foreach ($list as $product) {
    $name = clearName($product['NAME']);
    $dir = $storage . '/' . $name;

    if ($product['PREVIEW_PICTURE']) {
        $file = CFile::GetByID($product['PREVIEW_PICTURE'])->fetch();
        $path = $dir . '/preview.' . getExtension($file['CONTENT_TYPE']);
        CFile::CopyFile($product['PREVIEW_PICTURE'], true, $path);
    }
    if ($product['DETAIL_PICTURE']) {
        $file = CFile::GetByID($product['DETAIL_PICTURE'])->fetch();
        $path = $dir . '/detail.' . getExtension($file['CONTENT_TYPE']);
        CFile::CopyFile($product['DETAIL_PICTURE'], true, $path);
    }

    if (count($product['PHOTOS']) > 0) {
        foreach ($product['PHOTOS'] as $key => $photoId) {
            $file = CFile::GetByID($photoId)->fetch();
            $path = $dir . '/photo-' . $key . '.' . getExtension($file['CONTENT_TYPE']);
            CFile::CopyFile($photoId, true, $path);
        }
    }

    if (count($product['OFFERS']) > 0) {
        foreach ($product['OFFERS'] as $offer) {
            $dir = $storage . '/' . $name . '/' . clearName($offer['NAME']);
            if ($offer['PREVIEW_PICTURE']) {
                $file = CFile::GetByID($offer['PREVIEW_PICTURE'])->fetch();
                $path = $dir . '/preview.' . getExtension($file['CONTENT_TYPE']);
                CFile::CopyFile($offer['PREVIEW_PICTURE'], true, $path);
            }
            if ($offer['DETAIL_PICTURE']) {
                $file = CFile::GetByID($offer['DETAIL_PICTURE'])->fetch();
                $path = $dir . '/detail.' . getExtension($file['CONTENT_TYPE']);
                CFile::CopyFile($offer['DETAIL_PICTURE'], true, $path);
            }
        }
    }
}

$archived = zip($_SERVER['DOCUMENT_ROOT'] . '/upload/' . $storage, $_SERVER['DOCUMENT_ROOT'] . '/' . $zip);

if ($archived) {
    DeleteDirFilesEx('/upload/' . $storage);
    LocalRedirect('https://megre.ru/' . $zip);
}

function clearName($name)
{
    return str_replace(['*', '.', '"', '/', '[', ']', ':', ';', '|', '=', '?'], '', $name);
}

function getExtension($contentType)
{
    $external = 'jpg';
    if ($contentType === 'image/png') {
        $external = 'png';
    } else if ($contentType === 'image/jpeg') {
        $external = 'jpeg';
    } else if ($contentType === 'image/jpg') {
        $external = 'jpg';
    }
    return $external;
}

function zip($source, $destination)
{
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file) {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if (in_array(substr($file, strrpos($file, '/') + 1), array('.', '..')))
                continue;

            $file = realpath($file);
            $file = str_replace('\\', '/', $file);

            if (is_dir($file) === true) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } else if (is_file($file) === true) {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    } else if (is_file($source) === true) {
        $zip->addFromString(basename($source), file_get_contents($source));
    }
    return $zip->close();
}

die;
