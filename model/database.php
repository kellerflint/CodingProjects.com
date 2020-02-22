<?php
require_once "config.php";

class Database
{
    private $_db;

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
     * Gets all the projects
     * @return array projects associate array
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
     * @param $project_id int id of the project
     * @return array videos associate array for the given project id
     */
    function getVideos($project_id)
    {
        $sql = "SELECT * FROM Video WHERE project_id = ?";

        $statement = $this->_db->prepare($sql);

        $statement->execute([$project_id]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function getSession()
    {
        $sql = "SELECT * FROM User_Session WHERE user_id = ?";

        $statement = $this->_db->prepare($sql);

        $statement->execute([$_SESSION['user']->getUserId()]);

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

    function getSessionById($id)
    {
        $sql = "SELECT * FROM Session WHERE session_id = ?";

        $statement = $this->_db->prepare($sql);

        $statement->execute([$id]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }


    function userLogin($userName, $password)
    {
        $sql = "SELECT * FROM User WHERE user_name = ? AND user_password = ?";
        $statement = $this->_db->prepare($sql);

        $statement->execute([$userName, $password]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    function updateSession($id, $title, $description)
    {
        $sql = "UPDATE Session 
                SET session_title = ?, session_description = ? 
                WHERE session_id = ?";

        $statement = $this->_db->prepare($sql);

        $statement->execute([$title, $description, $id]);
    }

    function getUsersBySession($session_id)
    {
        $sql = "SELECT User.user_id, User.user_nickname FROM User 
            INNER JOIN User_Session ON User.user_id = User_Session.user_id WHERE session_id=?";
        $statement = $this->_db-> prepare($sql);
        $statement->execute([$session_id]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function getUserById($user_id)
    {
        $sql = "SELECT * FROM User WHERE user_id= ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$user_id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    function updateUser($id, $name, $nickName,$password)
    {
        $sql = "UPDATE User 
                SET user_name = ?, user_nickname = ?, user_password=?
                WHERE user_id = ?";
        $statement = $this->_db->prepare($sql);
        $statement->execute([$name, $nickName, $password,$id]);
    }
}