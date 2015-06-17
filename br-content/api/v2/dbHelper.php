<?php

    require_once '../config.php';

    class dbHelper {

        private $db;
        private $err;

        function __construct() {
            $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8';
            try {
                $this->db = new PDO($dsn, DB_USERNAME, DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            } catch (PDOException $e) {
                $response["status"] = "error";
                $response["message"] = 'Connection failed: ' . $e->getMessage();
                $response["data"] = null;
                exit;
            }
        }

        function select($table, $columns, $where) {
            try {
                $a = array();
                $w = "";

                foreach ($where as $key => $value) {
                    if ($w == "") {
                        $w .= " " .$key. " like :".$key;    
                    } else {
                        $w .= " and " .$key. " like :".$key;
                    }
                    $a[":".$key] = $value;
                }

                $stmt = $this->db->prepare("SELECT $columns FROM $table WHERE $w");
                $stmt->execute($a);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($rows) <= 0) {
                    $response["status"] = "warning";
                    $response["message"] = "No data found.";
                } else {
                    $response["status"] = "success";
                    $response["message"] = "Data selected from database";
                }
                $response["data"] = $rows;
            } catch(PDOException $e) {
                $response["status"] = "error";
                $response["message"] = 'Select Failed: ' .$e->getMessage();
                $response["stmt"] = $stmt;
                $response["data"] = null;
            }
            return $response;
        }

        function selectOrder($table, $columns, $where, $order) {
            try {
                $a = array();
                $w = "";

                foreach ($where as $key => $value) {
                    if ($w == "") {
                        $w .= " " .$key. " like :".$key;    
                    } else {
                        $w .= " and " .$key. " like :".$key;
                    }
                    $a[":".$key] = $value;
                }

                $stmt = $this->db->prepare("SELECT $columns FROM $table WHERE $w $order");
                $stmt->execute($a);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($rows) <= 0) {
                    $response["status"] = "warning";
                    $response["message"] = "No data found.";
                } else {
                    $response["status"] = "success";
                    $response["message"] = "Data selected from database";
                }
                $response["data"] = $rows;
            } catch(PDOException $e) {
                $response["status"] = "error";
                $response["message"] = 'Select Failed: ' .$e->getMessage();
                $response["data"] = null;
            }
            return $response;
        }

        function selectSize($table, $columns, $where, $size) {
            try {
                $a = array();
                $w = "";

                foreach ($where as $key => $value) {
                    if ($w == "") {
                        $w .= " " .$key. " like :".$key;    
                    } else {
                        $w .= " and " .$key. " like :".$key;
                    }
                    $a[":".$key] = $value;
                }

                $stmt = $this->db->prepare("SELECT $columns FROM $table WHERE $w LIMIT $size");
                $stmt->execute($a);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($rows) <= 0) {
                    $response["status"] = "warning";
                    $response["message"] = "No data found.";
                } else {
                    $response["status"] = "success";
                    $response["message"] = "Data selected from database";
                }
                $response["data"] = $rows;
            } catch(PDOException $e) {
                $response["status"] = "error";
                $response["message"] = 'Select Failed: ' .$e->getMessage();
                $response["data"] = null;
            }
            return $response;
        }

        function insert($table, $columnsArray, $requiredColumnsArray) {
            $this->verifyRequiredParams($columnsArray, $requiredColumnsArray);

            try {
                $a = array();
                $c = "";
                $v = "";

                foreach ($columnsArray as $key => $value) {
                    $c .= $key. ", ";
                    $v .= ":".$key. ", ";
                    $a[":".$key] = $value;
                }

                $c = rtrim($c,', ');
                $v = rtrim($v,', ');
                $stmt =  $this->db->prepare("INSERT INTO $table($c) VALUES($v)");
                $stmt->execute($a);
                $affected_rows = $stmt->rowCount();
                $lastInsertId = $this->db->lastInsertId();
                $response["status"] = "success";
                $response["message"] = $affected_rows." row inserted into database";
                $response["data"] = $lastInsertId;
            } catch(PDOException $e) {
                $response["status"] = "error";
                $response["message"] = 'Insert Failed: ' .$e->getMessage();
                $response["data"] = 0;
            }
            return $response;
        }

        function update($table, $columnsArray, $where, $requiredColumnsArray) {
            $this->verifyRequiredParams($columnsArray, $requiredColumnsArray);
            try {
                $a = array();
                $w = "";
                $c = "";

                foreach ($where as $key => $value) {
                    if ($w != "") {
                        $w .= " and " .$key. " = :".$key;    
                    } else {
                        $w .= " " .$key. " = :".$key;
                    }
                    $a[":".$key] = $value;
                }

                foreach ($columnsArray as $key => $value) {
                    $c .= $key. " = :".$key.", ";
                    $a[":".$key] = $value;
                }
                
                $c = rtrim($c,", ");
                $stmt =  $this->db->prepare("UPDATE $table SET $c WHERE ".$w);
                $stmt->execute($a);
                $affected_rows = $stmt->rowCount();

                if ($affected_rows <= 0) {
                    $response["status"] = "warning";
                    $response["message"] = "No row updated";
                } else {
                    $response["status"] = "success";
                    $response["message"] = $affected_rows." row(s) updated in database";
                }
            } catch(PDOException $e) {
                $response["status"] = "error";
                $response["stmt"] = $stmt;
                $response["message"] = "Update Failed: " .$e->getMessage();
            }
            return $response;
        }

        function delete($table, $where) {
            if (count($where) <= 0) {
                $response["status"] = "warning";
                $response["message"] = "Delete Failed: At least one condition is required";
            } else {
                try {
                    $a = array();
                    $w = "";

                    foreach ($where as $key => $value) {
                        $w .= " and " .$key. " = :".$key;
                        $a[":".$key] = $value;
                    }

                    $stmt =  $this->db->prepare("DELETE FROM $table WHERE 1=1 ".$w);
                    $stmt->execute($a);
                    $affected_rows = $stmt->rowCount();

                    if ($affected_rows <= 0) {
                        $response["status"] = "warning";
                        $response["message"] = "No row deleted";
                    } else {
                        $response["status"] = "success";
                        $response["message"] = $affected_rows." row(s) deleted from database";
                    }
                } catch(PDOException $e) {
                    $response["status"] = "error";
                    $response["message"] = 'Delete Failed: ' .$e->getMessage();
                }
            }
            return $response;
        }

        function verifyRequiredParams($inArray, $requiredColumns) {
            $error = false;
            $errorColumns = "";

            foreach ($requiredColumns as $field) {
                if (!isset($inArray->$field) || strlen(trim($inArray->$field)) <= 0) {
                    $error = true;
                    $errorColumns .= $field . ', ';
                }
            }

            if ($error) {
                $response = array();
                $response["status"] = "error";
                $response["message"] = 'Required field(s) ' . rtrim($errorColumns, ', ') . ' is missing or empty';
                echoResponse(200, $response);
                exit;
            }
        }

        function getSession() {
            if (!isset($_SESSION)) {
                session_start();
            }

            $sess = array();
            if (isset($_SESSION['uid'])) {
                $sess["uid"] = $_SESSION['uid'];
                $sess["name"] = $_SESSION['name'];
                $sess["email"] = $_SESSION['email'];
            } else {
                $sess["uid"] = '';
                $sess["name"] = 'Guest';
                $sess["email"] = '';
            }

            return $sess;
        }

        function destroySession() {
            if (!isset($_SESSION)) {
                session_start();
            }

            if (isSet($_SESSION['uid'])) {
                unset($_SESSION['uid']);
                unset($_SESSION['name']);
                unset($_SESSION['email']);

                $info = 'info';

                if (isSet($_COOKIE[$info])) {
                    setcookie ($info, '', time() - $cookie_time);
                }
                $msg = "Logged Out Successfully...";
            } else {
                $msg = "Not logged in...";
            }
            return $msg;
        }
    }
?>
