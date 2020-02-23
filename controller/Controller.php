<?php

class Controller
{
    private $_f3;

    function __construct($f3)
    {
        $this->_f3 = $f3;
    }

    function projectsPage()
    {
        global $db;
        $this->_f3->set("projects", $db->getProjects());
        $view = new Template();
        echo $view->render('views/home.html');
    }

    function videoPlayer($param)
    {
        global $db;
        $this->_f3->set('project_id', $param['item']);

        $videoArray = $db->getVideos($param['item']);

        foreach ($videoArray as $video) {
            if ($video["video_order"] == 1) {
                $this->_f3->set('video', $video);
                break;
            }
        }
        $view = new Template();
        echo $view->render('views/player.html');
    }

    function sessionsPage()
    {
        global $db;
        $this->_f3->set("session", $db->getSession());
        $view = new Template();
        echo $view->render('views/sessions.html');
    }

    function loginPage()
    {
        if (isset($_SESSION['user'])) {
            $this->_f3->reroute('/');
        }

        global $db;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_f3->set("username", $_POST["username"]);

            $user = $db->userLogin($_POST["username"], $_POST["password"]);

            if (!empty($user)) {
                $_SESSION['user'] = new User($user["user_id"], $user["user_name"],
                    $user["user_nickname"], $user["user_is_admin"]);
                $this->_f3->reroute('/');
            }
        }
        $view = new Template();
        echo $view->render('views/login.html');

    }

    function logout()
    {
        session_destroy();
        $this->_f3->reroute('/login');
    }

    function editSessionPage($param)
    {
        $test = 0;
        // TODO: check user permission level before update
        global $db;


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['update'])) {
                if (!isEmpty($_POST["title"]) && !isEmpty($_POST["description"])) {
                    $db->updateSession($param['id'], $_POST["title"], $_POST["description"]);
                }
            }
            if (isset($_POST['userUpdate'])) {
                if (!isEmpty($_POST["name"]) && !isEmpty($_POST["nickName"]) && !isEmpty($_POST['password'])) {
                    if ($_POST['userId'] == "0") {
                        $_POST['userId'] = $db->createUser($param["id"], $_POST["name"], $_POST["nickName"], $_POST['password']);
                    } else {
                        $db->updateUser($_POST['userId'], $_POST["name"], $_POST["nickName"], $_POST['password']);
                    }
                }
            }
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

        $this->_f3->set("users", $db->getUsersBySession($param['id']));

        $view = new Template();
        echo $view->render("/views/session_edit.html");
        echo "<p>" . $test . "</p>";
        var_dump($db->getUsersBySession($param['id']));

    }
}