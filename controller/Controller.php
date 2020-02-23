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
    function projectsPage()
    {
        global $db;
        $this->_f3->set("projects", $db->getProjects());
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
        $this->_f3->set("session", $db->getSession());
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

            $user = $db->userLogin($_POST["username"], $_POST["password"]);

            if (!empty($user)) {
                //creating an object of a user class, passed returned $user (array) as params
                $_SESSION['user'] = new User($user["user_id"], $user["user_name"],
                    $user["user_nickname"], $user["user_is_admin"]);
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
        $test = 0;
        // TODO: check user permission level before update
        global $db;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //update session
            if (isset($_POST['sessionUpdate'])) {
                if (!isEmpty($_POST["title"]) && !isEmpty($_POST["description"])) {
                    $db->updateSession($param['id'],
                        $_POST["title"], $_POST["description"]);//$pram[id] is session id
                }
            }
            //delete session
            if(isset($_POST['sessionDelete'])){
                //TODO check for user id less than 0
                $db->sessionDelete($param['id']);

            }
            //update user
            if (isset($_POST['userUpdate'])) {
                if (!isEmpty($_POST["name"]) && !isEmpty($_POST["nickName"]) && !isEmpty($_POST['password'])) {
                    if ($_POST['userId'] == "0") {
                        $_POST['userId'] = $db->createUser($param["id"], $_POST["name"], $_POST["nickName"], $_POST['password']);
                    } else {
                        $db->updateUser($_POST['userId'], $_POST["name"], $_POST["nickName"], $_POST['password']);
                    }
                }
            }
            //delete user
            if (isset($_POST['userDelete'])) {
                //TODO check for user id less than 0
                $db->userDelete($_POST['userId']);
            }
            // MUST BE LAST
            if (isset($_POST['userId'])) {
                $this->_f3->set("selectedUser", $db->getUserById($_POST['userId']));
            }
        }
        $this->_f3->set("session", $db->getSessionById($param['id']));

        $this->_f3->set("users", $db->getUsersBySession($param['id']));//gets all the user_id and user_nickname

        $view = new Template();
        echo $view->render("/views/session_edit.html");
    }
}