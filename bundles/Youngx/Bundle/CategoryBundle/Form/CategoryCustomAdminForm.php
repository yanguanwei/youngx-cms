<?php

namespace Youngx\Bundle\CategoryBundle\Form;

use Youngx\Bundle\CategoryBundle\Entity\CategoryCustomEntity;
use Youngx\Bundle\CategoryBundle\Entity\CategoryEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class CategoryCustomAdminForm extends Form
{
    /**
     * @var CategoryCustomEntity
     */
    protected $categoryCustom;
    /**
     * @var CategoryEntity
     */
    protected $category;

    protected $uid = 0;
    protected $category_id = 0;
    protected $label;
    protected $sort_num = 0;

    public function id()
    {
        return 'category-custom-admin';
    }

    protected function registerValidators()
    {
        return array(
            'label' => array(
                'required' => '分类名不能为空'
            ),
            'category_id' => array(
                'required' => '请选择一个父分类'
            )
        );
    }

    protected function fields()
    {
        return array(
            'uid', 'category_id', 'label', 'sort_num'
        );
    }

    protected function render(RenderableResponse $response)
    {
        $cancelUrl = $this->category ? $this->context->generateUrl('category-custom-admin', array(
                'category' => $this->category->getId()
            )) : $this->context->generateUrl('category-admin');

        $response->setContent($this->context->widget('Form', array(
                    '#form' => $this,
                    '#skin' => 'horizontal',
                    'cancel' => array(
                        'href' => $cancelUrl
                    )
                )));
        $response->addVariable('#subtitle', ($this->getCategoryCustom() ? '编辑' : '添加') . '自定义分类');
    }

    public function renderFormWidget(FormWidget $widget)
    {
        $widget->addField('category_id')->label('父分类')->select_category(array(
                '#chosen' => array('search_contains' => true),
            ));
        $widget->addField('label')->label('分类名')->text();
        $widget->addField('uid')
            ->label('用户')
            ->help('留空表示标准自定义分类')
            ->select_user();
        $widget->addField('sort_num')->label('排序')->text();
    }

    protected function submit(GetResponseEvent $event)
    {
        $categoryCustom = $this->categoryCustom ?: $this->context->repository()->create('category_custom');
        $categoryCustom->set($this->toArray());
        $categoryCustom->save();

        $this->categoryCustom = $categoryCustom;

        $this->context->flash()->add('success', sprintf('自定义分类 <i>%s</i> 保存成功', $categoryCustom->get('label')));

        $event->setResponse($this->context->redirectResponse(
                $this->context->generateUrl('category-custom-admin', array(
                        'category' => $categoryCustom->getCategory()->getId()
                    ))
            ));
    }

    /**
     * @param CategoryCustomEntity $categoryCustom
     */
    public function setCategoryCustom(CategoryCustomEntity $categoryCustom)
    {
        $this->categoryCustom = $categoryCustom;
        $this->set($categoryCustom->toArray());
    }

    /**
     * @return CategoryCustomEntity
     */
    public function getCategoryCustom()
    {
        return $this->categoryCustom;
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = trim($label);
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $sort_num
     */
    public function setSortNum($sort_num)
    {
        $this->sort_num = $sort_num;
    }

    /**
     * @return mixed
     */
    public function getSortNum()
    {
        return $this->sort_num;
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

    /**
     * @param CategoryEntity $category
     */
    public function setCategory(CategoryEntity $category)
    {
        $this->category = $category;
        $this->category_id = $category->getId();
    }
}