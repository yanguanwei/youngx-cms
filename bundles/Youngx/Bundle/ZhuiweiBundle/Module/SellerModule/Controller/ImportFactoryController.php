<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Controller;

use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Form\FactoryAdminForm;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Form\FactoryPhotoAdminForm;
use Youngx\MVC\Action\WizardAction;
use Youngx\MVC\Action\WizardActionCollection;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\RenderableResponse;

class ImportFactoryController extends WizardAction
{
    public function indexAction()
    {
        return $this->run();
    }

    protected function collectActions(WizardActionCollection $collection)
    {
        $collection->add('factory', '工厂基础信息', 'Form:FactoryAdmin@Zhuiwei:Seller');
        $collection->add('photo', '证件照上传', 'Form:FactoryPhotoAdmin@Zhuiwei:Seller');
    }

    protected function initPhotoAction(FactoryPhotoAdminForm $photoForm, FactoryAdminForm $factoryForm = null)
    {
        if ($factoryForm) {
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
        $this->context->flash()->add('success', sprintf('工厂 <i>%s</i> 录入成功！', $action->getFactory()->getName()));
        $event->setResponse($this->context->redirectResponse(
                $this->context->generateUrl('factory-import')
            ));
    }

    protected function render(RenderableResponse $response)
    {
        $response->addVariable('#subtitle', array(
                '录入工厂', $this->wizardContext->getActiveStepTitle()
            ));
    }
}