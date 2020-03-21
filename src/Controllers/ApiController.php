<?php

namespace App\Controllers;

class ApiController extends Controller {

    public function __construct($container){
        parent::__construct($container);
        global $users;
        $users = $container->settings['users'];
    }

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
        global $users;
	    $body = $request->getParsedBody();

	    if(count($users) > 0){
	        $auth = 0;
	        foreach ($users as $user){
                if($body["username"] == $user['username'] && $body['password'] == $user['password']) {
                    $_SESSION["id"] = $body["username"];
                    $_SESSION["username"] = $body["username"];
                    $auth = 1;
                    break;
                }
            }
	        if($auth == 1){
                return $response->withJson($this->getToken());
            }
	        else{
                return $response->withJson(array(
                    'status'    =>  403,
                    'message'   =>  'Username or Password not correct. Please try again.'
                ));
            }
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