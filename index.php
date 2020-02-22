<?php
//session_start();
//require the autoload file
require_once('vendor/autoload.php');
require_once('model/validation.php');

//THIS IS OUR CONTROLLER
//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();

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

$f3->route('GET|POST /login', function() {
    global $controller;
    $controller->loginPage();
});

$f3->route('GET|POST /logout', function() {
    global $controller;
    $controller->logout();
});

$f3->route('GET|POST /session-edit/@id', function($f3, $param) {
    global $controller;
    $controller->editSessionPage($param);
});

//run fat free
$f3->run();