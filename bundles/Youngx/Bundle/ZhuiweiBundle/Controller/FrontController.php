<?php

namespace Youngx\Bundle\ZhuiweiBundle\Controller;

use Youngx\Bundle\ArchiveBundle\Module\NewsModule\Entity\NewsEntity;
use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\MVC\Context;

class FrontController
{
    public function loginAction(Context $context)
    {
        return $context->actionResponse('Form:UserLogin@Zhuiwei');
    }

    public function buyerRegisterAction(Context $context)
    {
        return $context->actionResponse('Form:BuyerRegister@Zhuiwei');
    }

    public function sellerRegisterAction(Context $context)
    {
        return $context->actionResponse('Form:SellerRegister@Zhuiwei');
    }

    public function registerVerifyAction(Context $context, UserEntity $user)
    {
        return $context->actionResponse('Form:UserPhoneActivation@Zhuiwei', array(
                'user' => $user
            ));
    }

    public function registerSuccessAction(Context $context, UserEntity $user)
    {
        return $context->renderableResponse()->render('register-success.html.yui', array(
                'user' => $user
            ));
    }

    public function platformAction(Context $context, NewsEntity $news = null)
    {
        if (!$news) {
            $news = $context->repository()->load('news', 6);
        }

        return $context->renderableResponse()->setFile('platform.html.yui@Zhuiwei')
                ->addVariables(array(
                    'news' => $news,
                    'archive' => $news->getArchive()
                ));
    }
}