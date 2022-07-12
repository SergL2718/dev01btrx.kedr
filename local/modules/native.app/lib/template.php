<?php
/*
 * Изменено: 08 сентября 2021, среда
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */


namespace Native\App;


class Template
{
	public static function isNewVersion (): bool
	{
		return defined('SITE_TEMPLATE_ID') && SITE_TEMPLATE_ID === Helper::MEGRE && \CSite::InGroup([35]);
	}
}