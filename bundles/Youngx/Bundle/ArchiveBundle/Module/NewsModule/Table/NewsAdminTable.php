<?php

namespace Youngx\Bundle\ArchiveBundle\Module\NewsModule\Table;

use Youngx\Bundle\ArchiveBundle\Entity\ArchiveEntity;
use Youngx\Bundle\ArchiveBundle\Table\ArchiveAdminTable;
use Youngx\Database\Query;
use Youngx\MVC\RenderableResponse;

class NewsAdminTable extends ArchiveAdminTable
{
    public function id()
    {
        return 'news-admin';
    }

    protected function render(RenderableResponse $response)
    {
        parent::render($response);
        $response->addVariable('#subtitle', array(
                '资讯列表'
            ));
    }

    protected function generateEditUrl(ArchiveEntity $archive)
    {
        return $this->context->generateUrl('news-edit', array(
                'news' => $archive->getId(),
                'returnUrl' => $this->context->request()->getUri()
            ));
    }

    protected function generateDeleteUrl(ArchiveEntity $archive)
    {
        return $this->context->generateUrl('news-delete', array(
                'id' => $archive->getId(),
                'returnUrl' => $this->context->request()->getUri()
            ));
    }

    protected function query()
    {
        $query = parent::query();
        $query->where("type='news'");

        return $query;
    }
}