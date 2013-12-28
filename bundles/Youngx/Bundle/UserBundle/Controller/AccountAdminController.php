<?php

namespace Youngx\Bundle\UserBundle\Controller;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\MVC\Context;

class AccountAdminController
{
    public function indexAction(Context $context, UserEntity $user = null)
    {
        return $context->actionResponse('Form:AccountAdmin@User', array(
                'user' => $user ?: $context->identity()->getUserEntity()
            ));
    }

    public function profileAction(Context $context, UserEntity $user = null)
    {
        return $context->actionResponse('Form:ProfileAdmin@User', array(
                'user' => $user ?: $context->identity()->getUserEntity()
            ));
    }

    public function passwordAction(Context $context, UserEntity $user = null)
    {
        return $context->actionResponse('Form:ChangePasswordAdmin@User', array(
                'user' => $user ?: $context->identity()->getUserEntity()
            ));
    }
}