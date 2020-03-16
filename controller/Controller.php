<?php

/**
 * Class Controller
 */
class Controller
{
    private $_f3;//router
    private $_val;

    /**
     * Controller constructor.
     * @param $f3
     */
    function __construct($f3)
    {
        $this->_val = new Validation();
        $this->_f3 = $f3;
    }

    /**
     * ProjectPage
     * creating a view for project by getting projects form database
     */
    function homePage()
    {
        global $db;

        $this->_f3->set("stylesheets", ["styles/home.css"]);
        $this->_f3->set("scripts", ["scripts/home.js"]);

        $this->_f3->set("categories", $db->getCategory());

        $this->_f3->set("users", $db->getUsersBySession($_SESSION['session_id']));

        $view = new Template();
        echo $view->render('views/home.html');
    }

    function displayProjects($category_id, $user_id)
    {
        global $db;

        $projects = $db->getProjectByCategoryId($category_id);

        foreach ($projects as $key => $value) {
            //get date of user completed project
            if ($db->getUserProjectDate($user_id, $value['project_id'])) {
                $projects[$key]['project_complete'] = "true";
            } else {
                $projects[$key]['project_complete'] = "false";
            }
        }

        $this->_f3->set("projects", $projects);

        $view = new Template();
        return $view->render("views/projects.html");
    }

    /**
     * Video player
     * creating a view for video player by getting videos form database
     * @param $param
     */
    function videoPlayer($param)
    {
        $this->_f3->set("stylesheets", ["../styles/player.css"]);
        $this->_f3->set("scripts", ["scripts/player.js"]);
        $this->_f3->set("project_id", $param['item']);

        $view = new Template();
        echo $view->render('views/player.html');
    }

    /**
     * Session page
     * creating a view for session page by getting sessions form database
     */
    function sessionsPage()
    {
        global $db;

        $this->_f3->set("stylesheets", ["styles/sessions.css"]);
        //when sever request is post
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            //while connecting sessions, it stores session id that user selected
            //by clicking connect button from the session page
            $_SESSION['session_id'] = $_POST['sessionId'];
        }

        $this->_f3->set("session", $db->getSession($_SESSION['user']->getUserId()));
        $_SESSION["session_permission"] = $db->getUserSessionPermission($_SESSION['user']->getUserId(), $_SESSION['session_id']);

