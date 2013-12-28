<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Action;

use Youngx\Bundle\AdminBundle\Module\FileModule\Action\FileDeleteAction;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\FactoryEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Exception\HttpException;

class FactoryPhotoDeleteAction extends FileDeleteAction
{
    /**
     * @var FactoryEntity
     */
    protected $factory;

    protected $photo;

    protected $photoLabel;

    protected function initRequest()
    {
        $photo = $this->context->request()->get('photo');
        $photos = array(
            'identity1' => '身份证正面照',
            'identity2' => '身份证反面照',
            'business_license' => '营业执照',
            'occ' => '组织机构代码证'
        );

        if (!$photo || !isset($photos[$photo])) {
            throw new HttpException(404);
        }

        $this->photo = $photo;
        $this->photoLabel = $photos[$photo];
    }

    /**
     * @param FactoryEntity[] $entities
     * @return string
     */
    protected function getMessageForEntities(array $entities)
    {
        return sprintf('您确定要删除工厂 <i>%s</i> 的 <i>%s</i> 吗？', $this->factory->getName(), $this->photoLabel);
    }

    /**
     * @param array $entities
     * @return string
     */
    protected function getSuccessMessage(array $entities)
    {
        return sprintf('成功删除工厂 <i>%s</i> 的 <i>%s</i> ！', $this->factory->getName(), $this->photoLabel);
    }

    /**
     * @param array $entities
     * @param GetResponseEvent $event
     */
    protected function delete(array $entities, GetResponseEvent $event)
    {
        $this->factory->set($this->photo, 0);
        parent::delete($entities, $event);
    }

    /**
     * @param FactoryEntity $factory
     */
    public function setFactory(FactoryEntity $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return FactoryEntity
     */
    public function getFactory()
    {
        return $this->factory;
    }
}