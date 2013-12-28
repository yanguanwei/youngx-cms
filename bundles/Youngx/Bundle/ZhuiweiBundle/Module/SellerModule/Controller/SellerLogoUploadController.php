<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Controller;

use Youngx\Bundle\AdminBundle\Module\FileModule\Form\FileForm;
use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\SellerEntity;
use Youngx\MVC\Event\GetResponseEvent;

class SellerLogoUploadController extends FileForm
{
    protected $allowedExtensions = array(
            'jpg', 'jpeg', 'gif', 'png', 'bmp'
        );

    public function indexAction(UserEntity $user)
    {
        $this->setUser($user);

        return $this->run();
    }

    protected function initRequest()
    {
        if (!$this->user) {
            throw new \Exception('Logo上传未指定用户');
        }

        $this->entity = $this->context->repository()->load('seller', $this->user->getUid());
    }

    protected function submit(GetResponseEvent $event)
    {
        parent::submit($event);
        $file = reset($this->fileEntities);

        $seller = $this->entity;
        if ($seller && $seller instanceof SellerEntity) {
            $seller->setLogo($file->getId());
            $seller->save();
        }

        $json = array(
            'id' => $file->getId(),
            'url' => $this->context->locateUrl($file->getUri())
        );
        $event->setResponse($this->context->response(json_encode($json)));
    }
}