<?php

namespace Youngx\Bundle\AdminBundle;

use Youngx\MVC\Bundle;

class AdminBundle extends Bundle
{
    public function modules()
    {
        return array(
            'Ace', 'District', 'Vocabulary', 'File'
        );
    }
}