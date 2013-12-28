<?php

namespace Youngx\Bundle\ZhuiweiBundle\Form;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;

class UserPhoneActivationForm extends Form
{
    /**
     * @var UserEntity
     */
    protected $user;

    public function id()
    {
        return 'user-phone-activation';
    }

    public function submit(GetResponseEvent $event)
    {
        $event->setResponse($this->context->redirectResponse(
                $this->context->generateUrl('user-register-success', array(
                        'user' => $this->user->getUid()
                    ))
            ));
    }

    public function render(RenderableResponse $response)
    {
        $response->setContent($form = $this->context->widget('Form', array(
                    '#form' => $this,
                    '#autoRenderSubmitActions' => false,
                    '#alertErrors' => true
                )));

        $form->render('register-verify.html.yui@Zhuiwei');
    }

    /**
     * @param UserEntity $user
     */
    public function setUser(UserEntity $user)
    {
        $this->user = $user;
    }

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->user;
    }
}