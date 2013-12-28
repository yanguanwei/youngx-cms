<?php

namespace Youngx\Bundle\UserBundle\Module\RoleModule\Form;

use Youngx\Bundle\UserBundle\Module\RoleModule\Entity\RoleEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class RoleAdminForm extends Form
{
    /**
     * @var RoleEntity
     */
    private $role;
    private $label;

    /**
     * @param RoleEntity $role
     */
    public function setRole(RoleEntity $role)
    {
        $this->role = $role;
    }

    /**
     * @return RoleEntity
     */
    public function getRole()
    {
        return $this->role;
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

    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Form', array(
                    '#form' => $this,
                    '#skin' => 'horizontal'
                )));

        if ($this->role) {
            $response->addVariable('#subtitle', '角色编辑 #<i>'.$this->getRole()->getId().'</i>');
        } else {
            $response->addVariable('#subtitle', '角色添加');
        }
    }

    public function renderFormWidget(FormWidget $widget)
    {
        $widget->addField('label')->label('角色名称')->text();
    }

    protected function registerValidators()
    {
        return array(
            'label' => array(
                'required' => '角色名称不能为空'
            )
        );
    }

    protected function submit(GetResponseEvent $event)
    {
        if (!$this->role) {
            $this->role = $this->context->repository()->create('role');
        }

        $this->role->setLabel($this->label);
        $this->role->save();

        $this->context->flash()->add('success', sprintf('角色 <i>%s</i> 保存成功', $this->role->getLabel()));
        $event->setResponse($this->context->redirectResponse(
                $this->context->redirectResponse('role-edit', array('role' => $this->role->getId()))
            ));
    }

    public function id()
    {
        return 'user.role.admin';
    }
}