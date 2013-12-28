<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Controller;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\MVC\Context;

class AdminController
{
    public function indexAction(Context $context)
    {
        return $context->actionResponse('Table:BuyerAdmin@Zhuiwei:Buyer');
    }

    public function companyAction(Context $context, UserEntity $user = null)
    {
        return $context->actionResponse('Form:CompanyAdmin@Zhuiwei:Buyer', array(
                'user' => $user ?: $context->identity()->getUserEntity()
            ));
    }
}