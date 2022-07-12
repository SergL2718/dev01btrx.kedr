<?php
/*
 * Изменено: 03 января 2022, понедельник
 * Автор: Артамонов Денис <artamonov.d.i@yandex.com>
 * copyright (c) 2022
 */

namespace Native\App\Spam;


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
