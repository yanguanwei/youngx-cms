<?php

namespace Youngx\Bundle\AdminBundle\Module\DistrictModule\Entity;

use Youngx\Database\Entity;

class DistrictEntity extends Entity
{
    protected $id;
    protected $label;
    protected $code;
    protected $layer;
    protected $parent_id = 0;
    protected $sort_num = 0;

    public static function type()
    {
        return 'district';
    }

    public static function table()
    {
        return 'y_district';
    }

    public static function primaryKey()
    {
        return 'id';
    }

    public static function fields()
    {
        return array(
            'id', 'label', 'code', 'layer', 'parent_id', 'sort_num'
        );
    }

    /**
     * @return DistrictEntity[]
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
     * @return DistrictEntity | null
     */
    public function getParent()
    {
        return $this->parent_id ? $this->repository()->load('district', $this->parent_id) : null;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
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
     * @param mixed $layer
     */
    public function setLayer($layer)
    {
        $this->layer = $layer;
    }

    /**
     * @return mixed
     */
    public function getLayer()
    {
        return $this->layer;
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
        return $this->repository()->exist('district', 'parent_id=:parent_id', array(':parent_id' => $this->id));
    }
}