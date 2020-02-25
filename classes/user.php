<?php

/**
 * Class User stores data for a user.
 */
class User
{
    private $_user_id;
    private $_user_name;
    private $_user_nickName;
    private $_user_admin;

    /**
     * User constructor.
     *
     * @param $_user_id int The user id.
     * @param $_user_name string The user name.
     * @param $_user_nickName string The user nickname.
     * @param $_user_admin int Represents if the user is a site admin. 1 if admin, else 0.
     */
    public function __construct($_user_id, $_user_name, $_user_nickName, $_user_admin)
    {
        $this->_user_id = $_user_id;
        $this->_user_name = $_user_name;
        $this->_user_nickName = $_user_nickName;
        $this->_user_admin = $_user_admin;
    }

    /**
     * Returns the user's id.
     *
     * @return int The user's id.
     */
    public function getUserId()
    {
        return $this->_user_id;
    }

    /**
     * Returns the user's name.
     *
     * @return string The user's name.
     */
    public function getUserName()
    {
        return $this->_user_name;
    }

    /**
     * The user's nickname.
     *
     * @return string The user's nickname.
     */
    public function getUserNickName()
    {
        return $this->_user_nickName;
    }

    /**
     * Returns 0 or 1 depending on if the user is a site admin.
     *
     * @return int 0 if not site admin, 1 if admin.
     */
    public function getUserAdmin()
    {
        return $this->_user_admin;
    }


}