<?php
/*
 * @updated 07.12.2020, 19:16
 * @author Артамонов Денис <bitrix.developer@inbox.ru>
 * @copyright Copyright (c) 2020, Компания Webco <hello@wbc.cx>
 * @link http://wbc.cx
 */

namespace Zk\Main\Spam;

/**
 * Class Protect
 * @package Zk\Main\Spam
 * @deprecated since 2020-07-15
 */
class Protect
{
    const URL_STOP_FORUM_SPAM = 'http://api.stopforumspam.org/api?json';

    public function check($ip = false, $email = false)
    {
        if ($this->whitelist($email)) return true;

        // return true;

        $spam = false;
        $url = self::URL_STOP_FORUM_SPAM;
        if ($ip) {
            $url .= '&ip=' . $ip;
        }
        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $url .= '&email=' . urlencode($email);
        }
        if ($res = file_get_contents($url)) {
            if ($data = json_decode($res, true)) {
                if ($data['success'] == true) {
                    if ($ip && $data['ip']['appears'] == true) {
                        $spam = true;
                    }
                    if ($email && $data['email']['appears'] == true) {
                        $spam = true;
                    }
                }
            }
        }
        return $spam == true ? false : true;
    }

    private function whitelist($email)
    {
        $ar = [
            'antonov.a@mail.ru' => true
        ];

        return isset($ar[$email]);
    }
}