        $view = new Template();
        echo $view->render('views/sessions.html');
    }

    /**
     * Login page
     * if user is set reroute to home page
     * if user is not empty create new a object of user class
     */
    function loginPage()
    {
        $this->_f3->set("stylesheets", ["styles/login.css"]);

        //if user is set reroute it to home page
        if (isset($_SESSION['user'])) {
            $this->_f3->reroute('/');
        }

        global $db;
        //when sever request is post
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->_f3->set("userNameLogin", $_POST["username"]);
            $this->_f3->set("userPassword", $_POST["password"]);
            if ($this->_val->validateLoginCredentials()) {
                $user = $db->getUserByLogin($_POST["username"], $_POST["password"]);

            }

            if (!empty($user)) {
                //creating an object of a user class, passed returned $user (array) as params
                if ($user["user_is_admin"] == 1) {
                    $_SESSION['user'] = new UserAdmin($user["user_id"], $user["user_name"],
                        $user["user_nickname"]);
                } else {
                    $_SESSION['user'] = new User($user["user_id"], $user["user_name"],
                        $user["user_nickname"]);
                }
                $_SESSION['session_id'] = $db->getSession($user["user_id"])[0]['session_id'];
                $_SESSION["session_permission"] = $db->getUserSessionPermission($_SESSION['user']->getUserId(), $_SESSION['session_id']);

                $this->_f3->reroute('/');
            }
        }
        $view = new Template();
        echo $view->render('views/login.html');
    }

    /**
     * Logout
     * destroy session and reroute to login
     */
    function logout()
    {
        session_destroy();
        $this->_f3->reroute('/login');
    }

    /**
     * Edit Session page
     * Edit user
     * Edit session
     * Delete session and user
     * @param $param
     */
    function editSessionPage($param)
    {
        global $db;

        $this->_f3->set("stylesheets", ["styles/session_edit.css"]);
        $this->_f3->set("scripts", ["scripts/user_select.js", "scripts/session_edit.js"]);

        // check user permission level before update, redirect to /sessions if user has invalid credentials
        if ($db->getUserSessionPermission($_SESSION["user"]->getUserId(), $param["id"]) != "admin") {
            $this->_f3->reroute("/sessions");
        }

        $this->_f3->set('sessionTitle', $db->getSessionById($param["id"])["session_title"]);
        $this->_f3->set('sessionDescription', $db->getSessionById($param["id"])["session_description"]);

        //when sever request is post
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //update the session
            if (isset($_POST['sessionUpdate'])) {
                //store the data in hive
                $this->_f3->set('sessionTitle', $_POST["title"]);
                $this->_f3->set('sessionDescription', $_POST["description"]);
                //calling validation function (if valid)
                if ($this->_val->validateSession($_POST["title"], $_POST["description"])) {
                    $db->updateSession($param['id'],
                        $_POST["title"], $_POST["description"]);//$pram[id] is session id
                    $this->_f3->set("success['sessionUpdate']", "Session has been updated");
                }
            }
            //delete the session
            if (isset($_POST['sessionDelete'])) {
                $db->deleteSession($param['id']);
                //reroute to home page after session has been deleted
                $this->_f3->reroute("/sessions");

            }

            //when specific user is selected
            if (isset($_POST['userId'])) {
                $user = $db->getUserById($_POST["userId"]);
                $this->_f3->set('userId', $user["user_id"]);
                $this->_f3->set('userName', $user["user_name"]);
                $this->_f3->set('userNickName', $user["user_nickname"]);
                $this->_f3->set('password', $user["user_password"]);
            }
            //update the user
            if (isset($_POST['userUpdate'])) {
                //set the user input data in hive variable
                $this->_f3->set('userName', $_POST['name']);
                $this->_f3->set('userNickName', $_POST['nickName']);
                $this->_f3->set('password', $_POST['password']);

                //if the data input by user are valid
                if ($this->_val->validateUser($_POST['name'], $_POST['nickName'], $_POST['password'])) {
                    // If user id is 0, create a new user instead of updating and existing one
                    if ($_POST['userId'] == "0") {
                        $_POST['userId'] = $db->createUser($param["id"], $_POST["name"], $_POST["nickName"], $_POST['password']);
                        $this->_f3->set("success['createNewUser']", "You have successfully created new user");
                        $this->_f3->set("userId", $_POST['userId']);
                    } else {
                        $db->updateUser($_POST['userId'], $_POST["name"], $_POST["nickName"], $_POST['password']);
                        $this->_f3->set("success['userUpdate']", "You have successfully updated existing user");
                    }
                }
            }
            //delete the user
            if (isset($_POST['userDelete'])) {
                if ($_POST['userId'] == 0) {
                    $this->_f3->set("errors['unableToRemove']", "Please select user to delete");
                } else {
                    $db->deleteUser($_POST['userId']);
                    $this->_f3->set('userName', "");
                    $this->_f3->set('userNickName', "");
                    $this->_f3->set('password', "");
                    $this->_f3->set("success['userRemoved']", "You have successfully removed selected user");
                }
            }
        }
        // Adds session id to the hive
        $this->_f3->set("session", $db->getSessionById($param['id']));

        // Adds user user_ids and user_nicknames used to populate the user select dropdown
        $this->_f3->set("users", $db->getUsersBySession($param['id']));

        $view = new Template();
        echo $view->render("/views/session_edit.html");
    }

    /**
     * Project edit
     * Let the user to update the project
     * let the user delete the project
     * @param $param int takes project_id
     */
    function projectEditPage($param)
    {
        global $db;
        global $dirName;

        $this->_f3->set("stylesheets", array("styles/project_edit.css"));

        //setting the category in hive
        $this->_f3->set("categories", $db->getCategory());

        $this->_f3->set('projectTitle', $db->getProjectsById($param["id"])["project_title"]);
        $this->_f3->set('projectDescription', $db->getProjectsById($param["id"])["project_description"]);

        //when sever request is post
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            //add the new video
            if (isset($_POST['addVideo'])) {
                $this->_f3->set('addValidName', $_POST['videoName']);
                $this->_f3->set('addValidUrl', $_POST['videoUrl']);

                if ($this->_val->validateVideo($_POST['videoName'], $_POST['videoUrl'])) {
                    //creating new project
                    $maxOrder = $db->addVideo($param['id'], $_POST['videoName'], $_POST['videoUrl']);
                    $this->_f3->set("maxOrder", "$maxOrder");
                    $this->_f3->set("success['addedVideo']", "Added video successfully.");
                }
            }

            //update video
            if (isset($_POST["updateVideo"])) {

                $this->_f3->set("errors['id']", $_POST['videoId']);
                $this->_f3->set('videoName', $_POST['videoName']);
                $this->_f3->set('videoUrl', $_POST['videoUrl']);

                if ($this->_val->validateVideo($_POST['videoName'], $_POST['videoUrl'])) {
                    var_dump($_POST);
                    $db->updateVideoById($_POST['videoId'], $_POST['videoName'], $_POST['videoUrl']);
                }
            }

            //update/ create project
            if (isset($_POST["updateProject"])) {
                $this->_f3->set('projectTitle', $_POST['projectName']);
                $this->_f3->set('projectDescription', $_POST['projectDescription']);

                $randomFileName = $this->generateRandomString() . "." . explode("/", $_FILES['fileToUpload']['type'])[1];

                if ($this->_val->validateProject($_POST['projectName'], $_POST['projectDescription'], $_FILES['fileToUpload'], $randomFileName)) {
                    if ($param["id"] == 0) {
                        $id = $db->createProject($_POST["projectName"], $_POST["projectDescription"], $_POST["categoryId"]);
                        if (!empty($_FILES['fileToUpload']['name'])) {
                            move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $dirName . $randomFileName);
                            $db->uploadProjectImage($randomFileName, $id);
                        }
                        $this->_f3->reroute("/project-edit/$id");
                    } else {
                        $db->updateProject($param["id"], $_POST["projectName"], $_POST["projectDescription"], $_POST["categoryId"]);
                        if (!empty($_FILES['fileToUpload']['name'])) {
                            move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $dirName . $randomFileName);
                            $db->uploadProjectImage($randomFileName, $param['id']);
                        }
                        $this->_f3->set("success['updatedProject']", "Project has been updated successfully");
                    }
                }
            }
            //Change the projects order
            if (isset($_POST['movingUpButton'])) {
                //should move the project one level up
                $db->moveVideoUpOrDown($_POST['videoId'], "up", $param['id']);
            }
            if (isset($_POST['movingDownButton'])) {
                $db->moveVideoUpOrDown($_POST['videoId'], "down", $param['id']);

            }
            //removing video
            if (isset($_POST['removeVideo'])) {
                $db->removeVideo($_POST['videoId']);
            }
            //removing project
            if (isset($_POST['removeProject'])) {
                $db->removeProject($param["id"]);
                $this->_f3->reroute("/");
            }
        }
        //hive the data videos and projects
        $this->_f3->set("maxOrder", $db->getMaxOrder($param['id']));
        $this->_f3->set("minOrder", $db->getMinOrder($param['id']));
        $this->_f3->set("videos", $db->getVideos($param['id']));
        $this->_f3->set("project", $db->getProjectsById($param['id']));
        $view = new Template();
        echo $view->render('views/project_edit.html');
