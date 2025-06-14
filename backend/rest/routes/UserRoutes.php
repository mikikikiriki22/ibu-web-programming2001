<?php

require_once __DIR__ . "/../services/UserService.php";

// Make user service accessible via Flight
Flight::register('userService', 'UserService');

// GET ALL USERS
/**
 * @OA\Get(
 *     path="/users",
 *     summary="Get all users",
 *     tags={"Users"},
 *     @OA\Response(response=200, description="List of all users")
 * )
 */
Flight::route('GET /users', function () {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    $data = Flight::userService()->getAllUsers();
    Flight::json($data);
});

// GET USER BY ID
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
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);

    $user = Flight::userService()->getUserById($id);
    if ($user) {
        Flight::json($user);
    } else {
        Flight::json(["error" => "User not found"], 404);
    }
});

// UPDATE USER
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
 *     @OA\Response(response=200, description="User profile updated successfully"),
 *     @OA\Response(response=500, description="Failed to update user profile")
 * )
 */
Flight::route('PUT /users/@id', function ($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);

    $loggedInUser = Flight::get('user');

    if ($loggedInUser->role !== Roles::ADMIN && $loggedInUser->id != $id) {
        Flight::halt(403, "Forbidden: You can only update your own profile.");
    }

    $data = Flight::request()->data->getData();
    $updatedUser = Flight::userService()->updateUser($id, $data);

    Flight::json($updatedUser);
});


// DELETE USER
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
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    try {
        Flight::userService()->deleteUser($id);
        Flight::json(['message' => 'User deleted successfully']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 404);
    }
});
