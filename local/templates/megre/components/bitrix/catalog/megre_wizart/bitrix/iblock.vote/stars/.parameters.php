<?
/*
 * Изменено: 13 сентября 2021, понедельник
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arTemplateParameters = [
	"DISPLAY_AS_RATING" => [
		"NAME"    => GetMessage("TP_CBIV_DISPLAY_AS_RATING"),
		"TYPE"    => "LIST",
		"VALUES"  => [
			"rating"   => GetMessage("TP_CBIV_RATING"),
			"vote_avg" => GetMessage("TP_CBIV_AVERAGE"),
		],
		"DEFAULT" => "rating",
	],
];