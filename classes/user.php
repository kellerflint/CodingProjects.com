<?php
class User
{
    private $_user_id;
    private $_user_name;
    private $_user_nickName;
    private $_user_admin;

    /**
     * User constructor.
     * @param $_user_id
     * @param $_user_name
     * @param $_user_nickName
     * @param $_user_admin
     */
    public function __construct($_user_id, $_user_name, $_user_nickName, $_user_admin)
    {
        $this->_user_id = $_user_id;
        $this->_user_name = $_user_name;
        $this->_user_nickName = $_user_nickName;
        $this->_user_admin = $_user_admin;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->_user_id;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->_user_name;
    }

    /**
     * @return mixed
     */
    public function getUserNickName()
    {
        return $this->_user_nickName;
    }

    /**
     * @return mixed
     */
    public function getUserAdmin()
    {
        return $this->_user_admin;
    }


}