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
  In case you need to extend current generator class:
    - create some alternative file;
    - edit htaccess file and replace core/utils/getimg.php with your custom handler;
    - add here function "getGeneratorInstanceName()" which returns name of your generator class;
    - implement class and required methods which extends "oxdynimggenerator" class
    e.g.:

      file name "testgenerator.php"

      function getGeneratorInstanceName()
      {
          return "testImageGenerator";
      }
      include_once "oxdynimggenerator.php";
      class testImageGenerator extends oxdynimggenerator.php {...}
*/

// including generator class
require_once "core/oxdynimggenerator.php";

// rendering requested image
oxDynImgGenerator::getInstance()->outputImage();
