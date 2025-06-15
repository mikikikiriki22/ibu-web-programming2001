<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/ReviewDao.php';

class ReviewService extends BaseService
{
    public function __construct()
    {
        $this->dao = new ReviewDao();
        parent::__construct($this->dao);
    }


    public function getAllReviews()
    {

        return $this->dao->getAll();   //gets all fragrances from database

    }

    //GET REVIEW
    public function getReviewById($id)
    {
        $review = $this->dao->getById($id);

        if (!$review) {
            throw new Exception("Review not found.");
        }

        return $review;
    }

    //CREATE REVIEW
    public function createReview($reviewData)
    {
        // Basic validation
        if (empty($reviewData['user_id']) || empty($reviewData['parfume_id']) || empty($reviewData['rating'])) {
            throw new Exception("Missing required fields: user_id, fragrance_id, or rating.");
        }

        return parent::create($reviewData);
    }


    //UPDATING REVIEW
    public function updateReview($id, $data)
    {
        $review = $this->dao->getById($id);

        if (!$review) {
            throw new Exception("Review not found.");
        }

        return $this->dao->update($id, $data);
    }


    //DELETING REVIEW
    public function deleteReview($id)
    {
        $review = $this->dao->getById($id);

        if (!$review) {
            throw new Exception("Review not found.");
        }

        return $this->dao->delete($id);
    }

    public function getReviewsByFragranceId($fragranceId)
    {
        return $this->dao->getReviewsByFragranceId($fragranceId);
    }

    public function getReviewsByUserId($userId)
    {
        return $this->dao->getReviewsByUserId($userId);
    }
}
