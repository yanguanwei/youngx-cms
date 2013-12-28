<?php

namespace Youngx\Bundle\AdminBundle\Module\VocabularyModule\Entity;

use Youngx\Database\Entity;

class TermEntity extends Entity
{
    protected $id;
    protected $vocabulary_id;
    protected $label;
    protected $icon;
    protected $sort_num = 0;

    public static function type()
    {
        return 'term';
    }

    public static function table()
    {
        return 'y_term';
    }

    public static function primaryKey()
    {
        return 'id';
    }

    public static function fields()
    {
        return array(
            'id', 'vocabulary_id', 'label', 'icon', 'sort_num'
        );
    }

    /**
     * @return VocabularyEntity
     */
    public function getVocabulary()
    {
        return $this->resolveExtraFieldValue('vocabulary');
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
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
     * @param mixed $vocabulary_id
     */
    public function setVocabularyId($vocabulary_id)
    {
        $this->vocabulary_id = $vocabulary_id;
    }

    /**
     * @return mixed
     */
    public function getVocabularyId()
    {
        return $this->vocabulary_id;
    }
}