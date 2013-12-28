<?php

namespace Youngx\Bundle\UserBundle\Form;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\UserBundle\Module\ActivationModule\Entity\UserActivationEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\Form\FormErrorHandler;

class RegisterForm extends Form
{
    /**
     * @var UserEntity
     */
    protected $user;

    protected $username;
    protected $email;
    protected $password;
    protected $password_confirm;

    protected $verifyEmail = false;

    public function setUsername($username)
    {
        $this->username = trim($username);
    }

    public function setEmail($email)
    {
        $this->email = trim($email);
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
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

    protected function registerValidators()
    {
        return array(
            'username' => array(
                'required' => '用户名不能为空！',
                'name' => '用户名必须以字母开头的由小写字母、数字及下划线组成的4～16个字符！'
            ),
            'email' => array(
                'required' => 'E-mail不能为空',
                'email' => '无效的电子邮件格式！'
            ),
            'password' => array(
                'required' => '密码不能为空！'
            ),
            'password_confirm' => array(
                'equalTo' => array('两次密码不一致！', 'password')
            )
        );
    }

    protected function validate(FormErrorHandler $feh)
    {
        $repository = $this->context->repository();
        if ($repository->exist('user', 'name=:name', array(':name' => $this->username))) {
            $feh->add('username', '该用户名已被注册！');
        }

        if ($repository->exist('user', 'email=:email', array(':email' => $this->email))) {
            //$feh->add('email', '该邮箱已被注册！');
        }
    }

    protected function submit(GetResponseEvent $event)
    {
        $this->user = $this->context->repository()->create('user', array(
                'name' => $this->username,
                'password' => $this->password,
                'email' => $this->email,
                'created_at' => Y_TIME,
            ));

        $this->onUserBeforeSave($this->user);
        $this->user->save();

        if ($this->verifyEmail) {
            $this->verifyEmail($this->user);
        }
    }

    protected function verifyEmail(UserEntity $user)
    {
        $token = md5($user->getId() . $user->getEmail() . Y_TIME);

        $this->context->repository()->create('user-activation', array(
                'uid' => $user->getUid(),
                'type' => UserActivationEntity::TYPE_EMAIL,
                'value' => $user->getEmail(),
                'token' => $token,
                'created_at' => Y_TIME
            ))->save();

        $url = $this->context->generateUrl('user-email-activation', array(
               'token' => base64_encode($user->getId() . '.' . $token)
            ), true);

        $content = <<<code
{$user->getName()}:<br />
&nbsp;&nbsp;&nbsp;&nbsp; 感谢您在追尾网的注册，请点击<a href="$url" target="_blank">激活</a>链接以验证您的邮箱。感谢您的配合！<br />
&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; 追尾网
code;

        return $this->context->mail(array(
                $user->getEmail() => $user->getName()
            ), '追尾网的邮箱验证', $content);
    }

    protected function onUserBeforeSave(UserEntity $user)
    {
    }

    public function id()
    {
        return 'user-register';
    }

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->user;
    }
}