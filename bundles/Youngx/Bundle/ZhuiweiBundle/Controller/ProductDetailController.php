<?php

namespace Youngx\Bundle\ZhuiweiBundle\Controller;

use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductEntity;
use Youngx\MVC\Context;

class ProductDetailController
{
    public function indexAction(Context $context, ProductEntity $product)
    {
        return $context->renderableResponse()->setFile('product-detail.html.yui@Zhuiwei')
            ->addVariables(array(
                    'product' => $product,
                    'seller' => $product->getSeller()
                ));
    }
}