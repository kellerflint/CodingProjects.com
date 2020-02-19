<?php

class PlayerProjectController
{
    private $_f3;

    function __construct($f3)
    {
        $this->_f3 = $f3;
    }

    function projectsPage() {
        $this->_f3->set("projects", getProjects());
        $view = new Template();
        echo $view->render('views/home.html');
    }

    function videoPlayer($param) {
        $this->_f3-> set('project_id', $param['item']);

        $videoArray = getVideos($param['item']);
        foreach($videoArray as $video)
        {
            if($video["video_order"]==1)
            {
                $this->_f3-> set('video', $video);
                break;
            }
        }
        $view = new Template();
        echo $view->render('views/player.html');
    }

    function sessionsPage() {
        $this->_f3->set("session", getSession());
        $view = new Template();
        echo $view->render('views/sessions.html');
    }
}