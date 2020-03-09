<?php

namespace App\Controllers;

class HomeController extends Controller {

	public function landing($request, $response) {
        return $response->withJson(array(
            'message'   =>  'FreePBX API Server',
            'version'   =>  '1.0.0'
        ));
	}
}