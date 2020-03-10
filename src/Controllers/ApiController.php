<?php

namespace App\Controllers;

class ApiController extends Controller {

	public function check($request, $response) {
	    if(isset($_SESSION["id"])) {
			return $response->withJson($this->getToken());
	    }
        else{
            return $response->withJson(array(
                'code' => 401,
                'message' => 'Not Allowed'
            ));
        }
	}

	public function login($request, $response) {
	    $body = $request->getParsedBody();

	    if($body["username"] == "drum") {
	        $_SESSION["id"] = $body["username"];
	        $_SESSION["username"] = $body["username"];
	        return $response->withJson($this->getToken());
	    }
	    else{
            return $response->withJson(array(
                'code' => 401,
                'message' => 'Not Allowed'
            ));
        }
	}

	public function logout($request, $response) {
	    session_destroy();
        return $response->withJson(array(
            'code' => 204,
            'message' => 'Logged Out.'
        ));
	}

	private function getToken() {
        return array(
            "token" => session_id(),
            "username" => $_SESSION["username"]
        );
	}
}