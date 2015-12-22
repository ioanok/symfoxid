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
 * Class oxMailValidator
 */
class oxMailValidator
{

    /**
     * @var string
     */
    private $_sMailValidationRule = null;

    /**
     * Get mail validation rule.
     *
     * @return string
     */
    public function getMailValidationRule()
    {
        if (is_null($this->_sMailValidationRule)) {
            $this->_sMailValidationRule = "/^([\w+\-.])+\@([\w\-.])+\.([A-Za-z]{2,64})$/i";
        }

        return $this->_sMailValidationRule;
    }

    /**
     * Override mail validation rule.
     *
     * @param string $sMailValidationRule mail validation rule
     */
    public function setMailValidationRule($sMailValidationRule)
    {
        $this->_sMailValidationRule = $sMailValidationRule;
    }

    /**
     * Set mail validation rule from config.
     * Would use default rule if not defined in config.
     */
    public function __construct()
    {
        $oConfig = oxRegistry::getConfig();
        $sEmailValidationRule = $oConfig->getConfigParam('sEmailValidationRule');
        if (!empty($sEmailValidationRule)) {
            $this->_sMailValidationRule = $sEmailValidationRule;
        }
    }

    /**
     * User email validation function. Returns true if email is OK otherwise - false;
     * Syntax validation is performed only.
     *
     * @param string $sEmail user email
     *
     * @return bool
     */
    public function isValidEmail($sEmail)
    {
        $sEmailRule = $this->getMailValidationRule();
        $blValid = (getStr()->preg_match($sEmailRule, $sEmail) != 0);

        return $blValid;
    }
}
