<?php

// Allow options call on all routes
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});


$app->get('/', 'HomeController:landing');

$app->post('/api', 'ApiController:login');
$app->get('/api', 'ApiController:check');
$app->delete('/api', 'ApiController:logout');

$app->get('/api/sip', 'FreePbxController:getAll');
