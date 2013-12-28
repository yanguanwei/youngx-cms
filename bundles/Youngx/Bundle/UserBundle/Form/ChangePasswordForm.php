<?php

namespace Youngx\Bundle\UserBundle\Form;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\Form\FormErrorHandler;

class ChangePasswordForm extends Form
{
    /**
     * @var UserEntity
     */
    private $user;
    private $password_old;
    private $password;
    private $password_confirm;

    public function id()
    {
        return 'user-change-password';
    }

    protected function initRequest()
    {
        if (!$this->user) {
            throw new Form\FormErrorException('未指定用户！');
        }
    }

    protected function registerValidators()
    {
        return array(
            'password_old' => array(
                'required' => '原密码不能为空'
            ),
            'password' => array(
                'required' => '新密码不能为空'
            ),
            'password_confirm' => array(
                'required' => '确认密码不能为空',
                'equalTo' => array('新密码与确认空码不一致', 'password')
            )
        );
    }

    protected function validate(FormErrorHandler $feh)
    {
        if ($this->user->getPassword() !== $this->user->encryptPassword($this->password_old)) {
            $feh->add('password_old', '原密码错误');
        }
    }

    protected function submit(GetResponseEvent $event)
    {
        try {
            $this->user->updatePassword($this->password);
            $this->context->flash()->add('success', '修改密码成功');
        } catch (\Exception $e) {
            $this->context->flash()->add('error', $e->getMessage());
        }
    }

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->user;
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
     * @param mixed $password_old
     */
    public function setPasswordOld($password_old)
    {
        $this->password_old = $password_old;
    }

    /**
     * @return mixed
     */
    public function getPasswordOld()
    {
        return $this->password_old;
    }

    /**
     * @param UserEntity $user
     */
    public function setUser(UserEntity $user)
    {
        $this->user = $user;
    }
}