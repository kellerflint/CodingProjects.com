<?php
/**
 * Gets all the projects
 * @return array projects associate array
 */
function getProjects() {
    global $db;

    $sql = "SELECT * FROM Project";

    $statement= $db->prepare($sql);

    $statement->execute();


    $result = $statement -> fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

/**
 * Get the videos at the given project id
 * @param $project_id int id of the project
 * @return array videos associate array for the given project id
 */
function getVideos($project_id) {
    global $db;

    $sql = "SELECT * FROM Video WHERE project_id = :project_id ";

    $statement= $db->prepare($sql);

    $statement->bindParam(':project_id', $project_id, PDO::PARAM_INT);

    $statement->execute();

    $result = $statement -> fetchAll(PDO::FETCH_ASSOC);
    return $result;
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