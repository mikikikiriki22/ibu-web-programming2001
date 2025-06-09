<?php
require __DIR__ . '/vendor/autoload.php';



use Firebase\JWT\JWT;
use Firebase\JWT\Key;


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);





Flight::route('/*', function() {
   if(
       strpos(Flight::request()->url, '/auth/login') === 0 ||
       strpos(Flight::request()->url, '/auth/register') === 0 ||
       strpos(Flight::request()->url, '/parfumes') === 0
   ) {
       return TRUE;
   } else {
       try {
           $token = Flight::request()->getHeader("Authentication");
           if(!$token)
               Flight::halt(401, "Missing authentication header");


           $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));


           Flight::set('user', $decoded_token->user);
           Flight::set('jwt_token', $token);
           return TRUE;
       } catch (\Exception $e) {
           Flight::halt(401, $e->getMessage());
       }
   }
});


require __DIR__ . '/rest/routes/ParfumeRoute.php';
require __DIR__ . '/rest/routes/AuthRoutes.php';
require __DIR__ . '/rest/routes/UserRoutes.php';
require __DIR__ . '/rest/routes/ReviewRoutes.php';
require __DIR__ . '/rest/routes/NoteRoutes.php';
require __DIR__ . '/rest/routes/BrandRoutes.php';


Flight::route('/', function () {
    echo "Hello, FlightPHP!";
});

Flight::start();
