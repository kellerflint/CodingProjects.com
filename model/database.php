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

    // example add project function to show how to insert things
    function addProject($name)
    {
        $sql = "INSERT INTO Project VALUES (DEFAULT, :projectName, \"project.png\", \"descript\", \"Category 1\")";

        $statement = $this->_db->prepare($sql);

        $statement->bindParam(":projectName", $name);


        return $statement->execute();
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


        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Get the videos at the given project id
     * @param $project_id int id of the project
     * @return array videos associate array for the given project id
     */
    function getVideos($project_id)
    {
        $sql = "SELECT * FROM Video WHERE project_id = :project_id";

        $statement = $this->_db->prepare($sql);

        $statement->bindParam(':project_id', $project_id);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    function getSession()
    {
        $sql = "SELECT * FROM User_Session WHERE user_id =:user_id";

        $statement = $this->_db->prepare($sql);
        $statement->bindParam(':user_id', $_SESSION['user']->getUserId());


        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $resultSession = array();
        foreach ($result as $session) {
            $sql = "SELECT * FROM Session WHERE session_id = :session_id";
            $statementSession = $this->_db->prepare($sql);
            $statementSession->bindParam(':session_id', $session['session_id']);
            $statementSession->execute();
            array_push($resultSession, $statementSession->fetch(PDO::FETCH_ASSOC));
        }
        return $resultSession;
    }


    function getUser($userName, $password)
    {
        $sql = "SELECT * FROM User WHERE user_name = :userName AND user_password = :password";
        $statement = $this->_db->prepare($sql);
        $statement->bindParam(':userName', $userName);
        $statement->bindParam(':password', $password);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}