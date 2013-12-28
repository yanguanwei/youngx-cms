<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Controller;

use Youngx\Database\Entity;

class DeliveryAddressEntity extends Entity
{

    public static function type()
    {
        return 'delivery-address';
    }

    public static function table()
    {
        return 'zw_delivery_address';
    }

    public static function primaryKey()
    {
        return 'id';
    }

    public static function fields()
    {
        return array(
            'id', 'uid', 'district_id', 'address', 'zip_code', 'full_name', 'phone'
        );
    }
}