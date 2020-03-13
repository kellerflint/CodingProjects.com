<?php

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
$f3->route('GET|POST /project-edit/@id', function ($f3, $param) {
    global $controller;
    $controller->projectEditPage($param);
});
$f3->route('GET|POST /category-edit', function ($f3) {
    global $controller;
    $controller->editCategory();
});

$f3->route('POST /ajax', function ($f3) {
    global $db;
    global $controller;

    // Return video data for a project
    if (isset($_POST['displayVideos'])) {
        echo json_encode($db->getVideos($_POST['project_id']));
    }

    // Display projects
    if (isset($_POST["category_id"])) {
        echo $controller->displayProjects($_POST['category_id'], $_POST["user_id"]);
    }

    // remove a project from a user
    if (isset($_POST["remove"])) {
        $db->removeUserProject($_POST['user_id'], $_POST['project_id']);
        echo $_POST['project_id'];
    }

    // give a project to a user
    if (isset($_POST['give'])) {
        $db->giveUserProject($_POST['user_id'], $_POST['project_id']);
        echo $_POST['project_id'];
    }
});

//run fat free
$f3->run();