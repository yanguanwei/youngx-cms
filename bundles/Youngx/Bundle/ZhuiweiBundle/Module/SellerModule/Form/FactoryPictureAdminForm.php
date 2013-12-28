<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Form;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Youngx\Bundle\AdminBundle\Module\FileModule\Form\FileForm;
use Youngx\Bundle\jQueryBundle\Widget\ColorBoxWidget;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\FactoryEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class FactoryPictureAdminForm extends FileForm
{
    /**
     * @var FactoryEntity
     */
    protected $factory;
    protected $picture1;
    protected $picture2;
    protected $picture3;
    protected $picture4;
    protected $picture5;
    protected $allowedExtensions = array(
            'jpg', 'jpeg', 'gif', 'png', 'bmp'
        );

    public function id()
    {
        return 'seller-admin-picture';
    }

    protected function submit(GetResponseEvent $event)
    {
        parent::submit($event);

        if ($this->fileEntities) {
            foreach ($this->fileEntities as $key => $file) {
                $old = $this->factory->get($key);
                if ($old) {
                    $oldFile = $this->context->repository()->load('file', $old);
                    if ($oldFile) {
                        $oldFile->delete();
                    }
                }
                $this->factory->set($key, $file->getId());
            }
            $this->factory->save();
            $this->context->flash()->add('success', '文件上传成功');
        }

        $event->setResponse(
            $this->context->redirectResponse($this->context->request()->getUri())
        );
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Form', array(
                    '#form' => $this,
                    '#skin' => 'horizontal',
                    '#uploadable' => true
                )))->addVariable('#subtitle',  array(
                    '工厂 <i>' .  $this->factory->getName() . '</i>', '厂房图片'
                ));
    }

    public function renderFormWidget(FormWidget $widget)
    {
        $widget->addField('picture1')->label('厂房图片1')->file();
        $widget->addField('picture2')->label('厂房图片2')->file();
        $widget->addField('picture3')->label('厂房图片3')->file();
        $widget->addField('picture4')->label('厂房图片4')->file();
        $widget->addField('picture5')->label('厂房图片5')->file();

        $colorbox = $this->context->widget('ColorBox@jQuery');

        if ($colorbox instanceof ColorBoxWidget) {
            $returnUrl = $this->context->request()->getUri();

            foreach ($this->factory->getPictures() as $i => $file) {
                $fileUrl = $this->context->locateUrl($file->getUri());
                $li = $colorbox->addPicture(
                    $this->context->value('image-url', $file->getUri(), 150, 150),
                    $fileUrl,
                    array(
                        'delete' => array(
                            'title' => '删除',
                            '#icon' => 'remove red',
                            'href' => $this->context->generateUrl('factory-admin-picture-delete', array(
                                    'id' => $file->getId(),
                                    'picture' => $i,
                                    'factory' => $this->factory->getId(),
                                    'returnUrl' => $returnUrl,
                                ))
                        )
                    )
                );
                $li->find('link')->set('title', '厂房图片'.$i);
            }

            $widget->add('colorbox', '<div class="row"><div class="col-xs-12">'.$colorbox . '</div></div><div class="space-24"></div>', -1);
        }
    }

    public function setFactory(FactoryEntity $factory)
    {
        $this->factory = $factory;
        $this->setEntity($factory);
        $this->setUser($factory->getUser() ?: $this->context->identity()->getUserEntity());
    }

    /**
     * @return FactoryEntity
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @param mixed $picture1
     */
    public function setPicture1($picture1)
    {
        if ($picture1 && $picture1 instanceof UploadedFile) {
            $this->picture1 = $picture1;
            $this->setFile($picture1, 'picture1');
        }

    }

    /**
     * @return mixed
     */
    public function getPicture1()
    {
        return $this->picture1;
    }

    /**
     * @param mixed $picture2
     */
    public function setPicture2($picture2)
    {
        if ($picture2 && $picture2 instanceof UploadedFile) {
            $this->picture2 = $picture2;
            $this->setFile($picture2, 'picture2');
        }
    }

    /**
     * @return mixed
     */
    public function getPicture2()
    {
        return $this->picture2;
    }

    /**
     * @param mixed $picture3
     */
    public function setPicture3($picture3)
    {
        if ($picture3 && $picture3 instanceof UploadedFile) {
            $this->picture3 = $picture3;
            $this->setFile($picture3, 'picture3');
        }
    }

    /**
     * @return mixed
     */
    public function getPicture3()
    {
        return $this->picture3;
    }

    /**
     * @param mixed $picture4
     */
    public function setPicture4($picture4)
    {
        if ($picture4 && $picture4 instanceof UploadedFile) {
            $this->picture4 = $picture4;
            $this->setFile($picture4, 'picture4');
        }
    }

    /**
     * @return mixed
     */
    public function getPicture4()
    {
        return $this->picture4;
    }

    /**
     * @param mixed $picture5
     */
    public function setPicture5($picture5)
    {
        if ($picture5 && $picture5 instanceof UploadedFile) {
            $this->picture5 = $picture5;
            $this->setFile($picture5, 'picture5');
        }
    }

    /**
     * @return mixed
     */
    public function getPicture5()
    {
        return $this->picture5;
    }
}