<?php

	require '.././libs/Slim/Slim.php';
	require_once 'passwordHash.php';
	require_once 'dbHelper.php';

	\Slim\Slim::registerAutoloader();
	$app = new \Slim\Slim();
	$app = \Slim\Slim::getInstance();

	$db = new dbHelper();

/** 
 Gestao de usuário 
 */
	$app->get('/session', function() {

		global $db;
		$session = $db->getSession();

		$response["uid"] = $session['uid'];
		$response["email"] = $session['email'];
		$response["name"] = $session['name'];

		if ($session['uid'] != "") {
			$data = $db->selectSize("br_customer", "admin_id", array('customer_id' => $session['uid']), 1);
			$response["admin"] = $data['data'][0]['admin_id'];
		}
		
		echoResponse(200, $response);
	});

	$app->post('/login', function() use ($app) {
		require_once 'passwordHash.php';
		$response = array();

		$data = json_decode($app->request->getBody());
		verifyRequiredParams(array('email', 'password'), $data->customer);
		
		global $db;
		
		$password = $data->customer->password;
		$email = $data->customer->email;
		$condition = array('email' => $email);

		$query = $db->selectSize("br_customer", "customer_id, name, email, password, active", $condition, 1);

		if ($query["status"] == "success") {
			$user = $query["data"][0];

			if (passwordHash::check_password($user['password'], $password)) {
				$response['status'] = "success";
				$response['message'] = 'Oba! Você entrou no Blackroom!';
				$response['name'] = $user['name'];
				$response['customer_id'] = $user['customer_id'];
				$response['email'] = $user['email'];
				$response['active'] = $user['active'];

				if (!isset($_SESSION)) {
					session_start();
				}

				$_SESSION['uid'] = $user['customer_id'];
				$_SESSION['email'] = $email;
				$_SESSION['name'] = $user['name'];
			} else {
				$response['status'] = "error";
				$response['message'] = 'Ooopsss! Parece que seu email ou senha estão incorretos.';
			}
		} elseif ($query["status"] == "warning") {
			$response['status'] = "error";
			$response['message'] = 'Vixi! Parece que você ainda não está registrado!';
		} else {
			$response['status'] = "error";
			$response['message'] = 'Ooopsss!!! Ocorreu um erro grave no login!';
		}

		echoResponse(200, $response);
	});

	$app->post('/signUp', function() use ($app) {
		require_once 'passwordHash.php';
		$response = array();

		$data = json_decode($app->request->getBody());
		verifyRequiredParams(array('email', 'name', 'password'), $data->customer);
		
		global $db;
		
		$name = $data->customer->name;
		$email = $data->customer->email;
		$password = $data->customer->password;
		
		$obj = (object) array('name' => $name, 'email' => $email, 'password' => $password);

		$mandatory = array('name', 'email', 'password');
		$condition = array('email' => $email);

		$query = $db->selectSize("br_customer", "customer_id", $condition, 1);

		if ($query["status"] == "success") {

			$response["status"] = "error";
			$response["message"] = "Vixi! Esse email já está em uso por outro usuário!";

		} elseif ($query["status"] == "warning") {
			$obj->password = passwordHash::hash($password);
			$result = $db->insert("br_customer", $obj, $mandatory);

			if ($result["status"] == "success") {
				$response["status"] = "success";
				$response["message"] = "Olá, seja bem vindo! Seu usuário foi criado e já está disponível!";

				if (!isset($_SESSION)) {
					session_start();
				}

				$_SESSION['uid'] = $result["data"];
				$_SESSION['name'] = $name;
				$_SESSION['email'] = $email;
			} else {
				$response["status"] = "error";
				$response["message"] = "Ooopsss! Alguma coisa estranha aconteceu, e não conseguimos criar seu usuário!";
			}
		} else {
			$response['status'] = "error";
			$response['message'] = 'Ooopsss!!! Ocorreu um erro grave no login!';
		}

		echoResponse(200, $response);
	});

	$app->get('/logout', function() {
		global $db;
		$session = $db->destroySession();
		
		$response["status"] = "info";
		$response["message"] = "Você saiu do Blackroom. Até breve!";

		echoResponse(200, $response);
	});

/** 
 Album
 */
	$app->get('/album', function() {
		global $db;

		$user = $db->getSession();
		$condition = array('customer_id'=>$user['uid']);
		$response = $db->select("br_album", "album_id, name, description", $condition);

		echoResponse(200, $response);
	});

	$app->post('/album', function() use ($app) {
		global $db;
		$mandatory = array();
		
		$user = $db->getSession();
		$data = json_decode($app->request->getBody());
		$data->customer_id = $user['uid'];

		$response = $db->insert("br_album", $data, $mandatory);

		if ($response["status"] == "success") {
			$response["message"] = "Novo album criado com sucesso.";
		}

		echoResponse(200, $response);
	});

	$app->put('/album/:id', function($id) use ($app) {
		global $db;
		
		$user = $db->getSession();
		$data = json_decode($app->request->getBody());
		
		$condition = array('album_id'=>$id, 'customer_id'=>$user['uid']);
		$mandatory = array();
		
		$response = $db->update("br_album", $data, $condition, $mandatory);
		
		if ($response["status"] == "success") {
			$response["message"] = "Album atualizado com sucesso.";
		}
		
		echoResponse(200, $response);
	});

	$app->delete('/album/:id', function($id) {
		global $db;

		$user = $db->getSession();
		$condition = array('album_id'=>$id, 'customer_id'=>$user['uid']);

		$response = $db->delete("br_album", $condition);
		
		if ($response["status"] == "success") {
			$response["message"] = "Album removido com sucesso.";            
		}
		
		echoResponse(200, $response);
	});

