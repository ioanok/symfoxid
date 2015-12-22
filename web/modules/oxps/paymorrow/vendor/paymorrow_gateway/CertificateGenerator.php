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


class CertificateGenerator
{
    public function generateCertificate($certData)
    {
        $configParams = array(
            "digest_alg" => "sha512",
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );

        $privkey = openssl_pkey_new($configParams);

        //Now, using the private key, we can create the certificate. First we define the certificate parameters:

        //And then we can create the certificate:

        $csr = openssl_csr_new($certData, $privkey, $configParams);

        //Now we sign the certificate using the private key:

        $duration = 2 * 365;
        $sscert = openssl_csr_sign($csr, null, $privkey, $duration, $configParams);

        //Finally we can export the certificate and the private key:

        openssl_x509_export($sscert, $certout);
        $password = NULL;
        openssl_pkey_export($privkey, $pkout, $password, $configParams);
        //Note that a password is needed to export the private key. If a password is not needed, you must set $password
        //to NULL (don't set it to empty string as the private key password will be an empty string).

        return array(
            'privateKey' => $pkout,
            'certificate' => $certout
        );
    }
}

