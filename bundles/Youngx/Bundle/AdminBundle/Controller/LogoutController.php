<?php

namespace Youngx\Bundle\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Youngx\MVC\Context;

class LogoutController
{
    public function indexAction(Context $context)
    {
        $context->logout();

        $returnUrl = $context->request()->query->get('returnUrl');

        return RedirectResponse::create($returnUrl ? $returnUrl : $context->generateUrl('admin-login'));
    }
}