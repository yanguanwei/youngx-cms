<?php

namespace Youngx\Bundle\ArchiveBundle\Module\ChannelModule\Table;

use Youngx\Bundle\ArchiveBundle\Module\ChannelModule\Entity\ChannelEntity;
use Youngx\Database\Query;
use Youngx\EventHandler\Event\GetArrayEvent;
use Youngx\MVC\Html;
use Youngx\MVC\ListView\ColumnCollection;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Table\TreeTable;

class ChannelAdminTable extends TreeTable
{
    protected $parent_id = 0;

    /**
     * @param mixed $parent_id
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }

    public function id()
    {
        return 'channel-admin';
    }

    protected function render(RenderableResponse $response)
    {
        parent::render($response);

        $response->addVariable('#subtitle', '栏目列表');
    }

    /**
     * @return Query
     */
    protected function query()
    {
        return $this->context->repository()->query('channel');
    }

    protected function collectColumns(ColumnCollection $collection)
    {
        $collection->add('label', '名称', 1);
        $collection->add('operations', '操作', 2);
    }

    protected function formatOperationsColumn(ChannelEntity $channel, Html $html)
    {
        $add = $this->context->html('a', array(
                'href' => $this->context->generateUrl('channel-add', array(
                        'parent' => $channel->getId()
                    )),
                '#content' => '添加子栏目'
            ));

        $edit = $this->context->html('a', array(
                'href' => $this->context->generateUrl('channel-edit', array(
                        'channel' => $channel->getId()
                    )),
                '#content' => '编辑'
            ));

        $delete = $this->context->html('a', array(
                'href' => $this->context->generateUrl('channel-delete', array(
                        'id' => $channel->getId(),
                        'returnUrl' => $this->context->request()->getUri()
                    )),
                '#content' => '删除'
            )
        );

        $html->setContent("{$add} | {$edit} | {$delete}");
    }

    protected function filter(Query $query, GetArrayEvent $event)
    {
        $query->where('parent_id=:parent_id');
        $event->addValue($this->parent_id, ':parent_id');
    }

    protected function formatLabelColumnHtml(Html $td)
    {
        $td->style('width', '40%');
    }

    /**
     * @return string
     */
    public function getPostDataForScriptCodes()
    {
        return 'return {parent_id: entity.id}';
    }

    /**
     * @param ChannelEntity $entity
     * @return Boolean
     */
    public function hasChildForEntity($entity)
    {
        return $entity->hasChildren();
    }
}