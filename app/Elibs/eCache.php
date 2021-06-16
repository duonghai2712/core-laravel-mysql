<?php

namespace App\Elibs;


class eCache extends \Cache
{
    static $debug = '';

    static function add($key, $value, $minute=846000)
    {

        return parent::add($key, $value, $minute);
    }

    static function get($key, $default = NULL)
    {
        return parent::get($key, $default);
    }

    static function del($key)
    {
        return parent::forget($key);
    }
}
