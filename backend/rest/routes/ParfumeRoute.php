<?php

require_once __DIR__ . "/../services/ParfumeService.php";

Flight::register('parfumeService', 'ParfumeService');

Flight::route('GET /parfumes', function () {
    $data = Flight::parfumeService()->getAllFragrances();
    Flight::json($data);
});
