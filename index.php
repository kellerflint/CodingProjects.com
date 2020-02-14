<?php
//THIS IS OUR CONTROLLER
//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

//session_start();
//require the autoload file
require_once('vendor/autoload.php');

try{
    $db = new PDO('mysql:host=database;
         dbname=coding-projects',
        'root',
        'password');

    echo "connected to database";
}

catch(PDOException $e)
{
    echo $e-> getMessage();
}

require_once "model/query_functions.php";

//create an instance of the base class
$f3 = Base::instance();
//Define a default route
$f3->route('GET /', function ($f3) {
    $f3->set("projects", getProjects());
    $view = new Template();
    echo $view->render('views/home.html');
});

$f3->route('GET /player', function () {
    $view = new Template();
    echo $view->render('views/player.html');
});

//run fat free
$f3->run();