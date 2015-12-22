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
 * List of additional shop information links widget.
 * Forms info link list.
 */
class oxwInformation extends oxWidget
{

    /**
     * Current class template name
     *
     * @var string
     */
    protected $_sThisTemplate = 'widget/footer/info.tpl';

    /**
     * @var oxContentList
     */
    protected $_oContentList;

    /**
     * Returns service keys.
     *
     * @return array
     */
    public function getServicesKeys()
    {
        $oContentList = $this->_getContentList();

        return $oContentList->getServiceKeys();
    }

    /**
     * Get services content list
     *
     * @return array
     */
    public function getServicesList()
    {
        $oContentList = $this->_getContentList();
        $oContentList->loadServices();

        return $oContentList;
    }

    /**
     * Returns content list object.
     *
     * @return object|oxContentList
     */
    protected function _getContentList()
    {
        if (!$this->_oContentList) {
            $this->_oContentList = oxNew("oxContentList");
        }

        return $this->_oContentList;
    }
}
