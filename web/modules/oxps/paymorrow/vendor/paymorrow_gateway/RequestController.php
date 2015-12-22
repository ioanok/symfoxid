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

class RequestController
{
    private $gateway;
    private $resourceProxy;

    public function pmVerify($data)
    {
        $_SESSION["pmVerify"] = $data;

        return $this->gateway->prepareOrder($data);
    }

	public function pmConfirm()
	{
		return $this->gateway->confirmOrder();
	}
	
    public function getResource($path, $session_id = null)
    {
        return $this->resourceProxy->getResource($path, $session_id);
    }

    /**
     * @param mixed $gateway
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return mixed
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param mixed $resourceProxy
     */
    public function setResourceProxy($resourceProxy)
    {
        $this->resourceProxy = $resourceProxy;
    }

}

