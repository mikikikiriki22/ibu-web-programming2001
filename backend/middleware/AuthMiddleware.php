<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware
{

    public function verifyToken($token)
    {
        if (!$token)
            Flight::halt(401, "Missing authentication header");

        try {
            $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
        } catch (Exception $e) {
            Flight::halt(401, "Invalid token: " . $e->getMessage());
        }

        if (!isset($decoded_token->user)) {
            Flight::halt(401, "Invalid token structure: no user data");
        }

        Flight::set('user', $decoded_token->user);
        Flight::set('jwt_token', $token);
        return TRUE;
    }

    public function authorizeRole($requiredRole)
    {
        $user = Flight::get('user');
        if (!isset($user->role)) {
            Flight::halt(403, 'Access denied: role not found in token');
        }
        if ($user->role !== $requiredRole) {
            Flight::halt(403, 'Access denied: insufficient privileges');
        }
    }

    public function authorizeRoles($roles)
    {
        $user = Flight::get('user');

        if (!isset($user["role"])) {
            Flight::halt(403, 'Access denied: role not found');
        }
        if (!in_array($user->role, $roles)) {
            Flight::halt(403, 'Forbidden: role not allowed');
        }
    }

    public function authorizePermission($permission)
    {
        $user = Flight::get('user');
        if (!isset($user->permissions) || !is_array($user->permissions)) {
            Flight::halt(403, 'Forbidden: permissions missing');
        }
        if (!in_array($permission, $user->permissions)) {
            Flight::halt(403, 'Forbidden: permission not granted');
        }
    }
}
