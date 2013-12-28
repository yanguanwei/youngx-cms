<?php

namespace Youngx\Bundle\ArchiveBundle\Module\ChannelModule\Form;

use Youngx\Bundle\ArchiveBundle\Module\ChannelModule\Entity\ChannelEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class AdminForm extends Form
{
    protected $label;
    protected $parent_id = 0;
    protected $sort_num = 0;
    /**
     * @var ChannelEntity
     */
    protected $parent;
    /**
     * @var ChannelEntity
     */
    protected $channel;

    protected function validate(Form\FormErrorHandler $feh)
    {
        if ($this->parent_id && $this->channel) {
            if ($this->parent_id == $this->channel->getId()) {
                $feh->add('parent_id', '不能选择自己作为父栏目');
            } else {
                $parent = $this->context->repository()->load('channel', $this->parent_id);
                if ($parent) {
                    foreach ($parent->getPaths() as $category) {
                        if ($category->getId() == $this->channel->getId()) {
                            $feh->add('parent_id', '不能选择该分类的子栏目作为其父栏目');
                            break;
                        }
                    }
                } else {
                    $feh->add('parent_id', '不存在的父栏目');
                }
            }
        }
    }

    protected function submit(GetResponseEvent $event)
    {
        if (!$this->channel) {
            $this->channel = $this->context->repository()->create('channel');
        }

        $this->channel->set($this->toArray());
        $this->channel->save();

        $this->context->flash()->add('success', sprintf(
                '栏目 <i>%s</i> 保存成功', $this->channel->getLabel()
            ));

        $event->setResponse($this->context->redirectResponse(
                $this->context->generateUrl('channel-admin')
            ));
    }

    protected function fields()
    {
        return array(
            'label', 'parent_id', 'sort_num'
        );
    }

    protected function registerValidators()
    {
        return array(
            'label' => array(
                'required' => '栏目名称不能为空'
            )
        );
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Form', array(
                    '#form' => $this,
                    '#skin' => 'horizontal'
                )));
        if ($this->channel) {
            $response->addVariable('#subtitle', '编辑栏目 #' . $this->channel->getId());
        } else {
            $response->addVariable('#subtitle', '添加栏目');
        }
    }

    /**
     * @param \Youngx\Bundle\ArchiveBundle\Module\ChannelModule\Entity\ChannelEntity $parent
     */
    public function setParent(ChannelEntity $parent)
    {
        $this->parent = $parent;
        $this->parent_id = $parent->getId();
    }

    public function renderFormWidget(FormWidget $widget)
    {
        $widget->addField('parent_id')->label('父栏目')->select_channel(array(
                '#empty' => '作为一级栏目',
                '#chosen' => array('search_contains' => true),
            ));
        $widget->addField('label')->label('栏目名称')->text();
        $widget->addField('sort_num')->label('排序')->text();
    }

    public function id()
    {
        return 'channel-admin';
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

    public function setChannel(ChannelEntity $channel)
    {
        $this->channel = $channel;
        $this->set($channel->toArray());
    }
}