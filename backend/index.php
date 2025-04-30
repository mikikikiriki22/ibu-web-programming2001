<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/rest/routes/ParfumeRoute.php';

Flight::route('/', function () {
    echo "Hello, FlightPHP!";
});

Flight::start();
