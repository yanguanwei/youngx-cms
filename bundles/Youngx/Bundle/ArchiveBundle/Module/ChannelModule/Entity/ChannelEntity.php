<?php

namespace Youngx\Bundle\ArchiveBundle\Module\ChannelModule\Entity;

use Youngx\Database\Entity;
use Youngx\Database\Query;

class ChannelEntity extends Entity
{
    protected $id;
    protected $label;
    protected $parent_id = 0;
    protected $ancestor_id;
    protected $sort_num = 0;

    public static function type()
    {
        return 'channel';
    }

    public static function inParent(Query $query, $parentId)
    {
        $query->where("{$query->getAlias()}.parent_id IN (?)", $parentId);
    }

    /**
     * @return ChannelEntity
     */
    public function getParent()
    {
        return $this->resolveExtraFieldValue('parent');
    }


    public function hasParent()
    {
        return $this->parent_id != 0;
    }

    public function hasChildren()
    {
        return $this->repository()->exist('channel', 'parent_id=:parent_id', array(':parent_id' => $this->id));
    }

    /**
     * @return ChannelEntity[]
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
     * @return ChannelEntity[]
     */
    public function getChildren()
    {
        return $this->repository()
            ->query('channel')
            ->where('parent_id=:parent_id')
            ->order('sort_num ASC')
            ->all(array(':parent_id' => $this->id));
    }

    public static function table()
    {
        return 'y_channel';
    }

    public static function primaryKey()
    {
        return 'id';
    }

    public static function fields()
    {
        return array(
            'id', 'label', 'parent_id', 'sort_num'
        );
    }

    /**
     * @param mixed $ancestor_id
     */
    public function setAncestorId($ancestor_id)
    {
        $this->ancestor_id = $ancestor_id;
    }

    /**
     * @return mixed
     */
    public function getAncestorId()
    {
        return $this->ancestor_id;
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
     * @param int $parent_id
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * @param int $sort_num
     */
    public function setSortNum($sort_num)
    {
        $this->sort_num = $sort_num;
    }

    /**
     * @return int
     */
    public function getSortNum()
    {
        return $this->sort_num;
    }
}