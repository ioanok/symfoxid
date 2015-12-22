<?php
/**
 * Created by PhpStorm.
 * User: ioan
 * Date: 18.12.2015
 * Time: 11:46 AM
 */

global $kernel;

if ($kernel === null) {
    $env = getEnvironment();
    $debug = false;

    $kernel = new AppKernel($env, $debug);
    $kernel->boot();
}