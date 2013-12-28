<?php

namespace Youngx\Bundle\AdminBundle\Module\FileModule\Entity;

use Youngx\Database\Entity;
use Youngx\Database\Query;

class FileEntity extends Entity
{
    protected $id;
    protected $entity_type;
    protected $entity_id;
    protected $uid;
    protected $uri;
    protected $filename;
    protected $mime_type;
    protected $created_at;
    protected $sort_num = 0;

    public static function type()
    {
        return 'file';
    }

    public static function table()
    {
        return 'y_file';
    }

    public static function primaryKey()
    {
        return 'id';
    }

    public static function fields()
    {
        return array(
            'id', 'entity_type', 'entity_id', 'uid', 'uri', 'filename', 'mime_type', 'created_at', 'sort_num'
        );
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $entity_id
     */
    public function setEntityId($entity_id)
    {
        $this->entity_id = $entity_id;
    }

    /**
     * @return mixed
     */
    public function getEntityId()
    {
        return $this->entity_id;
    }

    /**
     * @param mixed $entity_type
     */
    public function setEntityType($entity_type)
    {
        $this->entity_type = $entity_type;
    }

    /**
     * @return mixed
     */
    public function getEntityType()
    {
        return $this->entity_type;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * @param mixed $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param mixed $mime_type
     */
    public function setMimeType($mime_type)
    {
        $this->mime_type = $mime_type;
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mime_type;
    }

    /**
     * @param int $sort_num
     */
    public function setSortNum($sort_num)
    {
        $this->sort_num = intval($sort_num);
    }

    /**
     * @return int
     */
    public function getSortNum()
    {
        return $this->sort_num;
    }

    public static function withEntity(Query $query, Entity $entity)
    {
        $query->where("entity_type='{$entity->type()}' AND entity_id='{$entity->identifier()}'");
        return $query;
    }

    public static function orderly(Query $query)
    {
        $query->order('sort_num ASC, id ASC');
        return $query;
    }
}