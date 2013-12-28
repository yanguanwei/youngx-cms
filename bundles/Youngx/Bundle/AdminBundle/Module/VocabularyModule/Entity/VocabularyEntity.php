<?php

namespace Youngx\Bundle\AdminBundle\Module\VocabularyModule\Entity;

use Youngx\Database\Entity;
use Youngx\Database\Query;

class VocabularyEntity extends Entity
{
    protected $id;
    protected $name;
    protected $label;

    public static function type()
    {
        return 'vocabulary';
    }

    public static function table()
    {
        return 'y_vocabulary';
    }

    public static function primaryKey()
    {
        return 'id';
    }

    public static function fields()
    {
        return array(
            'id', 'name', 'label'
        );
    }

    /**
     * @return TermEntity[]
     */
    public function getTerms()
    {
        return $this->resolveExtraFieldValue('terms');
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
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}