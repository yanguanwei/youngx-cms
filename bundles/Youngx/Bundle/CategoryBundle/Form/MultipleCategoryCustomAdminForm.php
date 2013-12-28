<?php

namespace Youngx\Bundle\CategoryBundle\Form;

use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class MultipleCategoryCustomAdminForm extends CategoryCustomAdminForm
{
    protected function submit(GetResponseEvent $event)
    {
        $labels = explode("\n", $this->label);
        $categoryCustom = $this->context->repository()->create('category_custom');
        $categoryCustom->set($this->toArray());
        foreach ($labels as $i => $label) {
            $label = trim($label);
            if ($label) {
                $clone = clone $categoryCustom;
                $clone->set('label', $label);
                $clone->save();
            } else {
                unset($labels[$i]);
            }
        }
        $this->context->flash()->add('success', sprintf('自定义分类 <i>%s</i> 保存成功！', implode('，', $labels)));
        $event->setResponse($this->context->redirectResponse($this->context->generateUrl('category-custom-admin', array(
                        'category' => $this->category->getId()
                    ))));
    }

    public function renderFormWidget(FormWidget $widget)
    {
        parent::renderFormWidget($widget);
        $widget->addField('label')->label('分类名')->help('一行一个分类名')->textarea();
    }
}