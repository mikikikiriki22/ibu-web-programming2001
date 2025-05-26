<?php

require_once 'BaseService.php';

require_once __DIR__ . '/../dao/ParfumeDao.php';

class ParfumeService extends BaseService
{

    public function __construct()
    {
        $dao = new ParfumeDao();

        parent::__construct($dao);     //calling constructor of parent class from child class so that we make sure $dao is initialized properly and that the core logic can be implemented
    }

    public function getAllFragrances()
    {

        return $this->dao->getAll();   //gets all fragrances from database

    }   //NO NEED FOR THIS, SINCE WE ARE INHERITING GET ALL FROM BASE

    public function getFragranceById($id)
    {
        $fragrance = $this->getById($id);    //gets certain frag by id
        if (!$fragrance) {
            throw new Exception("Fragrance not found.");
        }
        return $fragrance;
    }

    public function addFragrance($fragranceData)
    {

        if (empty($fragranceData['name']) || empty($fragranceData['brand_id'])) {
            throw new Exception("Fragrance name and brand are required.");
        }

        return $this->create($fragranceData);     //adding frags
    }

    public function updateFragrance($id, $fragranceData)
    {

        $fragrance = $this->getById($id);
        if (!$fragrance) {
            throw new Exception("Fragrance not found.");
        }

        return $this->update($id, $fragranceData);    //updating frags
    }

    public function deleteFragrance($id)
    {

        $fragrance = $this->getById($id);
        if (!$fragrance) {
            throw new Exception("Fragrance not found.");
        }

        return $this->delete($id);     //deleting frags

    }
}
