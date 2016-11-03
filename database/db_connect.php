<?php

/**
 * Created by PhpStorm.
 * User: xenial
 * Date: 11/2/16
 * Time: 11:39 AM
 */
class DB_CONNECT
{
    // constructor
    function __construct() {
        // connecting to database
        $this->connect();
    }

    // destructor
    function __destruct() {
        // closing db connection
        $this->close();
    }

    /**
     * Function to connect with database
     */
    function connect() {
        // import database connection variables
        require_once __DIR__ . '/db_config.php';

        // Connecting to mysql database
        $con = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD) or die(mysqli_connect_error());

        // Selecing database
        $db = mysqli_select_db($con,DB_DATABASE) or die(mysqli_connect_error());

        // returing connection cursor
        return $con;
    }

    /**
     * Function to close db connection
     */
    function close() {
        // closing db connection
//        mysqli_close();
    }
}