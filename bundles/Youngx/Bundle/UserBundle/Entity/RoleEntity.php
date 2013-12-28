<?php

namespace Youngx\Bundle\UserBundle\Entity;

use Youngx\Database\Entity;
use Youngx\Database\Query;

class RoleEntity extends Entity
{
    protected $id;
    protected $label;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    public static function withUser(Query $query, UserEntity $user)
    {
        $query->leftJoinTable(array('y_user_roles', 'ur'), "ur.uid={$query->getAlias()}.uid");
        $query->where('ur.uid=?', $user->getUid());

        return $query;
    }

    public static function type()
    {
        return 'role';
    }

    public static function table()
    {
        return 'y_role';
    }

    public static function primaryKey()
    {
        return 'id';
    }

    public static function fields()
    {
        return array('id', 'label');
    }
}