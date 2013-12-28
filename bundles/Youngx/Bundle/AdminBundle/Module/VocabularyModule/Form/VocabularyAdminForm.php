<?php

namespace Youngx\Bundle\AdminBundle\Module\VocabularyModule\Form;

use Youngx\Bundle\AdminBundle\Module\VocabularyModule\Entity\VocabularyEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class VocabularyAdminForm extends Form
{
    /**
     * @var VocabularyEntity
     */
    protected $vocabulary;
    protected $name;
    protected $label;

    protected function registerValidators()
    {
        return array(
            'name' => array(
                'required' => '机器名不能为空',
                'name' => '机器名必须为小字母、数字及下划线'
            ),
            'label' => array(
                'required' => '词汇表名称不能为空'
            )
        );
    }

    protected function validate(Form\FormErrorHandler $feh)
    {
        $condition = '';
        $params = array();

        if ($this->vocabulary) {
            $condition = 'id<>:id AND ';
            $params[':id'] = $this->vocabulary->getId();
        }

        $condition .= 'name=:name';
        $params[':name'] = $this->name;

        if ($this->context->repository()->exist('vocabulary', $condition, $params)) {
            $feh->add('name', '已经存在的机器名');
        }
    }

    protected function submit(GetResponseEvent $event)
    {
        if (!$this->vocabulary) {
            $this->vocabulary = $this->context->repository()->create('vocabulary');
        }

        $this->vocabulary->set(array(
                'name' => $this->name,
                'label' => $this->label
            ));

        $this->vocabulary->save();

        $this->context->flash()->add('success', sprintf('保存词汇表 <i>%s</i> 成功', $this->vocabulary->getLabel()));

        $event->setResponse($this->context->redirectResponse($this->context->generateUrl('vocabulary-admin')));
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Form', array(
                    '#form' => $this,
                )))->addVariable('#subtitle', ($this->vocabulary ? '编辑' : '添加').'词汇表');
    }

    public function renderFormWidget(FormWidget $widget)
    {
        $widget->addField('name')->label('机器名')->text();
        $widget->addField('label')->label('词汇表名')->text();
    }

    public function id()
    {
        return 'vocabulary-admin';
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
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
     * @param VocabularyEntity $vocabulary
     */
    public function setVocabulary(VocabularyEntity $vocabulary)
    {
        $this->vocabulary = $vocabulary;
        $this->name = $vocabulary->getName();
        $this->label = $vocabulary->getLabel();
    }
}