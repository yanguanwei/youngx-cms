<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Controller;

use Youngx\Bundle\UserBundle\Form\ProfileAdminForm;
use Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Form\UserCompanyAdminForm;
use Youngx\MVC\Action\WizardActionCollection;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\RenderableResponse;

class ImportCompanyUserController extends ImportController
{
    public function indexAction()
    {
        return $this->run();
    }

    protected function collectActions(WizardActionCollection $collection)
    {
        parent::collectActions($collection);
        $collection->add('company', '选择所属公司', 'Form:UserCompanyAdmin@Zhuiwei:Buyer');
    }

    protected function initCompanyAction(UserCompanyAdminForm $userCompanyForm, ProfileAdminForm $profile = null)
    {
        if ($profile) {
            $user = $profile->getUser();
        } else {
            $user = $this->context->repository()->load('user', $this->wizardContext->get('uid'));
        }
        $userCompanyForm->setUser($user);
    }

    protected function render(RenderableResponse $response)
    {
        $response->addVariable('#subtitle', array(
                '导入机构个人买家', $this->wizardContext->getActiveStepTitle()
            ));
    }

    /**
     * @param GetResponseEvent $event
     * @param ProfileAdminForm $action
     */
    protected function finish(GetResponseEvent $event, $action)
    {
        $this->context->flash()->add('success', sprintf('机构个人买家 <i>%s</i> 导入成功', $action->getUser()->getName()));
        $event->setResponse($this->context->redirectResponse($this->context->generateUrl('buyer-admin-import-company-user')));
    }
}