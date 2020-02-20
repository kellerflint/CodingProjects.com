<?php
//THIS IS OUR CONTROLLER
//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

//session_start();
//require the autoload file
require_once('vendor/autoload.php');

//create an instance of the base class
$f3 = Base::instance();

$db = new Database();
$controller = new Controller($f3);

//Define a default route
//call a function which gets the projects form database
$f3->route('GET /', function () {
    global $controller;
    $controller->projectsPage();
});


$f3->route('GET /player/@item', function ($f3, $param) {
    global $controller;
    $controller->videoPlayer($param);
});

//setting route for sessions page
$f3->route('GET /sessions', function () {
    global $controller;
    $controller->sessionsPage();
});

$f3->route('GET /login', function() {
    global $controller;
    $controller->loginPage();
});

//run fat free
$f3->run();