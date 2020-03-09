<?php

$settings = [];

// Slim settings
$settings['displayErrorDetails'] = true;
$settings['determineRouteBeforeAppMiddleware'] = true;

// Path settings
$settings['root'] = dirname(__DIR__);
$settings['temp'] = $settings['root'] . '/tmp';
$settings['public'] = $settings['root'] . '/public';

$settings['db']['host']   = '165.22.193.196';
$settings['db']['user']   = 'habibshahid';
$settings['db']['pass']   = 'habibshahid1221';
$settings['db']['dbname'] = 'slimapp';

return $settings;