<?php

// Load the .env file

use Framework\Core\DotEnv as DotEnv;

$envLoader = new DotEnv(__DIR__.'/.env');
$envLoader->load();

// Informations about the environment

define('ENV_NAME', getenv('ENV_NAME'));

// Informations about the site

define('DEV_NAME', 'Alban, Enzo, Mathis');
define('SITE_NAME', 'EAM+');
define('SEPARATOR', ' -uo ');
define('HOME_URL', getenv('HOME_URL'));
define('API_URL', getenv('API_URL'));

// Informations about the database

define('DB_HOST', getenv('DB_HOST'));
define('DB_NAME', getenv('DB_NAME'));
define('DB_PASSWORD' ,getenv('DB_PASSWORD'));
define('DB_PORT', getenv('DB_PORT'));
define('DB_USER', getenv('DB_USER'));
define('DB_TYPE', getenv('DB_TYPE'));
define('DB_CHARSET', getenv('DB_CHARSET'));
define('DB_CHARSET_COLLATE', getenv('DB_CHARSET_COLLATE'));
define('DB_ENGINE', getenv('DB_ENGINE'));

// Informations about HTML definition

define('DOCTYPE', 'html');
define('LANG', 'fr');
define('CHARSET', 'UTF-8');
define('HTTP_EQUIV', 'X-UA-Compatible');
define('HTTP_EQUIV_CONTENT', 'IE=edge');
define('NAME_VIEWPORT', 'viewport');
define('CONTENT_VIEWPORT', 'width=device-width, initial-scale=1.0');

// Informations about the password hash
define('PASSWORD_HASH', getenv('PASSWORD_HASH'));

define('CONFIG_LOADED', true);
