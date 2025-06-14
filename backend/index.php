<?php
require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . "/middleware/AuthMiddleware.php";



use Firebase\JWT\JWT;
use Firebase\JWT\Key;


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);





Flight::route('/*', function () {
    $url = Flight::request()->url;


    // Normalizuj URL bez basepath i index.php
    $cleaned_url = str_replace('/index.php', '', $url);

    // Rute koje ne zahtijevaju autentikaciju
    $public_routes = ['/auth/login', '/auth/register', "/parfumes"];

    foreach ($public_routes as $route) {
        if (strpos($cleaned_url, $route) !== false) {
            return true;
        }
    }

    // Flight::halt(500, "hello");
    // exit;

    // Token provjera
    $token = Flight::request()->getHeader("Authentication");


    if (!$token || trim($token) === '') {
        Flight::halt(401, "Missing or empty authentication token.");
    }

    try {
        // $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
        // Flight::set('user', $decoded_token->user);
        // Flight::set('jwt_token', $token);
        $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
        $decoded_token_array = json_decode(json_encode($decoded_token), true);

        Flight::set('user', $decoded_token_array['user']);
        Flight::set('jwt_token', $token);
        //Flight::halt(500, Flight::get("user")["id"]);
    } catch (\Exception $e) {
        Flight::halt(401, "Unauthorized: " . $e->getMessage());
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

Flight::map('auth_middleware', function () {
    return new AuthMiddleware();
});

Flight::start();
