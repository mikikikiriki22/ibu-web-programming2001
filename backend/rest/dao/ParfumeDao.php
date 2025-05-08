<?php
require_once 'BaseDao.php';

class ParfumeDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('parfumes');
    }

    public function getById($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM parfumes WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
}
