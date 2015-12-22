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
 * Vendor seo config class
 */
class Vendor_Seo extends Object_Seo
{

    /**
     * Updating showsuffix field
     *
     * @return null
     */
    public function save()
    {
        $oVendor = oxNew('oxbase');
        $oVendor->init('oxvendor');
        if ($oVendor->load($this->getEditObjectId())) {
            $sShowSuffixField = 'oxvendor__oxshowsuffix';
            $blShowSuffixParameter = oxRegistry::getConfig()->getRequestParameter('blShowSuffix');
            $oVendor->$sShowSuffixField = new oxField((int) $blShowSuffixParameter);
            $oVendor->save();
        }

        return parent::save();
    }

    /**
     * Returns current object type seo encoder object
     *
     * @return oxSeoEncoderVendor
     */
    protected function _getEncoder()
    {
        return oxRegistry::get("oxSeoEncoderVendor");
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
     * Returns true if SEO object id has suffix enabled
     *
     * @return bool
     */
    public function isEntrySuffixed()
    {
        $oVendor = oxNew('oxvendor');
        if ($oVendor->load($this->getEditObjectId())) {
            return (bool) $oVendor->oxvendor__oxshowsuffix->value;
        }
    }

    /**
     * Returns url type
     *
     * @return string
     */
    protected function _getType()
    {
        return 'oxvendor';
    }

    /**
     * Returns seo uri
     *
     * @return string
     */
    public function getEntryUri()
    {
        $oVendor = oxNew('oxvendor');
        if ($oVendor->load($this->getEditObjectId())) {
            return $this->_getEncoder()->getVendorUri($oVendor, $this->getEditLang());
        }
    }
}
