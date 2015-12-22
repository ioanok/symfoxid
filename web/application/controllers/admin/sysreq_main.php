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
 * Collects System information.
 * Admin Menu: Service -> System Requirements -> Main.
 */
class sysreq_main extends oxAdminDetails
{

    /**
     * Loads article Mercators info, passes it to Smarty engine and
     * returns name of template file "Mercator_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $oSysReq = new oxSysRequirements();

        $this->_aViewData['aInfo'] = $oSysReq->getSystemInfo();
        $this->_aViewData['aCollations'] = $oSysReq->checkCollation();

        return "sysreq_main.tpl";
    }

    /**
     * Returns module state
     *
     * @param int $iModuleState state integer value
     *
     * @return string
     */
    public function getModuleClass($iModuleState)
    {
        switch ($iModuleState) {
            case 2:
                $sClass = 'pass';
                break;
            case 1:
                $sClass = 'pmin';
                break;
            case -1:
                $sClass = 'null';
                break;
            default:
                $sClass = 'fail';
                break;
        }
        return $sClass;
    }

    /**
     * Returns hint URL
     *
     * @param string $sIdent Module ident
     *
     * @return string
     */
    public function getReqInfoUrl($sIdent)
    {
        $oSysReq = new oxSysRequirements();
        $sUrl = $oSysReq->getReqInfoUrl($sIdent);

        return $sUrl;
    }

    /**
     * return missing template blocks
     *
     * @see oxSysRequirements::getMissingTemplateBlocks
     *
     * @return array
     */
    public function getMissingTemplateBlocks()
    {
        $oSysReq = oxNew('oxSysRequirements');

        return $oSysReq->getMissingTemplateBlocks();
    }
}
