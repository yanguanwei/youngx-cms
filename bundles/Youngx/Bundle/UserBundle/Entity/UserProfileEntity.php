<?php

namespace Youngx\Bundle\UserBundle\Entity;

use Youngx\Database\Entity;

class UserProfileEntity extends Entity
{
    protected $uid;
    protected $full_name;
    protected $qq;
    protected $msn;
    protected $skype;
    protected $telephone;

    public static function type()
    {
        return 'user-profile';
    }

    public static function table()
    {
        return 'y_user_profile';
    }

    public static function primaryKey()
    {
        return 'uid';
    }

    public static function fields()
    {
        return array(
            'uid', 'full_name', 'qq', 'msn', 'skype', 'telephone'
        );
    }

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->repository()->load('user', $this->getUid());
    }

    /**
     * @param mixed $full_name
     */
    public function setFullName($full_name)
    {
        $this->full_name = $full_name;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * @param mixed $msn
     */
    public function setMsn($msn)
    {
        $this->msn = $msn;
    }

    /**
     * @return mixed
     */
    public function getMsn()
    {
        return $this->msn;
    }

    /**
     * @param mixed $qq
     */
    public function setQq($qq)
    {
        $this->qq = $qq;
    }

    /**
     * @return mixed
     */
    public function getQq()
    {
        return $this->qq;
    }

    /**
     * @param mixed $skype
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
    }

    /**
     * @return mixed
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
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
}