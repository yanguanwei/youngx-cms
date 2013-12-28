<?php

namespace Youngx\Bundle\ZhuiweiBundle\Form;

use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Form\ProductForm;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\RenderableResponse;

class SellerProductForm extends ProductForm
{
    protected function submit(GetResponseEvent $event)
    {
        parent::submit($event);

        $this->context->flash()->add('success', sprintf('产品 <i>%s</i> 保存成功！', $this->product->getTitle()));

        $event->setResponse($this->context->redirectResponse(
                $this->context->generateUrl('seller-products')
            ));
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($formWidget = $this->context->widget('Form', array(
                    '#form' => $this,
                    '#autoRenderSubmitActions' => false,
                    '#alertErrors' => true
                )))
            ->addVariables(array(
                    '#subtitle' => $this->product ? '编辑产品' : '添加产品'
                ));
        $formWidget->render('seller/product-form.html.yui', array(
                'product_id' => $this->product ? $this->product->getId() : 0
            ));
    }
}