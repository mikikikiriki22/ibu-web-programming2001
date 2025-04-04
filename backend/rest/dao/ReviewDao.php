<?php
require_once 'BaseDao.php';

class ReviewDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('reviews');
    }

    public function getById($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(' :id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
}
