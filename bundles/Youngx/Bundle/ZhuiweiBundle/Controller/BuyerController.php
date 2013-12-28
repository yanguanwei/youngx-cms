<?php

namespace Youngx\Bundle\ZhuiweiBundle\Controller;

use Youngx\MVC\Context;

class BuyerController
{
    public function indexAction(Context $context)
    {
        $context->assets()->registerStyleUrl('buyer', 'Zhuiwei/css/mai_1.css');
        return $context->renderableResponse();
    }

    public function sellersAction(Context $context)
    {
        return $context->renderableResponse()->render('buyer/sellers.html.yui@Zhuiwei', array(
                '#subtitle' => '关注我的卖家'
            ));
    }

    public function ordersSellersAction(Context $context)
    {
        return $context->renderableResponse()->render('buyer/orders-sellers.html.yui@Zhuiwei', array(
                '#subtitle' => '交易中的卖家'
            ));
    }

    public function ratingsAction(Context $context)
    {
        return $context->renderableResponse()->render('buyer/ratings.html.yui@Zhuiwei', array(
                '#subtitle' => '评价过的卖家及评价'
            ));
    }

    public function ordersProductsAction(Context $context)
    {
        return $context->renderableResponse()->render('buyer/orders-products.html.yui@Zhuiwei', array(
                '#subtitle' => '正在交易的产品'
            ));
    }

    public function ordersCompleteAction(Context $context)
    {
        return $context->renderableResponse()->render('buyer/orders-complete.html.yui@Zhuiwei', array(
                '#subtitle' => '已完成的交易'
            ));
    }
}