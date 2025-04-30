<?php

require_once __DIR__ . '/../dao/BaseDao.php';                    //using BaseDao  in this file

class BaseService
{

    protected $dao;                             //variable that holds DAO-s   

    public function __construct($dao)           //constructor method that runs when we create an object. it accepts $dao as a parameter
    {

        $this->dao = $dao;                      //dao that we instantiated will be the variable $dao
    }

    public function getAll()
    {

        return $this->dao->getAll();          //calling getAll method to get all records of dao that we instantiated ($this->dao)
    }

    public function getById($id)
    {

        return $this->dao->getById($id);
    }

    public function create($data)
    {

        return $this->dao->insert($data);
    }

    public function update($id, $data)
    {

        return $this->dao->update($id, $data);
    }

    public function delete($id)
    {

        return $this->dao->delete($id);
    }
}
