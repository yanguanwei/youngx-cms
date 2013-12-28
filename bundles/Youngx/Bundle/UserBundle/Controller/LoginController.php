<?php

namespace Youngx\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Youngx\Bundle\UserBundle\Form\LoginForm;
use Youngx\MVC\Application;
use Youngx\MVC\RenderableResponse;

class LoginController extends LoginForm
{
    public function indexAction()
    {
        return $this->run();
    }

    public function render()
    {
        return Application::getInstance()->render('login.html.php@User', array(
            'form' => $this
        ));
    }
}