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
$app->put('/api/v1/sipExtension/{id}', 'FreePbxController:updateSIPExtension');
$app->delete('/api/v1/sipExtension/{id}', 'FreePbxController:deleteSIPExtension');

//SIP Trunks
$app->get('/api/v1/sipTrunks', 'FreePbxController:getAllSIPTrunks');
$app->post('/api/v1/sipTrunk', 'FreePbxController:createSIPTrunk');
$app->put('/api/v1/sipTrunk/{id}', 'FreePbxController:updateSIPTrunk');
$app->delete('/api/v1/sipTrunk/{id}', 'FreePbxController:deleteSIPTrunk');

