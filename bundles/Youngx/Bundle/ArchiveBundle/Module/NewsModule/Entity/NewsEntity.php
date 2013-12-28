<?php

namespace Youngx\Bundle\ArchiveBundle\Module\NewsModule\Entity;

use Youngx\Database\Entity;

class NewsEntity extends Entity
{
    protected $id;
    protected $content;

    /**
     * @return \Youngx\Bundle\ArchiveBundle\Entity\ArchiveEntity
     */
    public function getArchive()
    {
        return $this->repository()->load('archive', $this->id);
    }

    public static function type()
    {
        return 'news';
    }

    public static function table()
    {
        return 'y_archive_news';
    }

    public static function primaryKey()
    {
        return 'id';
    }

    public static function fields()
    {
        return array(
            'id', 'content'
        );
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}