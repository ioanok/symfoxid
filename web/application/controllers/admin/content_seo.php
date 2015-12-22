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
 * Content seo config class
 */
class Content_Seo extends Object_Seo
{

    /**
     * Returns url type
     *
     * @return string
     */
    protected function _getType()
    {
        return 'oxcontent';
    }

    /**
     * Returns current object type seo encoder object
     *
     * @return oxSeoEncoderContent
     */
    protected function _getEncoder()
    {
        return oxRegistry::get("oxSeoEncoderContent");
    }

    /**
     * Returns seo uri
     *
     * @return string
     */
    public function getEntryUri()
    {
        $oContent = oxNew('oxcontent');
        if ($oContent->load($this->getEditObjectId())) {
            return $this->_getEncoder()->getContentUri($oContent, $this->getEditLang());
        }
    }
}
