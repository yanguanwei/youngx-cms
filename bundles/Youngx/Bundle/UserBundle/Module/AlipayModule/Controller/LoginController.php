<?php

namespace Youngx\Bundle\UserBundle\Module\AlipayModule\Controller;

use Symfony\Component\HttpFoundation\Response;
use Youngx\Component\Alipay\AlipayConfig;
use Youngx\Component\Alipay\AlipayRequest;

class LoginController
{
    public function indexAction()
    {
        $request = new AlipayRequest(new AlipayConfig('2088002367201540', 'hg4z2vrb0abywt978q82uxxo9dqbo8pb'));
        return new Response($request->buildForm(array(
                "return_url" => 'http://youngx.dev/user/login/alipay/return',
            )
        ));
    }
}