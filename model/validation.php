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

    function validateSession($sessionTitle, $sessionDescription)
    {
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
     * Validation for editing project
     * @return bool
     */
    function validateProject($projectTitle, $projectDescription)
    {
        global $f3;
        $isValid = true;//flag
        $errors = [];

        // title validation
        if (empty(trim($projectTitle))) {
            $isValid = false;
            array_push($errors, ["val-empty-error" => "Cannot be empty."]);
        }
        if (strlen($projectTitle) > 255) {
            $isValid = false;
            array_push($errors, ["val-lessThan255-error" => "Cannot be longer than 255 characters."]);
        }
        $f3->set("errors['projectTitle']", $errors);
        $errors = [];

        // description validation
        if (empty(trim($projectDescription))) {
            $isValid = false;
            array_push($errors, ["val-empty-error" => "Cannot be empty."]);
        }
        if (strlen($projectDescription) > 5000) {
            $isValid = false;
            array_push($errors, ["val-lessThan5000-error" => "Cannot be longer than 5000 characters."]);
        }
        $f3->set("errors['projectDescription']", $errors);
        $errors = [];

        return $isValid;
    }

    /**
     * Validation for adding new videos
     * @param $videoName
     * @param $videoURL
     * @return bool
     */
    function validateVideo($videoName, $videoURL)
    {
        global $f3;
        $isValid = true;//flag
        $errors = [];

        // title validation
        if (empty(trim($videoName))) {
            $isValid = false;
            array_push($errors, ["val-empty-error" => "Cannot be empty."]);
        }
        if (strlen($videoName) > 255) {
            $isValid = false;
            array_push($errors, ["val-lessThan255-error" => "Cannot be longer than 255 characters."]);
        }
        $f3->set("errors['videoName']", $errors);
        $errors = [];

        // URL validation
        if (!$this->validUrl($videoURL)) {
            $isValid = false;
            array_push($errors, ["val-empty-error" => "Must be valid URL."]);
        }
        $f3->set("errors['videoUrl']", $errors);
        $errors = [];

        return $isValid;
    }

    function validateLoginCredentials()
    {
        global $f3;
        $isValid = true;//flag
        if (!$this->validateUserLogIn($f3->get('userNameLogin'), $f3->get('userPassword'))) {
            $isValidLogin = false;
            $f3->set("errors['invalid']", "Enter valid credentials ");
        }
        return $isValid;
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