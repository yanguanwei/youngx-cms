<?php

namespace Youngx\Bundle\ArchiveBundle\Table;

use Youngx\Bundle\ArchiveBundle\Entity\ArchiveEntity;
use Youngx\Database\Query;
use Youngx\MVC\Html;
use Youngx\MVC\ListView\ColumnCollection;
use Youngx\MVC\ListView\ColumnInterface;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Table;
use Youngx\MVC\Widget\TableWidget;

abstract class ArchiveAdminTable extends Table
{
    public function renderTableWidget(TableWidget $table)
    {
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Table', array(
                    '#table' => $this
                )));
    }

    protected function formatIdColumnHeading(Html $th)
    {
        $th->style('width', '5%')->addClass('center');
    }

    protected function formatIdColumnHtml(Html $td)
    {
        $td->addClass('center');
    }

    protected function collectColumns(ColumnCollection $collection)
    {
        $collection->add('id', 'ID', 1);
        $collection->add('title', '标题', 2)->sortable();
        $collection->add('channel', '栏目', 3);
        $collection->add('operations', '操作', 4);
    }

    protected function formatChannelColumnHeading(Html $th)
    {
        $th->addClass('center');
    }

    protected function formatChannelColumn(ArchiveEntity $archive, Html $html)
    {
        if ($archive->getChannel()) {
            $html->setContent($archive->getChannel()->getLabel());
            $html->style('width', '15%')->addClass('center');
        }
    }

    protected function formatOperationsColumnHeading(Html $th)
    {
        $th->style('width', '20%');
    }

    protected function formatOperationsColumn(ArchiveEntity $archive, Html $td)
    {
        $td->append(
            $this->context->html('a', array(
                    'href' => $this->generateEditUrl($archive),
                    '#content' => '编辑'
                ))
        )->append($this->context->html(
                    'a', array(
                        'href' => $this->generateDeleteUrl($archive),
                        '#content' => '删除'
                    )
                ));
    }

    abstract protected function generateEditUrl(ArchiveEntity $archive);
    abstract protected function generateDeleteUrl(ArchiveEntity $archive);

    /**
     * @return Query
     */
    protected function query()
    {
        $query = $this->context->repository()->query('archive');
        $query->join('channel');

        return $query;
    }
}