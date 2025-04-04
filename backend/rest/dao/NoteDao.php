<?php
require_once 'BaseDao.php';

class NoteDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('notes');
    }

    public function getById($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(' :id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
}
