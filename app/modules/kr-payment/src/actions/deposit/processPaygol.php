<?php

/**
 * Process payment paypal action
 *
 * @package Krypto
 * @author Ovrley <hello@ovrley.com>
 */

session_start();

require "../../../../../../config/config.settings.php";
require $_SERVER['DOCUMENT_ROOT'].FILE_PATH."/vendor/autoload.php";
require $_SERVER['DOCUMENT_ROOT'].FILE_PATH."/app/src/MySQL/MySQL.php";
require $_SERVER['DOCUMENT_ROOT'].FILE_PATH."/app/src/App/App.php";
require $_SERVER['DOCUMENT_ROOT'].FILE_PATH."/app/src/App/AppModule.php";
require $_SERVER['DOCUMENT_ROOT'].FILE_PATH."/app/src/User/User.php";

try {

    // Load app modules
    $App = new App(true);
    $App->_loadModulesControllers();

} catch (Exception $e) {
    error_log($e->getMessage());

}
