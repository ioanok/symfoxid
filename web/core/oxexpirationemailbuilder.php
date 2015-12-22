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
 * Expiration email builder class to send mail about shop offline
 */
class oxExpirationEmailBuilder
{

    /**
     * Constant defines left days till grace period ends.
     */
    const LEFT_DAYS_TO_SEND_LAST_EMAIL = 1;

    /**
     * Builds oxExpirationEmail object dependent on days left till grace period ends.
     *
     * @param int $iDaysLeft Is day count when grace period ends and eShop goes offline.
     *
     * @return oxEmail
     */
    public function build($iDaysLeft)
    {
        /** @var oxEmail $oExpirationEmail */
        $oExpirationEmail = oxNew('oxEmail');
        $oExpirationEmail->setSubject(oxRegistry::getLang()->translateString('SHOP_LICENSE_ERROR_INFORMATION', null, true));
        $oExpirationEmail->setRecipient($this->_getShopInfoAddress());
        $oExpirationEmail->setFrom($this->_getShopInfoAddress());
        $oExpirationEmail->setBody($this->_getBody($iDaysLeft));

        return $oExpirationEmail;
    }

    /**
     * Returns active shop info email address.
     *
     * @return string
     */
    private function _getShopInfoAddress()
    {
        $oShop = oxRegistry::getConfig()->getActiveShop();

        return $oShop->oxshops__oxinfoemail->value;
    }

    /**
     * Returns email content dependent on days left till grace period ends.
     *
     * @param integer $iDaysLeft Days left.
     *
     * @return string
     */
    private function _getBody($iDaysLeft)
    {
        if ($iDaysLeft <= self::LEFT_DAYS_TO_SEND_LAST_EMAIL) {
            $sBody = oxRegistry::getLang()->translateString('SHOP_LICENSE_ERROR_GRACE_WILL_EXPIRE', null, true);
        } else {
            $sBody = oxRegistry::getLang()->translateString('SHOP_LICENSE_ERROR_shop_unlicensed', null, true);
        }

        return $sBody;
    }
}
