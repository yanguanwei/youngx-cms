<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Controller;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\UserBundle\Form\AccountAdminForm;
use Youngx\Bundle\UserBundle\Form\ProfileAdminForm;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\SellerEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Form\FactoryAdminForm;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Form\FactoryPhotoAdminForm;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Form\SellerAdminForm;
use Youngx\MVC\Action\WizardAction;
use Youngx\MVC\Action\WizardActionCollection;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\User\Identity;

class ImportSellerController extends WizardAction
{
    public function indexAction()
    {
        return $this->run();
    }

    protected function collectActions(WizardActionCollection $collection)
    {
        $collection->add('account', '帐号信息', 'Form:AccountAdmin@User', array('roles' => array(Identity::ROLE_SELLER)));
        $collection->add('profile', '个人资料', 'Form:ProfileAdmin@User');
        $collection->add('seller', '卖家信息', 'Form:SellerAdmin@Zhuiwei:Seller');
        $collection->add('factory', '工厂基础信息', 'Form:FactoryAdmin@Zhuiwei:Seller');
        $collection->add('photo', '证件照上传', 'Form:FactoryPhotoAdmin@Zhuiwei:Seller');
    }

    protected function initAccountAction(AccountAdminForm $account)
    {
        if ($this->wizardContext->has('uid')) {
            $account->setUser($this->context->repository()->load('user', $this->wizardContext->get('uid')));
        }
    }

    protected function initProfileAction(ProfileAdminForm $profile, AccountAdminForm $account = null)
    {
        if ($account) {
            $seller = $this->context->repository()->load('seller', $this->wizardContext->get('uid'));
            if (!$seller) {
                $this->context->repository()->create('seller', array(
                        'uid' => $account->getUser()->getUid()
                    ))->save();
            }
            $this->wizardContext->add('uid', $account->getUser()->getUid());
            $user = $account->getUser();
        } else {
            $user = $this->context->repository()->load('user', $this->wizardContext->get('uid'));
        }
        $profile->setUser($user);
    }

    protected function initSellerAction(SellerAdminForm $sellerForm, ProfileAdminForm $profile = null)
    {
        if ($profile) {
            $user = $profile->getUser();
        } else {
            $user = $this->context->repository()->load('user', $this->wizardContext->get('uid'));
        }
        $sellerForm->setUser($user);
    }

    protected function initFactoryAction(FactoryAdminForm $factoryForm, SellerAdminForm $sellerForm = null)
    {
        if ($sellerForm) {
            $user = $sellerForm->getUser();
        } else {
            $user = $this->context->repository()->load('user', $this->wizardContext->get('uid'));
        }

        $factoryForm->setUser($user);

        $seller = $this->context->repository()->load('seller', $user->getUid());
        if ($seller && $seller instanceof SellerEntity) {
            $factory = $seller->getFactory();
            if ($factory) {
                $factoryForm->setFactory($factory);
            }
        }
    }

    protected function initPhotoAction(FactoryPhotoAdminForm $photoForm, FactoryAdminForm $factoryForm = null)
    {
        if ($factoryForm) {
            $seller = $this->context->repository()->load('seller', $this->wizardContext->get('uid'));
            if ($seller && $seller instanceof SellerEntity) {
                $seller->set('factory_id', $factoryForm->getFactory()->getId())->save();
            }
            $this->wizardContext->add('factory_id', $factoryForm->getFactory()->getId());
            $factory = $factoryForm->getFactory();
        } else {
            $factory = $this->context->repository()->load('factory', $this->wizardContext->get('factory_id'));
        }
        $photoForm->setFactory($factory);
    }

    /**
     * @param GetResponseEvent $event
     * @param FactoryPhotoAdminForm $action
     */
    protected function finish(GetResponseEvent $event, $action)
    {
        $this->context->flash()->add('success', sprintf('卖家 <i>%s</i> 录入成功！', $action->getFactory()->getUser()->getName()));
        $event->setResponse($this->context->redirectResponse(
                $this->context->generateUrl('seller-import')
            ));
    }

    protected function render(RenderableResponse $response)
    {
        $response->addVariable('#subtitle', array(
                '录入卖家', $this->wizardContext->getActiveStepTitle()
            ));
    }
}