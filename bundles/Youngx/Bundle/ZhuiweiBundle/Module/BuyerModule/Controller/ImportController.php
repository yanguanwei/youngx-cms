<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Controller;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\UserBundle\Form\AccountAdminForm;
use Youngx\Bundle\UserBundle\Form\ProfileAdminForm;
use Youngx\MVC\Action\WizardAction;
use Youngx\MVC\Action\WizardActionCollection;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\User\Identity;

class ImportController extends WizardAction
{
    public function indexAction()
    {
        return $this->run();
    }

    protected function collectActions(WizardActionCollection $collection)
    {
        $collection->add('account', '帐号信息', 'Form:AccountAdmin@User', array(
                'roles' => array(Identity::ROLE_BUYER)
            ));
        $collection->add('profile', '个人资料', 'Form:ProfileAdmin@User');
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
            $this->wizardContext->add('uid', $account->getUser()->getUid());
            $user = $account->getUser();

            $buyer = $this->context->repository()->load('buyer', $user->getUid());
            if (!$buyer) {
                $this->context->repository()->create('buyer', array(
                        'uid' => $user->getUid()
                    ))->save();
            }
        } else {
            $user = $this->context->repository()->load('user', $this->wizardContext->get('uid'));
        }
        $profile->setUser($user);
    }

    protected function render(RenderableResponse $response)
    {
        $response->addVariable('#subtitle', array(
                '导入买家', $this->wizardContext->getActiveStepTitle()
            ));
    }

    /**
     * @param GetResponseEvent $event
     * @param ProfileAdminForm $action
     */
    protected function finish(GetResponseEvent $event, $action)
    {
        $this->context->flash()->add('success', sprintf('买家 <i>%s</i> 导入成功', $action->getUser()->getName()));
        $event->setResponse($this->context->redirectResponse($this->context->generateUrl('buyer-admin-import')));
    }
}