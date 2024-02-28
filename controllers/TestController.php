<?php

include_once "ApiController.php" ;

class TestController extends ApiController {

    protected function do_get() {
        $db = $this->connect_db_or_exit() ;
        // виконання запитів
        $sql = "CREATE TABLE IF NOT EXISTS Users (
            `id` CHAR(36) PRIMARY KEY DEFAULT ( UUID() ),
            `email` VARCHAR(128) NOT NULL,
            `name` VARCHAR(64) NOT NULL,
            `password` CHAR(32) NOT NULL COMMENT 'Hash of password',
            `avatar` VARCHAR(128) NULL
        ) ENGINE = INNODB, DEFAULT CHARSET = utf8mb4";
        try {
            $db->query( $sql ) ;
        }
        catch( PDOException $ex ) {
            http_response_code( 500 ) ;
            echo "query error: " . $ex->getMessage();
            exit ;
        }

        echo "Hello from do_get - Query OK" ;
    }

    protected function do_post() {
        $db = $this->connect_db_or_exit() ;

        $sql = "CREATE TABLE IF NOT EXISTS Registration_Log (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `date_time` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `user_id` CHAR(36) NOT NULL,
            `token` CHAR(64) NOT NULL
            ) ENGINE = InnoDB, DEFAULT CHARSET = utf8mb4";
        echo "Registration Log table created successfully." ;

        try {
            $db->query( $sql ) ;
        }
        catch( PDOException $ex ) {
            http_response_code( 500 ) ;
            echo "query error: " . $ex->getMessage();
            exit ;
        }
    }
}