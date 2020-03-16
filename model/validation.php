<?php

/**
 * Class Validation contains functions to validate all the user input data before inserting them into database.
 *
 * @author Keller Flint
 * @author Laxmi Kandel
 */

class Validation
{
    /**
     * Validation new user information
     *
     * @param $userName String The username
     * @param $userNickName String The nickname
     * @param $userPassword String The password
     * @return bool true if valid
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

    /**
     * Validation for sessions
     * @param $sessionTitle String The title
     * @param $sessionDescription String The description
     * @return bool true if valid
     */
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
     * Validation for project data
     *
     * @param $projectTitle String title
     * @param $projectDescription string description
     * @param $file array file upload information from $_FILE super global
     * @param $newName string the name of the file as it will be stored on the server
     * @return bool true if valid
     */
    function validateProject($projectTitle, $projectDescription, $file, $newName)
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

        // validate image
        if (isset($file)) {
            if (!empty($file["name"])) {
                if (!$this->validateFileUpload($file, $newName)) {
                    $isValid = false;
                }
            }
        }

        return $isValid;
    }

    /**
     * Validation for adding new videos
     *
     * @param $videoName String name
     * @param $videoURL String URL
     * @return bool true if valid
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

    /**
     * Login credentials validation
     *
     * @return bool true if login credentials are valid
     */
    function validateLoginCredentials()
    {
        global $f3;
        $isValid = true;//flag
        if (!$this->validateUserLogIn($f3->get('userNameLogin'), $f3->get('userPassword'))) {
            $isValid = false;
            $f3->set("errors['invalid']", "Enter valid credentials ");
        }
        return $isValid;
    }

    /**
     * Validating user input url
     *
     * @param $url String URL
     * @return bool true if valid
     */
    function validUrl($url)
    {
        $url = trim($url);
        return !empty($url) && (filter_var($url, FILTER_VALIDATE_URL));
    }

    /**
     * Check to see if a string has white space
     *
     * @param $string String input string
     * @return bool true if the string contains no whitespace
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
     *
     * @param $userName String username
     * @param $password String password
     * @return bool returns true if user and password combination exists
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
     *
     * @param $categoryTitle String title
     * @return bool true if valid
     */
    function validCategory($categoryTitle)
    {
        $categoryTitle = trim($categoryTitle);
        return !empty($categoryTitle);
    }

    /**
     * Uploading cover picture for project
     *
     * @param $file array information about the uploaded file from the $_FILE super global
     * @param $newName String the name the file will have on the server
     * @return bool true if valid
     */
    function validateFileUpload($file, $newName)
    {
        global $dirName;
        global $f3;

        $isValid = true;
        //defining the valid file type
        $validateType = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png');

        //checking the file size 2MB-maximum
        if ($_SERVER['CONTENT_LENGTH'] > 3000000) {
            $f3->set("errors['largeImg", "Sorry! file size too large Maximum file size is 3 MB ");
            $isValid = false;
        } //check the file type
        elseif (in_array($file['type'], $validateType)) {
            if ($file['error'] > 0) {
                $f3->set("errors['returnCode']", "Sorry! file could not be uploaded Try again");
                $isValid = false;
            }

            //checking for duplicate
            if (file_exists($dirName . $newName)) {
                $f3->set("errors['duplicatedImage']", "Sorry! This image is already exist choose another one");
                $isValid = false;
            } else {
                $f3->set("success['uploadSuccessfully']", "Updated successfully");
            }
        } else {
            $f3->set("errors['wrongFileType']", "Sorry! Only supports .jpeg, .jpg, .gif and .png images");
            $isValid = false;
        }
        return $isValid;
    }
}