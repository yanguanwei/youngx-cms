<?php

namespace Youngx\Bundle\CategoryBundle\Entity;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Database\Entity;

class CategoryCustomEntity extends Entity
{
    protected $id;
    protected $uid;
    protected $category_id;
    protected $label;
    protected $sort_num;

    public static function type()
    {
        return 'category_custom';
    }

    public static function table()
    {
        return 'y_category_custom';
    }

    public static function primaryKey()
    {
        return 'id';
    }

    public static function fields()
    {
        return array(
            'id', 'uid', 'category_id', 'label', 'sort_num'
        );
    }

    /**
     * @return CategoryEntity
     */
    public function getCategory()
    {
        return $this->resolveExtraFieldValue('category');
    }

    /**
     * @return UserEntity | null
     */
    public function getUser()
    {
        return $this->uid ? $this->repository()->load('user', $this->uid) : null;
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

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

    /**
     * @param mixed $sort_num
     */
    public function setSortNum($sort_num)
    {
        $this->sort_num = $sort_num;
    }

    /**
     * @return mixed
     */
    public function getSortNum()
    {
        return $this->sort_num;
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