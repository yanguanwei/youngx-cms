<?php

namespace Youngx\Bundle\ZhuiweiBundle;

use Youngx\MVC\Bundle;

class ZhuiweiBundle extends Bundle
{
    public function modules()
    {
        return array(
            'Buyer', 'Seller', 'Product', 'Order'
        );
    }
}