<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Action;

use Youngx\Bundle\AdminBundle\Module\FileModule\Form\FileForm;
use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Form\ProductAdminForm;
use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Form\ProductCategoryAdminForm;
use Youngx\MVC\Action\WizardAction;
use Youngx\MVC\Action\WizardActionCollection;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\RenderableResponse;

class ProductImportAction extends WizardAction
{

    protected function collectActions(WizardActionCollection $collection)
    {
        $collection->add('category', '选择产品品类', 'Form:ProductCategoryAdmin@Zhuiwei:Product');
        $collection->add('product', '填写产品基础信息', 'Form:ProductAdmin@Zhuiwei:Product');
        $collection->add('photo', '上传产品图片', 'Form:File@Admin:File');
    }

    /**
     * @param GetResponseEvent $event
     * @param FileForm $action
     */
    protected function finish(GetResponseEvent $event, $action)
    {
        $this->context->flash()->add('success', sprintf('产品 <i>%s</i> 保存成功！', $action->getEntity()->get('title')));
        $event->setResponse(
            $this->context->redirectResponse($this->context->generateUrl('product-admin-add'))
        );
    }

    protected function initProductAction(ProductAdminForm $productForm, ProductCategoryAdminForm $categoryForm = null)
    {
        if ($categoryForm) {
            $this->wizardContext->add('product_id', $categoryForm->getProduct()->getId());
            $product = $categoryForm->getProduct();
        } else {
            $product = $this->context->repository()->load('product', $this->wizardContext->get('product_id'));
        }

        $productForm->setProduct($product);
    }

    protected function initPhotoAction(FileForm $fileForm, ProductAdminForm $productForm = null)
    {
        if ($productForm) {
            $product = $productForm->getProduct();
        } else {
            $product = $this->context->repository()->load('product', $this->wizardContext->get('product_id'));
        }
        $fileForm->setUser($product->getUser());
        $fileForm->setEntity($product);
    }

    protected function render(RenderableResponse $response)
    {
        $response->addVariable('#subtitle', array('添加产品'));
    }
}