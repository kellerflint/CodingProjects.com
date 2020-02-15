<?php
//THIS IS OUR CONTROLLER
//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

//session_start();
//require the autoload file
require_once('vendor/autoload.php');
//setting the connection to the database
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
//call a function which gets the projects form database
$f3->route('GET /', function ($f3) {
    $f3->set("projects", getProjects());
    $view = new Template();
    echo $view->render('views/home.html');
});


$f3->route('GET /player/@item', function ($f3, $param) {

    $f3-> set('project_id', $param['item']);

   $videoArray = getVideos($param['item']);
   foreach($videoArray as $video)
   {
       if($video["video_order"]==1)
       {
           $f3-> set('video', $video);
            break;
       }
   }



    $view = new Template();
    echo $view->render('views/player.html');
});

//run fat free
$f3->run();