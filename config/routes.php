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
$app->put('/api/v1/sipExtension/{id}', 'FreePbxController:updateSIPExtension'); //todo
$app->delete('/api/v1/sipExtension/{id}', 'FreePbxController:deleteSIPExtension'); //todo

//SIP Trunks
$app->get('/api/v1/sipTrunks', 'FreePbxController:getAllSIPTrunks');
$app->post('/api/v1/sipTrunk', 'FreePbxController:createSIPTrunk');
$app->put('/api/v1/sipTrunk/{id}', 'FreePbxController:updateSIPTrunk'); //todo
$app->delete('/api/v1/sipTrunk/{id}', 'FreePbxController:deleteSIPTrunk'); //todo

//Inbound Routes
$app->get('/api/v1/inboundRoutes', 'FreePbxController:getAllInboundRoutes');
$app->post('/api/v1/inboundRoute', 'FreePbxController:createInboundRoute');
$app->put('/api/v1/inboundRoute/{id}', 'FreePbxController:updateInboundRoute'); //todo
$app->delete('/api/v1/inboundRoute/{id}', 'FreePbxController:deleteInboundRoute'); //todo

//Outbound Routes
$app->get('/api/v1/outboundRoutes', 'FreePbxController:getAllOutboundRoutes');
$app->post('/api/v1/outboundRoute', 'FreePbxController:createOutboundRoute');
$app->put('/api/v1/outboundRoute/{id}', 'FreePbxController:updateOutboundRoute'); //todo
$app->delete('/api/v1/outboundRoute/{id}', 'FreePbxController:deleteOutboundRoute'); //todo


$app->get('/api/v1/checkSQLite', 'FreePbxController:checkSQLite');