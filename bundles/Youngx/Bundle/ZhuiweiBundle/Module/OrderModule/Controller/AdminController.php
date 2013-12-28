<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\OrderModule\Controller;

use Youngx\Bundle\ZhuiweiBundle\Module\OrderModule\Entity\OrderEntity;
use Youngx\MVC\Context;

class AdminController
{
    public function indexAction(Context $context)
    {
        return $context->actionResponse('Table:OrderAdmin@Zhuiwei:Order');
    }

    public function viewAction(Context $context, OrderEntity $order)
    {
        $response = $context->renderableResponse()->setFile('admin/view.html.yui@Zhuiwei:Order');

        $response->addVariable('#subtitle', '查看订单');

        return $response;
    }
}