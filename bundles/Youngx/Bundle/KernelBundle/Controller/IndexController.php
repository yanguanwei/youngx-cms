<?php

namespace Youngx\Bundle\KernelBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Youngx\MVC\Application;
use Youngx\Util\Captcha\Captcha;

class IndexController
{
    public function indexAction(Application $app)
    {
        var_dump($app);

        return new Response('hello world');
    }

    public function captchaAction(Context $context, $id = null)
    {
        $captcha = new Captcha($id);
        $response = $context->response($captcha->create(false));
        $response->headers->set('Content-Type', 'image/png');
        return $response;
    }

    public function renderAction(Context $context, $path, array $variables = array())
    {
        return $context->renderableResponse()->render($path, $variables);
    }
}
