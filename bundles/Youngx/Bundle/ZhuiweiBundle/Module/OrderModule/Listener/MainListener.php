<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\OrderModule\Listener;

use Youngx\Bundle\ZhuiweiBundle\Module\OrderModule\Entity\OrderEntity;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Context;

class MainListener implements Registration
{
    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function getOrderStatuses()
    {
        return array(
            OrderEntity::STATUS_UNPAID => '未付款',
            OrderEntity::STATUS_SHIPPED => '已发货',
            OrderEntity::STATUS_RECEIVED => '已收货',
            OrderEntity::STATUS_INSPECTED => '已验货',
            OrderEntity::STATUS_PAID => '已付款',
            OrderEntity::STATUS_RETURNED => '已退货',
            OrderEntity::STATUS_COMPLETED => '已完成'
        );
    }

    public static function registerListeners()
    {
        return array(
            'kernel.value#order-statuses' => 'getOrderStatuses',
        );
    }
}