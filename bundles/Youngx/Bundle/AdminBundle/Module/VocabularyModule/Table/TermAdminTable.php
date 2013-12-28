<?php

namespace Youngx\Bundle\AdminBundle\Module\VocabularyModule\Table;

use Youngx\Bundle\AdminBundle\Module\VocabularyModule\Entity\TermEntity;
use Youngx\Bundle\AdminBundle\Module\VocabularyModule\Entity\VocabularyEntity;
use Youngx\Database\Query;
use Youngx\EventHandler\Event\GetArrayEvent;
use Youngx\MVC\Html;
use Youngx\MVC\ListView\ColumnCollection;
use Youngx\MVC\ListView\ColumnInterface;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Table;

class TermAdminTable extends Table
{
    /**
     * @var VocabularyEntity
     */
    protected $vocabulary;

    public function id()
    {
        return 'term-admin';
    }

    /**
     * @return Query
     */
    protected function query()
    {
        return $this->context->repository()->query('term');
    }

    protected function filter(Query $query, GetArrayEvent $event)
    {
        $query->where('vocabulary_id=?', $this->vocabulary->getId());
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Table', array(
                    '#table' => $this
                )))->addVariable('#subtitle', $this->vocabulary->getLabel());
    }

    protected function collectColumns(ColumnCollection $collection)
    {
        $collection->add('label', '术语名称');
        $collection->add('operations', '操作');
    }

    protected function formatOperationsColumn(TermEntity $term, Html $td)
    {
        $add = $this->context->html('a', array(
                'href' => $this->context->generateUrl('term-edit', array(
                        'term' => $term->getId(),
                        'returnUrl' => $this->context->request()->getUri()
                    )),
                '#content' => '编辑'
            ));

        $delete = $this->context->html(
            'a', array(
                'href' => $this->context->generateUrl('term-delete', array(
                        'id' => $term->getId(),
                        'returnUrl' => $this->context->request()->getUri()
                    )),
                '#content' => '删除'
            )
        );

        $td->setContent("{$add} | {$delete}");
    }

    /**
     * @param VocabularyEntity $vocabulary
     */
    public function setVocabulary(VocabularyEntity $vocabulary)
    {
        $this->vocabulary = $vocabulary;
    }
}