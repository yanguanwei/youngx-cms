<?php

namespace Youngx\Bundle\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Youngx\Bundle\UserBundle\Form\LoginForm;
use Youngx\MVC\RenderableResponse;

class LoginController extends LoginForm
{
    public function indexAction()
    {
        return $this->run();
    }

    protected function computeRedirectUrl()
    {
        return $this->context->generateUrl('admin');
    }

    protected function render(RenderableResponse $response)
    {
        $response->setFile('login.html.yui@Admin:Ace');
    }
}
