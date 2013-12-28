<?php

namespace Youngx\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Youngx\MVC\Context;

class HomeController
{
    public function indexAction(Context $context)
    {
        var_dump($context->identity());

        return new Response('');
    }
}