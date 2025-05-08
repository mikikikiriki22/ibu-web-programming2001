<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';

class AuthService extends BaseService
{

    public function __construct()
    {
        $dao = new UserDao();
        parent::__construct($dao);
    }

    //REGISTRATION

    public function register($userData)
    {

        //validating required fields:

        if (empty($userData['email']) || empty($userData['username']) || empty($userData['password']) || empty($userData['gender'])) {
            throw new Exception("All fields are required.");
        }

        //Checking if user already exists:

        $existing = $this->dao->getByEmail($userData['email']);

        if ($existing) {
            throw new Exception("User with this email already exists.");
        }

        //hashing pass

        $userData['password'] = password_hash($userData['password'], PASSWORD_BCRYPT);

        //adding user into database

        $userData = parent::create($userData);

        //remove password field from $userData before returning it

        unset($userData['password']);

        //confirmation message

        return ['success' => true, 'data' => $userData];
    }


    //LOGIN

    public function login($credidentials)
    {

        if (empty($credidentials['email']) || empty($credidentials['password'])) {
            throw new Exception("All fields are required.");
        }

        //fetching user by email

        $user = $this->dao->getByEmail($credidentials['email']);

        if (!$user) {
            throw new Exception("User not found.");
        }

        //verifying the pass

        if (!$user || !password_verify($credidentials['password'], $user['password'])) {
            return ['success' => false, 'error' => 'Invalid username or password.'];
        }

        unset($user['password']);

        return ['success' => true, 'data' => $user];
    }
}
