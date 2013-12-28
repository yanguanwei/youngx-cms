<?php

namespace Youngx\Bundle\CategoryBundle\Table;

use Youngx\Bundle\CategoryBundle\Entity\CategoryCustomEntity;
use Youngx\Bundle\CategoryBundle\Entity\CategoryEntity;
use Youngx\Database\Query;
use Youngx\EventHandler\Event\GetArrayEvent;
use Youngx\MVC\Html;
use Youngx\MVC\ListView\ColumnCollection;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Table;
use Youngx\MVC\Widget\ButtonGroupWidget;

class CategoryCustomAdminTable extends Table
{
    /**
     * @var CategoryEntity
     */
    protected $category;

    /**
     * @param mixed $category
     */
    public function setCategory(CategoryEntity $category)
    {
        $this->category = $category;
    }

    protected function filter(Query $query, GetArrayEvent $event)
    {
        $query->where('category_id=:category_id');
        $event->addValue($this->category->getId(), ':category_id');
    }

    public function id()
    {
        return 'category-custom-admin';
    }

    /**
     * @return Query
     */
    protected function query()
    {
        return $this->context->repository()->query('category_custom');
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Table', array(
                    '#table' => $this
                )));

        $paths = array();
        foreach ($this->category->getPaths() as $category) {
            $paths[] = $category->getLabel();
        }
        $response->addVariable('#subtitle', array(
                implode(' &gt; ', $paths), '自定义分类'
            ));
    }

    protected function collectColumns(ColumnCollection $collection)
    {
        $collection->add('label', '分类名');
        $collection->add('user', '用户 | 标准');
        $collection->add('operations', '操作');
    }

    protected function formatUserColumnHeading(Html $th)
    {
        $th->style('width', '20%')->addClass('center');
    }

    protected function formatUserColumn(CategoryCustomEntity $entity, Html $td)
    {
        $user = $entity->getUser();
        if ($user) {
            $td->setContent('用户 | ' . $user->getName());
        } else {
            $td->setContent('标准');
        }

        $td->addClass('center');
    }

    protected function formatOperationsColumn(CategoryCustomEntity $entity, Html $td)
    {
        $edit = $this->context->html('a', array(
                'href' => $this->context->generateUrl('category-custom-admin-edit', array(
                        'category_custom' => $entity->getId(),
                        'returnUrl' => $this->context->request()->getUri()
                    )),
                '#content' => '编辑'
            ));

        $delete = $this->context->html('a', array(
                'href' => $this->context->generateUrl('category-custom-admin-delete', array(
                        'id' => $entity->getId(),
                        'returnUrl' => $this->context->request()->getUri()
                    )),
                '#content' => '删除'
            ));

        $td->setContent("{$edit} | {$delete}");
    }

    public function getBatchName()
    {
        return 'id';
    }

    public function renderButtonGroupWidget(ButtonGroupWidget $widget)
    {
        $default = $widget->getGroup('default')->setLabel('操作');
        $default->add('delete', '批量删除', $this->context->generateUrl('category-custom-admin-delete'));
    }
}