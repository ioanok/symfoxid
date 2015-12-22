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

$sLangName = "Deutsch";

$aLang = array(
    "charset"                                      => "ISO-8859-15", // Supports DE chars like: �, �, �, etc.
    "oxpspaymorrow"                                => "Paymorrow",

    'NAVIGATION_PAYMORROW'                         => '<a href="https://paymorrow.de/" target="_blank">Paymorrow Payment</a>',
    'SHOP_MODULE_GROUP_oxpsPaymorrowConfiguration' => 'API-Konfiguration',
    'SHOP_MODULE_GROUP_oxpsPaymorrowProfileUpdate' => 'Bestelldaten-Aktualisierung',
    'OXPSPAYMORROW_PAYMENT_TYPE_INVOICE'           => 'Rechnungskauf',
    'OXPSPAYMORROW_PAYMENT_TYPE_DIRECT_DEBIT'      => 'Lastschriftverfahren',
    'oxpspaymorrow_form_error_log'                 => 'Protokoll',
    'oxpspaymorrow_paymorrow_info'                 => 'Paymorrow-Info',
    'oxpspaymorrow_payment_map'                    => 'Paymorrow',

    // Main Menu Settings
    'OXPSPAYMORROW_MAIN_MENU_SETTINGS_TITLE'       => 'Fehlerprotokoll',
    'SHOP_MODULE_paymorrowSandboxMode'             => 'Sandbox-Modus',
    'SHOP_MODULE_paymorrowMerchantId'              => 'Live-Webservice-Benutzer',
    'SHOP_MODULE_paymorrowMerchantIdTest'          => 'Test-Webservice-Benutzer',
    'SHOP_MODULE_paymorrowEndpointUrlTest'         => 'Test-Endpoint-URL',
    'SHOP_MODULE_paymorrowEndpointUrlProd'         => 'Live-Endpoint-URL',
    'SHOP_MODULE_paymorrowLoggingEnabled'          => 'Protokollierung aktivieren',
    'SHOP_MODULE_paymorrowResourcePath'            => 'Live-Resource-Pfad (JavaScript/CSS)',
    'SHOP_MODULE_paymorrowResourcePathTest'        => 'Test-Resource-Pfad (JavaScript/CSS)',
    'SHOP_MODULE_paymorrowOperationMode'           => 'Live-Operation-Mode',
    'SHOP_MODULE_paymorrowOperationModeTest'       => 'Test-Operation-Mode',

    // RSA Keys fields
    'SHOP_MODULE_paymorrowKeysJson'                => 'Feld aller Daten',
    'SHOP_MODULE_paymorrowPrivateKey'              => 'Live - aktiver privater Schl�ssel',
    'SHOP_MODULE_paymorrowPrivateKeyTest'          => 'Test - aktiver privater Schl�ssel',
    'SHOP_MODULE_paymorrowPublicKey'               => 'Live - aktiver �ffenticher Schl�ssel',
    'SHOP_MODULE_paymorrowPublicKeyTest'           => 'Test - aktiver �ffenticher Schl�ssel',
    'SHOP_MODULE_paymorrowPaymorrowKey'            => 'Live - �ffenticher Schl�ssel Paymorrow',
    'SHOP_MODULE_paymorrowPaymorrowKeyTest'        => 'Test - �ffenticher Schl�ssel Paymorrow',

    // Profile data normalization settings
    'SHOP_MODULE_paymorrowUpdateAddresses'         => 'Zur�ckspielen der Anschriften bei Ver�nderung im Checkout',
    'SHOP_MODULE_paymorrowUpdatePhones'            => 'Zur�ckspielen der Telefonnummer bei Ver�nderung im Checkout',

    // Help Idents
    'PM_HELP_ADMIN_PAYMENT_METHODS_ACTIVATE'       => 'Aktivierung bewirkt die Zuordnung dieser Zahlungsart zu Paymorrow.',
    'PM_HELP_ADMIN_PAYMENT_METHODS_INVOICE'        => 'Aktivierung bewirkt, dass diese Paymorrow zugeordnete Zahlungsart f�r den Paymorrow-Rechnungskauf freigeschaltet wird.',
    'PM_HELP_ADMIN_PAYMENT_METHODS_SDD'            => 'Aktivierung bewirkt, dass diese Paymorrow zugeordnete Zahlungsart f�r das Paymorrow-Lastschriftverfahren freigeschaltet wird.',
);
