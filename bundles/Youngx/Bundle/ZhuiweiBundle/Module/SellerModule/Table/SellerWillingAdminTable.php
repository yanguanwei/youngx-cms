<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Table;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\UserBundle\Table\UserAdminTable;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\SellerEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\SellerVipEntity;
use Youngx\Database\Query;
use Youngx\EventHandler\Event\GetArrayEvent;
use Youngx\MVC\Html;
use Youngx\MVC\ListView\ColumnCollection;
use Youngx\MVC\RenderableResponse;

class SellerWillingAdminTable extends SellerAdminTable
{
    protected $vip;
    protected $vipTypes;

    protected function filter(Query $query, GetArrayEvent $event)
    {
        parent::filter($query, $event);
        $query->where('zws.type=?', SellerEntity::TYPE_WILLING);
    }

    protected function render(RenderableResponse $response)
    {
        parent::render($response);
        $response->addVariable('#subtitle', '潜在客户');
    }

    protected function collectColumns(ColumnCollection $collection)
    {
        parent::collectColumns($collection);
        $collection->remove('vip_type');
    }
}