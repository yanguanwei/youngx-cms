<?php

namespace Youngx\Bundle\ArchiveBundle;

use Youngx\MVC\Bundle;

class ArchiveBundle extends Bundle
{
    public function modules()
    {
        return array(
            'Channel', 'News'
        );
    }
}