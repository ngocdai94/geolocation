<?php 
// config.php

// THIS_PAGE is a file path
define ('THIS_PAGE', $_SERVER['PHP_SELF']);
define ('FILE_NAME', basename(THIS_PAGE));

// Get API Keys
require_once('api_keys.php');

// helps prevent date errors
date_default_timezone_set('America/Los_Angeles');

// parent directory
$parentDir = dirname(THIS_PAGE, 1);

// dynamic page variables
$title = '';
$homeActive = '';
$mysqlActive = '';
$dmsActive = '';

// dynamic css variable
$cssForm = '';
$dmsCSS = '';

// Google Maps Script
$googleMaps = '';
$mysqlScript = '';

// edit dynamic pages
$title = FILE_NAME;
switch(FILE_NAME){
    case 'index.php':
        $title = "Sync & Scale MySQL Geolocation";
        //$logo="fa-home";
        $homeActive = 'active';
    break;

    case 'mysql-geolocation.php':
        $title = "MySQL Database";
        //$logo="fa-home";
        $mysqlActive = 'active';
        $mysqlScript = '/shared/js/googleGeolocation_MySQL.js';
        $googleMaps = GOOGLE_MAPS;
        break;

    case 'dms-geolocation.php':
        $title = "DMS Geolocation";
        $dmsActive = 'active';
        $dmsCSS = '/shared/css/dms.css';
        $mysqlScript = '/shared/js/googleGeolocation_Embedded.js';
        $googleMaps = GOOGLE_MAPS;
    break;
};
?>