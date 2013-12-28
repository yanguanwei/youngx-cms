<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Form;

use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class ProductCategoryAdminForm extends Form
{
    /**
     * @var ProductEntity
     */
    protected $product;
    protected $category_id;

    public function id()
    {
        return 'product-category-admin';
    }

    protected function registerValidators()
    {
        return array(
            'category_id' => array(
                'required' => '请选择产品品类！'
            )
        );
    }

    protected function submit(GetResponseEvent $event)
    {
        $product = $this->product ?: $this->context->repository()->create('product');

        $product->set('category_id', $this->category_id);
        $product->save();

        $this->product = $product;

        $this->context->flash()->add('success', sprintf('产品 <i>%s</i> 的品类保存成功！', $product->get('title')));

        $event->setResponse($this->context->redirectResponse(
                $this->context->generateUrl('product-admin')
            ));
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Form', array(
                    '#form' => $this,
                    '#skin' => 'horizontal'
                )));
        $response->addVariable('#subtitle', $this->product ? array(
                $this->product->getTitle(), '编辑品类'
            ) : '选择产品品类');
    }

    public function renderFormWidget(FormWidget $widget)
    {
        $widget->addField('category_id')->label('品类')->select_category(array(
                '#chosen' => array('search_contains' => true),
            ));
    }

    /**
     * @param ProductEntity $product
     */
    public function setProduct(ProductEntity $product)
    {
        $this->product = $product;
        $this->setCategoryId($product->getCategoryId());
    }

    /**
     * @return ProductEntity
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }
}