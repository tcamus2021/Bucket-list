<?php

namespace App\Util;

class Censurator
{

    private $motInterdit = ['pute', 'enculé', 'cocaïne'];

    public function purify(?string $text)
    {
        if (strlen($text)) {
            foreach ($this->motInterdit as $mot) {
                $nbDigit = strlen($mot);
                $censored = "";
                for ($i = 0; $i < $nbDigit; $i++) {
                    $censored .= "*";
                }
                $text = str_ireplace($mot, "$censored", $text);
            }
        }
        return $text;
    }
}