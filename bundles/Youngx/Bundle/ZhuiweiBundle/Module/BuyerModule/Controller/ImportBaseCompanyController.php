<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Controller;

use Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Form\CompanyAdminForm;
use Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Form\CompanyPhotoAdminForm;
use Youngx\MVC\Action\WizardAction;
use Youngx\MVC\Action\WizardActionCollection;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\RenderableResponse;

class ImportBaseCompanyController extends WizardAction
{
    public function indexAction()
    {
        return $this->run();
    }

    protected function collectActions(WizardActionCollection $collection)
    {
        $collection->add('company', '公司信息', 'Form:CompanyAdmin@Zhuiwei:Buyer');
        $collection->add('photo', '证件照上传', 'Form:CompanyPhotoAdmin@Zhuiwei:Buyer');
    }

    protected function initPhotoAction(CompanyPhotoAdminForm $photoForm, CompanyAdminForm $companyForm = null)
    {
        if ($companyForm) {
            $this->wizardContext->add('company_id', $companyForm->getCompany()->getId());
            $company = $companyForm->getCompany();
        } else {
            $company = $this->context->repository()->load('company', $this->wizardContext->get('company_id'));
        }
        $photoForm->setCompany($company);
    }

    protected function render(RenderableResponse $response)
    {
        $response->addVariable('#subtitle', array(
                '导入基础公司', $this->wizardContext->getActiveStepTitle()
            ));
    }

    /**
     * @param GetResponseEvent $event
     * @param CompanyPhotoAdminForm $action
     */
    protected function finish(GetResponseEvent $event, $action)
    {
        $this->context->flash()->add('success', sprintf('基础公司 <i>%s</i> 导入成功', $action->getCompany()->getName()));
        $event->setResponse($this->context->redirectResponse($this->context->generateUrl('buyer-admin-import-company-base')));
    }
}