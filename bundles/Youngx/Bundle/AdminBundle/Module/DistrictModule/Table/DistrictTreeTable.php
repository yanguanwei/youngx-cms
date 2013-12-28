<?php

namespace Youngx\Bundle\AdminBundle\Module\DistrictModule\Table;

use Youngx\Bundle\AdminBundle\Module\DistrictModule\Entity\DistrictEntity;
use Youngx\Database\Query;
use Youngx\EventHandler\Event\GetArrayEvent;
use Youngx\MVC\Html;
use Youngx\MVC\ListView\ColumnCollection;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Table\TreeTable;

class DistrictTreeTable extends TreeTable
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
        return 'district-tree';
    }

    protected function render(RenderableResponse $response)
    {
        parent::render($response);

        $response->addVariable('#subtitle', '地区列表');
    }

    /**
     * @return Query
     */
    protected function query()
    {
        return $this->context->repository()->query('district');
    }

    protected function collectColumns(ColumnCollection $collection)
    {
        return $collection->add('label', '地区名称');
    }

    protected function filter(Query $query, GetArrayEvent $event)
    {
        $query->where('parent_id=:parent_id');
        $event->addValue($this->parent_id, ':parent_id');
    }

    /**
     * @return string
     */
    public function getPostDataForScriptCodes()
    {
        return 'return {parent_id: entity.id}';
    }

    /**
     * @param DistrictEntity $entity
     * @return Boolean
     */
    public function hasChildForEntity($entity)
    {
        return $entity->hasChildren();
    }

    protected function formatLabelColumnHtml(Html $td)
    {
        $td->style('width', '40%');
    }
}