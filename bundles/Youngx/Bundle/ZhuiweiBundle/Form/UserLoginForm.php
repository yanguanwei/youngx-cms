<?php

namespace Youngx\Bundle\ZhuiweiBundle\Form;

use Youngx\Bundle\UserBundle\Form\LoginForm;
use Youngx\MVC\RenderableResponse;

class UserLoginForm extends LoginForm
{
    protected $hasCaptcha = true;

    protected function render(RenderableResponse $response)
    {
        $response->setContent($form = $this->context->widget('Form', array(
                    '#form' => $this,
                    '#autoRenderSubmitActions' => false,
                    '#alertErrors' => true
                )));
        $form->render('login.html.yui@Zhuiwei');
    }
}