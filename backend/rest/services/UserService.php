<?php

require_once 'BaseService.php';

require_once __DIR__ . '/../dao/UserDao.php';

class UserService extends BaseService
{

    public function __construct()
    {
        $dao = new UserDao();

        parent::__construct($dao);     //calling constructor of parent class from child class so that we make sure $dao is initialized properly and that the core logic can be implemented
    }


    //admin side of viewing all users

    public function getAllUsers() {
        return $this->dao->getAll(); 
    }


    //admin side of viewing certain user

    public function getUserById($id)
    {
        $user = $this->dao->getById($id);    //gets certain user by id
        if (!$user) {
            throw new Exception("User not found.");
        }
        return $user;
    }

    //deleting user profile (allowed to user and admin)

    public function deleteUser($id)
    {
        $user = $this->dao->getById($id);
        if (!$user) {
            throw new Exception("User not found.");
        }

        return $this->dao->delete($id);
    }

    //user side of editing profile

    public function updateUser($id, $userData)
    {

        $user = $this->dao->getById($id);
        if (!$user) {
            throw new Exception("User not found.");
        }

        if (!empty($userData['password'])) {
            $userData['password'] = password_hash($userData['password'], PASSWORD_BCRYPT);
        }

        return $this->dao->update($id, $userData);
    }
}
