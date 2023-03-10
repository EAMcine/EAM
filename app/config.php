<?php

// Load the .env file

require_once 'envLoader.php';

$envLoader = new DotEnv(__DIR__.'/.env');
$envLoader->load();

// Informations about the site

define('DEV_NAME', 'Alban, Enzo, Mathis');
define('SITE_NAME', 'EAM+');
define('SEPARATOR', ' - ');
define('HOME_URL', getenv('HOME_URL'));

// Informations about the database

define('DB_HOST', getenv('DB_HOST'));
define('DB_NAME', getenv('DB_NAME'));
define('DB_PASSWORD' ,getenv('DB_PASSWORD'));
define('DB_PORT', getenv('DB_PORT'));
define('DB_USER', getenv('DB_USER'));

// Informations about HTML definition

define('DOCTYPE', 'html');
define('LANG', 'fr');
define('CHARSET', 'UTF-8');
define('HTTP_EQUIV', 'X-UA-Compatible');
define('CONTENT', 'IE=edge');
define('NAME_VIEWPORT', 'viewport');
define('CONTENT_VIEWPORT', 'width=device-width, initial-scale=1.0');

define('CONFIG_LOADED', true);

?>