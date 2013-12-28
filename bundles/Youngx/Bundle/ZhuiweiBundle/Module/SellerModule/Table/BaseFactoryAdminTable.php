<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Table;

use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\FactoryEntity;
use Youngx\Database\Query;
use Youngx\EventHandler\Event\GetArrayEvent;
use Youngx\MVC\Html;
use Youngx\MVC\ListView\ColumnCollection;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Table;

class BaseFactoryAdminTable extends Table
{
    public function id()
    {
        return 'factory-admin';
    }

    /**
     * @return Query
     */
    protected function query()
    {
        return $this->context->repository()->query('factory');
    }

    protected function filter(Query $query, GetArrayEvent $event)
    {
        $query->where('uid=0')
            ->order('id DESC');
    }

    protected function collectColumns(ColumnCollection $collection)
    {
        $collection->add('id', '工厂ID');
        $collection->add('name', '工厂名称');
        $collection->add('operations', '操作');
    }

    protected function formatOperationsColumn(FactoryEntity $entity, Html $td)
    {
        $edit = $this->context->html('a', array(
                'href' => $this->context->generateUrl('factory-admin-edit', array(
                        'factory' => $entity->getId(),
                        'returnUrl' => $this->context->request()->getUri()
                    )),
                '#content' => '编辑'
            ));

        $delete = $this->context->html(
            'a', array(
                'href' => $this->context->generateUrl('factory-admin-delete', array(
                        'id' => $entity->getId(),
                        'returnUrl' => $this->context->request()->getUri()
                    )),
                '#content' => '删除'
            )
        );

        $td->setContent("{$edit} | {$delete}");
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Table', array(
                    '#table' => $this
                )))->addVariable('#subtitle', '基础工厂列表');
    }
}