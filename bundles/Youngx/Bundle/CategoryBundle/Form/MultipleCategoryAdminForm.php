<?php

namespace Youngx\Bundle\CategoryBundle\Form;

use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class MultipleCategoryAdminForm extends CategoryAdminForm
{
    protected function submit(GetResponseEvent $event)
    {
        $labels = explode("\n", $this->label);
        $category = $this->context->repository()->create('category');
        $category->set($this->toArray());
        foreach ($labels as $i => $label) {
            $label = trim($label);
            if ($label) {
                $clone = clone $category;
                $clone->set('label', $label);
                $clone->save();
            } else {
                unset($labels[$i]);
            }
        }
        $this->context->flash()->add('success', sprintf('批量添加分类 <i>%s</i>成功！', implode('，', $labels)));
        $event->setResponse($this->context->redirectResponse($this->context->generateUrl('category-admin')));
    }

    protected function render(RenderableResponse $response)
    {
        parent::render($response);
        $response->addVariable('#subtitle', '批量添加分类');
    }

    public function renderFormWidget(FormWidget $widget)
    {
        parent::renderFormWidget($widget);
        $widget->addField('label')->label('分类名')->help('一行一个分类名')->textarea();
    }
}