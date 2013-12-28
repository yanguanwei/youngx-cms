<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Entity;

use Youngx\Database\Entity;

class BuyerEntity extends Entity
{
    const TYPE_STAFF = 0;
    const TYPE_COMPANY = 1;

    protected $uid;
    protected $company_id = 0;
    protected $type = 0;

    /**
     * @return CompanyEntity | null
     */
    public function getCompany()
    {
        return $this->company_id ? $this->repository()->load('company', $this->company_id) : null;
    }

    public static function type()
    {
        return 'buyer';
    }

    public static function table()
    {
        return 'zw_buyer';
    }

    public static function primaryKey()
    {
        return 'uid';
    }

    public static function fields()
    {
        return array(
            'uid', 'company_id', 'type'
        );
    }

    /**
     * @param mixed $company_id
     */
    public function setCompanyId($company_id)
    {
        $this->company_id = $company_id;
    }

    /**
     * @return mixed
     */
    public function getCompanyId()
    {
        return $this->company_id;
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
}