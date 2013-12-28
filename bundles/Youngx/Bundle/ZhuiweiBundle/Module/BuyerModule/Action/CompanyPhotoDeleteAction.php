<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Action;

use Youngx\Bundle\AdminBundle\Module\FileModule\Action\FileDeleteAction;
use Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Entity\CompanyEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Exception\HttpException;

class CompanyPhotoDeleteAction extends FileDeleteAction
{
    /**
     * @var CompanyEntity
     */
    protected $company;

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
     * @param CompanyEntity[] $entities
     * @return string
     */
    protected function getMessageForEntities(array $entities)
    {
        return sprintf('您确定要删除公司 <i>%s</i> 的 <i>%s</i> 吗？', $this->company->getName(), $this->photoLabel);
    }

    /**
     * @param array $entities
     * @return string
     */
    protected function getSuccessMessage(array $entities)
    {
        return sprintf('成功删除公司 <i>%s</i> 的 <i>%s</i> ！', $this->company->getName(), $this->photoLabel);
    }

    /**
     * @param array $entities
     * @param GetResponseEvent $event
     */
    protected function delete(array $entities, GetResponseEvent $event)
    {
        $this->company->set($this->photo, 0);
        parent::delete($entities, $event);
    }

    /**
     * @param CompanyEntity $company
     */
    public function setCompany(CompanyEntity $company)
    {
        $this->company = $company;
    }

    /**
     * @return CompanyEntity
     */
    public function getCompany()
    {
        return $this->company;
    }
}