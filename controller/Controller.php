<?php

/**
 * Class Controller
 */
class Controller
{
    private $_f3;//router

    /**
     * Controller constructor.
     * @param $f3
     */
    function __construct($f3)
    {
        $this->_f3 = $f3;
    }

    /**
     * ProjectPage
     * creating a view for project by getting projects form database
     */
    function homePage()
    {
        global $db;

        $projects = $db->getProjects();

        $this->_f3->set("users", $db->getUsersBySession($_SESSION['session_id']));
        $this->_f3->set("permission", $db->getUserSessionPermission($_SESSION['user']->getUserId(), $_SESSION['session_id']));

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_POST["remove"])) {
                $db->removeUserProject($_POST['selectedUser'], $_POST['selectedProject']);

            }
            if (isset($_POST['give'])) {
                $db->giveUserProject($_POST['selectedUser'], $_POST['selectedProject']);
            }

            $this->_f3->set("selectedUser", $_POST['selectedUser']);
            //key=pair value
            foreach ($projects as $key => $value) {
                //get date of user completed project
                if ($db->getUserProjectDate($_POST['selectedUser'], $value['project_id'])) {
                    $projects[$key]['project_complete'] = "true";
                } else {
                    $projects[$key]['project_complete'] = "false";
                }
            }
        }
        $this->_f3->set("projects", $projects);
        $view = new Template();
        echo $view->render('views/home.html');
    }


    /**
     * Video player
     * creating a view for video player by getting videos form database
     * @param $param
     */
    function videoPlayer($param)
    {
        global $db;
        $this->_f3->set('project_id', $param['item']);//$param['item'] is user selected project id


        $videoArray = $db->getVideos($param['item']);

        foreach ($videoArray as $video) {
            if ($video["video_order"] == 1) {
                $this->_f3->set('video', $video); //set a row to video if condition match
                break;
            }
        }
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
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            //while connecting sessions, it stores session id that user selected
            //by clicking connect button from the session page
            $_SESSION['session_id'] = $_POST['sessionId'];
        }

        $this->_f3->set("session", $db->getSession($_SESSION['user']->getUserId()));
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
        //if user is set reroute it to home page
        if (isset($_SESSION['user'])) {
            $this->_f3->reroute('/');
        }

        global $db;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_f3->set("username", $_POST["username"]);

            $user = $db->getUserByLogin($_POST["username"], $_POST["password"]);

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

        // check user permission level before update, redirect to /sessions if user has invalid credentials
        if ($db->getUserSessionPermission($_SESSION["user"]->getUserId(), $param["id"]) != "admin") {
            $this->_f3->reroute("/sessions");
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //update the session
            if (isset($_POST['sessionUpdate'])) {
                if (!isEmpty($_POST["title"]) && !isEmpty($_POST["description"])) {
                    $db->updateSession($param['id'],
                        $_POST["title"], $_POST["description"]);//$pram[id] is session id
                }
            }
            //delete the session
            if (isset($_POST['sessionDelete'])) {
                $db->deleteSession($param['id']);

            }
            //update the user
            if (isset($_POST['userUpdate'])) {
                if (!isEmpty($_POST["name"]) && !isEmpty($_POST["nickName"]) && !isEmpty($_POST['password'])) {
                    // If user id is 0, create a new user instead of updating and existing one
                    if ($_POST['userId'] == "0") {
                        $_POST['userId'] = $db->createUser($param["id"], $_POST["name"], $_POST["nickName"], $_POST['password']);
                    } else {
                        $db->updateUser($_POST['userId'], $_POST["name"], $_POST["nickName"], $_POST['password']);
                    }
                }
            }

            //delete the user
            if (isset($_POST['userDelete'])) {
                $db->deleteUser($_POST['userId']);
            }

            // Get's the data for the currently selected user and adds it to the $f3 hive
            // MUST BE LAST since the selectedUser's information might have been updated
            if (isset($_POST['userId'])) {
                $this->_f3->set("selectedUser", $db->getUserById($_POST['userId']));
            }
        }

        // Adds session id to the hive
        $this->_f3->set("session", $db->getSessionById($param['id']));

        // Adds user user_ids and user_nicknames used to populate the user select dropdown
        $this->_f3->set("users", $db->getUsersBySession($param['id']));

        $view = new Template();
        echo $view->render("/views/session_edit.html");
    }

    function projectEditPage($param)
    {
        global $db;

        $this->_f3->set("categories", $db->getCategory());

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["updateProject"])) {
                $db->updateProject($param["id"], $_POST["projectName"], $_POST["projectDescription"], $_POST["categoryId"]);
            }
            if (isset($_POST["updateVideo"])) {
                if ($_POST['videoId'] == 0) {
                    $db->addVideo($param['id'], $_POST['videoName'], $_POST['videoUrl']);
                } else {
                    $db->updateVideoById($_POST['videoId'], $_POST["videoName"], $_POST["videoUrl"], $_POST["videoOrder"]);
                }

            }
            if (isset($_POST['removeVideo'])) {
                $db->removeVideo($_POST['videoId']);
            }
            if (isset($_POST['removeProject'])) {
                $db->removeProject($param["id"]);
                $this->_f3->reroute("/");
            }


        }

        $this->_f3->set("videos", $db->getVideos($param['id']));
        $this->_f3->set("project", $db->getProjectsById($param['id']));

        $view = new Template();
        echo $view->render('views/project_edit.html');
    }

    function editCategory()
    {
        global $db;

        $this->_f3->set("category", $db->getCategory());
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["category"])) {
                $this->_f3->set("selectedCategory", $_POST['category']);
            }

            if (isset($_POST["categoryUpdate"])) {
                if ($_POST['category'] == "0") {
                    echo"hi";
                    var_dump($_POST);
                    $db->addCategory($_POST['categoryTitle'], $_POST['categoryDescription']);

                } else {
                    $db->updateCategory($_POST['category'], $_POST['categoryTitle'], $_POST['categoryDescription']);
                }
            }
            $this->_f3->set("categories", $db->getCategoryById($_POST['category']));
            if (isset($_POST['categoryRemove'])) {
                $db->removeCategory($_POST['category']);
            }
        }


        $view = new Template();
        echo $view->render('views/category_edit.html');
    }
}