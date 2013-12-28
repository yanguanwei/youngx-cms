<?php

namespace Youngx\Bundle\AdminBundle\Module\VocabularyModule\Table;

use Youngx\Bundle\AdminBundle\Module\VocabularyModule\Entity\VocabularyEntity;
use Youngx\Database\Query;
use Youngx\MVC\Html;
use Youngx\MVC\ListView\ColumnCollection;
use Youngx\MVC\ListView\ColumnInterface;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Table;

class VocabularyAdminTable extends Table
{

    public function id()
    {
        return 'vocabulary-admin';
    }

    /**
     * @return Query
     */
    protected function query()
    {
        return $this->context->repository()->query('vocabulary');
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Table', array(
                    '#table' => $this
                )));
    }

    protected function collectColumns(ColumnCollection $collection)
    {
        $collection->add('name', '机器名');
        $collection->add('label', '词汇表名');
        $collection->add('operations', '操作');
    }

    protected function formatOperationsColumn(VocabularyEntity $vocabulary, Html $td)
    {
        $td->append(
            $this->context->html('a', array(
                    'href' => $this->context->generateUrl('term-admin', array(
                            'vocabulary' => $vocabulary->getId(),
                        )),
                    '#content' => '术语表'
                ))
        )->append(
            $this->context->html('a', array(
                    'href' => $this->context->generateUrl('term-add', array(
                            'vocabulary' => $vocabulary->getId(),
                        )),
                    '#content' => '添加术语'
                ))
        )->append(
            $this->context->html('a', array(
                    'href' => $this->context->generateUrl('vocabulary-edit', array(
                            'vocabulary' => $vocabulary->getId(),
                            'returnUrl' => $this->context->request()->getUri()
                        )),
                    '#content' => '编辑'
                ))
        )->append($this->context->html(
                    'a', array(
                        'href' => $this->context->generateUrl('vocabulary-delete', array(
                                'id' => $vocabulary->getId(),
                                'returnUrl' => $this->context->request()->getUri()
                            )),
                        '#content' => '删除'
                    )
                ));
    }
}