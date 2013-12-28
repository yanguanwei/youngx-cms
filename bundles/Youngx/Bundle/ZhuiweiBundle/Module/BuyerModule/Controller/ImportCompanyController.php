<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Controller;

use Youngx\Bundle\UserBundle\Form\ProfileAdminForm;
use Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Entity\BuyerEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Form\CompanyAdminForm;
use Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Form\CompanyPhotoAdminForm;
use Youngx\MVC\Action\WizardActionCollection;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\RenderableResponse;

class ImportCompanyController extends ImportController
{
    public function indexAction()
    {
        return $this->run();
    }

    protected function collectActions(WizardActionCollection $collection)
    {
        parent::collectActions($collection);
        $collection->add('company', '公司信息', 'Form:CompanyAdmin@Zhuiwei:Buyer');
        $collection->add('photo', '证件照上传', 'Form:CompanyPhotoAdmin@Zhuiwei:Buyer');
    }

    protected function initCompanyAction(CompanyAdminForm $companyForm, ProfileAdminForm $profileForm = null)
    {
        if ($profileForm) {
            $user = $profileForm->getUser();
        } else {
            $user = $this->context->repository()->load('user', $this->wizardContext->get('uid'));
        }

        $companyForm->setUser($user);

        $buyer = $this->context->repository()->load('buyer', $user->getUid());
        if ($buyer && $buyer instanceof BuyerEntity) {
            $company = $buyer->getCompany();
            if ($company) {
                $companyForm->setCompany($company);
            }
        }
    }

    protected function initPhotoAction(CompanyPhotoAdminForm $photoForm, CompanyAdminForm $companyForm = null)
    {
        if ($companyForm) {
            $company = $companyForm->getCompany();
            $this->wizardContext->add('company_id', $company->getId());
            $buyer = $this->context->repository()->load('buyer', $companyForm->getUser()->getUid());
            if ($buyer && $buyer instanceof BuyerEntity) {
                $buyer->setCompanyId($company->getId());
                $buyer->setType(BuyerEntity::TYPE_COMPANY);
                $buyer->save();
            }
        } else {
            $company = $this->context->repository()->load('company', $this->wizardContext->get('company_id'));
        }
        $photoForm->setCompany($company);
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
        $this->context->flash()->add('success', sprintf('机构买家 <i>%s</i> 导入成功', $action->getUser()->getName()));
        $event->setResponse($this->context->redirectResponse($this->context->generateUrl('buyer-admin-import-company')));
    }
}