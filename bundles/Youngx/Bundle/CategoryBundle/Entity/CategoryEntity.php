<?php

namespace Youngx\Bundle\CategoryBundle\Entity;

use Youngx\Database\Entity;
use Youngx\Database\Query;

class CategoryEntity extends Entity
{
    protected $id;
    protected $label;
    protected $uid = 0;
    protected $parent_id = 0;
    protected $sort_num = 0;

    public static function type()
    {
        return 'category';
    }

    public static function table()
    {
        return 'y_category';
    }

    public static function primaryKey()
    {
        return 'id';
    }

    public static function fields()
    {
        return array(
            'id', 'label', 'uid', 'parent_id', 'sort_num'
        );
    }

    public function getUser()
    {
        return $this->uid ? $this->repository()->load('user', $this->uid) : null;
    }

    /**
     * @return CategoryCustomEntity[]
     */
    public function getCustomCategories()
    {
        return $this->resolveExtraFieldValue('custom_categories');
    }

    /**
     * @return CategoryEntity | null
     */
    public function getParent()
    {
        return $this->parent_id ? $this->repository()->load('category', $this->parent_id) : null;
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
     * @param mixed $parent_id
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parent_id;
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

    public function hasChildren()
    {
        return $this->repository()->exist('category', 'parent_id=:parent_id', array(':parent_id' => $this->id));
    }

    /**
     * @param int $uid
     * @return CategoryEntity[]
     */
    public function getChildren($uid = 0)
    {
        return $this->repository()
            ->query('category')
            ->where('uid=:uid AND parent_id=:parent_id')
            ->order('sort_num ASC')
            ->all(array(':uid' => $uid, ':parent_id' => $this->id));
    }

    /**
     * @return CategoryEntity[]
     */
    public function getPaths()
    {
        $current = $this;
        $paths = array();
        while ($current) {
            array_unshift($paths, $current);
            $current = $current->getParent();
        }
        return $paths;
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

    public static function standard(Query $query)
    {
        return $query->where("{$query->getAlias()}.uid='0'");
    }

    public static function ancestral(Query $query)
    {
        return $query->where("{$query->getAlias()}.parent_id='0'");
    }

    public static function orderly(Query $query)
    {
        $alias = $query->getAlias();
        return $query->order("{$alias}.sort_num ASC, {$alias}.id ASC");
    }
}