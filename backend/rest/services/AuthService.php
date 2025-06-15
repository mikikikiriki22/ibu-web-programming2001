<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';
require_once __DIR__ . '/../../data/roles.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService extends BaseService
{
    private $auth_dao;

    public function __construct()
    {
        $this->auth_dao = new AuthDao();
        parent::__construct(new AuthDao());
    }

    public function get_user_by_email($email)
    {
        return $this->auth_dao->get_user_by_email($email);
    }

    //REGISTRATION

    public function register($entity)
    {
        // Validate required fields
        if (empty($entity['email']) || empty($entity['password']) || empty($entity['username'])) {
            return ['success' => false, 'error' => 'Email, password and username are required.'];
        }

        // Validate email format
        if (!filter_var($entity['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Invalid email format.'];
        }

        // Check if email already exists
        $email_exists = $this->auth_dao->get_user_by_email($entity['email']);
        if ($email_exists) {
            return ['success' => false, 'error' => 'Email already registered.'];
        }

        // Check if username already exists
        $username_exists = $this->auth_dao->get_user_by_username($entity['username']);
        if ($username_exists) {
            return ['success' => false, 'error' => 'Username already taken.'];
        }

        // Hash password
        $entity['password'] = password_hash($entity['password'], PASSWORD_BCRYPT);

        // Set default role to user
        $entity['role'] = Roles::USER;

        // Add user to database
        $entity = parent::create($entity);

        // Remove password from response
        unset($entity['password']);

        return ['success' => true, 'data' => $entity];
    }

    //LOGIN

    public function login($entity)
    {
        // Validate required fields
        if (empty($entity['email']) || empty($entity['password'])) {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

        // Get user by email
        $user = $this->auth_dao->get_user_by_email($entity['email']);
        if (!$user) {
            return ['success' => false, 'error' => 'Invalid email or password.'];
        }

        // Verify password
        if (!password_verify($entity['password'], $user['password'])) {
            return ['success' => false, 'error' => 'Invalid email or password.'];
        }

        // Generate JWT token
        $payload = [
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'username' => $user['username'],
                'role' => $user['role']
            ],
            'exp' => time() + (60 * 60 * 24) // 24 hours expiration
        ];

        $token = JWT::encode($payload, Config::JWT_SECRET(), 'HS256');

        // Remove password from user data
        unset($user['password']);

        return [
            'success' => true,
            'data' => [
                'token' => $token,
                'user' => $user
            ]
        ];
    }

    public function verify_token($token)
    {
        try {
            $decoded = JWT::decode($token, new Key(Flight::JWT_SECRET(), 'HS256'));
            return ['success' => true, 'data' => $decoded];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Invalid token'];
        }
    }
}
