<?php

    require '.././libs/Slim/Slim.php';
    require_once 'dbHelper.php';

    \Slim\Slim::registerAutoloader();
    $app = new \Slim\Slim();
    $app = \Slim\Slim::getInstance();

    $db = new dbHelper();

    $app->get('/br_album', function() {
        global $db;

        $rows = $db->select("br_album", "album_id", array());
        
        echoResponse(200, $rows);
    });

    $app->post('/br_album', function() use ($app) {
        global $db;
        
        $data = json_decode($app->request->getBody());
        $mandatory = array('name');

        $rows = $db->insert("br_album", $data, $mandatory);

        if ($rows["status"] == "success") {
            $rows["message"] = "Product added successfully.";
        }
        echoResponse(200, $rows);
    });

    $app->put('/br_album/:id', function($id) use ($app) {
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

    $app->delete('/br_album/:id', function($id) {
        global $db;

        $rows = $db->delete("br_album", array('id'=>$id));
        
        if ($rows["status"] == "success") {
            $rows["message"] = "Product removed successfully.";            
        }
        
        echoResponse(200, $rows);
    });

    function echoResponse($status_code, $response) {
        global $app;
        $app->status($status_code);
        $app->contentType('application/json');
        echo json_encode($response,JSON_NUMERIC_CHECK);
    }

    $app->run();

?>