<?php

$sMetadataVersion = '1.0';

/**
 * Module information
 */
$aModule = array(
    'id'            => 'sxsymfony',
    'title'         => 'Sologics :: Symfony Bridge Module',
    'description'   => 'OXID integration with Symfony components.',
    'thumbnail'     => 'picture.png',
    'version'       => '1.0.0-dev',
    'author'        => 'Sologics',
    'email'         => 'ia@sologics.de',
    'url'           => 'http://www.sologics.de/',

    'extend' => array(
        'oxshopcontrol' => 'sologics/symfony/core/sxsymfonyoxshopcontrol',
        'oxutilsobject' => 'sologics/symfony/core/sxsymfonyoxutilsobject',
    )
);