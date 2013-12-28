<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Form;

use Youngx\Bundle\AdminBundle\Module\FileModule\Form\FileForm;
use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class ProductPictureAdminForm extends FileForm
{
    /**
     * @var ProductEntity
     */
    protected $product;
    protected $allowedExtensions = array(
            'jpg', 'jpeg', 'gif', 'png', 'bmp'
        );

    public function id()
    {
        return 'product-admin-picture';
    }

    protected function submit(GetResponseEvent $event)
    {
        parent::submit($event);
        if ($this->fileEntities) {
            $this->context->flash()->add('success', '文件上传成功');
        }

        $event->setResponse(
            $this->context->redirectResponse($this->context->request()->getUri())
        );
    }

    protected function initRequest()
    {
        $this->setEntity($this->product);

        parent::initRequest();
    }

    protected function render(RenderableResponse $response)
    {
        parent::render($response);

        $response->addVariable('#subtitle', $this->product ? array(
                $this->product->getTitle(), '上传图片'
            ) : '上传产品图片');
    }

    public function renderFormWidget(FormWidget $widget)
    {
        parent::renderFormWidget($widget);

        $colorbox = $this->context->widget('ColorBox@jQuery');
        $default = $this->product->getPicture();
        $files = $this->context->value('files', $this->product);
        if ($files) {
            $returnUrl = $this->context->request()->getUri();
            foreach ($files as $file) {
                $fileUrl = $this->context->locateUrl($file->getUri());
                $li = $colorbox->addPicture(
                    $this->context->value('image-url', $file->getUri(), 150, 150),
                    $fileUrl,
                    array(
                        'link' => array(
                            'title' => '设为默认',
                            '#icon' => 'link',
                            'href' => $this->context->generateUrl('product-admin-picture-default', array(
                                    'file' => $file->getId(),
                                    'product' => $this->product->getId(),
                                    'returnUrl' => $returnUrl,
                                ))
                        ),
                        'delete' => array(
                            'title' => '删除',
                            '#icon' => 'remove red',
                            'href' => $this->context->generateUrl('file-admin-delete', array(
                                    'id' => $file->getId(),
                                    'returnUrl' => $returnUrl,
                                ))
                        )
                    )
                );

                if ($default == $file->getUri()) {
                    $li->addClass('active');
                }

            }
        }

        $widget->add('colorbox', '<div class="row"><div class="col-xs-12">'.$colorbox . '</div></div><div class="space-24"></div>', -1);
    }

    /**
     * @param mixed $product
     */
    public function setProduct(ProductEntity $product)
    {
        $this->product = $product;
    }

    /**
     * @return ProductEntity
     */
    public function getProduct()
    {
        return $this->product;
    }
}