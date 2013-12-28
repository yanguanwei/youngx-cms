<?php

namespace Youngx\Bundle\ArchiveBundle\Module\NewsModule\Controller;

use Youngx\Bundle\ArchiveBundle\Module\ChannelModule\Entity\ChannelEntity;
use Youngx\Bundle\ArchiveBundle\Module\NewsModule\Entity\NewsEntity;
use Youngx\MVC\Context;

class AdminController
{
    public function indexAction(Context $context)
    {
        return $context->actionResponse('Table:NewsAdmin@Archive:News');
    }

    public function addAction(Context $context, ChannelEntity $channel = null)
    {
        $data = array();
        if ($channel) {
            $data['channel'] = $channel;
        }
        return $context->actionResponse('Form:News@Archive:News', $data);
    }

    public function editAction(NewsEntity $news, Context $context)
    {
        return $context->actionResponse('Form:News@Archive:News', array(
                'archive' => $news->getArchive(),
                'news' => $news
            ));
    }

    public function deleteAction(Context $context)
    {
        return $context->actionResponse('Action:NewsDelete@Archive:News');
    }
}