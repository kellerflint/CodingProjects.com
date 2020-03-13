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
    function validateUser($userName, $userNickName, $userPassword)
    {
        global $f3;
        $isValid = true;//flag
        $errors = [];

        // username validation
        if (empty(trim($userName))) {
            $isValid = false;
            array_push($errors, ["val-empty-error" => "Cannot be empty."]);
        }
        if (strlen($userName) > 255) {
            $isValid = false;
            array_push($errors, ["val-lessThan255-error" => "Cannot be longer than 255 characters."]);
        }
        if ($this->hasWhiteSpace($userName)) {
            $isValid = false;
            array_push($errors, ["val-hasSpaces-error" => "Cannot contain whitespace."]);
        }
        $f3->set("errors['userName']", $errors);
        $errors = [];

        // nickname validation
        if (empty(trim($userNickName))) {
            $isValid = false;
            array_push($errors, ["val-empty-error" => "Cannot be empty."]);
        }
        if (strlen($userNickName) > 255) {
            $isValid = false;
            array_push($errors, ["val-lessThan255-error" => "Cannot be longer than 255 characters."]);
        }
        $f3->set("errors['userNickName']", $errors);
        $errors = [];

        // password validation
        if (empty(trim($userPassword))) {
            $isValid = false;
            array_push($errors, ["val-empty-error" => "Cannot be empty."]);
        }
        if (strlen($userPassword) > 255) {
            $isValid = false;
            array_push($errors, ["val-lessThan255-error" => "Cannot be longer than 255 characters."]);
        }
        if ($this->hasWhiteSpace($userPassword)) {
            $isValid = false;
            array_push($errors, ["val-hasSpaces-error" => "Cannot contain whitespace."]);
        }
        $f3->set("errors['password']", $errors);
        $errors = [];

        return $isValid;
    }

    function validateSession($sessionTitle, $sessionDescription) {
        global $f3;
        $isValid = true;//flag
        $errors = [];

        // title validation
        if (empty(trim($sessionTitle))) {
            $isValid = false;
            array_push($errors, ["val-empty-error" => "Cannot be empty."]);
        }
        if (strlen($sessionTitle) > 255) {
            $isValid = false;
            array_push($errors, ["val-lessThan255-error" => "Cannot be longer than 255 characters."]);
        }
        $f3->set("errors['sessionTitle']", $errors);
        $errors = [];

        // description validation
        if (empty(trim($sessionDescription))) {
            $isValid = false;
            array_push($errors, ["val-empty-error" => "Cannot be empty."]);
        }
        if (strlen($sessionDescription) > 5000) {
            $isValid = false;
            array_push($errors, ["val-lessThan5000-error" => "Cannot be longer than 5000 characters."]);
        }
        $f3->set("errors['sessionDescription']", $errors);
        $errors = [];

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
            $f3->set("errors['sessionTitle']", ["val-empty-error" => "Cannot be empty."]);
        }
        if (!$this->sessionUpdateDescription($f3->get('sessionDescription'))) {
            $isValidSession = false;
            $f3->set("errors['sessionDescription']", "Invalid Entry. Cannot be empty or greater than 5000 characters.");

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
        if (empty($string)) {
            return false;
        }

        if (strpos($string, ' ') < 0 || strpos($string, ' ') === false) {
            return false;
        }

        return true;
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