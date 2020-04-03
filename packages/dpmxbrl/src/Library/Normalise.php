<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DpmXbrl\Library;
/**
 *
 *
 * @author begicf
 */
class Normalise
{

    public static function taxPath($path)
    {


        if (strpos($path, 'public' . DIRECTORY_SEPARATOR . 'tax')):
            $tmp_path = substr($path, strpos($path, 'public' . DIRECTORY_SEPARATOR . 'tax') + 11);
            return self::_normalise($tmp_path);
        elseif (strpos($path, "webroot/")):
            $tmp_path = self::_normalise(substr($path, strpos($path, "webroot/") + 8));
            return self::_normalise($tmp_path);
        else:
            return self::_normalise($path);
        endif;
    }

    public static function _normalise($path, $encoding = "UTF-8")
    {

        // Attempt to avoid path encoding problems.
        $path = iconv($encoding, "$encoding//IGNORE//TRANSLIT", $path);
        // Process the components
        $parts = explode('/', $path);
        $safe = array();
        foreach ($parts as $idx => $part) {
            if (empty($part) || ('.' == $part)) {
                continue;
            } elseif ('..' == $part) {
                array_pop($safe);
                continue;
            } else {
                $safe[] = $part;
            }
        }
        // Return the "clean" path
        $path = implode(DIRECTORY_SEPARATOR, $safe);
        return $path;
    }


}
