<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Table;

use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\SellerEntity;
use Youngx\Database\Query;
use Youngx\EventHandler\Event\GetArrayEvent;

class SellerVipAdminTable extends SellerAdminTable
{
    protected function filter(Query $query, GetArrayEvent $event)
    {
        parent::filter($query, $event);
        $query->where('zws.type>?', SellerEntity::TYPE_WILLING);
    }
}