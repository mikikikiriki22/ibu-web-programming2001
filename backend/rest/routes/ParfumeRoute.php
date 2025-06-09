<?php

require_once __DIR__ . '/../services/ParfumeService.php';

// Make parfume service accessible via Flight
Flight::register('parfumeService', 'ParfumeService');

// GET ALL FRAGRANCES
/**
 * @OA\Get(
 *     path="/parfumes",
 *     tags={"Fragrances"},
 *     summary="Get all fragrances",
 *     @OA\Response(
 *         response=200,
 *         description="List of all fragrances"
 *     )
 * )
 */
Flight::route('GET /parfumes', function () {

    $data = Flight::parfumeService()->getAllFragrances();
    Flight::json($data);
});

// GET FRAGRANCE BY ID
/**
 * @OA\Get(
 *     path="/parfumes/{id}",
 *     tags={"Fragrances"},
 *     summary="Get fragrance by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Fragrance found"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Fragrance not found"
 *     )
 * )
 */
Flight::route('GET /parfumes/@id', function ($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);

    $fragrance = Flight::parfumeService()->getFragranceById($id);
    if ($fragrance) {
        Flight::json($fragrance);
    } else {
        Flight::json(["error" => "Fragrance not found"], 404);
    }
});

// CREATE FRAGRANCE
/**
 * @OA\Post(
 *     path="/parfumes",
 *     tags={"Fragrances"},
 *     summary="Add a new fragrance",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "brand", "notes"},
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="brand", type="string"),
 *             @OA\Property(property="notes", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Fragrance added successfully"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Failed to add fragrance"
 *     )
 * )
 */
Flight::route('POST /parfumes', function () {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    $data = Flight::request()->data->getData();
    $result = Flight::parfumeService()->addFragrance($data);
    Flight::json($result, 201);
});

// UPDATE FRAGRANCE
/**
 * @OA\Put(
 *     path="/parfumes/{id}",
 *     tags={"Fragrances"},
 *     summary="Update a fragrance",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="brand", type="string"),
 *             @OA\Property(property="notes", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Fragrance updated successfully"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Failed to update fragrance"
 *     )
 * )
 */
Flight::route('PUT /parfumes/@id', function ($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    $data = Flight::request()->data->getData();
    $updated = Flight::parfumeService()->updateFragrance($id, $data);
    Flight::json(['message' => 'Fragrance updated successfully', 'fragrance' => $updated]);
});

// DELETE FRAGRANCE
/**
 * @OA\Delete(
 *     path="/parfumes/{id}",
 *     tags={"Fragrances"},
 *     summary="Delete a fragrance",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Fragrance deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Fragrance not found"
 *     )
 * )
 */
Flight::route('DELETE /parfumes/@id', function ($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    Flight::parfumeService()->deleteFragrance($id);
    Flight::json(['message' => 'Fragrance deleted successfully']);
});
