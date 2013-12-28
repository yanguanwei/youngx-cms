<?php

namespace Youngx\Bundle\ZhuiweiBundle\Controller;

use Youngx\MVC\Context;
use Youngx\MVC\User\Identity;

class UserController
{
    public function indexAction(Context $context)
    {
        if ($context->identity()->hasRole(Identity::ROLE_SELLER)) {
            return $context->redirectResponse($context->generateUrl('seller-home'));
        } else {
            return $context->redirectResponse($context->generateUrl('buyer-home'));
        }
    }
}