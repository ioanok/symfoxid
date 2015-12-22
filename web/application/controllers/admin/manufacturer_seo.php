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
 * Manufacturer seo config class
 */
class Manufacturer_Seo extends Object_Seo
{

    /**
     * Updating showsuffix field
     *
     * @return null
     */
    public function save()
    {
        $oManufacturer = oxNew('oxbase');
        $oManufacturer->init('oxmanufacturers');
        if ($oManufacturer->load($this->getEditObjectId())) {
            $sShowSuffixField = 'oxmanufacturers__oxshowsuffix';
            $blShowSuffixParameter = oxRegistry::getConfig()->getRequestParameter('blShowSuffix');
            $oManufacturer->$sShowSuffixField = new oxField((int) $blShowSuffixParameter);
            $oManufacturer->save();
        }

        return parent::save();
    }

    /**
     * Returns current object type seo encoder object
     *
     * @return oxSeoEncoderManufacturer
     */
    protected function _getEncoder()
    {
        return oxRegistry::get("oxSeoEncoderManufacturer");
    }

    /**
     * This SEO object supports suffixes so return TRUE
     *
     * @return bool
     */
    public function isSuffixSupported()
    {
        return true;
    }

    /**
     * Returns url type
     *
     * @return string
     */
    protected function _getType()
    {
        return 'oxmanufacturer';
    }

    /**
     * Returns true if SEO object id has suffix enabled
     *
     * @return bool
     */
    public function isEntrySuffixed()
    {
        $oManufacturer = oxNew('oxmanufacturer');
        if ($oManufacturer->load($this->getEditObjectId())) {
            return (bool) $oManufacturer->oxmanufacturers__oxshowsuffix->value;
        }
    }

    /**
     * Returns seo uri
     *
     * @return string
     */
    public function getEntryUri()
    {
        $oManufacturer = oxNew('oxmanufacturer');
        if ($oManufacturer->load($this->getEditObjectId())) {
            return $this->_getEncoder()->getManufacturerUri($oManufacturer, $this->getEditLang());
        }
    }
}
