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

    function loginPage() {
        $view = new Template();
        echo $view->render('views/login.html');
    }
}