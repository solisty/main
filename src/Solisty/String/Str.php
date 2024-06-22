<?php

namespace Solisty\String;

class Str
{
    public static function endsWith(string $str, string $what): bool
    {
        return substr($str, -strlen($what)) === $what;
    }

    public static function startWith(string $str, string $what): bool
    {
        return strpos($str, $what) === 0;
    }

    public static function contains(string $str, string $what): bool
    {
        return strpos($str, $what) !== false;
    }

    public static function before(string $str, string $what): string
    {
        $pos = strpos($str, $what);
        return $pos !== false ? substr($str, 0, $pos) : '';
    }

    public static function after(string $str, string $what): string
    {
        $pos = strpos($str, $what);
        return $pos !== false ? substr($str, $pos + strlen($what)) : '';
    }

    public static function replace(string $str, string|array $lookup, string|array $replace): string
    {
        return str_replace($lookup, $replace, $str);
    }

    public static function replaceAt(string $str, string $what, int $pos, int $length): string
    {
        return substr_replace($str, $what, $pos, $length);
    }

    public static function subStr(string $str, int $start, int $end): string
    {
        return substr($str, $start, $end);
    }

    public static function trim(string $str): string
    {
        return trim($str);
    }

    public static function trimLeft(string $str): string
    {
        return ltrim($str);
    }

    public static function trimRight(string $str): string
    {
        return rtrim($str);
    }

    public static function split(string $str, string $separator): array
    {
        return explode($separator, $str);
    }

    public static function join(array $strings, string $separator = ''): string
    {
        return implode($separator, $strings);
    }

    public static function uppercase(string $str): string
    {
        return strtoupper($str);
    }

    public static function lowercase(string $str): string
    {
        return strtolower($str);
    }

    public static function capitalize(string $str): string
    {
        return ucwords(Str::lowercase($str));
    }

    public static function slug(string $str): string
    {
        $str = preg_replace('/[^a-z0-9-]+/i', '-', $str);
        $str = trim($str, '-');
        $str = strtolower($str);
        return $str;
    }

    public static function firstOffsetOf(string $str, string $what): int
    {
        return strpos($str, $what);
    }

    public static function lastOffsetOf(string $str, string $what): int
    {
        return strrpos($str, $what);
    }

    public static function isUppercase(string $str): bool
    {
        return $str === strtoupper($str);
    }

    public static function isLowercase(string $str): bool
    {
        return $str === strtolower($str);
    }

    public static function length(string $str): int
    {
        return strlen($str);
    }

    public static function urlEncode(string $str): string
    {
        return urlencode($str);
    }

    public static function urlDecode(string $str): string
    {
        return urldecode($str);
    }

    /**
     * Pluralizes an English word.
     *
     * @param string $word The word to be pluralized.
     * @return string The plural form of the word.
     */
    public static function pluralize(string $word): string
    {
        $irregular = array(
            'analysis' => 'analyses',
            'basis' => 'bases',
            'criterion' => 'criteria',
            'datum' => 'data',
            'diagnosis' => 'diagnoses',
            'hypothesis' => 'hypotheses',
            'index' => 'indices',
            'phenomenon' => 'phenomena',
            'synthesis' => 'syntheses',
            'thesis' => 'theses'
        );

        if (array_key_exists($word, $irregular)) {
            return $irregular[$word];
        }

        $uncountable = array(
            'information', 'rice', 'money', 'species', 'series', 'fish', 'sheep'
        );

        if (in_array($word, $uncountable)) {
            return $word;
        }

        $lastChar = strtolower($word[strlen($word) - 1]);

        switch ($lastChar) {
            case 'y':
                if (preg_match('/[aeiou]y$/i', $word)) {
                    return $word . 's';
                }
                return substr($word, 0, -1) . 'ies';
            case 's':
            case 'x':
            case 'z':
            case 'h':
                if (preg_match('/(s|x|z|ch|sh)$/i', $word)) {
                    return $word . 'es';
                }
                break;
            default:
                return $word . 's';
        }

        return $word . 's';
    }

    /**
     * Singularizes an English word.
     *
     * @param string $word The word to be singularized.
     * @return string The singular form of the word.
     */
    public static function singularize(string $word): string
    {
        $irregular = array(
            'analyses' => 'analysis',
            'bases' => 'basis',
            'criteria' => 'criterion',
            'data' => 'datum',
            'diagnoses' => 'diagnosis',
            'hypotheses' => 'hypothesis',
            'indices' => 'index',
            'phenomena' => 'phenomenon',
            'syntheses' => 'synthesis',
            'theses' => 'thesis'
        );

        if (array_key_exists($word, $irregular)) {
            return $irregular[$word];
        }

        $uncountable = array(
            'information', 'rice', 'money', 'species', 'series', 'fish', 'sheep'
        );

        if (in_array($word, $uncountable)) {
            return $word;
        }

        if (substr($word, -3) === 'ies') {
            return substr($word, 0, -3) . 'y';
        }

        if (substr($word, -2) === 'es') {
            $secondLastChar = strtolower($word[strlen($word) - 3]);
            if (in_array(substr($word, -4, -2), ['ch', 'sh']) || in_array($secondLastChar, ['s', 'x', 'z'])) {
                return substr($word, 0, -2);
            }
        }

        if (substr($word, -1) === 's' && substr($word, -2) !== 'ss') {
            return substr($word, 0, -1);
        }

        return $word;
    }
}
