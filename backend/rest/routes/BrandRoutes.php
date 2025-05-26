<?php

require_once __DIR__ . '/../services/BrandService.php';

// Make brand service accessible via Flight
Flight::register('brandService', 'BrandService');


// GET ALL BRANDS
/**
 * @OA\Get(
 *     path="/brands",
 *     summary="Get all brands",
 *     tags={"Brands"},
 *     @OA\Response(response=200, description="List of all brands")
 * )
 */
Flight::route('GET /brands', function () {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    Flight::json(Flight::brandService()->getAll());
});


// GET BRAND BY ID
/**
 * @OA\Get(
 *     path="/brands/{id}",
 *     summary="Get brand by ID",
 *     security={{"ApiKey":{}}},
 *     tags={"Brands"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Brand found"),
 *     @OA\Response(response=404, description="Brand not found")
 * )
 */
Flight::route('GET /brands/@id', function ($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    Flight::json(Flight::brandService()->getBrandById($id));
});


// CREATE BRAND
/**
 * @OA\Post(
 *     path="/brands",
 *     summary="Create a new brand",
 *     tags={"Brands"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="image", type="string")
 *         )
 *     ),
 *     @OA\Response(response=201, description="Brand created successfully"),
 *     @OA\Response(response=400, description="Invalid input")
 * )
 */
Flight::route('POST /brands', function () {

    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    $data = Flight::request()->data->getData();
    $brand = Flight::brandService()->createBrand($data);
    Flight::json($brand, 201);
});


// UPDATE BRAND
/**
 * @OA\Put(
 *     path="/brands/{id}",
 *     summary="Update a brand",
 *     tags={"Brands"},
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
 *             @OA\Property(property="image", type="string")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Brand updated successfully"),
 *     @OA\Response(response=404, description="Brand not found")
 * )
 */
Flight::route('PUT /brands/@id', function ($id) {
    
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    $data = Flight::request()->data->getData();
    $updated = Flight::brandService()->updateBrand($id, $data);
    Flight::json([
        'message' => 'Brand updated successfully',
        'brand' => $updated
    ]);
});


// DELETE BRAND
/**
 * @OA\Delete(
 *     path="/brands/{id}",
 *     summary="Delete a brand",
 *     tags={"Brands"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Brand deleted successfully"),
 *     @OA\Response(response=404, description="Brand not found")
 * )
 */
Flight::route('DELETE /brands/@id', function ($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    Flight::brandService()->deleteBrand($id);
    Flight::json(['message' => 'Brand deleted successfully']);
});

