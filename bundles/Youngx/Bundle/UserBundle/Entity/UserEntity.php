<?php

namespace Youngx\Bundle\UserBundle\Entity;

use Youngx\MVC\Entity;
use Youngx\MVC\Query;

class UserEntity extends Entity implements \Youngx\MVC\UserEntity
{
    protected $uid;
    protected $name;
    protected $email;
    protected $password;
    protected $created_at = 0;
    protected $phone;
    protected $status = 0;
    protected $roles;

    public function __sleep()
    {
        if ($this->roles === null) {
            $this->getRoles();
        }

        return array_merge($this->fields(), array(
                'roles'
            ));
    }

    /**
     * @return array role ids
     */
    public function getRoles()
    {
        if ($this->roles === null) {
            $roles = array();
            $db = $this->repository()->getConnection();
            $sql = "SELECT role_id FROM y_user_roles WHERE uid=:uid";
            foreach ($db->query($sql, array(':uid' => $this->getUid()))->fetchAll() as $row) {
                $roles[] = $row['role_id'];
            }
            $this->roles = $roles;
        }
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function hasRole($role)
    {
        $roles = $this->getRoles();
        return isset($roles[$role]);
    }

    /**
     * @return UserProfileEntity
     */
    public function getProfile()
    {
        return $this->repository()->load('user-profile', $this->getUid());
    }

    public function getId()
    {
        return $this->uid;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setPassword($password)
    {
        $this->password = $this->encryptPassword($password);
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    public function updatePassword($password)
    {
        return $this->repository()->getConnection()->update($this->table(), array(
                'password' => $this->encryptPassword($password)
            ), 'uid=:uid', array(':uid' => $this->uid));
    }

    public function encryptPassword($password)
    {
        return md5($password);
    }

    public static function findByName(Query $query, $name)
    {
        return $query->where(array(
                'name=?' => $name
            ))->one();
    }
}
