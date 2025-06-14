<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../dao/BrandDao.php';

class BrandService extends BaseService
{
    public function __construct()
    {
        $dao = new BrandDao();
        parent::__construct($dao);
    }

    public function getAll() {
        return $this->getAll();
    }

    // get brand by ID
    public function getBrandById($id)
    {
        $brand = $this->getById($id);
        if (!$brand) {
            throw new Exception("Brand not found.");
        }
        return $brand;
    }

    // create brand
    public function createBrand($data)
    {

        if (empty($data['name']) || empty($data['image'])) {
            throw new Exception("Missing required fields: name or image.");
        }

        // Use BaseService create method to insert brand
        return $this->create($data);
    }

    // update brand
    public function updateBrand($id, $data)
    {
        $brand = $this->getById($id);
        if (!$brand) {
            throw new Exception("Brand not found.");
        }
        return $this->update($id, $data);
    }

    // delete brand
    public function deleteBrand($id)
    {
        $brand = $this->getById($id);
        if (!$brand) {
            throw new Exception("Brand not found.");
        }
        return $this->delete($id);
    }
}
