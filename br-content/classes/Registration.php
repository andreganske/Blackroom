<?php

class Registration {

    private $db_connection = null;
    public $errors = array();
    public $messages = array();

    public function __construct() {
        if (isset($_POST["register"])) {
            $this->registerNewUser();
        }
    }

    private function registerNewUser() {

            // create a database connection
        $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            // change character set to utf8 and check it
        if (!$this->db_connection->set_charset("utf8")) {
            $this->errors[] = $this->db_connection->error;
        }

            // if no connection errors (= working database connection)
        if (!$this->db_connection->connect_errno) {

                // escaping, additionally removing everything that could be (html/javascript-) code
            $user_name = $this->db_connection->real_escape_string(strip_tags($_POST['user_name'], ENT_QUOTES));
            $user_email = $this->db_connection->real_escape_string(strip_tags($_POST['user_email'], ENT_QUOTES));

            $user_password = $_POST['user_password_new'];

                // crypt the user's password with PHP 5.5's password_hash() function, results in a 60 character
                // hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using
                // PHP 5.3/5.4, by the password hashing compatibility library
            $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);

                // check if user or email address already exists
            $sql = "SELECT * FROM users WHERE user_name = '" . $user_name . "' OR user_email = '" . $user_email . "';";
            $query_check_user_name = $this->db_connection->query($sql);

            if ($query_check_user_name->num_rows == 1) {
                $this->errors[] = "Sorry, that username / email address is already taken.";
            } else {
                    // write new user's data into database
                $sql = "INSERT INTO users (user_name, user_password_hash, user_email)
                VALUES('" . $user_name . "', '" . $user_password_hash . "', '" . $user_email . "');";
                $query_new_user_insert = $this->db_connection->query($sql);

                    // if user has been added successfully
                if ($query_new_user_insert) {
                    $this->messages[] = "Your account has been created successfully. You can now log in.";
                } else {
                    $this->errors[] = "Sorry, your registration failed. Please go back and try again.";
                }
            }
        } else {
            $this->errors[] = "Sorry, no database connection.";
        }
    }
}
