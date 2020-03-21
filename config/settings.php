<?php

$settings = [];

// Slim settings
$settings['displayErrorDetails'] = true;
$settings['determineRouteBeforeAppMiddleware'] = true;

// Path settings
$settings['root'] = dirname(__DIR__);
$settings['temp'] = $settings['root'] . '/tmp';
$settings['public'] = $settings['root'] . '/public';

$settings['db']['host']   = 'locahost';
$settings['db']['user']   = 'root';
$settings['db']['pass']   = '';
$settings['db']['dbname'] = 'asterisk';

$settings['sqlite']['path'] = '/var/lib/asterisk/astdb.sqlite3';

$settings['users'] = array(
    array(
        "username"  => "drum",
        "password"  => "superSecret@#$%%"
    ),
);


return $settings;