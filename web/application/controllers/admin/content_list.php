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
 * Admin Contents manager.
 * Collects Content base information (Description), there is ability to filter
 * them by Description or delete them.
 * Admin Menu: Customerinformations -> Content.
 */
class Content_List extends oxAdminList
{

    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = 'oxcontent';

    /**
     * Type of list.
     *
     * @var string
     */
    protected $_sListType = 'oxcontentlist';

    /**
     * Executes parent method parent::render() and returns name of template
     * file "Content_list.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $sFolder = oxRegistry::getConfig()->getRequestParameter("folder");
        $sFolder = $sFolder ? $sFolder : -1;

        $this->_aViewData["folder"] = $sFolder;
        $this->_aViewData["afolder"] = $this->getConfig()->getConfigParam('aCMSfolder');

        return "content_list.tpl";
    }

    /**
     * Adding folder check and empty folder field check
     *
     * @param array  $aWhere  SQL condition array
     * @param string $sqlFull SQL query string
     *
     * @return $sQ
     */
    protected function _prepareWhereQuery($aWhere, $sqlFull)
    {
        $sQ = parent::_prepareWhereQuery($aWhere, $sqlFull);
        $sFolder = oxRegistry::getConfig()->getRequestParameter('folder');
        $sViewName = getviewName("oxcontents");

        //searchong for empty oxfolder fields
        if ($sFolder == 'CMSFOLDER_NONE' || $sFolder == 'CMSFOLDER_NONE_RR') {
            $sQ .= " and {$sViewName}.oxfolder = '' ";
        } elseif ($sFolder && $sFolder != '-1') {
            $sFolder = oxDb::getDb()->quote($sFolder);
            $sQ .= " and {$sViewName}.oxfolder = {$sFolder}";
        }


        return $sQ;
    }
}
