<?php

// Allow options call on all routes
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

//routes
$app->get('/', 'HomeController:landing');

//Login Auth
$app->post('/api/v1', 'ApiController:login');
$app->get('/api/v1', 'ApiController:check');
$app->delete('/api/v1', 'ApiController:logout');

//SIP Extensions
$app->get('/api/v1/sipExtensions', 'FreePbxController:getAllSIPExtensions');
$app->post('/api/v1/sipExtension', 'FreePbxController:createSIPExtension');

//SIP Trunks
$app->get('/api/v1/sipTrunks', 'FreePbxController:getAllSIPTrunks');
$app->post('/api/v1/sipTrunk', 'FreePbxController:createSIPTrunk');

