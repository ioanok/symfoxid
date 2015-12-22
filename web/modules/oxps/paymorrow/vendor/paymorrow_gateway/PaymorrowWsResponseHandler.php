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
 */

/* OXPS Patch - Start *///namespace Paymorrow;/* OXPS Patch - End */

interface PaymorrowWsResponseHandler
{
    public function handlePrepareOrderResponseOK($responseData);

    public function handlePrepareOrderResponseError($responseData);

    public function handleConfirmOrderResponseOK($responseData);

    public function handleConfirmOrderResponseError($responseData);
} 