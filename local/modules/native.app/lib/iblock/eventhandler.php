<?php
/*
 * Изменено: 16 декабря 2021, четверг
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

namespace Native\App\IBlock;


use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

class EventHandler
{
	/**
	 * @param $arFields
	 *
	 * @return bool
	 * @throws ArgumentException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public function OnBeforeIBlockElementUpdate (&$arFields): bool
	{
		global $APPLICATION;
		$iblockId =& $arFields['IBLOCK_ID'];
		$elementId =& $arFields['ID'];
		$elementPropertyList =& $arFields['PROPERTY_VALUES'];
		// Обмен с 1С
		if ($_REQUEST['mode'] === 'import') {
			unset($arFields['PREVIEW_PICTURE'], $arFields['DETAIL_PICTURE'], $arFields['PREVIEW_TEXT'], $arFields['DETAIL_TEXT']);
			return true;
		}
		// Данные свойств
		$propertyList = [];
		$properties = PropertyTable::getList([
			'select' => [
				'ID',
				'CODE',
			],
			'filter' => [
				'=IBLOCK_ID' => $iblockId,
				'=CODE'      => [
					'SHORT_NAME',
					'DUPLICATE',
					'DUPLICATED',
				],
			],
		]);
		if ($properties->getSelectedRowsCount() > 0) {
			while ($property = $properties->fetchRaw()) {
				$propertyList[$property['CODE']] = $property;
			}
		}
		// https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/15953/
		// Проверим свойство Короткое название для товара
		if ($elementPropertyList[$propertyList['SHORT_NAME']['ID']]) {
			$propertyShortNameId =& $propertyList['SHORT_NAME']['ID'];
			$property =& $elementPropertyList[$propertyShortNameId][array_key_first($elementPropertyList[$propertyShortNameId])];
			if ($property['VALUE'] && mb_strlen($property['VALUE']) > 60) {
				$APPLICATION->throwException('Короткое название не может содержать более 60 символов');
				return false;
			} else if (empty($property['VALUE']) && mb_strlen($arFields['NAME']) <= 60) {
				$property['VALUE'] = $arFields['NAME'];
			}
		}
		// https://megre.bitrix24.ru/workgroups/group/9/tasks/task/view/21639/
		// Если у элемента заполнены Дублирующие элементы
		// Тогда обновим свойство Дублируемый элемент у Дублирующих элементов
		// В случае, если у дублируемого элемента уже имеется элемент, который он дублирует, тогда отменим дублирование
		// А если у дублируемого элемента удаляется дублирующий элемент, тогда удалим значение свойства дублируемый элемент у дублирующего элемента
		$propertyDuplicateId =& $propertyList['DUPLICATE']['ID'];
		$new = [];    // новые элементы, которые дублируют текущий элемент - обновим свойство у дублирующих элементов
		$delete = []; // ранее элементы дублировали текущий, но теперь не дублируют - обновим свойство у дублирующих элементов
		foreach ($elementPropertyList[$propertyDuplicateId] as $valueId => $item) {
			if ($valueId > 0 && empty($item['VALUE'])) {
				$delete[] = $valueId;
			} else if (mb_strpos($valueId, 'n') !== false && !empty($elementPropertyList[$propertyDuplicateId][$valueId]['VALUE'])) {
				$new[] = $elementPropertyList[$propertyDuplicateId][$valueId]['VALUE'];
			}
		}
		if (count($delete) > 0) {
			foreach ($delete as $propertyValueId) {
				if ($duplicatedElementId = Application::getConnection()->query('select VALUE from b_iblock_element_property where ID="' . $propertyValueId . '" limit 1')->fetchRaw()['VALUE']) {
					\CIBlockElement::SetPropertyValues($duplicatedElementId, $iblockId, false, 'DUPLICATED');
				}
			}
		}
		if (count($new) > 0) {
			$error = [];
			foreach ($new as $duplicatedElementId) {
				// Проверим, вдруг у дублиющуего элемента уже имеется дублируемый элемент
				// И если он не равен текущему элементу, тогда запретим обновлять свойство у дублирующего элемента
				$duplicatedElementProperty = \CIBlockElement::GetProperty($iblockId, $duplicatedElementId, false, false, ['CODE' => 'DUPLICATED']);
				if ($duplicatedElementProperty = $duplicatedElementProperty->fetch()) {
					if (!empty($duplicatedElementProperty['VALUE'])) {
						if ($duplicatedElementProperty['VALUE'] != $elementId) {
							$error[] = 'Элемент ' . $duplicatedElementId . ' не может быть установлен в качестве дублирующего, он уже имеет дублируемый элемент.';
						}
						continue;
					}
				}
				\CIBlockElement::SetPropertyValues($duplicatedElementId, $iblockId, $elementId, 'DUPLICATED');
			}
			if (count($error) > 0) {
				$APPLICATION->throwException(implode('<br>', $error));
				return false;
			}
		}
		return true;
	}

	public function OnAfterIBlockElementUpdate (&$arFields): bool
	{
		return true;
	}

	/**
	 * @param $arFields
	 */
	public function OnBeforeIBlockElementAdd (&$arFields)
	{
		if ($_REQUEST['mode'] === 'import') {
			unset($arFields['PREVIEW_PICTURE'], $arFields['DETAIL_PICTURE'], $arFields['PREVIEW_TEXT'], $arFields['DETAIL_TEXT']);
		}
	}

	/**
	 * @param $arFields
	 */
	public function OnBeforeIBlockSectionUpdate (&$arFields)
	{
		if ($_REQUEST['mode'] === 'import' && !array_key_exists('UF_NOT_SHOW', $arFields)) {
			$arFields['UF_NOT_SHOW'] = \CIBlockSection::GetList([], ['IBLOCK_ID' => $arFields['IBLOCK_ID'], 'ID' => $arFields['ID']], false, ['ID', 'UF_NOT_SHOW'], false)->fetch()['UF_NOT_SHOW'];
		}
		if (isset($arFields['UF_NOT_SHOW'])) {
			$arFields['ACTIVE'] = $arFields['UF_NOT_SHOW'] ? 'N' : 'Y';
		}
		// Удалим кеш главного меню
		// /local/templates/megre/components/bitrix/menu/header.menu/result_modifier.php
		if ($arFields['IBLOCK_ID'] == 66) {
			$cachePath = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/cache/zg/bitrix/menu';
			Directory::deleteDirectory($cachePath . '/GeneralMenuSections/');
			Directory::deleteDirectory($cachePath . '/GeneralMenuProducts/');
		}
	}
}
