<?php

use Slim\Container;

/** @var \Slim\App $app */
$container = $app->getContainer();

// Activating routes in a subfolder
$container['environment'] = function () {
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $_SERVER['SCRIPT_NAME'] = dirname(dirname($scriptName)) . '/' . basename($scriptName);
    return new Slim\Http\Environment($_SERVER);
};

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$container['sqlite'] = function ($c) {
    $db = $c['settings']['sqlite'];
    $pdoSQLite = new PDO("sqlite:" . $db['path']);
    $pdoSQLite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdoSQLite->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdoSQLite;
};

$container['notAllowedHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']->withStatus(405);
    };
};

$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']->withStatus(404);
    };
};

$container['HomeController'] = function ($c) {
    return new App\Controllers\HomeController($c);
};

$container['ApiController'] = function ($c) {
    return new App\Controllers\ApiController($c);
};

$container['FreePbxController'] = function ($c) {
    return new App\Controllers\FreePbxController($c);
};