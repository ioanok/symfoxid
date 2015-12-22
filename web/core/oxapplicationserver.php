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
 * Class used as entity for server node information.
 *
 * @internal Do not make a module extension for this class.
 * @see      http://wiki.oxidforge.org/Tutorials/Core_OXID_eShop_classes:_must_not_be_extended
 *
 * @ignore   This class will not be included in documentation.
 */
class oxApplicationServer
{

    /**
     * @var string
     */
    private $_sId;

    /**
     * @var string
     */
    private $_sIp;

    /**
     * @var int
     */
    private $_iTimestamp;

    /**
     * Flag which stores timestamp.
     *
     * @var int
     */
    private $_iLastFrontendUsage;

    /**
     * Flag which stores timestamp.
     *
     * @var int
     */
    private $_iLastAdminUsage;


    /**
     * Flag - server is used or not
     *
     * @var bool
     */
    private $_blIsValid = false;

    /**
     * Sets id.
     *
     * @param string $sId
     */
    public function setId($sId)
    {
        $this->_sId = $sId;
    }

    /**
     * Gets id
     *
     * @return string
     */
    public function getId()
    {
        return $this->_sId;
    }

    /**
     * Sets ip.
     *
     * @param string $sIp
     */
    public function setIp($sIp)
    {
        $this->_sIp = $sIp;
    }

    /**
     * Gets ip.
     *
     * @return string
     */
    public function getIp()
    {
        return $this->_sIp;
    }

    /**
     * Sets timestamp.
     *
     * @param int $iTimestamp
     */
    public function setTimestamp($iTimestamp)
    {
        $this->_iTimestamp = $iTimestamp;
    }

    /**
     * Gets timestamp.
     *
     * @return int
     */
    public function getTimestamp()
    {
        return $this->_iTimestamp;
    }

    /**
     * Sets last admin usage.
     *
     * @param int|null $iLastAdminUsage
     */
    public function setLastAdminUsage($iLastAdminUsage)
    {
        $this->_iLastAdminUsage = $iLastAdminUsage;
    }

    /**
     * Gets last admin usage.
     *
     * @return int|null
     */
    public function getLastAdminUsage()
    {
        return $this->_iLastAdminUsage;
    }

    /**
     * Sets last frontend usage.
     *
     * @param int|null $iLastFrontendUsage Admin server flag which stores timestamp.
     */
    public function setLastFrontendUsage($iLastFrontendUsage)
    {
        $this->_iLastFrontendUsage = $iLastFrontendUsage;
    }

    /**
     * Gets last frontend usage.
     *
     * @return int|null Frontend server flag which stores timestamp.
     */
    public function getLastFrontendUsage()
    {
        return $this->_iLastFrontendUsage;
    }

    /**
     * Sets whether is valid.
     *
     * @param bool $blValid Flag to set if application server is valid
     */
    public function setIsValid($blValid = true)
    {
        $this->_blIsValid = $blValid;
    }

    /**
     * Checks if valid.
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->_blIsValid;
    }
}
