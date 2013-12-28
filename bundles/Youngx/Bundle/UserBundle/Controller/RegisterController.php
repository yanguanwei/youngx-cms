<?php

namespace Youngx\Bundle\UserBundle\Controller;

use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\Form\FormErrorHandler;
use Youngx\MVC\RenderableResponse;

class RegisterController extends Form
{
    protected $username;
    protected $email;
    protected $password;
    protected $password_confirm;

    public function indexAction()
    {
        return $this->run();
    }

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
                'rangelength' => array('用户名必须大于3个字符小于16个字符！', 3, 16)
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

    protected function validate(GetResponseEvent $event, FormErrorHandler $feh)
    {
        $repository = $this->context->repository();
        if ($repository->exist('user', 'name=:name', array(':name' => $this->username))) {
            $feh->add('username', '该用户名已被注册！');
        }

        if ($repository->exist('user', 'email=:email', array(':email' => $this->email))) {
            $feh->add('email', '该邮箱已被注册！');
        }
    }

    protected function submit(GetResponseEvent $event)
    {
        $user = $this->context->repository()->create('user', array(
            'name' => $this->username,
            'password' => $this->password,
            'email' => $this->email,
            'created_at' => Y_TIME,
        ));
        $user->save();

        $this->context->flash()->add('success', '注册成功，请登录您的帐号');
        $this->context->redirectResponse($this->context->generateUrl('user-login'));
    }

    protected function render(RenderableResponse $response)
    {
        $response->setFile('register.html.yui@User')
            ->addVariable('form', $this);
    }

    public function id()
    {
        return 'user.register';
    }
}