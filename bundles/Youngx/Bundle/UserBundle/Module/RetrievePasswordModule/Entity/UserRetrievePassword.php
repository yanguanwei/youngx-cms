<?php

namespace Youngx\Bundle\UserBundle\Module\RetrievePasswordModule\Entity;


use Youngx\Database\Entity;

class UserRetrievePassword extends Entity
{
    public $uid;
    public $token;
    public $create_time;

    public static function type()
    {
        return 'user.retrieve-password';
    }

    public static function table()
    {
        // TODO: Implement table() method.
    }

    public static function primaryKey()
    {
        // TODO: Implement primaryKey() method.
    }

    public static function fields()
    {
        // TODO: Implement fields() method.
    }
}