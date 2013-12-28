<?php

namespace Youngx\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Youngx\MVC\Context;

class LogoutController
{
    public function indexAction(Context $context)
    {
        $context->logout();
        return $context->redirectResponse($context->generateUrl('user-login'));
    }
}