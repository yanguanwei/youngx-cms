<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity;

use Youngx\Bundle\AdminBundle\Module\DistrictModule\Entity\DistrictEntity;
use Youngx\Bundle\AdminBundle\Module\FileModule\Entity\FileEntity;
use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Database\Entity;

class FactoryEntity extends Entity
{
    protected $id;
    protected $uid = 0;
    protected $status = 0;
    protected $name;
    protected $industries;
    protected $telephone;
    protected $mobilephone;
    protected $district_id = 0;
    protected $address;
    protected $corporation;
    protected $identity1 = 0;
    protected $identity2 = 0;
    protected $business_license = 0;
    protected $occ = 0;
    protected $description;
    protected $established_at = 0;
    protected $area;
    protected $capacity;

    protected $picture1;
    protected $picture2;
    protected $picture3;
    protected $picture4;
    protected $picture5;

    public static function type()
    {
        return 'factory';
    }

    public static function table()
    {
        return 'zw_factory';
    }

    public static function primaryKey()
    {
        return 'id';
    }

    public static function fields()
    {
        return array(
            'id', 'uid', 'status', 'name', 'industries', 'telephone', 'mobilephone', 'district_id',
            'address', 'corporation', 'identity1', 'identity2', 'business_license', 'occ', 'description',
            'established_at', 'area', 'capacity', 'picture1', 'picture2', 'picture3', 'picture4', 'picture5'
        );
    }

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->uid ? $this->repository()->load('user', $this->uid) : null;
    }

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

    public function setArea($area)
    {
        $this->area = $area;
    }

    public function getArea()
    {
        return $this->area;
    }

    public function setBusinessLicense($business_license)
    {
        $this->business_license = $business_license;
    }

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
     * @param mixed $capacity
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
    }

    /**
     * @return mixed
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

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

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }


    public function setDistrictId($district_id)
    {
        $this->district_id = $district_id;
    }

    /**
     * @return DistrictEntity | null
     */
    public function getDistrict()
    {
        return $this->district_id ? $this->repository()->load('district', $this->district_id) : null;
    }

    public function getDistrictId()
    {
        return $this->district_id;
    }


    public function setEstablishedAt($established_at)
    {
        if (is_numeric($established_at)) {
            $this->established_at = intval($established_at);
        } else {
            $this->established_at = strtotime($established_at);
        }
    }

    /**
     * @return mixed
     */
    public function getEstablishedAt()
    {
        return $this->established_at;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
    public function getIdentity1File()
    {
        return $this->identity1 ? $this->repository()->load('file', $this->identity1) : null;
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
     * @return FileEntity | null
     */
    public function getIdentity2File()
    {
        return $this->identity2 ? $this->repository()->load('file', $this->identity2) : null;
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
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
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

    /**
     * @param mixed $picture1
     */
    public function setPicture1($picture1)
    {
        $this->picture1 = $picture1;
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
        $this->picture2 = $picture2;
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
        $this->picture3 = $picture3;
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
        $this->picture4 = $picture4;
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
        $this->picture5 = $picture5;
    }

    /**
     * @return mixed
     */
    public function getPicture5()
    {
        return $this->picture5;
    }

    /**
     * @return FileEntity[]
     */
    public function getPictures()
    {
        $pictures = array();

        for ($i=1; $i<6; $i++) {
            $var = 'picture'.$i;
            if ($this->$var) {
                if (null != ($file = $this->repository()->load('file', $this->$var))) {
                    $pictures[$i] = $file;
                }
            }
        }

        return $pictures;
    }
}