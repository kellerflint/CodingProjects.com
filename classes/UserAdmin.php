<?php
class UserAdmin extends User {
    function __construct($_user_id, $_user_name, $_user_nickName)
    {
        parent::__construct($_user_id, $_user_name, $_user_nickName);
    }
}