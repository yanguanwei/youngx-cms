<?php

namespace Youngx\Bundle\ArchiveBundle\Module\ChannelModule\Controller;

use Youngx\Bundle\ArchiveBundle\Module\ChannelModule\Entity\ChannelEntity;
use Youngx\MVC\Context;

class AdminController
{

    public function indexAction(Context $context)
    {
        return $context->actionResponse("Table:ChannelAdmin@Archive:Channel");
    }

    public function addAction(Context $context, ChannelEntity $parent = null)
    {
        return $context->actionResponse('Form:Admin@Archive:Channel', $parent ? array(
                'parent' => $parent
            ) : array());
    }

    public function editAction(Context $context, ChannelEntity $channel)
    {
        return $context->actionResponse('Form:Admin@Archive:Channel', array(
               'channel' => $channel
            ));
    }

    public function deleteAction(Context $context)
    {
        return $context->actionResponse('Action:ChannelDelete@Archive:Channel');
    }
}