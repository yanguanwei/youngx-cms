<?php

namespace Youngx\Bundle\CategoryBundle\Table;

use Youngx\Bundle\CategoryBundle\Entity\CategoryEntity;
use Youngx\Database\Query;
use Youngx\EventHandler\Event\GetArrayEvent;
use Youngx\MVC\Html;
use Youngx\MVC\ListView\ColumnCollection;
use Youngx\MVC\ListView\ColumnInterface;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Table\TreeTable;

class CategoryAdminTreeTable extends TreeTable
{
    protected $parent_id = 0;
    protected $uid = 0;

    /**
     * @param mixed $parent_id
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }

    public function setUid($uid)
    {
        $this->uid = intval($uid);
    }

    protected function filter(Query $query, GetArrayEvent $event)
    {
        $query->where('uid=:uid AND parent_id=:parent_id');
        $event->addValue($this->parent_id, ':parent_id');
        $event->addValue($this->uid, ':uid');
    }

    public function getPostDataForScriptCodes()
    {
        return 'return {parent_id: entity.id, uid: '.$this->uid.'}';
    }

    public function id()
    {
        return 'category-list';
    }

    /**
     * @return Query
     */
    protected function query()
    {
        return $this->context->repository()->query('category');
    }

    /**
     * @param CategoryEntity $entity
     * @return Boolean
     */
    public function hasChildForEntity($entity)
    {
        return $entity->hasChildren();
    }

    protected function render(RenderableResponse $response)
    {
        parent::render($response);
        $response->addVariable('#subtitle', '分类');
    }

    protected function collectColumns(ColumnCollection $collection)
    {
        $collection->add('label', '分类名');
        $collection->add('operations', '操作');
    }

    protected function formatOperationsColumn(CategoryEntity $entity, Html $td)
    {
        $add = $this->context->html('a', array(
                'href' => $this->context->generateUrl('category-add', array(
                        'parent' => $entity->getId(),
                        'returnUrl' => $this->context->request()->getUri()
                    )),
                '#content' => '添加子分类'
            ));
/*
        $addCustom = $this->context->html('a', array(
                'href' => $this->context->generateUrl('category-custom-admin', array(
                        'category' => $entity->getId()
                    )),
                '#content' => '自定义'
            ));
*/
        $edit = $this->context->html('a', array(
                'href' => $this->context->generateUrl('category-edit', array(
                        'category' => $entity->getId(),
                        'returnUrl' => $this->context->request()->getUri()
                    )),
                '#content' => '编辑'
            ));

        $delete = $this->context->html('a', array(
                'href' => $this->context->generateUrl('category-delete', array(
                        'id' => $entity->getId(),
                        'returnUrl' => $this->context->request()->getUri()
                    )),
                '#content' => '删除'
            ));

        $td->setContent("{$add} | {$edit} | {$delete}");
    }

    protected function formatLabelColumnHtml(Html $td)
    {
        $td->style('width', '40%');
    }
}