/** 
 Photo
 */
	$app->get('/photo', function() {
		global $db;

		$user = $db->getSession();
		$condition = array('customer_id'=>$user['uid']);
		$response = $db->select("br_image", "image_id, name, description, path", $condition);

		echoResponse(200, $response);
	});

	$app->get('/guest/photos', function() {
		global $db;

		$user = $db->getSession();
		$data = $db->selectSize("br_customer", "admin_id", array('customer_id' => $user['uid']), 1);
		$admin = $data['data'][0]['admin_id'];

		$condition = array('customer_id'=>$admin);
		$response = $db->select("br_image", "image_id, name, description, path", $condition);

		echoResponse(200, $response);
	});

/** 
 Guest
 */
	$app->get('/guest', function() {
		global $db;

		$user = $db->getSession();
		$condition = array('admin_id'=>$user['uid']);
		$response = $db->select("br_customer", "customer_id, name, email, active", $condition);

		echoResponse(200, $response);
	});

	$app->post('/guest', function() use ($app) {
		require_once 'passwordHash.php';
		$response = array();

		$data = json_decode($app->request->getBody());
		verifyRequiredParams(array('email', 'name', 'password'), $data->guest);
		
		global $db;
		
		$name = $data->guest->name;
		$email = $data->guest->email;
		$password = $data->guest->password;

		$user = $db->getSession();
		$admin_id = $user['uid'];
		
		$obj = (object) array('name' => $name, 'email' => $email, 'password' => $password, 'admin_id' => $admin_id);

		$mandatory = array('name', 'email', 'password');
		$condition = array('email' => $email);

		$query = $db->selectSize("br_customer", "customer_id", $condition, 1);

		if ($query["status"] == "success") {

			$response["status"] = "error";
			$response["message"] = "Vixi! Esse email já está em uso por outro usuário!";

		} elseif ($query["status"] == "warning") {
			$obj->password = passwordHash::hash($password);
			$result = $db->insert("br_customer", $obj, $mandatory);

			if ($result["status"] == "success") {
				$response["status"] = "success";
				$response["message"] = "Oba! Seu convidado foi criado e já está disponível!";
			} else {
				$response["status"] = "error";
				$response["message"] = "Ooopsss! Alguma coisa estranha aconteceu, e não conseguimos criar seu convidado!";
			}
		} else {
			$response['status'] = "error";
			$response['message'] = 'Ooopsss!!! Ocorreu um erro grave ao criar um novo convidado!';
		}

		echoResponse(200, $response);
	});

	$app->put('/guest/:id', function($id) use ($app) {
		global $db;
		
		$user = $db->getSession();
		$data = json_decode($app->request->getBody());
		
		$condition = array('customer_id'=>$id, 'admin_id'=>$user['uid']);
		$mandatory = array();
		
		$response = $db->update("br_customer", $data, $condition, $mandatory);
		
		if ($response["status"] == "success") {
			$response["message"] = "Convidado atualizado com sucesso.";
		}
		
		echoResponse(200, $response);
	});

	$app->delete('/guest/:id', function($id) {
		global $db;

		$user = $db->getSession();
		$condition = array('customer_id'=>$id, 'admin_id'=>$user['uid']);

		$response = $db->delete("br_customer", $condition);
		
		if ($response["status"] == "success") {
			$response["message"] = "Convidado removido com sucesso.";
		}
		
		echoResponse(200, $response);
	});


/** 
 Host
 */
	$app->get('/host', function() {
		global $db;

		$user = $db->getSession();
		$data = $db->selectSize("br_customer", "admin_id", array('customer_id' => $user['uid']), 1);
		$host_id = $data['data'][0]['admin_id'];
		$response = $db->selectSize("br_customer", "customer_id, name, email, active", array('customer_id' => $host_id), 1);

		echoResponse(200, $response);
	});


/** 
 Utils
 */
	function echoResponse($status_code, $response) {
		global $app;
		$app->status($status_code);
		$app->contentType('application/json');
		echo json_encode($response,JSON_NUMERIC_CHECK);
	}

	function verifyRequiredParams($required_fields,$request_params) {
		$error = false;
		$error_fields = "";

		foreach ($required_fields as $field) {
			if (!isset($request_params->$field) || strlen(trim($request_params->$field)) <= 0) {
				$error = true;
				$error_fields .= $field . ', ';
			}
		}

		if ($error) {
			$response = array();
			$app = \Slim\Slim::getInstance();
			$response["status"] = "error";
			$response["message"] = 'Os campos ' . substr($error_fields, 0, -2) . ' são obrigatórios!';
			echoResponse(200, $response);
			$app->stop();
		}
	}

	$app->run();
?>


