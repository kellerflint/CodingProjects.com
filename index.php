<?php

/**
 * Fat free instantiation and routing.
 *
 * @author Keller Flint
 * @author Laxmi Kandel
 */

//require the autoload file
require_once('vendor/autoload.php');
require_once('model/validation.php');

//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();

//create an instance of the base class
$f3 = Base::instance();

$db = new Database();
$controller = new Controller($f3);
$dirName = 'uploads/';

// Define a default route
$f3->route('GET|POST /', function () {
    global $controller;
    $controller->homePage();
});

// Define video player route
$f3->route('GET /player/@item', function ($f3, $param) {
    global $controller;
    $controller->videoPlayer($param);
});

// Define sessions page route
$f3->route('GET|POST /sessions', function () {
    global $controller;
    $controller->sessionsPage();
});

// Define login route
$f3->route('GET|POST /login', function () {
    global $controller;
    $controller->loginPage();
});

// Define logout route
$f3->route('GET|POST /logout', function () {
    global $controller;
    $controller->logout();
});

// Define session-edit route
$f3->route('GET|POST /session-edit/@id', function ($f3, $param) {
    global $controller;
    $controller->editSessionPage($param);
});

// Define project-edit route
$f3->route('GET|POST /project-edit/@id', function ($f3, $param) {
    global $controller;
    $controller->projectEditPage($param);
});

// Define category-edit route
$f3->route('GET|POST /category-edit', function ($f3) {
    global $controller;
    $controller->editCategory();
});

// Define help route
$f3->route('GET /help', function () {
    global $controller;
    $controller->helper();
});

// Define ajax route (where all ajax requests are sent)
$f3->route('POST /ajax', function ($f3) {
    global $controller;
    echo $controller->ajax();
});

//run fat free
$f3->run();