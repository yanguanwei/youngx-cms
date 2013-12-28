<?php

namespace Youngx\Bundle\ZhuiweiBundle\Controller;

use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductEntity;
use Youngx\MVC\Context;

class SellerController
{
    public function indexAction(Context $context)
    {
        $context->assets()->registerStyleUrl('seller', 'Zhuiwei/css/M_4.css');
        return $context->renderableResponse();
    }

    public function productsAction(Context $context)
    {
        return $context->actionResponse('Action:SellerProducts@Zhuiwei');
    }

    public function addProductAction(Context $context)
    {
        return $context->actionResponse('Form:SellerProduct@Zhuiwei', array(
                'user' => $context->identity()->getUserEntity()
            ));
    }

    public function editProductAction(Context $context, ProductEntity $product)
    {
        return $context->actionResponse('Form:SellerProduct@Zhuiwei', array(
                'product' => $product
            ));
    }

    public function buyersAction(Context $context)
    {
        return $context->renderableResponse()->render('seller/buyers.html.yui@Zhuiwei', array(
                '#subtitle' => '个人买家'
            ));
    }

    public function companiesAction(Context $context)
    {
        return $context->renderableResponse()->render('seller/companies.html.yui@Zhuiwei', array(
                '#subtitle' => '机构买家'
            ));
    }

    public function ordersBuyersAction(Context $context)
    {
        return $context->renderableResponse()->render('seller/orders-buyers.html.yui@Zhuiwei', array(
                '#subtitle' => '交易中的买家'
            ));
    }

    public function ordersBuyersCompleteAction(Context $context)
    {
        return $context->renderableResponse()->render('seller/orders-buyers-complete.html.yui@Zhuiwei', array(
                '#subtitle' => '已完成交易的买家'
            ));
    }

    public function ordersProductsAction(Context $context)
    {
        return $context->renderableResponse()->render('seller/orders-products.html.yui@Zhuiwei', array(
                '#subtitle' => '正在交易中的产品'
            ));
    }

    public function ordersCompleteAction(Context $context)
    {
        return $context->renderableResponse()->render('seller/orders-complete.html.yui@Zhuiwei', array(
                '#subtitle' => '已完成的交易'
            ));
    }

    public function factoryAction(Context $context)
    {
        return $context->renderableResponse()->render('seller/factory.html.yui@Zhuiwei', array(
                '#subtitle' => '工厂信息'
            ));
    }

    public function factoryEquipmentAction(Context $context)
    {
        return $context->renderableResponse()->render('seller/factory-equipment.html.yui@Zhuiwei', array(
                '#subtitle' => '工厂设备'
            ));
    }

    public function paymentAction(Context $context)
    {
        return $context->renderableResponse()->render('seller/payment.html.yui@Zhuiwei', array(
                '#subtitle' => '支付管理'
            ));
    }

    public function shippingAction(Context $context)
    {
        return $context->renderableResponse()->render('seller/shipping.html.yui@Zhuiwei', array(
                '#subtitle' => '物流管理'
            ));
    }
}