<?php

namespace Youngx\Bundle\ArchiveBundle\Form;

use Youngx\Bundle\ArchiveBundle\Entity\ArchiveEntity;
use Youngx\Bundle\ArchiveBundle\Module\ChannelModule\Entity\ChannelEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;

abstract class ArchiveForm extends Form
{
    protected $channel_id;
    protected $title;
    protected $subtitle;
    protected $link;
    protected $cover;
    protected $is_top;
    protected $status;
    protected $updated_at;
    /**
     * @var ChannelEntity
     */
    protected $channel;

    /**
     * @var ArchiveEntity
     */
    protected $archive;

    protected function submit(GetResponseEvent $event)
    {
        $this->updated_at = strtotime($this->updated_at) ?: Y_TIME;
        if (!$this->archive) {
            $this->archive = $this->context->repository()->create('archive', array(
                    'created_at' => Y_TIME
                ));
        }

        $this->archive->set($this->toArray());
        $this->archive->setType($this->getType());
        $this->archive->save();
        $this->save($this->archive, $event);
    }

    protected function registerValidators()
    {
        return array(
            'title' => array(
                'required' => '标题不能为空'
            ),
            'channel_id' => array(
                'required' => '请选择一个栏目'
            )
        );
    }

    /**
     * @return string
     */
    abstract protected function getType();
    abstract protected function save(ArchiveEntity $archive, GetResponseEvent $event);

    public function setChannel(ChannelEntity $channel)
    {
        $this->channel = $channel;
        $this->channel_id = $channel->getId();
    }

    public function setArchive(ArchiveEntity $archive)
    {
        $this->archive = $archive;
        $this->set($archive->toArray());
    }

    protected function fields()
    {
        return array(
            'channel_id', 'title', 'subtitle', 'link', 'cover', 'is_top', 'status', 'updated_at'
        );
    }

    /**
     * @return ArchiveEntity
     */
    public function getArchive()
    {
        return $this->archive;
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