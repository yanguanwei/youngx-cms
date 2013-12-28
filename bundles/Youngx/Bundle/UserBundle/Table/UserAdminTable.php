<?php

namespace Youngx\Bundle\UserBundle\Table;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Database\Query;
use Youngx\EventHandler\Event\GetArrayEvent;
use Youngx\MVC\Html;
use Youngx\MVC\ListView\ColumnCollection;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Table;
use Youngx\MVC\Widget\ButtonGroupWidget;

class UserAdminTable extends Table
{
    private $uid;
    private $name;

    public function id()
    {
        return 'user.admin';
    }

    public function getBatchName()
    {
        return 'id';
    }

    public function renderButtonGroupWidget(ButtonGroupWidget $widget)
    {
        $default = $widget->getGroup('default')->setLabel('操作');
        $default->add('delete', '批量删除', $this->context->generateUrl('user-delete'));
    }

    protected function collectColumns(ColumnCollection $collection)
    {
        $collection->add('uid', 'ID')->sortable();
        $collection->add('name', '用户名')->sortable();
        $collection->add('operations', '操作', 2);
    }

    protected function formatOperationsColumn(UserEntity $entity, Html $html)
    {
        $returnUrl = $this->context->request()->getUri();

        $edit = $this->context->html('a', array(
                'href' => $this->context->generateUrl('user-admin-account', array(
                        'user' => $entity->getId(),
                        'returnUrl' => $returnUrl
                    )),
                '#content' => '编辑'
            ));

        $delete = $this->context->html(
            'a', array(
                'href' => $this->context->generateUrl('user-admin-delete', array(
                        'uid' => $entity->getId(),
                        'returnUrl' => $returnUrl
                    )),
                '#content' => '删除'
            )
        );

        $html->setContent("{$edit} | {$delete}");
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Table', array(
                    '#table' => $this
                )))
            ->addVariable('#subtitle', array(
                    '用户', '列表'
                ));
    }

    /**
     * @return Query
     */
    protected function query()
    {
        return $this->context->repository()->query('user');
    }

    protected function filter(Query $query, GetArrayEvent $event)
    {
        $query->order('y_user.uid DESC');
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }
}