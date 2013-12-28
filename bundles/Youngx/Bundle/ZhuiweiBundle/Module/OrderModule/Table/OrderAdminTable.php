<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\OrderModule\Table;

use Youngx\Bundle\ZhuiweiBundle\Module\OrderModule\Entity\OrderEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductEntity;
use Youngx\Database\Query;
use Youngx\EventHandler\Event\GetArrayEvent;
use Youngx\MVC\Html;
use Youngx\MVC\ListView\ColumnCollection;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Table;
use Youngx\MVC\Widget\FormWidget;

class OrderAdminTable extends Table
{
    protected $id;
    protected $buyer_uid;
    protected $seller_uid;
    protected $status;

    public function id()
    {
        return 'order-admin';
    }

    /**
     * @return Query
     */
    protected function query()
    {
        return $this->context->repository()->query('order');
    }

    protected function collectColumns(ColumnCollection $collection)
    {
        $collection->add('id', '订单号');
        $collection->add('product', '产品');
        $collection->add('seller', '卖家');
        $collection->add('buyer', '买家');
        $collection->add('created_at', '下单时间');
        $collection->add('operations', '操作');
    }

    protected function formatBuyerColumn(OrderEntity $entity, Html $td)
    {
        $user = $entity->getBuyer();
        if ($user) {
            $td->setContent($user->getName());
        }
    }

    protected function formatSellerColumn(OrderEntity $entity, Html $td)
    {
        $user = $entity->getSeller();
        if ($user) {
            $td->setContent($user->getName());
        }
    }

    protected function formatProductColumn(OrderEntity $entity, Html $td)
    {
        $product = $entity->getProduct();
        if ($product) {
            $td->setContent($product->getTitle());
        }
    }

    protected function formatCreatedAtColumn(OrderEntity $entity, Html $td)
    {
        $td->setContent(date('Y-m-d H:i:s', $entity->getCreatedAt()));
    }

    protected function formatOperationsColumn(OrderEntity $entity, Html $td)
    {
        $view = $this->context->html('a', array(
                'href' => $this->context->generateUrl('order-admin-view', array(
                        'order' => $entity->getId(),
                        'returnUrl' => $this->context->request()->getUri()
                    )),
                '#content' => '查看'
            ));

        $edit = $this->context->html('a', array(
                'href' => $this->context->generateUrl('order-admin-edit', array(
                        'order' => $entity->getId(),
                        'returnUrl' => $this->context->request()->getUri()
                    )),
                '#content' => '编辑'
            ));

        $delete = $this->context->html(
            'a', array(
                'href' => $this->context->generateUrl('order-admin-delete', array(
                        'id' => $entity->getId(),
                        'returnUrl' => $this->context->request()->getUri()
                    )),
                '#content' => '删除'
            )
        );

        $td->setContent("{$view} | {$edit} | {$delete}");
    }

    protected function render(RenderableResponse $response)
    {
        $searchForm = $this->context->widget('Form', array(
                '#skin' => 'search',
                '#action' => $this
            ));

        $response->setContent($searchForm . $this->context->widget('Table', array(
                    '#table' => $this
                )))->addVariable('#subtitle', '订单列表');
    }

    public function renderFormWidget(FormWidget $form)
    {
        $form->addField('id')->label('订单号')->text();
        $form->addField('seller_uid')->label('卖家帐号')->select_user();
    }

    protected function filter(Query $query, GetArrayEvent $event)
    {
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

    /**
     * @param mixed $seller_uid
     */
    public function setSellerUid($seller_uid)
    {
        $this->seller_uid = $seller_uid;
    }

    /**
     * @return mixed
     */
    public function getSellerUid()
    {
        return $this->seller_uid;
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
     * @param mixed $buyer_uid
     */
    public function setBuyerUid($buyer_uid)
    {
        $this->buyer_uid = $buyer_uid;
    }

    /**
     * @return mixed
     */
    public function getBuyerUid()
    {
        return $this->buyer_uid;
    }
}