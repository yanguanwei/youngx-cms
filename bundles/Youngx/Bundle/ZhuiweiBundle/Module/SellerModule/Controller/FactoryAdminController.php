<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Controller;

use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\FactoryEntity;
use Youngx\MVC\Context;

class FactoryAdminController
{
    public function indexAction(Context $context)
    {
        return $context->actionResponse('Table:FactoryAdmin@Zhuiwei:Seller');
    }

    public function baseAction(Context $context)
    {
        return $context->actionResponse('Table:BaseFactoryAdmin@Zhuiwei:Seller');
    }

    public function deleteAction(Context $context)
    {
        return $context->actionResponse('Action:FactoryDelete@Zhuiwei:Seller');
    }

    public function editAction(Context $context, FactoryEntity $factory)
    {
        return $context->actionResponse('Form:FactoryAdmin@Zhuiwei:Seller', array(
                'factory' => $factory
            ));
    }

    public function photoAction(Context $context, FactoryEntity $factory)
    {
        return $context->actionResponse('Form:FactoryPhotoAdmin@Zhuiwei:Seller', array(
                'factory' => $factory
            ));
    }

    public function photoDeleteAction(Context $context, FactoryEntity $factory)
    {
        return $context->actionResponse('Action:FactoryPhotoDelete@Zhuiwei:Seller', array(
                'factory' => $factory
            ));
    }

    public function pictureAction(Context $context, FactoryEntity $factory)
    {
        return $context->actionResponse('Form:FactoryPictureAdmin@Zhuiwei:Seller', array(
                'factory' => $factory
            ));
    }

    public function pictureDeleteAction(Context $context, FactoryEntity $factory)
    {
        return $context->actionResponse('Action:FactoryPictureDelete@Zhuiwei:Seller', array(
                'factory' => $factory
            ));
    }
}