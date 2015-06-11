<?php

    $app->get('/session', function() {

        $db = new DbHandler();
        $session = $db->getSession();

        $response["customer_id"] = $session['customer_id'];
        $response["email"] = $session['email'];
        $response["name"] = $session['name'];

        echoResponse(200, $session);
    });

    $app->post('/login', function() use ($app) {

        require_once 'passwordHash.php';
        
        $r = json_decode($app->request->getBody());
        verifyRequiredParams(array('email', 'password'),$r->customer);
        
        $response = array();
        $db = new DbHandler();
        
        $password = $r->customer->password;
        $email = $r->customer->email;
        $user = $db->getOneRecord("select customer_id, name, password, email, active from br_customer where email='$email'");
        
        if ($user != NULL) {
            if (passwordHash::check_password($user['password'], $password)) {
                $response['status'] = "success";
                $response['message'] = 'Logged in successfully.';
                $response['name'] = $user['name'];
                $response['customer_id'] = $user['customer_id'];
                $response['email'] = $user['email'];
                $response['active'] = $user['active'];

                if (!isset($_SESSION)) {
                    session_start();
                }

                $_SESSION['customer_id'] = $user['customer_id'];
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $user['name'];

            } else {
                $response['status'] = "error";
                $response['message'] = 'Login failed. Incorrect credentials';
            }
        } else {
            $response['status'] = "error";
            $response['message'] = 'No such user is registered';
        }

        echoResponse(200, $response);
    });

    $app->post('/signUp', function() use ($app) {
        require_once 'passwordHash.php';
        
        $response = array();
        $db = new DbHandler();
        $r = json_decode($app->request->getBody());
        verifyRequiredParams(array('email', 'name', 'password'),$r->customer);


        $name = $r->customer->name;
        $email = $r->customer->email;
        $password = $r->customer->password;
        $isUserExists = $db->getOneRecord("select 1 from br_customer where email='$email'");

        if (!$isUserExists) {
            $r->customer->password = passwordHash::hash($password);
            $tabble_name = "br_customer";
            $column_names = array('name', 'email', 'password');
            $result = $db->insertIntoTable($r->customer, $column_names, $tabble_name);

            if ($result != NULL) {
                $response["status"] = "success";
                $response["message"] = "User account created successfully";
                $response["customer_id"] = $result;

                if (!isset($_SESSION)) {
                    session_start();
                }

                $_SESSION['customer_id'] = $response["customer_id"];
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;

                echoResponse(200, $response);
            } else {
                $response["status"] = "error";
                $response["message"] = "Failed to create customer. Please try again";

                echoResponse(201, $response);
            }
        } else {
            $response["status"] = "error";
            $response["message"] = "An user with the provided email exists!";
            echoResponse(201, $response);
        }
    });

    $app->get('/logout', function() {
        $db = new DbHandler();
        $session = $db->destroySession();
        
        $response["status"] = "info";
        $response["message"] = "Logged out successfully";

        echoResponse(200, $response);
    });

?>