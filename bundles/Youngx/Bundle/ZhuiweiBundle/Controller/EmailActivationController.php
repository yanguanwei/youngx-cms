<?php

namespace Youngx\Bundle\ZhuiweiBundle\Controller;

use Youngx\Bundle\UserBundle\Module\ActivationModule\Action\ActivationVerifyAction;
use Youngx\MVC\RenderableResponse;

class EmailActivationController extends ActivationVerifyAction
{
    public function indexAction($token)
    {
        $this->setToken($token);
        return $this->run();
    }

    protected function render(RenderableResponse $response)
    {
        $response->render('email-verify.html.yui@Zhuiwei', array(
                'result' => $this->result
            ));
    }
}