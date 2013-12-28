<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Listener;

use Youngx\Bundle\AdminBundle\Module\FileModule\Entity\FileEntity;
use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\FactoryEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\SellerEntity;
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

    public function getSellerVipTypes()
    {
        return array(
            SellerEntity::TYPE_VIP1 => '半年型',
            SellerEntity::TYPE_VIP2 => '一年型',
            SellerEntity::TYPE_VIP3 => '两年型',
            SellerEntity::TYPE_VIP4 => '三年型',
        );
    }

    public function deleteFactory(FactoryEntity $factory)
    {
        $file = $factory->getBusinessLicenseFile();
        if ($file) {
            $file->delete();
        }

        $file = $factory->getIdentity1File();
        if ($file) {
            $file->delete();
        }

        $file = $factory->getIdentity2File();
        if ($file) {
            $file->delete();
        }

        $file = $factory->getOccFile();
        if ($file) {
            $file->delete();
        }

        $files = $this->context->value('files', $factory);
        if ($files) {
            foreach ($files as $file) {
                if ($file instanceof FileEntity) {
                    $file->delete();
                }
            }
        }
    }

    public function deleteUser(UserEntity $user)
    {
        $seller = $this->context->repository()->load('seller', $user->getUid());
        if ($seller) {
            $seller->delete();
        }
    }

    public function deleteSeller(SellerEntity $seller)
    {
        $factory = $seller->getFactory();
        if ($factory) {
            $factory->delete();
        }
        $logo = $seller->getLogoFile();
        if ($logo) {
            $logo->delete();
        }
    }

    public static function registerListeners()
    {
        return array(
            'kernel.value#seller-vip-types' => 'getSellerVipTypes',
            'kernel.entity.delete#factory' => 'deleteFactory',
            'kernel.entity.delete#user' => 'deleteUser',
            'kernel.entity.delete#seller' => 'deleteSeller',
        );
    }
}