<?php

/**
 * Class Validation
 */
class Validation
{
    /**
     * Validation for add new user
     * @return bool
     */
    function ValidNewUser()
    {
        global $f3;
        $isValid = true;//flag
        if (!$this->validUserName($f3->get('userName'))) {
            $isValid = false;
            $f3->set("errors['userName']", "Please enter valid user name ");
        }
        if (!$this->validNickName($f3->get('userNickName'))) {
            $isValid = false;
            $f3->set("errors['userNickName']", "Please enter nick name ");
        }
        if (!$this->validPassword($f3->get('password'))) {
            $isValid = false;
            $f3->set("errors['password']", "Please enter valid password ");
        }
        return $isValid;
    }

    /**
     * Validation for editing session page
     * @return bool
     */
    function editSessionToUpdate()
    {
        global $f3;
        $isValidSession = true;//flag
        if (!$this->sessionUpdateTitle($f3->get('sessionTitle'))) {
            $isValidSession = false;
            $f3->set("errors['sessionTitle']", "Please enter new session title ");

        }
        if (!$this->sessionUpdateDescription($f3->get('sessionDescription'))) {
            $isValidSession = false;
            $f3->set("errors['sessionDescription']", "Please enter new session description ");

        }
        return $isValidSession;
    }

    /**
     * Validation for editing project
     * @return bool
     */
    function validateProjectPage()
    {
        global $f3;
        $isValidProject = true;//flag
        if (!$this->projectTitle($f3->get('projectTitle'))) {
            $isValidProject = false;
            $f3->set("errors['projectTitle']", "Please enter Project title ");
        }
        if (!$this->projectDescription($f3->get('projectDescription'))) {
            $isValidProject = false;
            $f3->set("errors['projectDescription']", "Please enter Project Description ");
        }
        return $isValidProject;
    }

    /**
     * Validation for adding new videos
     * @return bool
     */
    function validateNewVideo()
    {
        global $f3;
        $isValidVideo = true;//flag

        if (!$this->validVideoName($f3->get('addValidName'))) {
            $isValidVideo = false;
            $f3->set("errors['validVideoName']", "Please enter Video Name");
        } else {
            $f3->clear("errors['validVideoName']");
        }
        if (!$this->validUrl($f3->get('addValidUrl'))) {
            $isValidVideo = false;
            $f3->set("errors['videoUrl']", "Please insert valid URL ");
        } else {
            $f3->clear("errors['videoUrl']");
        }
        return $isValidVideo;

    }

    function validateLoginCredentials()
    {
        global $f3;
        $isValidLogin = true;//flag
        if (!$this->validateUserLogIn($f3->get('userNameLogin'), $f3->get('userPassword'))) {
            $isValidLogin = false;
            $f3->set("errors['invalid']", "Enter valid credentials ");
        }

        return $isValidLogin;

    }

    /**
     * validating user name
     * @param $userName
     * @return bool
     */
    function validUserName($userName)
    {
        $userName = trim($userName);
        return !empty($userName) && !$this->hasWhiteSpace($userName) && (strlen($userName) <= 255);
    }

    /**
     * Validating user nick name
     * @param $nickName
     * @return bool
     */
    function validNickName($nickName)
    {
        $nickName = trim($nickName);
        return !empty($nickName);
    }

    /**
     * Validating user input password
     * @param $password
     * @return bool
     */
    function validPassword($password)
    {
        return !empty($password) && !$this->hasWhiteSpace($password) && (strlen($password) <= 255);
    }

    /**
     * Validating user input session title
     * @param $sessionTitle
     * @return bool
     */
    function sessionUpdateTitle($sessionTitle)
    {
        $sessionTitle = trim($sessionTitle);
        return !empty($sessionTitle);
    }

    /**
     * Validating user input session description
     * @param $sessionDescription
     * @return bool
     */
    function sessionUpdateDescription($sessionDescription)
    {
        $sessionDescription = trim($sessionDescription);
        return !empty($sessionDescription);
    }

    /**
     * Validating user input project title
     * @param $projectTitle
     * @return bool
     */
    function projectTitle($projectTitle)
    {
        $projectTitle = trim($projectTitle);
        return !empty($projectTitle);
    }

    /**
     * validating user input project description
     * @param $projectDescription
     * @return bool
     */
    function projectDescription($projectDescription)
    {
        return !empty($projectDescription);
    }

    /**
     * Validation for user input video name for add new video
     * @param $videoName
     * @return bool
     */
    function validVideoName($videoName)
    {
        $videoName = trim($videoName);
        return !empty($videoName);
    }

    /**
     * Validating user input url
     * @param $url
     * @return bool
     */
    function validUrl($url)
    {
        $url = trim($url);
        return !empty($url) && (filter_var($url, FILTER_VALIDATE_URL));
    }

    /**
     * validate the user input videos
     * @param $videoName
     * @param $videoUrl
     * @param $videoOrder
     * @return bool
     */
    function validateUserSelectedVideo($videoName, $videoUrl, $videoOrder)
    {
        if (!empty($videoName) && !empty($videoUrl)
            && !empty($videoOrder)
            && ctype_digit($videoOrder)
            && (filter_var($videoUrl, FILTER_VALIDATE_URL))) {
            return true;
        }
    }

    /**
     * Check to see if a string has white space
     * @param $string
     * @return false|int
     */
    function hasWhiteSpace($string)
    {
        return strpos($string, ' ');
    }

    /**
     * Validate login credentials
     * @param $userName
     * @param $password
     * @return bool
     */
    function validateUserLogIn($userName, $password)
    {
        global $db;
        if (!empty($db->getUserByLogin($userName, $password))) {
            return true;
        }
        return false;
    }

    /**
     * Validate category title
     * @param $categoryTitle
     * @return bool
     */
    function validCategory($categoryTitle)
    {
        $categoryTitle = trim($categoryTitle);
        return !empty($categoryTitle);
    }
}