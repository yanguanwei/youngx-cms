<?php

namespace Youngx\Bundle\UserBundle\Module\RetrievePasswordModule\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Youngx\Kernel\Container as Y;
use Youngx\Bundle\UserBundle\Entity\User;
use Youngx\Bundle\UserBundle\Module\RetrievePasswordModule\Entity\UserRetrievePassword;
use Youngx\Kernel\Form;
use Youngx\Kernel\Request;

class ApplyController extends Form
{
    public $username;
    public $email;

    /**
     * @var UserRetrievePassword
     */
    private $record;
    /**
     * @var User
     */
    private $user;

    protected function validate()
    {
        $user = User::findByName($this->username);

        if (!$user) {
            $this->addError('帐号不存在！', 'username');
            return false;
        }

        if ($user->email != $this->email) {
            $this->addError('帐号与邮箱不匹配！', 'email');
            return false;
        }

        $this->record = $record = UserRetrievePassword::find($user->uid);

        if ($record && Y_TIME - intval($record->create_time) < ($hour = $this->getExpire()) * 86400) {
            //Y::session()->getFlashBag()->add('error', sprintf('取回密码在%s小时内只能申请一次！', $hour));
            //return Y::renderResponse('RetrievePassword:message.html.yui@User');
        }

        $this->user = $user;
    }

    protected function getExpire()
    {
        return Y::config('user:retrieve.password.expire', 48);
    }

    protected function submit()
    {
        $user = $this->user;
        $encode = md5($user->getId() . $user->getPassword() . $user->getEmail() . Y_TIME);

        if ($this->record) {
            $record = $this->record;
        } else {
            $record = new UserRetrievePassword();
        }

        $record->uid = $user->getId();
        $record->token = $encode;
        $record->create_time = Y_TIME;

        $record->save();

        $url = Y::request()->getUriForPath('/user/retrieve/password/check') . '?token=' . base64_encode("{$user->getId()}.{$encode}");

        $mail = Y::mail(array($user->getEmail() => $user->getName()), 'Retrieve Password', sprintf('Please click <a href="%s" target="_blank">%s</a>', $url, $url));
        $mail->IsHTML(true);
        $mail->Send();

        Y::session()->getFlashBag()->add('success', $url);
        Y::session()->getFlashBag()->add('success', sprintf('已向您的邮箱%s发送了一封邮件，请在%s小时内点击链接！', $user->getEmail(), $this->getExpire()));

        return Y::renderResponse('RetrievePassword:message.html.yui@User');
    }

    protected function render()
    {
        return new Response(Y::render('RetrievePassword:apply.html.yui@User', array(
                'form' => $this
            )));
    }

    public function indexAction()
    {
        return $this->execute();
    }
}