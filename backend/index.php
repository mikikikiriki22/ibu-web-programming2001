<?php
require __DIR__ . '/vendor/autoload.php';

// Load services
require __DIR__ . '/rest/services/AuthService.php';
require __DIR__ . '/rest/services/ParfumeService.php';
require __DIR__ . '/rest/services/UserService.php';
require __DIR__ . '/rest/services/ReviewService.php';
require __DIR__ . '/rest/services/BrandService.php';
require __DIR__ . '/rest/services/NoteService.php';

// Load middleware
require_once __DIR__ . '/middleware/AuthMiddleware.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Register services
Flight::register('auth_service', 'AuthService');
Flight::register('parfume_service', 'ParfumeService');
Flight::register('user_service', 'UserService');
Flight::register('review_service', 'ReviewService');
Flight::register('brand_service', 'BrandService');
Flight::register('note_service', 'NoteService');

// Register middleware
Flight::register('auth_middleware', 'AuthMiddleware');

// Register JWT secret
Flight::map('JWT_SECRET', function() {
    return 'mileLegenda333';
});

// Global authentication middleware
Flight::route('/*', function() {
    // Public routes that don't require authentication
    $public_routes = [
        '/auth/login',
        '/auth/register',
        '/auth/verify',
        '/perfumes',  // GET requests for viewing perfumes
        '/brands',    // GET requests for viewing brands
        '/notes',     // GET requests for viewing notes
        '/login',     // add these for robustness
        '/register'
    ];

    $request_url = rtrim(Flight::request()->url, '/');
    $is_public_route = false;
    foreach ($public_routes as $route) {
        if (
            $request_url === $route &&
            (
                Flight::request()->method === 'GET' ||
                (in_array($route, ['/auth/login', '/auth/register', '/login', '/register']) && Flight::request()->method === 'POST')
            )
        ) {
            $is_public_route = true;
            break;
        }
    }

    if ($is_public_route) {
        return TRUE;
    }

    // For protected routes, verify JWT token
    try {
        $token = Flight::request()->getHeader("Authentication");
        if (Flight::auth_middleware()->verifyToken($token)) {
            return TRUE;
        }
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }
});

// Load routes
require_once __DIR__ . '/rest/routes/AuthRoutes.php';
require_once __DIR__ . '/rest/routes/ParfumeRoute.php';
require_once __DIR__ . '/rest/routes/UserRoutes.php';
require_once __DIR__ . '/rest/routes/ReviewRoutes.php';
require_once __DIR__ . '/rest/routes/BrandRoutes.php';
require_once __DIR__ . '/rest/routes/NoteRoutes.php';

// Enable CORS
Flight::before('start', function() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
    if (Flight::request()->method == 'OPTIONS') {
        Flight::halt(200);
    }
});

Flight::route('/', function () {
    echo "Hello, FlightPHP!";
});

Flight::start();
