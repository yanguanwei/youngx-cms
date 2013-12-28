<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Controller;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\SellerEntity;
use Youngx\MVC\Context;

class AdminController
{
    public function indexAction(Context $context)
    {
        return $context->actionResponse('Table:SellerAdmin@Zhuiwei:Seller');
    }

    public function vipAction(Context $context)
    {
        return $context->actionResponse('Table:SellerVipAdmin@Zhuiwei:Seller');
    }

    public function willingAction(Context $context)
    {
        return $context->actionResponse('Table:SellerWillingAdmin@Zhuiwei:Seller');
    }

    public function editAction(Context $context, UserEntity $user)
    {
        return $context->actionResponse('Form:SellerAdmin@Zhuiwei:Seller', array(
                'user' => $user
            ));
    }

    public function setWillingAction(Context $context, UserEntity $user)
    {
        $seller = $context->repository()->load('seller', $user->getUid());
        if (!$seller) {
            $seller = $context->repository()->create('seller', array(
                    'uid' => $user->getUid()
                ));
        }

        if ($seller->get('type') > SellerEntity::TYPE_WILLING) {
            $context->flash()->add('error', sprintf('卖家 <i>%s</i> 已是VIP客户！', $user->getName()));
        } else {
            $seller->set('type', SellerEntity::TYPE_WILLING);
            $seller->save();

            $context->flash()->add('success', sprintf('成功设置卖家 <i>%s</i> 成为有意付费客户！', $user->getName()));
        }

        return $context->redirectResponse($context->generateUrl('seller-admin-vip'));
    }
}