<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Controller;

use Youngx\Bundle\AdminBundle\Module\FileModule\Entity\FileEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductEntity;
use Youngx\MVC\Context;

class AdminController
{
    public function indexAction(Context $context)
    {
        return $context->actionResponse('Table:ProductAdmin@Zhuiwei:Product');
    }

    public function addAction(Context $context)
    {
        return $context->actionResponse('Form:ProductAdmin@Zhuiwei:Product');
    }

    public function editAction(Context $context, ProductEntity $product)
    {
        return $context->actionResponse('Form:ProductAdmin@Zhuiwei:Product', array(
                'product' => $product
            ));
    }

    public function categoryAction(Context $context, ProductEntity $product)
    {
        return $context->actionResponse('Form:ProductCategoryAdmin@Zhuiwei:Product', array(
                'product' => $product
            ));
    }

    public function deleteAction(Context $context)
    {
        return $context->actionResponse('Action:ProductDeleteAdmin@Zhuiwei:Product');
    }

    public function pictureAction(Context $context, ProductEntity $product)
    {
        return $context->actionResponse('Form:ProductPictureAdmin@Zhuiwei:Product', array(
                'product' => $product
            ));
    }

    public function defaultPictureAction(Context $context, ProductEntity $product, FileEntity $file)
    {
        $product->setPicture($file->getUri());
        $product->save();

        $context->flash()->add('success', sprintf('设置图片 <i>%s</i> 为默认成功！', $file->getFilename()));

        return $context->redirectResponse($context->generateUrl('product-admin-picture', array(
                    'product' => $product->getId()
                )));
    }
}