//        var_dump($_POST);
    }

    /**
     * Edit Category
     * let the user update category
     * let the user remove category
     */
    function editCategory()
    {
        global $db;
        $this->_f3->set("stylesheets", array("styles/category_edit.css"));
        $this->_f3->set("scripts", array("scripts/category_select.js"));

        //when sever request is post
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["category"])) { //category is CategoryId
                //if user select options get the data from database
                $this->_f3->set("selectedCategory", $_POST['category']);
                $category = $db->getCategoryById($_POST['category']);
                $this->_f3->set("categoryTitle", $category['category_title']);

                $this->_f3->set("categoryDescription", $category['category_description']);
            }

            //updating category
            if (isset($_POST["categoryUpdate"])) {
                if ($this->_val->validCategory($_POST['categoryTitle'])) {
                    $this->_f3->set("categoryTitle", $_POST['categoryTitle']);
                    $this->_f3->set("categoryDescription", $_POST['categoryDescription']);
                    if ($_POST['category'] == "0") {
                        $db->addCategory($_POST['categoryTitle'], $_POST['categoryDescription']);
                        $this->_f3->set("success['categoryAdded']", "New category has been added");
                    } else {
                        $db->updateCategory($_POST['category'], $_POST['categoryTitle'], $_POST['categoryDescription']);
                        $this->_f3->set("success['categoryUpdated']", "Existing category has been updated");
                    }
                } else {
                    $this->_f3->set("errors['categoryTitle']", "Category title can't be empty");
                }
            }

            //Removing category
            if (isset($_POST['categoryRemove'])) {
                if ($_POST['category'] == 0) {
                    $this->_f3->set("errors['unableToRemoveCategory']", "Please select category to remove");
                } else {
                    $db->removeCategory($_POST['category']);
                    $this->_f3->set('categoryTitle', "");
                    $this->_f3->set('categoryDescription', "");
                    $this->_f3->set("success['categoryRemove']", "Category has been removed");
                }
            }
        }
        //setting category data in hive variables
        $this->_f3->set("categories", $db->getCategory());
        $this->_f3->set("category", $db->getCategoryById($_POST['category']));
        $view = new Template();
        echo $view->render('views/category_edit.html');
    }

    /**
     * Generte random name for input images
     * @return string randomly generated name
     */
    function generateRandomString()
    {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Helper page
     * view for helper page
     */
    function helper()
    {
        $view = new Template();
        echo $view->render('views/helper.html');
    }
}



