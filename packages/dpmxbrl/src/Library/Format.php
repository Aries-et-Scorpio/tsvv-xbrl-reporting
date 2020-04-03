<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DpmXbrl\Library;

/**
 * Description of Format
 *
 * @author begicf
 */
class Format
{
//put your code here

    /**
     *
     * @param type $string
     * @param type $length
     * @param type $wrap
     * @param type $from
     * @return type
     */
    public static function myWordWrap($string, $length = 3, $wrap = '.', $from = 'right')
    {
        if (substr($string, 0, 1) == '-') {
            $string = substr($string, 1);

            if ($from == 'left')
                $txt = wordwrap($string, $length, $wrap, true);
            if ($from == 'right') {
                $txt = strrev($string);
                $temp = wordwrap($txt, $length, $wrap, true);
                $txt = strrev($temp);
            }

            return "-" . $txt;
        } else {

            if ($from == 'left')
                $txt = wordwrap($string, $length, $wrap, true);
            if ($from == 'right') {
                $txt = strrev($string);
                $temp = wordwrap($txt, $length, $wrap, true);
                $txt = strrev($temp);
            }

            return $txt;
        }
    }

    /**
     * Dobavi sting poslije specificiranog karaktera
     * @param type $string
     * @param type $char
     * @param type $num
     * @return type
     */
    public static function getAfterSpecChar($string, $char, $num = 1)
    {
        return substr($string, strpos($string, $char) + $num);
    }

    /**
     * @param $string
     * @param $char
     * @return bool|int
     */
    public static function getBeforeSpecChar($string, $char): string
    {

        return strtok($string, $char);
    }

    /**
     * @param $url
     * @return string|null
     */
    public static function getHostFromUrl($url): ?string
    {

        $parse = parse_url($url);

        return $parse['host'] ?? null;

    }


    /**
     * @param $before_what
     * @param $in_string
     * @return false|string
     */
    public static function getLastBeforeSpecChar($haystack, $needle)
    {

        return $short = substr($haystack, 0, strrpos($haystack, $needle));
    }




}
