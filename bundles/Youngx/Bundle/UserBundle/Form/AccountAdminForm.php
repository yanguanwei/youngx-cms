<?php

namespace Youngx\Bundle\UserBundle\Form;

use Youngx\Bundle\UserBundle\Entity\RoleEntity;
use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\User\Identity;
use Youngx\MVC\Widget\FormWidget;

class AccountAdminForm extends Form
{
    /**
     * @var UserEntity
     */
    protected $user;
    protected $name;
    protected $password;
    protected $password_confirm;
    protected $email;
    protected $status;
    protected $roles = array();

    protected function registerValidators()
    {
        $validators = array(
            'name' => array(
                'required' => '用户名不能为空',
                'name' => '用户必须以字母开头由字母、数字及下划级组成的3～16位字符'
            )
        );

        if (!$this->user) {
            $validators['password'] = array(
                'required' => '密码不能为空'
            );
            $validators['password_confirm'] = array(
                'equalTo' => array('两次密码输入不一致', 'password')
            );
        }

        return $validators;
    }

    protected function validate(Form\FormErrorHandler $feh)
    {
        if ($this->context->repository()->exist('user', 'name=:name', array(':name' => $this->name), $this->user)) {
            $feh->add('name', '用户名已经存在！');
        }
    }

    protected function submit(GetResponseEvent $event)
    {
        $user = $this->user ?: $this->context->repository()->create('user', array(
                'created_at' => Y_TIME
            ));

        $user->set($this->toArray());
        $user->save();

        $this->context->flash()->add('success', sprintf('保存用户 <i>%s</i> 的帐号信息成功！', $user->getName()));
        $this->user = $user;

        $event->setResponse($this->context->redirectResponse(
                $this->context->request()->getUri()
            ));
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Form', array(
                    '#form' => $this,
                    '#skin' => 'horizontal',
                    'cancel' => $this->context->generateUrl('user-admin')
                )))
            ->addVariable('#subtitle', array(
                    '用户' . empty($this->user) ? '' : ( '<i>'.$this->user->getName().'</i>'), '帐号信息'
                ));
    }

    public function renderFormWidget(FormWidget $widget)
    {
        $roles =  (array) $this->context->value('role-labels');
        unset($roles[Identity::ROLE_ANONYMOUS], $roles[Identity::ROLE_REGISTERED]);

        $widget->addField('roles')->label('角色')->select(array(
                '#options' => $roles,
                '#chosen' => true,
                'data-placeholder' => '请选择角色',
                'multiple' => true
            ));

        $widget->addField('name')->label('用户名')->text();
        if (!$this->user) {
            $widget->addField('password')->label('密码')->password();
            $widget->addField('password_confirm')->label('确认密码')->password();
        }
        $widget->addField('email')->label('E-mail')->text();
        $widget->addField('status')->label('状态')->radio(array(
                    '#options' => array(
                        '无效', '有效'
                    )
                ));
    }

    public function id()
    {
        return 'user-admin-account';
    }

    /**
     * @return array
     */
    protected function fields()
    {
        $fields = array(
            'name', 'email', 'status', 'roles'
        );

        if (!$this->user) {
            $fields = array_merge($fields, array('password', 'password_confirm'));
        }

        return $fields;
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
     * @param UserEntity $user
     */
    public function setUser(UserEntity $user)
    {
        $this->user = $user;
        $this->set($user->toArray());
    }

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password_confirm
     */
    public function setPasswordConfirm($password_confirm)
    {
        $this->password_confirm = $password_confirm;
    }

    /**
     * @return mixed
     */
    public function getPasswordConfirm()
    {
        return $this->password_confirm;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = array();
        foreach ($roles as $role) {
            if ($role instanceof RoleEntity) {
                $this->roles[] = $role->getId();
            } else {
                $this->roles[] = $role;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }
}