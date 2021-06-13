<?php


namespace App\Service;

class Slugify
{
    const REPLACE_SPECIAL_CHAR = [
        'é' => 'e',
        'à' => 'a',
        'è' => 'e',
        'ê' => 'e',
        'ô' => 'o',
        'â' => 'a',
    ];
    const PUNCTUATION = ['!',',','.',';',':','?'];

    public function generate(string $input): string
    {
        $sentence = trim($input);
        $sentence = str_replace(" ", "-", $sentence);
        $sentence = str_replace("--", "-", $sentence);
        foreach (self::REPLACE_SPECIAL_CHAR as $key => $replace){
            $sentence = str_replace($key, $replace, $sentence);
        }
        foreach (self::PUNCTUATION as $point){
            $sentence = str_replace($point, "", $sentence);
        }
        return strtolower($sentence);
    }
}

