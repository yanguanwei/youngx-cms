<?php

namespace Youngx\Bundle\UserBundle\Form;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\MVC\Application;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\FormBuilder;
use Youngx\MVC\FormField;
use Youngx\MVC\Identity;
use Youngx\MVC\Form;
use Youngx\MVC\ValidationResult;
use Youngx\MVC\Validator\RequiredValidator;

class LoginForm extends Form
{
    protected $app;
    protected $username;
    protected $password;
    protected $rememberMe;
    protected $returnUrl;

    protected function setup(FormBuilder $builder)
    {
        $builder->addField($username = new FormField('username', 'user name'));
        $username->addValidator(new RequiredValidator());

        $builder->addField($password = new FormField('password', 'password'));
        $password->addValidator(new RequiredValidator());
    }

    public function setUsername($username)
    {
        $this->username = trim($username);
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $rememberMe
     */
    public function setRememberMe($rememberMe)
    {
        $this->rememberMe = $rememberMe;
    }

    /**
     * @return mixed
     */
    public function getRememberMe()
    {
        return $this->rememberMe;
    }

    /**
     * @param mixed $returnUrl
     */
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
    }

    /**
     * @return mixed
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    protected function validate(ValidationResult $result)
    {
        $app = Application::getInstance();
        $user = $app->repository()->query('user')->findByName($this->username);
        if ($user && $user instanceof UserEntity) {
            if ($user->getPassword() === $user->encryptPassword($this->password)) {
                $app->login(Identity::createFromEntity($user), $this->rememberMe ? 86400 * 365 : 0);
                return ;
            }
        }
        $app->flash()->add('error', '用户名或密码错误！');
        $result->add('password', '用户或密码错误！');
    }

    protected function submitForm(GetResponseEvent $event)
    {
        $app = Application::getInstance();
        $returnUrl = $this->returnUrl ?: $this->computeRedirectUrl($app);
        $event->setResponse($app->redirectResponse($returnUrl));
    }

    protected function computeRedirectUrl(Application $app)
    {
        return $app->generateUrl('user-home');
    }
}