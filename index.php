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
//(project_id,project_title,project_image,project_description,project_category)
//$sql = "INSERT INTO Project
//VALUES(:project_id, :project_title, :project_image, :project_description, :project_category)";
////prepare the statement
//$statement = $db->prepare($sql);
//
//$id = 4;
//$title = 'test1';
//$image = 'img.png';
//$des = 'descioesfoieiosjfoisej';
//$cat = 'cateogy 1';
//
////bind the parameter
//$statement->bindParam(':project_id', $id, PDO::PARAM_INT);
//$statement->bindParam(':project_title', $title, PDO::PARAM_STR);
//$statement->bindParam(':project_image', $image, PDO::PARAM_STR);
//$statement->bindParam(':project_description', $des, PDO::PARAM_STR);
//$statement->bindParam(':project_category', $cat, PDO::PARAM_STR);
//
//
////execute
//$statement->execute();
//var_dump($db->errorCode());
// var_dump();


$sql = "SELECT * FROM Project WHERE project_id = :id";

$statement= $db->prepare($sql);

$id =3;
$statement->bindParam(':id',$id,PDO::PARAM_INT);

$statement->execute();


$row= $statement -> fetch(PDO::FETCH_ASSOC);
echo $row['project_title'];


//create an instance of the base class
$f3 = Base::instance();
//Define a default route
$f3->route('GET /', function () {
    $view = new Template();
    echo $view->render('views/home.html');
});

$f3->route('GET /player', function () {
    $view = new Template();
    echo $view->render('views/player.html');
});

//run fat free
$f3->run();