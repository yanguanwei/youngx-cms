<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Form;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Youngx\Bundle\AdminBundle\Module\FileModule\Entity\FileEntity;
use Youngx\Bundle\AdminBundle\Module\FileModule\Form\FileForm;
use Youngx\Bundle\jQueryBundle\Widget\ColorBoxWidget;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\FactoryEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class FactoryPhotoAdminForm extends FileForm
{
    /**
     * @var FactoryEntity
     */
    protected $factory;
    protected $identity1;
    protected $identity2;
    protected $business_license;
    protected $occ;
    protected $allowedExtensions = array(
        'jpg', 'jpeg', 'gif', 'png', 'bmp'
    );

    public function id()
    {
        return 'seller-admin-photo';
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
                    '工厂 <i>' .  $this->factory->getName() . '</i>', '证件照上传'
                ));
    }

    public function renderFormWidget(FormWidget $widget)
    {
        $widget->addField('identity1')->label('身份证正面照')->file();
        $widget->addField('identity2')->label('身份下反面照')->file();
        $widget->addField('business_license')->label('营业执照')->file();
        $widget->addField('occ')->label('组织机构代码证')->file();

        $colorbox = $this->context->widget('ColorBox@jQuery');

        if ($colorbox instanceof ColorBoxWidget) {
            $returnUrl = $this->context->request()->getUri();
            $photos = array();
            if ($this->factory->getIdentity1()) {
                $photos[$this->factory->getIdentity1()] = array(
                    'title' => '身份证正面照',
                    'name' => 'identity1'
                );
            }
            if ($this->factory->getIdentity2()) {
                $photos[$this->factory->getIdentity2()] = array(
                    'title' => '身份证反面照',
                    'name' => 'identity2'
                );
            }
            if ($this->factory->getBusinessLicense()) {
                $photos[$this->factory->getBusinessLicense()] = array(
                    'title' => '营业执照',
                    'name' => 'business_license'
                );
            }
            if ($this->factory->getOcc()) {
                $photos[$this->factory->getOcc()] = array(
                    'title' => '组织机构代码证',
                    'name' => 'occ'
                );
            }

            foreach ($photos as $fileId => $photo) {
                $file = $this->context->repository()->load('file', $fileId);
                if ($file instanceof FileEntity) {
                    $fileUrl = $this->context->locateUrl($file->getUri());
                    $li = $colorbox->addPicture(
                        $this->context->value('image-url', $file->getUri(), 150, 150),
                        $fileUrl,
                        array(
                            'delete' => array(
                                'title' => '删除',
                                '#icon' => 'remove red',
                                'href' => $this->context->generateUrl('factory-admin-photo-delete', array(
                                        'id' => $file->getId(),
                                        'factory' => $this->factory->getId(),
                                        'photo' => $photo['name'],
                                        'returnUrl' => $returnUrl,
                                    ))
                            )
                        )
                    );
                    $li->find('link')->set('title', $photo['title']);
                }
            }

            $widget->add('colorbox', '<div class="row"><div class="col-xs-12">'.$colorbox . '</div></div><div class="space-24"></div>', -1);
        }
    }

    /**
     * @param mixed UploadedFile
     */
    public function setBusinessLicense($business_license)
    {
        if ($business_license && $business_license instanceof UploadedFile) {
            $this->business_license = $business_license;
            $this->setFile($business_license, 'business_license');
        }
    }

    /**
     * @return UploadedFile
     */
    public function getBusinessLicense()
    {
        return $this->business_license;
    }

    /**
     * @param UploadedFile $identity1
     */
    public function setIdentity1($identity1)
    {
        if ($identity1 && $identity1 instanceof UploadedFile) {
            $this->identity1 = $identity1;
            $this->setFile($identity1, 'identity1');
        }
    }

    /**
     * @return UploadedFile
     */
    public function getIdentity1()
    {
        return $this->identity1;
    }

    /**
     * @param UploadedFile $identity2
     */
    public function setIdentity2($identity2)
    {
        if ($identity2 && $identity2 instanceof UploadedFile) {
            $this->identity2 = $identity2;
            $this->setFile($identity2, 'identity2');
        }
    }

    /**
     * @return UploadedFile
     */
    public function getIdentity2()
    {
        return $this->identity2;
    }

    /**
     * @param UploadedFile $occ
     */
    public function setOcc($occ)
    {
        if ($occ && $occ instanceof UploadedFile) {
            $this->occ = $occ;
            $this->setFile($occ, 'occ');
        }
    }

    /**
     * @return UploadedFile
     */
    public function getOcc()
    {
        return $this->occ;
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
}