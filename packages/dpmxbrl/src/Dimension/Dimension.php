<?php

namespace DpmXbrl\Dimension;

use DpmXbrl\Config\Config;
use DpmXbrl\Library\DomToArray;
use DpmXbrl\Library\Directory;
use DpmXbrl\Library\Format;
use DpmXbrl\Library\Normalise;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Metric
 *
 * @author begicf
 */
class Dimension
{

//put your code here
    private static $path;
    private static $dom;

    private static function _setPath($path)
    {
        self::$path = $path;
    }

    private static function _setDom()
    {
        self::$dom = DomToArray::invoke(self::$path);
    }

    public static function getDimension($path, $xpathQuery)
    {


        //uslov nije dobro definisam potrebno ga je zamjenit
        if ((strpos($path, "http") !== false) or (strpos($path, "https") !== false)):

            $path =
                Config::publicDir() . Directory::getRootName($path) . DIRECTORY_SEPARATOR . substr($path, strpos($path, 'http://') + 7);

        endif;


        if (file_exists($path)):


            self::_setPath($path);
            self::_setDom();


            $xpath = new \DomXpath(self::$dom);


            foreach ($xpath->query('//xs:element[@id="' . $xpathQuery . '"]') as $element) {

                $dimension['name'] = $element->getAttribute('name');
                $dimension['abstract'] = $element->getAttribute('abstract');
                $dimension['substitutionGroup'] = $element->getAttribute('substitutionGroup');
                $dimension['type'] = $element->getAttribute('type');
                $dimension['periodType'] = $element->getAttribute('xbrli:periodType');
                $dimension['nillable'] = $element->getAttribute('nillable');
                $dimension['typedDomainRef'] = $element->getAttribute('xbrldt:typedDomainRef');
                $dimension['fromDate'] = $element->getAttribute('model:fromDate');
                $dimension['creationDate'] = $element->getAttribute('model:fromDate');
                $dimension['id'] = $element->getAttribute('id');
            }

            if (!empty($dimension['typedDomainRef'])):

                $dim = dirname(Normalise::_normalise($path));
                $type = strtok($dimension['typedDomainRef'], '#');

                $typePath = DIRECTORY_SEPARATOR . Normalise::_normalise($dim . DIRECTORY_SEPARATOR . $type);


                $typ = self::getDimension($typePath, Format::getAfterSpecChar($dimension['typedDomainRef'], '#'));

                $namespace = self::getNamespace();

                $key = array_search(self::getTarget(), $namespace);
                $typ['key'] = $key;
                $dimension['namespace'] = $namespace;
                $dimension['typ'] = $typ;


            endif;

            return $dimension;
        endif;
    }

    private static function getNamespace()
    {
        $namespace = array();

        $xPath = new \DOMXPath(self::$dom);

        $xPath->registerNamespace('xs', 'http://www.w3.org/2001/XMLSchema');

        $context = self::$dom->documentElement;

        foreach ($xPath->query('namespace::*', $context) as $node) {

            $namespace[$node->prefix] = $node->nodeValue;
        }

        return $namespace;
    }

    private static function getTarget()
    {

        $context = self::$dom->documentElement;

        return $context->getAttribute('targetNamespace');
    }

}
