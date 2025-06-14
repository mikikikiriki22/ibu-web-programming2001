<?php

require_once __DIR__ . '/../services/NoteService.php';

// Make note service accessible via Flight
Flight::register('noteService', 'NoteService');


// GET ALL NOTES
/**
 * @OA\Get(
 *     path="/notes",
 *     summary="Get all notes",
 *     tags={"Notes"},
 *     @OA\Response(response=200, description="List of all notes")
 * )
 */
Flight::route('GET /notes', function () {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);

    $notes = Flight::noteService()->getAllNotes();
    Flight::json($notes);
});


// GET NOTE BY ID
/**
 * @OA\Get(
 *     path="/notes/{id}",
 *     summary="Get note by ID",
 *     tags={"Notes"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Note found"),
 *     @OA\Response(response=404, description="Note not found")
 * )
 */
Flight::route('GET /notes/@id', function ($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);

    $note = Flight::noteService()->getNoteById($id);
    if ($note) {
        Flight::json($note);
    } else {
        Flight::json(['error' => 'Note not found'], 404);
    }
});


// CREATE NOTE
/**
 * @OA\Post(
 *     path="/notes",
 *     summary="Create a new note",
 *     tags={"Notes"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="description", type="string")
 *         )
 *     ),
 *     @OA\Response(response=201, description="Note created successfully"),
 *     @OA\Response(response=400, description="Invalid input")
 * )
 */
Flight::route('POST /notes', function () {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    $data = Flight::request()->data->getData();
    $note = Flight::noteService()->createNote($data);
    Flight::json($note, 201);
});


// UPDATE NOTE
/**
 * @OA\Put(
 *     path="/notes/{id}",
 *     summary="Update a note",
 *     tags={"Notes"},
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
 *             @OA\Property(property="description", type="string")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Note updated successfully"),
 *     @OA\Response(response=404, description="Note not found")
 * )
 */
Flight::route('PUT /notes/@id', function ($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    $data = Flight::request()->data->getData();
    $updated = Flight::noteService()->updateNote($id, $data);
    Flight::json(['message' => 'Note updated successfully', 'note' => $updated]);
});


// DELETE NOTE
/**
 * @OA\Delete(
 *     path="/notes/{id}",
 *     summary="Delete a note",
 *     tags={"Notes"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Note deleted successfully"),
 *     @OA\Response(response=404, description="Note not found")
 * )
 */
Flight::route('DELETE /notes/@id', function ($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    Flight::noteService()->deleteNote($id);
    Flight::json(['message' => 'Note deleted successfully']);
});
