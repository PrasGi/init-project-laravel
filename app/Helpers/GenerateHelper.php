<?php

namespace App\Helpers;

class GenerateHelper {
    public static function codeStringNumber(int $total) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        for ($i = 0; $i < $total; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $code;
    }

    public static function codeNumber(int $total) {
        $digits = '0123456789';
        $code = '';
        for ($i = 0; $i < $total; $i++) {
            $code .= $digits[random_int(0, strlen($digits) - 1)];
        }

        return $code;
    }
}
