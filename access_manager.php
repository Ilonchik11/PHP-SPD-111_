<?php

// echo '<pre>' ; // замість <?= --- виведення відповіді
// print_r( $_SERVER ) ; // друк масиву 
$uri = $_SERVER['REQUEST_URI'] ;
// якщо у запиті є гет-параметри (знак ?), то прибираємо цю частину
$pos = strpos( $uri, '?' ) ;
if( $pos > 0 ) {
    $uri = substr( $uri, 1, $pos ) ;
}
else {
    $uri = substr( $uri, 1 ) ;
}
if( $uri != "") {
    $filename = "./wwwroot/{$uri}" ; 
    // без зазначення типу контенту файли можуть бути ігноровані
    // а також з метою обмеження прямого доступу до деяких файлів
    // аналізуємо розширення файлу
    if ( is_readable( $filename ) ) {

    $ext = pathinfo( $filename, PATHINFO_EXTENSION ) ;
// echo $ext ; exit ; 
    switch( $ext ) {
        case 'png':
        case 'bmp':
        case 'gif': 
            $content_type = "image/{$ext}" ; 
            break;
        case 'jpg':
        case 'jpeg': 
            $content_type = "image/jpeg" ; 
            break;
        case 'js':
            $content_type = "text/javascript" ;
            break;
        case 'css':
        case 'html':
            $content_type = "text/{$ext}" ;
            break;
    }
    if( isset( $content_type ) ) {
        header( "Content-Type: {$content_type}" ) ;
        readfile( $filename ) ;
    }
    else {
        http_response_code( 404 ) ;
        echo "Not found" ;
    }
    exit ;
}
}

$routes = [
    '' => 'index.php',
    'basics' => 'basics.php',
    'layout' => 'layout.php',
    'regexp' => 'regexp.php',
    'api' => 'api.php',
    'formRegistration' => 'formRegistration.php'
];
if( isset( $routes[ $uri ] ) ) { // у маршрутах є відповідний запис
    $page_body = $routes[ $uri ] ;
    include '_layout.php' ;
}
else {
    // перевіряємо, чи є такий контролер - [Uri]Controller
    $uri_name = ucfirst($uri) ; // the first letter in upper case
    $controller_name = "{$uri_name}Controller" ;
    $controller_path = "./controllers/{$controller_name}.php" ;
    if( is_readable( $controller_path ) ) {
        include $controller_path ;
        $controller_object = new $controller_name() ;
        $controller_object->serve() ;
    }
    else {
        echo "$uri not found" ;
    }
}