<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Form;

use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;

class ProductAdminForm extends ProductForm
{
    public function id()
    {
        return 'product-admin';
    }

    protected function registerValidators()
    {
        return array_merge(parent::registerValidators(), array(
                'uid' => array(
                    'required' => '请选择一个卖家'
                )
            ));
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($form = $this->context->widget('Form', array(
                    '#form' => $this,
                    '#skin' => 'vertical'
                )))->addVariable('#subtitle', $this->product ? ('编辑产品 #<i>'.$this->product->getId().'</i>') : '添加产品');

        $form->render('admin/form.html.yui@Zhuiwei:Product', array(
                'product_id' => $this->product ? $this->product->getId() : 0
            ));
    }

    protected function submit(GetResponseEvent $event)
    {
        parent::submit($event);

        $this->context->flash()->add('success', sprintf('产品 <i>%s</i> 保存成功！', $this->product->getTitle()));

        $event->setResponse($this->context->redirectResponse(
                $this->context->generateUrl('product-admin')
            ));
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }
}