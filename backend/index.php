<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/rest/routes/ParfumeRoute.php';
require __DIR__ . '/rest/routes/AuthRoutes.php';
require __DIR__ . '/rest/routes/UserRoutes.php';
require __DIR__ . '/rest/routes/ReviewRoutes.php';

Flight::route('/', function () {
    echo "Hello, FlightPHP!";
});

Flight::start();
