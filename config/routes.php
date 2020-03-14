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
$app->put('/api/v1/sipExtension/{extension}', 'FreePbxController:updateSIPExtension');
$app->delete('/api/v1/sipExtension/{extension}', 'FreePbxController:deleteSIPExtension');

//SIP Trunks
$app->get('/api/v1/sipTrunks', 'FreePbxController:getAllSIPTrunks');
$app->post('/api/v1/sipTrunk', 'FreePbxController:createSIPTrunk');
$app->put('/api/v1/sipTrunk/{trunkName}', 'FreePbxController:updateSIPTrunk');
$app->delete('/api/v1/sipTrunk/{trunkName}', 'FreePbxController:deleteSIPTrunk');

//Inbound Routes
$app->get('/api/v1/inboundRoutes', 'FreePbxController:getAllInboundRoutes');
$app->post('/api/v1/inboundRoute', 'FreePbxController:createInboundRoute');
$app->put('/api/v1/inboundRoute/{routeName}/{did}', 'FreePbxController:updateInboundRoute');
$app->delete('/api/v1/inboundRoute/{routeName}/{did}', 'FreePbxController:deleteInboundRoute');

//Outbound Routes
$app->get('/api/v1/outboundRoutes', 'FreePbxController:getAllOutboundRoutes');
$app->post('/api/v1/outboundRoute', 'FreePbxController:createOutboundRoute');
$app->put('/api/v1/outboundRoute/{routeName}', 'FreePbxController:updateOutboundRoute');
$app->delete('/api/v1/outboundRoute/{routeName}', 'FreePbxController:deleteOutboundRoute');


$app->get('/api/v1/checkSQLite', 'FreePbxController:checkSQLite');