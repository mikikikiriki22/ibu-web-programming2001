<?php

require_once 'BaseService.php';

require_once __DIR__ . '/../dao/BrandDao.php';

class BrandService extends BaseService
{
    public function __construct()
    {
        $dao = new BrandDao();

        parent::__construct($dao);     // calling parent constructor to ensure $dao is properly initialized for base logic
    }

    public function getAllBrands()
    {
        return $this->dao->getAll();   // gets all brands from database
    }

    public function getBrandById($id)
    {
        $brand = $this->dao->getById($id);    // gets specific brand by id
        if (!$brand) {
            throw new Exception("Brand not found.");
        }
        return $brand;
    }

    public function addBrand($brandData)
    {
        if (empty($brandData['name']) || empty($brandData['country'])) {
            throw new Exception("Brand name and country are required.");
        }

        return $this->dao->insert($brandData);     // adding a brand
    }

    public function updateBrand($id, $brandData)
    {
        $brand = $this->dao->getById($id);
        if (!$brand) {
            throw new Exception("Brand not found.");
        }

        return $this->dao->update($id, $brandData);    // updating a brand
    }

    public function deleteBrand($id)
    {
        $brand = $this->dao->getById($id);
        if (!$brand) {
            throw new Exception("Brand not found.");
        }

        return $this->dao->delete($id);     // deleting a brand
    }
}
