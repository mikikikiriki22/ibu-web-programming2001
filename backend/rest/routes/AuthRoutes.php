<?php

require_once __DIR__ . "/../services/AuthService.php";


// making parfume service class accesible
Flight::register('authService', 'AuthService');



//REGISTER

/**
 * @OA\Post(
 *     path="/register",
 *     tags={"Auth"},
 *     summary="Register a new user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "username", "password", "gender"},
 *             @OA\Property(property="email", type="string", example="user@example.com"),
 *             @OA\Property(property="username", type="string", example="john_doe"),
 *             @OA\Property(property="password", type="string", example="securePass123"),
 *             @OA\Property(property="gender", type="string", example="male")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User registered successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Registration failed due to validation error or existing user"
 *     )
 * )
 */

Flight::route('POST /register', function () {
    
    // Get the request data (email, username, password, gender)
    $data = Flight::request()->data->getData();

    try {
        // Call register method of AuthService
        $authService = new AuthService();
        $result = $authService->register($data);  // Register the user

        // Return a success response
        Flight::json(['message' => 'User registered successfully', 'user' => $result['data']], 201);
    
    } catch (Exception $e) {
        // In case of error (validation or already existing user), return an error message
        Flight::json(['error' => $e->getMessage()], 400);
    }
});



//LOGIN

/**
 * @OA\Post(
 *     path="/login",
 *     tags={"Auth"},
 *     summary="User login",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", example="user@example.com"),
 *             @OA\Property(property="password", type="string", example="securePass123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login successful"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - invalid credentials"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request - missing data or server error"
 *     )
 * )
 */

Flight::route('POST /login', function () {
    $data = Flight::request()->data->getData();
    try {
        $authService = new AuthService();
        $result = $authService->login($data);  // Try to login with provided credentials
        if ($result['success']) {
            Flight::json(['message' => 'Login successful', 'user' => $result['data']], 200);
        } else {
            Flight::json(['error' => $result['error']], 401);
        }
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

