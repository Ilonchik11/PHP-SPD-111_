<?php

include_once "ApiController.php" ;

class AuthController extends ApiController {

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

    /*
        Автентифікація нового користувача (Read)
    */
    protected function do_patch() {
        $result = [ //REST - як "шаблон" однаковості відповідей API
            'status' => 0,
            'meta' => [
                'api' => 'auth',
                'service' => 'authentification',
                'time' => time()
            ],
            'data' => [
                'message' => $_GET
            ],
        ] ;
        if( empty( $_GET[ "email" ] ) ) {
            $result['data']['message'] = "Missing required parameter: 'email'" ;
            $this->end_with( $result ) ;
        }
        $email = trim( $_GET[ "email" ] ) ;

        if( empty( $_GET[ "password" ] ) ) {
            $result['data']['message'] = "Missing required parameter: 'password'" ;
            $this->end_with( $result ) ;
        }
        $password = $_GET[ "password" ] ;

        $sql = "SELECT * FROM Users u WHERE u.email = ? AND u.password = ?" ;
        $db = $this->connect_db_or_exit() ;
        try {
            $prep = $db->prepare( $sql ) ; 
            $prep->execute( [ $email, md5( $password ) ] ) ;
            $res = $prep->fetch() ;
            // $result[ 'data' ][ 'message' ] = var_export( $res, true ) ;
            if( $res === false ) {
                $result[ 'data' ][ 'message' ] = "Credentials rejected" ;
                $this->end_with( $result ) ;
            }
        }
        catch( PDOException $ex ) {
            http_response_code( 500 ) ;
            echo "query error: " . $ex->getMessage();
            exit ;
        }
        // Work with sessions 
        session_start() ;
        $_SESSION[ 'user' ] = $res ;
        $_SESSION[ 'auth-moment' ] = time() ;
        
        $result[ 'status' ] = 1 ;
        // $result[ 'data' ][ 'message' ] = "Signup OK" ;

        $this->end_with( $result ) ;
    }

    /*
        Вихід із системи
    */
    protected function do_delete() {
        $result = [
            'status' => 0,
            'meta' => [
                'api' => 'auth',
                'service' => 'exit',
                'time' => time()
            ],
            'data' => [
                'message' => $_GET
            ],
        ];
        session_start();
        // Проверить, инициализирована ли сессия
        if (isset($_SESSION['user'])) {
            // Уничтожить сессию
            session_destroy();
            $result['data']['message'] = "Exit OK";
        } else {
            $result['data']['message'] = "No active session";
        }
    
        $result['status'] = 1;
    
        $this->end_with($result);
    }   

    /*
        Реєстрація нового користувача (Create)
    */

    protected function do_post() {
        // Check 
        //$result = [ 'get' => $_GET, 'post' => $_POST, 'files' => $_FILES, ] ;
        $result = [
            'status' => 0,
            'meta' => [
                'api' => 'auth',
                'service' => 'signup',
                'time' => time()
            ],
            'data' => [
                'message' => ""
            ],
        ] ;
        if( empty( $_POST[ "user-name" ] ) ) {
            $result['data']['message'] = "Missing required parameter: 'user-name'" ;
            $this->end_with( $result ) ;
        }
        $user_name = trim( $_POST[ "user-name" ] ) ;
        if( strlen( $user_name ) < 2 ) {
            $result['data']['message'] = "Validation violation: 'user-name' too short" ;
            $this->end_with( $result ) ;
        }
        if( preg_match( '/\d/', $user_name ) ) {
            $result['data']['message'] = "Validation violation: 'user-name' must not contain digit(s)" ;
            $this->end_with( $result ) ;
        }
        //Password
        if( empty( $_POST[ "user-password" ] ) ) {
            $result['data']['message'] = "Missing required parameter: 'user-password'" ;
            $this->end_with( $result ) ;
        }
        $user_password = $_POST[ "user-password" ] ;
        //E-Mail
        if( empty( $_POST[ "user-email" ] ) ) {
            $result['data']['message'] = "Missing required parameter: 'user-email'" ;
            $this->end_with( $result ) ;
        }
        $user_email = trim( $_POST[ "user-email" ] ) ;

        //File
        $filename = null ;
        if( ! empty( $_FILES[ 'user-avatar'] ) ) {
            // file is optional
            if( $_FILES[ 'user-avatar'][ 'error' ] != 0
            || $_FILES[ 'user-avatar'][ 'size' ] == 0 
            ) {
                $result[ 'data' ][ 'message' ] = "File upload error" ;
                $this->end_with( $result ) ;
            }
            // check the file extension
            $ext = pathinfo( $_FILES[ 'user-avatar'][ 'name' ], PATHINFO_EXTENSION ) ;
            if( strpos( ".png.jpg.bmp", $ext ) == -1 ) {
                $result[ 'data' ][ 'message' ] = "File type error" ;
                $this->end_with( $result ) ;
            }
            // generate file name, save extension
            do {
                $filename = uniqid() . "." . $ext ;
            } while( is_file( "./wwwroot/avatar/" . $filename ) ) ; // check if_not_exist_file

            // transfer uploaded file to the new location
            move_uploaded_file( 
                $_FILES[ 'user-avatar'][ 'tmp_name' ],
                "./wwwroot/avatar/" . $filename ) ;

                $filepath = "/wwwroot/avatar/" . $filename;
        }

        /*
            Запити до БД роділяються на 2 типи - звичайні та підготовлені. 
            У звичайних запитах дані підставляються у текст запиту (SQL),
            у підготовлених - ідуть окремо. 
            Звичайні запити виконуються за 1 акт комунікації (БД-PHP),
            підготовлені - за 2: перший запит "готує", другий передає дані. 
            !!Хоча підготовлені запити призначені для повторного (багаторазового) 
            використання, вони мають значно кращі параметри безпеки щодо 
            SQL-ін'єкції. 
            ... WHERE name='%s' ... (name = "o'Brian") -> 
            ... WHERE name='o'Brian' ... - пошкоджений запит (Syntax Error)
            Тому використання підготовлених запитів рекомендується у всіх випадках,
            коли у запит додаються дані, що приходять зовні 
        */

        $db = $this->connect_db_or_exit() ;
        // виконання підготовлених запитів
        // 1. у запиті залишаються "плейсхолдери" - знаки "?"
        $sql = "INSERT INTO Users(`email`, `name`, `password`, `avatar`)
        VALUES(?,?,?,?) ";
        try {
            $prep = $db->prepare( $sql ) ; // 2. Запит готується 
            // 3. Запит виконується з передачею параметрів
            $prep->execute( [
                $user_email,
                $user_name,
                md5( $user_password ),
                $filename
            ] ) ;
        }
        catch( PDOException $ex ) {
            http_response_code( 500 ) ;
            echo "query error: " . $ex->getMessage();
            exit ;
        }
        
        $result[ 'status' ] = 1 ;
        $result[ 'data' ][ 'message' ] = "Signup OK" ;
        $this->end_with( $result ) ;
    }
}

/*
CRUD-повнота - - реалізація щонайменше 4х операцій з даними
Create - POST
Read - GET
Update - PUT
Delete - DELETE
*/