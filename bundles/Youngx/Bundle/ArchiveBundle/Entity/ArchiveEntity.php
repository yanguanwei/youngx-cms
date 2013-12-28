<?php

namespace Youngx\Bundle\ArchiveBundle\Entity;

use Youngx\Bundle\ArchiveBundle\Module\ChannelModule\Entity\ChannelEntity;
use Youngx\Database\Entity;
use Youngx\Database\Query;

class ArchiveEntity extends Entity
{
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

    protected $id;
    protected $channel_id;
    protected $type;
    protected $title;
    protected $subtitle;
    protected $link;
    protected $cover;
    protected $is_top;
    protected $status;
    protected $updated_at;
    protected $created_at;

    public static function type()
    {
        return 'archive';
    }

    public static function published(Query $query)
    {
        $query->where("{$query->getAlias()}.status=?", self::STATUS_PUBLISHED);
    }

    public static function recently(Query $query, $limit = 5)
    {
        $query->order("{$query->getAlias()}.updated_at DESC")->limit($limit);
    }

    public static function inChannels(Query $query, $cid)
    {
        $query->where("{$query->getAlias()}.cid IN (?)", $cid);
    }

    public static function table()
    {
        return 'y_archive';
    }

    public static function primaryKey()
    {
        return 'id';
    }

    public static function fields()
    {
        return array(
            'id', 'channel_id', 'type', 'title', 'subtitle', 'link', 'cover', 'is_top', 'status',
            'created_at', 'updated_at'
        );
    }

    /**
     * @return ChannelEntity
     */
    public function getChannel()
    {
        return $this->resolveExtraFieldValue('channel');
    }

    /**
     * @param mixed $channel_id
     */
    public function setChannelId($channel_id)
    {
        $this->channel_id = $channel_id;
    }

    /**
     * @return mixed
     */
    public function getChannelId()
    {
        return $this->channel_id;
    }

    /**
     * @param mixed $cover
     */
    public function setCover($cover)
    {
        $this->cover = $cover;
    }

    /**
     * @return mixed
     */
    public function getCover()
    {
        return $this->cover;
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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $is_top
     */
    public function setIsTop($is_top)
    {
        $this->is_top = $is_top;
    }

    /**
     * @return mixed
     */
    public function getIsTop()
    {
        return $this->is_top;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * @return mixed
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}