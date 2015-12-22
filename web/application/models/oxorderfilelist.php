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
 * Article file link manager.
 *
 */
class oxOrderFileList extends oxList
{

    /**
     * List Object class name
     *
     * @var string
     */
    protected $_sObjectsInListName = 'oxorderfile';

    /**
     * Returns orders
     *
     * @param string $sUserId - user id
     */
    public function loadUserFiles($sUserId)
    {
        $oOrderFile = $this->getBaseObject();
        $sFields = $oOrderFile->getSelectFields();
        $sShopId = $this->getConfig()->getShopId();

        $oOrderFile->addFieldName('oxorderfiles__oxarticletitle');
        $oOrderFile->addFieldName('oxorderfiles__oxarticleartnum');
        $oOrderFile->addFieldName('oxorderfiles__oxordernr');
        $oOrderFile->addFieldName('oxorderfiles__oxorderdate');

        $sSql = "SELECT " . $sFields . " ,
                      `oxorderarticles`.`oxtitle` AS `oxorderfiles__oxarticletitle`,
                      `oxorderarticles`.`oxartnum` AS `oxorderfiles__oxarticleartnum`,
                      `oxfiles`.`oxpurchasedonly` AS `oxorderfiles__oxpurchasedonly`,
                      `oxorder`.`oxordernr` AS `oxorderfiles__oxordernr`,
                      `oxorder`.`oxorderdate` AS `oxorderfiles__oxorderdate`,
                      IF( `oxorder`.`oxpaid` != '0000-00-00 00:00:00', 1, 0 ) AS `oxorderfiles__oxispaid`
                    FROM `oxorderfiles`
                        LEFT JOIN `oxorderarticles` ON `oxorderarticles`.`oxid` = `oxorderfiles`.`oxorderarticleid`
                        LEFT JOIN `oxfiles` ON `oxfiles`.`oxid` = `oxorderfiles`.`oxfileid`
                        LEFT JOIN `oxorder` ON `oxorder`.`oxid` = `oxorderfiles`.`oxorderid`
                    WHERE `oxorder`.`oxuserid` = '" . $sUserId . "'
                        AND `oxorderfiles`.`oxshopid` = '" . $sShopId . "'
                        AND `oxorder`.`oxstorno` = 0
                        AND `oxorderarticles`.`oxstorno` = 0
                    ORDER BY `oxorder`.`oxordernr`";

        $this->selectString($sSql);
    }

    /**
     * Returns oxorderfiles list
     *
     * @param string $sOrderId - order id
     */
    public function loadOrderFiles($sOrderId)
    {
        $oOrderFile = $this->getBaseObject();
        $sFields = $oOrderFile->getSelectFields();
        $sShopId = $this->getConfig()->getShopId();

        $oOrderFile->addFieldName('oxorderfiles__oxarticletitle');
        $oOrderFile->addFieldName('oxorderfiles__oxarticleartnum');

        $sSql = "SELECT " . $sFields . " ,
                      `oxorderarticles`.`oxtitle` AS `oxorderfiles__oxarticletitle`,
                      `oxorderarticles`.`oxartnum` AS `oxorderfiles__oxarticleartnum`,
                      `oxfiles`.`oxpurchasedonly` AS `oxorderfiles__oxpurchasedonly`
                    FROM `oxorderfiles`
                        LEFT JOIN `oxorderarticles` ON `oxorderarticles`.`oxid` = `oxorderfiles`.`oxorderarticleid`
                        LEFT JOIN `oxfiles` ON `oxfiles`.`oxid` = `oxorderfiles`.`oxfileid`
                    WHERE `oxorderfiles`.`oxorderid` = '" . $sOrderId . "' AND `oxorderfiles`.`oxshopid` = '" . $sShopId . "'
                        AND `oxorderarticles`.`oxstorno` = 0";

        $this->selectString($sSql);
    }
}
