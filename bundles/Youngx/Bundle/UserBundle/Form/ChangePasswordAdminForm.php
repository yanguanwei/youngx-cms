<?php

namespace Youngx\Bundle\UserBundle\Form;

use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class ChangePasswordAdminForm extends ChangePasswordForm
{
    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Form', array(
                    '#form' => $this,
                    '#skin' => 'horizontal'
                )))
            ->addVariable('#subtitle', array(
                    '用户 <i>'.$this->getUser()->getName().'</i>', '修改密码'
                ));
    }

    public function renderFormWidget(FormWidget $widget)
    {
        $widget->addField('password_old')->label('原密码')->password();
        $widget->addField('password')->label('新密码')->password();
        $widget->addField('password_confirm')->label('确认密码')->password();
    }
}