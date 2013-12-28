<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\OrderModule\Entity;

use Youngx\Database\Entity;

class OrderDeliveryEntity extends Entity
{
    protected $order_id;
    protected $district_id;
    protected $address;
    protected $zip_code;
    protected $phone;
    protected $full_name;

    /**
     * @return OrderEntity
     */
    public function getOrder()
    {
        return $this->repository()->load('order', $this->order_id);
    }

    public static function type()
    {
        return 'order_delivery';
    }

    public static function table()
    {
        return 'zw_order_delivery';
    }

    public static function primaryKey()
    {
        return 'order_id';
    }

    public static function fields()
    {
        return array(
            'order_id', 'district_id', 'address', 'zip_code', 'phone', 'full_name'
        );
    }
}