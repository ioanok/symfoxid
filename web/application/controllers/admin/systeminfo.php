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
 * Admin systeminfo manager.
 * Returns template, that arranges two other templates ("delivery_list.tpl"
 * and "delivery_main.tpl") to frame.
 */
class SystemInfo extends oxAdminView
{

    /**
     * Executes parent method parent::render(), prints shop and
     * PHP configuration information.
     *
     * @return null
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        parent::render();

        $oAuthUser = oxNew('oxuser');
        $oAuthUser->loadAdminUser();
        $blisMallAdmin = $oAuthUser->oxuser__oxrights->value == "malladmin";

        if ($blisMallAdmin && !$myConfig->isDemoShop()) {
            $aClassVars = get_object_vars($myConfig);
            $aSystemInfo = array();
            $aSystemInfo['pkg.info'] = $myConfig->getPackageInfo();
            $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();
            while (list($name, $value) = each($aClassVars)) {
                if (gettype($value) == "object") {
                    continue;
                }
                // security fix - we do not output dbname and dbpwd cause of demoshops
                if ($name == "oDB" || $name == "dbUser" || $name == "dbPwd" ||
                    $name == "oSerial" || $name == "aSerials" || $name == "sSerialNr"
                ) {
                    continue;
                }

                $value = var_export($value, true);
                $value = str_replace("\n", "<br>", $value);
                $aSystemInfo[$name] = $value;
                //echo( "$name = $value <br>");
            }
            $oSmarty->assign("oViewConf", $this->_aViewData["oViewConf"]);
            $oSmarty->assign("oView", $this->_aViewData["oView"]);
            $oSmarty->assign("shop", $this->_aViewData["shop"]);
            $oSmarty->assign("isdemo", $myConfig->isDemoShop());
            $oSmarty->assign("aSystemInfo", $aSystemInfo);
            echo $oSmarty->fetch("systeminfo.tpl");
            echo("<br><br>");

            phpinfo();

            oxRegistry::getUtils()->showMessageAndExit("");
        } else {
            return oxRegistry::getUtils()->showMessageAndExit("Access denied !");
        }
    }
}
