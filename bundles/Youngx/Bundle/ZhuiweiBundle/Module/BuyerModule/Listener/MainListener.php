<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Listener;

use Youngx\Bundle\AdminBundle\Module\FileModule\Entity\FileEntity;
use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Entity\BuyerEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Entity\CompanyEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Input\SelectCompanyInput;
use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductPriceEntity;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Context;

class MainListener implements Registration
{
    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }


    public function selectCompanyInput(array $attributes)
    {
        return new SelectCompanyInput($this->context, $attributes);
    }

    public function deleteCompany(CompanyEntity $entity)
    {
        $file = $entity->getBusinessLicenseFile();
        if ($file) {
            $file->delete();
        }

        $file = $entity->getIdentity1File();
        if ($file) {
            $file->delete();
        }

        $file = $entity->getIdentity2File();
        if ($file) {
            $file->delete();
        }

        $file = $entity->getOccFile();
        if ($file) {
            $file->delete();
        }
    }

    public function deleteUser(UserEntity $user)
    {
        $buyer = $this->context->repository()->load('buyer', $user->getUid());
        if ($buyer) {
            $buyer->delete();
        }
    }

    public function deleteBuyer(BuyerEntity $buyer)
    {
        $company = $buyer->getCompany();
        if ($company && $company->getUid() == $buyer->getCompanyId()) {
            $company->setUid(0);
            $company->save();
        }
    }

    public static function registerListeners()
    {
        return array(
            'kernel.input#select-company' => 'selectCompanyInput',
            'kernel.entity.delete#company' => 'deleteCompany',
            'kernel.entity.delete#user' => 'deleteUser',
            'kernel.entity.delete#buyer' => 'deleteBuyer'
        );
    }
}