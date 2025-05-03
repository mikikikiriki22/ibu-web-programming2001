<?php

require_once __DIR__ . "/../services/ReviewService.php";


// making parfume service class accesible
Flight::register('reviewService', 'ReviewService');


//GET ALL REVIEWS

/**
 * @OA\Get(
 *     path="/reviews",
 *     summary="Get all reviews",
 *     tags={"Reviews"},
 *     @OA\Response(response=200, description="List of all reviews")
 * )
 */

Flight::route('GET /reviews', function () {
    $data = Flight::reviewService()->getAllReviews();
    Flight::json($data);
});

//GET REVIEW BY ID

/**
 * @OA\Get(
 *     path="/reviews/{id}",
 *     summary="Get review by ID",
 *     tags={"Reviews"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Review found"),
 *     @OA\Response(response=404, description="Review not found")
 * )
 */

Flight::route('GET /reviews/@id', function ($id) {
    $review = Flight::reviewService()->getReviewById($id);

    if ($review) {
        Flight::json($review);
    } else {
        Flight::json(["error" => "Fragrance not found"], 404);
    }
});

//ADDING REVIEW

/**
 * @OA\Post(
 *     path="/reviews",
 *     summary="Add a new review",
 *     tags={"Reviews"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="fragrance_id", type="integer"),
 *             @OA\Property(property="rating", type="number", format="float"),
 *             @OA\Property(property="comment", type="string")
 *         )
 *     ),
 *     @OA\Response(response=201, description="Review added successfully"),
 *     @OA\Response(response=500, description="Failed to add review")
 * )
 */

Flight::route('POST /reviews', function () {
    // get all the data the user sent in the body
    $data = Flight::request()->data->getData();

    // call the service to add the review
    $reviewService = new ReviewService();
    $result = $reviewService->createReview($data);

    // give response based on the result
    if ($result) {
        Flight::json(['message' => 'Review added successfully'], 201);
    } else {
        Flight::json(['message' => 'Failed to add review'], 500);
    }
});

//UPDATING REVIEW

/**
 * @OA\Put(
 *     path="/reviews/{id}",
 *     summary="Update a review",
 *     tags={"Reviews"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="rating", type="number", format="float"),
 *             @OA\Property(property="comment", type="string")
 *         )
 *     ),
 *     @OA\Response(response=201, description="Review updated successfully"),
 *     @OA\Response(response=500, description="Failed to update review")
 * )
 */

Flight::route('PUT /reviews/@id', function ($id) {
    // get all the data the user sent in the body
    $data = Flight::request()->data->getData();

    // call the service to update the review
    $reviewService = new ReviewService();
    $result = $reviewService->updateReview($id, $data);

    // give response based on the result
    if ($result) {
        Flight::json(['message' => 'Review updated successfully'], 201);
    } else {
        Flight::json(['message' => 'Failed to update review'], 500);
    }
});

//DELETING REVIEW

/**
 * @OA\Delete(
 *     path="/reviews/{id}",
 *     summary="Delete a review",
 *     tags={"Reviews"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Review deleted successfully"),
 *     @OA\Response(response=404, description="Review not found")
 * )
 */

Flight::route('DELETE /reviews/@id', function ($id) {
    try {
        Flight::reviewService()->deleteReview($id);
        Flight::json(['message' => 'Review deleted successfully']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 404);
    }
});