<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Youngx\Bundle\AdminBundle\Module\FileModule\Entity\FileEntity;
use Youngx\Bundle\AdminBundle\Module\FileModule\Form\FileForm;
use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form\FormErrorHandler;

class ProductPictureController extends FileForm
{
    protected $sort = 0;
    protected $previewWidth = 0;
    protected $previewHeight = 0;

    public function indexAction($sort, ProductEntity $product = null)
    {
        $this->sort = $sort;
        $this->allowedExtensions = array(
            'jpg', 'jpeg', 'gif', 'png', 'bmp'
        );

        if ($product) {
            $this->setEntity($product);
        } else {
            $this->setEntityType('product');
        }

        return $this->run();
    }

    protected function invalid(GetResponseEvent $event, FormErrorHandler $feh)
    {
        $event->setResponse($this->context->response('非法的文件格式！'));
    }

    protected function submit(GetResponseEvent $event)
    {
        parent::submit($event);
        $file = reset($this->fileEntities);
        if ($this->entity) {
            $this->context->repository()->deleteCachedEntity($this->entity);
        }

        $json = array(
            'id' => $file->getId(),
            'url' => $this->context->locateImageUrl($file->getUri(), $this->previewWidth, $this->previewHeight)
        );
        $event->setResponse($this->context->response(json_encode($json)));
    }

    protected function onFileBeforeSave($key, FileEntity $file, UploadedFile $upload)
    {
        $file->setSortNum($this->sort);
    }

    /**
     * @param int $previewWidth
     */
    public function setPreviewWidth($previewWidth)
    {
        $this->previewWidth = intval($previewWidth);
    }

    /**
     * @return int
     */
    public function getPreviewWidth()
    {
        return $this->previewWidth;
    }

    /**
     * @param int $previewHeight
     */
    public function setPreviewHeight($previewHeight)
    {
        $this->previewHeight = intval($previewHeight);
    }

    /**
     * @return int
     */
    public function getPreviewHeight()
    {
        return $this->previewHeight;
    }
}