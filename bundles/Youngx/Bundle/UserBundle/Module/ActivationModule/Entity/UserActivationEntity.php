<?php

namespace Youngx\Bundle\UserBundle\Module\ActivationModule\Entity;


use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Database\Entity;

class UserActivationEntity extends Entity
{
    const TYPE_EMAIL = 0;
    const TYPE_PHONE = 1;

    public $id;
    public $uid;
    public $type;
    public $value;
    public $token;
    public $is_activated = 0;
    public $created_at;

    public static function type()
    {
        return 'user-activation';
    }

    public static function table()
    {
        return 'y_user_activation';
    }

    public static function primaryKey()
    {
        return 'id';
    }

    public static function fields()
    {
        return array(
            'id', 'uid', 'type', 'value', 'token', 'is_activated', 'created_at'
        );
    }

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->repository()->load('user', $this->uid);
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = intval($created_at);
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return intval($this->created_at);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $is_activated
     */
    public function setIsActivated($is_activated)
    {
        $this->is_activated = $is_activated;
    }

    /**
     * @return mixed
     */
    public function getIsActivated()
    {
        return $this->is_activated;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}