<?php
/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2015
 * @version   OXID eShop PE
 */

/**
 * XML document handler
 */
class oxUtilsXml extends oxSuperCfg
{

    /**
     * Takes XML string and makes DOMDocument
     * Returns DOMDocument or false, if it can't be loaded
     *
     * @param string      $sXml         XML as a string
     * @param DOMDocument $oDomDocument DOM handler
     *
     * @return DOMDocument|bool
     */
    public function loadXml($sXml, $oDomDocument = null)
    {
        if (!$oDomDocument) {
            $oDomDocument = new DOMDocument('1.0', 'utf-8');
        }

        libxml_use_internal_errors(true);
        $oDomDocument->loadXML($sXml);
        $errors = libxml_get_errors();
        $blLoaded = empty($errors);
        libxml_clear_errors();

        if ($blLoaded) {
            return $oDomDocument;
        }

        return false;
    }
}
