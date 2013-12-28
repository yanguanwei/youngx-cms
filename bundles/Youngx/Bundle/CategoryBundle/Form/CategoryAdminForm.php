<?php

namespace Youngx\Bundle\CategoryBundle\Form;

use Youngx\Bundle\CategoryBundle\Entity\CategoryEntity;
use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class CategoryAdminForm extends Form
{
    protected $label;
    protected $uid = 0;
    protected $parent_id = 0;
    protected $sort_num = 0;

    /**
     * @var CategoryEntity
     */
    protected $parent;

    /**
     * @var CategoryEntity
     */
    protected $category;

    protected $user;

    public function id()
    {
        return 'category-admin';
    }

    protected function validate(Form\FormErrorHandler $feh)
    {
        if ($this->parent_id && $this->category) {
            if ($this->parent_id == $this->category->getId()) {
                $feh->add('parent_id', '不能选择自己作为父分类');
            } else {
                $parent = $this->context->repository()->load('category', $this->parent_id);
                if ($parent) {
                    foreach ($parent->getPaths() as $category) {
                        if ($category->getId() == $this->category->getId()) {
                            $feh->add('parent_id', '不能选择该分类的子分类作为其父分类');
                            break;
                        }
                    }
                } else {
                    $feh->add('parent_id', '不存在的父分类');
                }
            }
        }
    }

    protected function submit(GetResponseEvent $event)
    {
        if (!$this->category) {
            $this->category = $this->context->repository()->create('category');
        }

        $this->category->set($this->toArray());
        $this->category->save();

        $this->context->flash()->add('success', sprintf('分类 <i>%s</i> 保存成功', $this->category->getLabel()));

        $event->setResponse($this->context->redirectResponse($this->context->generateUrl('category-admin')));
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Form', array(
                    '#form' => $this,
                    '#skin' => 'horizontal',
                    'cancel' => array(
                        'href' => $this->context->generateUrl('category-admin')
                    )
                )))
            ->addVariable('#subtitle', $this->category ? '编辑分类' : '添加分类');
    }

    public function renderFormWidget(FormWidget $widget)
    {
        $widget->addField('parent_id')->label('父ID')->select_category(array(
                '#chosen' => array('search_contains' => true),
                '#empty' => '作为顶级分类'
            ));
        $widget->addField('label')->label('分类名')->text();
        $widget->addField('uid')
            ->label('用户')
            ->help('留空表示标准自定义分类')
            ->select_user();
        $widget->addField('sort_num')->label('排序')->text();
    }

    protected function registerValidators()
    {
        return array(
            'label' => array(
                'required' => '分类标题不能为空'
            )
        );
    }

    public function setLabel($label)
    {
        $this->label = trim($label);
    }

    public function setSortNum($sortNum)
    {
        $this->sort_num = intval($sortNum);
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    public function setParentId($parent_id)
    {
        $this->parent_id = intval($parent_id);
    }

    /**
     * @return int
     */
    public function getSortNum()
    {
        return $this->sort_num;
    }

    /**
     * @param \Youngx\Bundle\CategoryBundle\Entity\CategoryEntity $parent
     */
    public function setParent(CategoryEntity $parent)
    {
        $this->parent = $parent;
        $this->setParentId($parent->getId());
    }

    /**
     * @param \Youngx\Bundle\CategoryBundle\Entity\CategoryEntity $category
     */
    public function setCategory(CategoryEntity $category)
    {
        $this->category = $category;
        $this->set($category->toArray());
    }

    protected function fields()
    {
        return array(
            'label', 'uid', 'parent_id', 'sort_num'
        );
    }

    /**
     * @param UserEntity $user
     */
    public function setUser(UserEntity $user)
    {
        $this->user = $user;
        $this->uid = $user->getUid();
    }

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param int $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return int
     */
    public function getUid()
    {
        return $this->uid;
    }
}