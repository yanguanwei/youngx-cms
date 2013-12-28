<?php

namespace Youngx\Bundle\ZhuiweiBundle\Form;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\UserBundle\Form\RegisterForm;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\FactoryEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\User\Identity;

class SellerRegisterForm extends RegisterForm
{
    protected $hasCaptcha = true;
    protected $verifyEmail = true;
    protected $factory;
    /**
     * @var FactoryEntity
     */
    protected $seller;

    protected function registerValidators()
    {
        return array_merge(parent::registerValidators(), array(
                'factory' => array(
                    'required' => '请填写企业名称'
                )
            ));
    }

    protected function onUserBeforeSave(UserEntity $user)
    {
        $user->setRoles(array(
                Identity::ROLE_SELLER
            ));
    }

    protected function submit(GetResponseEvent $event)
    {
        parent::submit($event);

        $this->seller = $this->context->repository()->create('seller', array(
                'uid' => $this->user->getUid(),
            ));

        $factory = $this->context->repository()->query('factory')
            ->where("uid='0' AND name=:name")->one(array(':name' => $this->factory));

        if (!$factory) {
            $factory = $this->context->repository()->create('factory', array(
                    'name' => $this->factory
                ));
        }

        if ($factory instanceof FactoryEntity) {
            $factory->setUid($this->user->getUid());
            $factory->save();
            $this->seller->setFactoryId($factory->getId());
        }

        $this->seller->save();

        $event->setResponse($this->context->redirectResponse(
                $this->context->generateUrl('user-register-verify', array(
                        'user' => $this->user->getUid()
                    ))
            ));
    }


    protected function render(RenderableResponse $response)
    {
        $response->setContent($form = $this->context->widget('Form', array(
                    '#form' => $this,
                    '#autoRenderSubmitActions' => false,
                    '#alertErrors' => true
                )));
        $form->render('register-seller.html.yui@Zhuiwei');
    }

    public function id()
    {
        return 'seller-register';
    }

    /**
     * @param mixed $factory
     */
    public function setFactory($factory)
    {
        $this->factory = trim($factory);
    }

    /**
     * @return mixed
     */
    public function getFactory()
    {
        return $this->factory;
    }
}