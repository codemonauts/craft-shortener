<?php

namespace codemonauts\shortener\services;

use codemonauts\shortener\elements\ShortUrl as ShortUrlElement;
use craft\base\Component;

/**
 * Class ShortUrl
 */
class ShortUrl extends Component
{
    public function generateUniqueCode()
    {
        do {
            $code = $this->_generateCode();
        } while(ShortUrlElement::find()->code($code)->exists());

        return $code;
    }

    private function _generateCode($length = 5, $available_sets = 'lud')
    {
        $sets = [];
        if (strpos($available_sets, 'l') !== false) {
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        }
        if (strpos($available_sets, 'u') !== false) {
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        }
        if (strpos($available_sets, 'd') !== false) {
            $sets[] = '23456789';
        }
        $all = '';
        $code = '';
        foreach ($sets as $set) {
            $code .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for ($i = 0; $i < $length - count($sets); $i++) {
            $code .= $all[array_rand($all)];
        }

        $code = str_shuffle($code);

        return $code;
    }

}
