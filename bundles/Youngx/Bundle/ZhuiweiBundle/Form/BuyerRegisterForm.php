<?php

namespace Youngx\Bundle\ZhuiweiBundle\Form;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\UserBundle\Form\RegisterForm;
use Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Entity\BuyerEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Entity\CompanyEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\User\Identity;

class BuyerRegisterForm extends RegisterForm
{
    protected $type = 0;
    protected $company;
    protected $hasCaptcha = true;
    protected $verifyEmail = true;

    /**
     * @var BuyerEntity
     */
    protected $buyer;

    protected function registerValidators()
    {
        $validators = parent::registerValidators();

        if ($this->type) {
            $validators = array_merge($validators, array(
                    'company' => array(
                        'required' => '请填写企业名称'
                    )
                ));
        }

        return $validators;
    }

    protected function onUserBeforeSave(UserEntity $user)
    {
        $user->setRoles(array(
               Identity::ROLE_BUYER
            ));
    }

    protected function submit(GetResponseEvent $event)
    {
        parent::submit($event);

        $this->buyer = $this->context->repository()->create('buyer', array(
                'uid' => $this->user->getUid(),
            ));

        if ($this->type) {
            $company = $this->context->repository()->query('company')
                ->where('name=:name')->one(array(':name' => $this->company));

            if (!$company) {
                $company = $this->context->repository()->create('company', array(
                        'name' => $this->company
                    ));
            }

            if ($company instanceof CompanyEntity) {
                if (!$company->getUid()) {
                    $company->setUid($this->buyer->getUid());
                    $this->buyer->setType(BuyerEntity::TYPE_COMPANY);
                }
                $company->save();
                $this->buyer->setCompanyId($company->getId());
            }
        }

        $this->buyer->save();

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
        $form->render('register-buyer.html.yui@Zhuiwei');
    }

    public function id()
    {
        return 'buyer-register';
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = trim($company);
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }
}
