<?php
/**
 * Created by PhpStorm.
 * User: ioan
 * Date: 18.12.2015
 * Time: 12:12 PM
 */

use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class sxSymfonyOxUtilsObject extends sxSymfonyOxUtilsObject_parent
{
    protected function _getObject($sClassName, $iArgCnt, $aParams)
    {
        $oObject = parent::_getObject($sClassName, $iArgCnt, $aParams);

        if ($oObject instanceof ContainerAwareInterface) {
            global $kernel;
            $oObject->setContainer($kernel->getContainer());
        }

        return $oObject;
    }
}