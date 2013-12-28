<?php

namespace Youngx\Bundle\AdminBundle\Module\VocabularyModule\Form;

use Youngx\Bundle\AdminBundle\Module\VocabularyModule\Entity\TermEntity;
use Youngx\Bundle\AdminBundle\Module\VocabularyModule\Entity\VocabularyEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class TermAdminForm extends Form
{
    /**
     * @var TermEntity
     */
    protected $term;
    /**
     * @var VocabularyEntity
     */
    protected $vocabulary;
    protected $label;
    protected $icon;

    public function id()
    {
        return 'term-admin';
    }

    protected function registerValidators()
    {
        return array(
            'vocabulary' => array(
                'required' => '没有指定词汇表',
            ),
            'label' => array(
                'required' => '术语名称不能为空'
            )
        );
    }

    protected function submit(GetResponseEvent $event)
    {
        if (!$this->term) {
            $this->term = $this->context->repository()->create('term', array(
                    'vocabulary_id' => $this->vocabulary->getId(),
                ));
        }

        $this->term->set(array(
                'label' => $this->label,
                'icon' => $this->icon,
            ));

        $this->term->save();

        $this->context->flash()->add('success', sprintf('保存术语 <i>%s</i> 成功', $this->term->getLabel()));

        $event->setResponse($this->context->redirectResponse($this->context->generateUrl('term-admin', array(
                        'vocabulary' => $this->term->getVocabularyId()
                    ))));
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Form', array(
                    '#form' => $this,
                    '#skin' => 'horizontal',
                    'cancel' => array(
                        'href' => $this->context->generateUrl('term-admin', array(
                                'vocabulary' => $this->vocabulary->getId()
                            ))
                    )
                )))->addVariable('#subtitle', array(
                    $this->vocabulary->getLabel(), ($this->term ? '编辑' : '添加').'术语'
                ));
    }

    public function renderFormWidget(FormWidget $widget)
    {
        $widget->addField('label')->label('术语名称')->text();
        $widget->addField('icon')->label('图标')->ckfinder();
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
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
     * @param TermEntity $term
     */
    public function setTerm(TermEntity $term)
    {
        $this->term = $term;
        $this->vocabulary = $term->getVocabulary();
        $this->label = $term->getLabel();
        $this->icon = $term->getIcon();
    }

    /**
     * @param VocabularyEntity $vocabulary
     */
    public function setVocabulary(VocabularyEntity $vocabulary)
    {
        $this->vocabulary = $vocabulary;
    }

    /**
     * @return VocabularyEntity
     */
    public function getVocabulary()
    {
        return $this->vocabulary;
    }
}