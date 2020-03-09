<?php

require_once __DIR__ . '/../vendor/autoload.php';

session_name('FreePBXAPIServer');
session_start();

$app = new \Slim\App(['settings' => require __DIR__ . '/settings.php']);

// Set up dependencies
require  __DIR__ . '/container.php';

// Register middleware
require __DIR__ . '/middleware.php';

// Register routes
require __DIR__ . '/routes.php';

return $app;