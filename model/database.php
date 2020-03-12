<?php
require_once "config.php";

/**
 * Class Database containing functions to create, read, update and delete data from database.
 */
class Database
{
    private $_db;

    /**
     * Database constructor.
     */
    function __construct()
    {
        try {
            $this->_db = new PDO(DB_DSN,
                DB_USERNAME,
                DB_PASSWORD);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Gets all the projects.
     *
     * @return array Projects associate arrays.
     */
    function getProjects()
    {
        $sql = "SELECT * FROM Project";

        $statement = $this->_db->prepare($sql);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get the videos at the given project id
     *
     * @param $project_id int Id of the project
     * @return array Videos associate arrays for the given project id
     */
    function getVideos($project_id)
    {
        $sql = "SELECT * FROM Video WHERE project_id = ? ORDER BY video_order ASC";

        $statement = $this->_db->prepare($sql);

        $statement->execute([$project_id]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all of the sessions for a specific user.
     *
     * @param $userId
     * @return array The session associative arrays for the given user.
     */
    function getSession($userId)
    {
        $sql = "SELECT * FROM User_Session WHERE user_id = ? ORDER BY user_session_last_login ASC";

        $statement = $this->_db->prepare($sql);

        $statement->execute([$userId]);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $resultSession = array();
        foreach ($result as $session) {
            $sql = "SELECT * FROM Session WHERE session_id = ?";
            $statementSession = $this->_db->prepare($sql);
            $statementSession->execute([$session['session_id']]);
            array_push($resultSession, $statementSession->fetch(PDO::FETCH_ASSOC));
        }
        return $resultSession;
    }

    /**
     * Get session information for a specific session id.
     *
     * @param $id int The id of the session.
     * @return array Associative array of session data.
     */
    function getSessionById($id)
    {
        $sql = "SELECT * FROM Session WHERE session_id = ?";

        $statement = $this->_db->prepare($sql);

        $statement->execute([$id]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get a user by their login info.
     *
     * @param $userName string The user name.
     * @param $password string The user password.
     * @return array The associative array of user data.
     */
    function getUserByLogin($userName, $password)
    {
        $sql = "SELECT * FROM User WHERE user_name = ? AND user_password = ?";
        $statement = $this->_db->prepare($sql);

        $statement->execute([$userName, $password]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update session data.
     *
     * @param $id int The id of the session to update.
     * @param $title string The new title for the session.
     * @param $description string The new description for the session.
     */
    function updateSession($id, $title, $description)
    {
        $sql = "UPDATE Session 
                SET session_title = ?, session_description = ? 
                WHERE session_id = ?";

        $statement = $this->_db->prepare($sql);

        $statement->execute([$title, $description, $id]);
    }

    /**
     * Get users by the session id
     *
     * @param $session_id int The id of the session.
     * @return array The user associative arrays.
     */
    function getUsersBySession($session_id)
    {
        $sql = "SELECT User.user_id, User.user_nickname FROM User 
            INNER JOIN User_Session ON User.user_id = User_Session.user_id WHERE session_id=?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$session_id]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get user data by the user's id
     *
     * @param $user_id int THe user's id.
     * @return array The associative array of user data.
     */
    function getUserById($user_id)
    {
        $sql = "SELECT * FROM User WHERE user_id= ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$user_id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update a user's info by their id.
     *
     * @param $id int The user's id.
     * @param $name string The new user name.
     * @param $nickName string The new user nickname.
     * @param $password string The new user password.
     */
    function updateUser($id, $name, $nickName, $password)
    {
        $sql = "UPDATE User 
                SET user_name = ?, user_nickname = ?, user_password=?
                WHERE user_id = ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$name, $nickName, $password, $id]);
    }

    /**
     * Delete a session.
     *
     * @param $id int The session's id.
     */
    function deleteSession($id)
    {
        $sql = "DELETE FROM User_Session WHERE session_id = ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$id]);

        $sql = "DELETE FROM Session WHERE session_id = ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$id]);
    }

    /**
     * Delete a user.
     *
     * @param $id int The user's id.
     */
    function deleteUser($id)
    {
        $sql = "DELETE FROM User_Session WHERE user_id = ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$id]);

        $sql = "DELETE FROM User_Project WHERE user_id = ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$id]);

        $sql = "DELETE FROM User WHERE user_id = ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$id]);
    }

    /**
     * Create a new user and add them to a session.
     *
     * @param $sessionId int The id of the session.
     * @param $name string The user name of the user.
     * @param $nickName string The nickname of the user.
     * @param $password string The password of the user.
     * @return string The newly created user's id.
     */
    function createUser($sessionId, $name, $nickName, $password)
    {
        $sql = "INSERT INTO User VALUES(DEFAULT, ?, ?, ?, NULL, 0)";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$name, $nickName, $password]);
        $id = $this->_db->lastInsertId();

        $sql = "INSERT INTO User_Session VALUES(?, ?, NOW(), NULL, 'user')";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$id, $sessionId]);
        return $id;
    }

    /**
     * Get a user's permission level for a session.
     *
     * @param $user_id int The user id.
     * @param $session_id int The session id.
     * @return string The user's permission level for the session.
     */
    function getUserSessionPermission($user_id, $session_id)
    {
        $sql = "SELECT user_session_permission FROM User_Session WHERE user_id = ? AND session_id = ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$user_id, $session_id]);
        return $statement->fetch(PDO::FETCH_ASSOC)["user_session_permission"];
    }

    /**
     * Get users projects complete date
     * @param $userId int The user id
     * @param $projectId int the project id
     * @return DATETIME
     */

    function getUserProjectDate($userId, $projectId)
    {
        $sql = "SELECT user_project_date_complete FROM User_Project
            WHERE user_id=?  AND project_id=?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$userId, $projectId]);
        return $statement->fetch(PDO::FETCH_ASSOC)["user_project_date_complete"];
    }

    /**
     * Update the project with user input data
     * @param $userId takes user's id
     * @param $projectId takes project's id
     */
    function giveUserProject($userId, $projectId)
    {
        $sql = "UPDATE User_Project 
        SET user_project_date_complete = NOW() WHERE user_id = ? AND project_id = ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$userId, $projectId]);

        $sql = "INSERT INTO User_Project VALUES(?, ? ,1, NOW())";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$userId, $projectId]);
    }

    /**
     * Remove the project
     * @param $userId takes user's id
     * @param $projectId takes project's id
     */
    function removeUserProject($userId, $projectId)
    {
        $sql = "UPDATE User_Project 
        SET user_project_date_complete = NULL WHERE user_id = ? AND project_id = ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$userId, $projectId]);
    }

    /**
     * Get all the project by its id
     * @param $project_id takes project's id
     * @return mixed all the projects of the specified project id
     */
    function getProjectsById($project_id)
    {
        $sql = "SELECT *FROM Project WHERE project_id=?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$project_id]);
        return $statement->fetch(PDO::FETCH_ASSOC);

    }

    /**
     * Update project with user input data
     * @param $project_id takes project id  (user selected)
     * @param $project_title takes project title (user input)
     * @param $project_description takes project description (user input)
     * @param $categoryId takes category id (user selected)
     */
    function updateProject($project_id, $project_title, $project_description, $categoryId)
    {
        $sql = "UPDATE Project SET project_title = ?, project_description = ?, category_id = ? WHERE project_id = ?";

        $statement = $this->_db->prepare($sql);

        $statement->execute([$project_title, $project_description, $categoryId, $project_id]);
    }

    function createProject($projectTitle, $projectDescription, $categoryId)
    {
        $sql = "INSERT INTO Project values (DEFAULT,?,'test.png',?,?)";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$projectTitle, $projectDescription, $categoryId]);
        return $this->_db->lastInsertId();
    }

    /**
     * Update video
     * @param $videoId takes video id
     * @param $videoTitle takes video title
     * @param $videoUrl takes video url
     * @param $videoOrder takes video order
     */
    function updateVideoById($videoId, $videoTitle, $videoUrl, $videoOrder)
    {
        $sql = "UPDATE Video SET video_title = ? , video_url = ?, video_order = ? WHERE video_id=?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$videoTitle, $videoUrl, $videoOrder, $videoId]);
    }

    /**
     * Remove video
     * @param $videoId
     */
    function removeVideo($videoId)
    {
        $sql = "UPDATE User_Project SET user_project_bookmark =
                NULL WHERE user_project_bookmark = ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$videoId]);

        $sql = "DELETE FROM Video WHERE video_id = ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$videoId]);

    }

    /**
     * Add video
     * @param $projectId takes project id
     * @param $videoTitle takes video title
     * @param $videoUrl takes video url
     */
    function addVideo($projectId, $videoTitle, $videoUrl)
    {
        $sql = "SELECT MAX(video_order) FROM Video WHERE project_id = ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$projectId]);
        $max = $statement->fetch(PDO::FETCH_ASSOC)['MAX(video_order)'] + 1;

        $sql = "INSERT INTO Video VALUES(DEFAULT ,?,?,?,?)";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$projectId, $videoTitle, $videoUrl, $max]);
    }

    /**
     * Remove project
     * @param $projectId takes project id
     */
    function removeProject($projectId)
    {
        $sql = "DELETE FROM User_Project  WHERE project_id=?";//deleting row if match project_id
        $statement = $this->_db->prepare($sql);
        $statement->execute([$projectId]);

        //deleting videos associated with project_id
        $sql = "DELETE FROM Video  WHERE project_id=?";//deleting row if match project_id
        $statement = $this->_db->prepare($sql);
        $statement->execute([$projectId]);

        //deleting project
        $sql = "DELETE FROM Project  WHERE project_id=?";//deleting row if match project_id
        $statement = $this->_db->prepare($sql);
        $statement->execute([$projectId]);
    }

    /**
     * Get all the category
     * @return array
     */
    function getCategory()
    {
        $sql = "SELECT * FROM Category";
        $statement = $this->_db->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Get the category by the category id
     * @param $categoryId takes category id
     * @return mixed
     */
    function getCategoryById($categoryId)
    {
        $sql="SELECT * FROM Category WHERE category_id=?";
        $statement = $this-> _db->prepare($sql);
        $statement -> execute([$categoryId]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update category
     * @param $categoryId takes category id
     * @param $categoryTitle takes category title
     * @param $categoryDescription takes category description
     */
    function  updateCategory($categoryId,$categoryTitle,$categoryDescription)
    {
        $sql = "UPDATE Category SET category_title =?, category_description=?
                WHERE category_id= ?";
        $statement =$this->_db->prepare($sql);
        $statement->execute([$categoryTitle,$categoryDescription,$categoryId]);
    }

    /**
     * Remove specified category
     * @param $categoryId takes category id
     */
    function removeCategory($categoryId)
    {
        $projects = $this->getProjectByCategoryId($categoryId);
        foreach ($projects as $value) {
            $this->removeProject($value['project_id']);
        }
        $sql = "DELETE FROM Category  WHERE category_id=?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$categoryId]);
    }

    /**
     * Get all the project of specified category id
     * @param $categoryId takes category id
     * @return array
     */
    function  getProjectByCategoryId($categoryId)
    {
        $sql = "SELECT * FROM Project WHERE category_id= ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$categoryId]);
        return $statement->fetchALL(PDO::FETCH_ASSOC);
    }


    /**
     * Add new category
     * @param $categoryTitle takes user input category title
     * @param $categoryDescription takes user input category description
     */
    function addCategory($categoryTitle,$categoryDescription)
    {
       $sql =  "SELECT MAX(category_order) FROM Category";
        $statement = $this->_db->prepare($sql);
        $statement->execute();
        $max = $statement->fetch(PDO::FETCH_ASSOC)["MAX(category_order)"]+1;
        //INSERTING INTO CATEGORY
        $sql = "INSERT INTO Category VALUES(DEFAULT ,? ,? ,?)";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$categoryTitle,$categoryDescription,$max]);
    }

    function uploadProjectImage($filePath, $projectId) {
        $sql = "UPDATE Project SET project_image = ? WHERE project_id = ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$filePath, $projectId]);
    }
    function moveVideoUpOrDown($videoId, $change, $projectId)
    {
        // get the info of the current video
        $sql = "SELECT * FROM Video WHERE video_id = ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$videoId]);
        $currentVideo = $statement->fetch(PDO::FETCH_ASSOC);

        // get the info of the video above/below
        $otherOrder = $currentVideo['video_order'];
        ($change == "up") ? $otherOrder-- : $otherOrder++;

        $sql ="SELECT * FROM Video WHERE video_order = ? AND project_id = ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$otherOrder,  $projectId]);
        $otherVideo = $statement->fetch(PDO::FETCH_ASSOC);

        // set the order of the current video to the order of the other video
        $sql = "UPDATE Video SET video_order = ? WHERE video_id = ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$otherVideo['video_order'], $currentVideo['video_id']]);
        $statement->execute([$currentVideo['video_order'], $otherVideo['video_id']]);

    }
}