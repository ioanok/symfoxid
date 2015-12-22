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
 * Class oxOnlineServerEmailBuilder is responsible for email sending when it's not possible to make call via CURL.
 *
 * @internal Do not make a module extension for this class.
 * @see      http://wiki.oxidforge.org/Tutorials/Core_OXID_eShop_classes:_must_not_be_extended
 *
 * @ignore   This class will not be included in documentation.
 */
class oxOnlineServerEmailBuilder
{

    /**
     * Created oxEmail object and sets values.
     *
     * @param string $sBody Email body in XML format.
     *
     * @return oxEmail
     */
    public function build($sBody)
    {
        /** @var oxEmail $oExpirationEmail */
        $oExpirationEmail = oxNew('oxEmail');
        $oExpirationEmail->setSubject(oxRegistry::getLang()->translateString('SUBJECT_UNABLE_TO_SEND_VIA_CURL', null, true));
        $oExpirationEmail->setRecipient('olc@oxid-esales.com');
        $oExpirationEmail->setFrom($this->_getShopInfoAddress());
        $oExpirationEmail->setBody($sBody);

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
}
