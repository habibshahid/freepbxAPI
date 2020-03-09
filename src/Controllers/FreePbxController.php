<?php

namespace App\Controllers;

class FreePbxController extends Controller {

	public function getAll($request, $response) {
	    $sql = "SELECT * FROM customers;";
	    $stmt = $this->c->db->query($sql);
	    $customers = $stmt->fetchAll();

	    return $response->withJson($customers);
	}
}