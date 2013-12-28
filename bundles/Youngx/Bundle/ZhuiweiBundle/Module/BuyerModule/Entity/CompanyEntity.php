<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Entity;

use Youngx\Bundle\AdminBundle\Module\FileModule\Entity\FileEntity;
use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Database\Entity;

class CompanyEntity extends Entity
{
    protected $id;
    protected $uid;
    protected $type = 0;
    protected $name;
    protected $industries;
    protected $telephone;
    protected $mobilephone;
    protected $district_id = 0;
    protected $address;
    protected $corporation;
    protected $identity1;
    protected $identity2;
    protected $business_license;
    protected $occ;
    protected $description;

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->uid ? $this->repository()->load('user', $this->uid) : null;
    }

    public static function type()
    {
        return 'company';
    }

    public static function table()
    {
        return 'zw_company';
    }

    public static function primaryKey()
    {
        return 'id';
    }

    public static function fields()
    {
        return array(
            'id', 'uid', 'type', 'name', 'industries', 'telephone', 'mobilephone', 'district_id',
            'address', 'corporation', 'identity1', 'identity2', 'business_license', 'occ', 'description'
        );
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $business_license
     */
    public function setBusinessLicense($business_license)
    {
        $this->business_license = $business_license;
    }

    /**
     * @return mixed
     */
    public function getBusinessLicense()
    {
        return $this->business_license;
    }

    /**
     * @return FileEntity | null
     */
    public function getBusinessLicenseFile()
    {
        return $this->business_license ? $this->repository()->load('file', $this->business_license) : null;
    }

    /**
     * @param mixed $corporation
     */
    public function setCorporation($corporation)
    {
        $this->corporation = $corporation;
    }

    /**
     * @return mixed
     */
    public function getCorporation()
    {
        return $this->corporation;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $district_id
     */
    public function setDistrictId($district_id)
    {
        $this->district_id = $district_id;
    }

    /**
     * @return mixed
     */
    public function getDistrictId()
    {
        return $this->district_id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FileEntity | null
     */
    public function getIdentity1File()
    {
        return $this->identity1 ? $this->repository()->load('file', $this->identity1) : null;
    }

    /**
     * @param mixed $identity1
     */
    public function setIdentity1($identity1)
    {
        $this->identity1 = $identity1;
    }

    /**
     * @return mixed
     */
    public function getIdentity1()
    {
        return $this->identity1;
    }

    /**
     * @return FileEntity | null
     */
    public function getIdentity2File()
    {
        return $this->identity2 ? $this->repository()->load('file', $this->identity2) : null;
    }

    /**
     * @param mixed $identity2
     */
    public function setIdentity2($identity2)
    {
        $this->identity2 = $identity2;
    }

    /**
     * @return mixed
     */
    public function getIdentity2()
    {
        return $this->identity2;
    }

    /**
     * @param mixed $industries
     */
    public function setIndustries($industries)
    {
        $this->industries = $industries;
    }

    /**
     * @return mixed
     */
    public function getIndustries()
    {
        return $this->industries;
    }

    /**
     * @param mixed $mobilephone
     */
    public function setMobilephone($mobilephone)
    {
        $this->mobilephone = $mobilephone;
    }

    /**
     * @return mixed
     */
    public function getMobilephone()
    {
        return $this->mobilephone;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $occ
     */
    public function setOcc($occ)
    {
        $this->occ = $occ;
    }

    /**
     * @return mixed
     */
    public function getOcc()
    {
        return $this->occ;
    }

    /**
     * @return FileEntity | null
     */
    public function getOccFile()
    {
        return $this->occ ? $this->repository()->load('file', $this->occ) : null;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }
}