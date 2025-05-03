<?php

require_once __DIR__ . "/../services/UserService.php";


// making parfume service class accesible
Flight::register('userService', 'UserService');


//GET ALL USERS

/**
 * @OA\Get(
 *     path="/users",
 *     summary="Get all users",
 *     tags={"Users"},
 *     @OA\Response(response=200, description="List of all users")
 * )
 */

Flight::route('GET /users', function () {
    $data = Flight::userService()->getAllUsers();
    Flight::json($data);
});

//GET USER BY ID

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     summary="Get user by ID",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="User found"),
 *     @OA\Response(response=404, description="User not found")
 * )
 */

Flight::route('GET /users/@id', function ($id) {
    $user = Flight::userService()->getUserById($id);

    if ($user) {
        Flight::json($user);
    } else {
        Flight::json(["error" => "Fragrance not found"], 404);
    }
});

//EDITING USER PROFILE

/**
 * @OA\Put(
 *     path="/users/{id}",
 *     summary="Update user profile",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="username", type="string"),
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="password", type="string")
 *         )
 *     ),
 *     @OA\Response(response=201, description="User profile updated successfully"),
 *     @OA\Response(response=500, description="Failed to update user profile")
 * )
 */

Flight::route('PUT /users/@id', function ($id) {
    // get all the data the user sent in the body
    $data = Flight::request()->data->getData();

    // call the service to update user
    $userService = new UserService();
    $result = $userService->updateUser($id, $data);

    // give response based on the result
    if ($result) {
        Flight::json(['message' => 'User profile updated successfully'], 201);
    } else {
        Flight::json(['message' => 'Failed to update user profile'], 500);
    }
});

//DELETING USER

/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     summary="Delete a user",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="User deleted successfully"),
 *     @OA\Response(response=404, description="User not found")
 * )
 */

Flight::route('DELETE /users/@id', function ($id) {
    try {
        Flight::userService()->deleteUser($id);
        Flight::json(['message' => 'User deleted successfully']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 404);
    }
});