<?php

    require '.././libs/Slim/Slim.php';
    require_once 'passwordHash.php';
    require_once 'dbHelper.php';

    \Slim\Slim::registerAutoloader();
    $app = new \Slim\Slim();
    $app = \Slim\Slim::getInstance();

    $db = new dbHelper();

    $app->get('/photos', function() {
        global $db;

        $rows = $db->select("br_album", array(), array());
        
        echoResponse(200, $rows);
    });

    $app->post('/photos', function() use ($app) {
        global $db;
        
        $data = json_decode($app->request->getBody());
        $mandatory = array('name');

        $rows = $db->insert("br_album", $data, $mandatory);

        if ($rows["status"] == "success") {
            $rows["message"] = "Product added successfully.";
        }
        echoResponse(200, $rows);
    });

    $app->put('/photos/:id', function($id) use ($app) {
        global $db;
        
        $data = json_decode($app->request->getBody());
        $condition = array('id'=>$id);
        $mandatory = array();
        
        $rows = $db->update("br_album", $data, $condition, $mandatory);
        
        if ($rows["status"] == "success") {
            $rows["message"] = "Product information updated successfully.";
        }
        
        echoResponse(200, $rows);
    });

    $app->delete('/photos/:id', function($id) {
        global $db;

        $rows = $db->delete("br_album", array('id'=>$id));
        
        if ($rows["status"] == "success") {
            $rows["message"] = "Product removed successfully.";            
        }
        
        echoResponse(200, $rows);
    });

    $app->get('/session', function() {

        global $db;
        $session = $db->getSession();

        $response["uid"] = $session['uid'];
        $response["email"] = $session['email'];
        $response["name"] = $session['name'];

        echoResponse(200, $session);
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
        $response["message"] = "Estamos tristes que você saiu do Blackroom. Promete que volta logo?";

        echoResponse(200, $response);
    });

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