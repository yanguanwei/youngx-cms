<?php

namespace Youngx\Bundle\UserBundle\Module\RetrievePasswordModule\Controller;

use Youngx\Bundle\UserBundle\Entity\User;
use Youngx\Kernel\Container as Y;
use Symfony\Component\HttpFoundation\Response;
use Youngx\Bundle\UserBundle\Module\RetrievePasswordModule\Entity\UserRetrievePassword;
use Youngx\Kernel\Form;
use Youngx\Kernel\Request;

class CheckController extends Form
{
    public $token;
    public $password;
    public $password_confirm;

    /**
     * @var UserRetrievePassword
     */
    protected $record;

    protected function validate()
    {
        $this->record = $record = $this->check($this->token);
        if (!$record) {
            Y::session()->getFlashBag()->add('error', '已失效的链接！');
            return Y::renderResponse('RetrievePassword:message.html.yui@User');
        }

        if (!$this->password) {
            $this->addError('密码不能为空！', 'password');
            return false;
        }

        if ($this->password != $this->password_confirm) {
            $this->addError('两次密码输入不一致！', 'password_confirm');
            return false;
        }
    }

    protected function submit()
    {
        User::updatePassword($this->record->uid, $this->password);
        Y::session()->getFlashBag()->add('success', '密码更改成功！');

        $this->record->delete();

        return Y::renderResponse('RetrievePassword:message.html.yui@User');
    }

    protected function render()
    {
        $this->token = Y::request()->query->get('token');
        return Y::renderResponse('RetrievePassword:reset.html.yui@User', array(
                'form' => $this
            ));
    }

    public function indexAction(Request $request)
    {
        if ($request->query->has('token')) {
            $record = $this->check($request->query->get('token'));
            if ($record) {
                return $this->execute();
            }
        }

        Y::session()->getFlashBag()->add('error', '已失效的链接！');
        return Y::renderResponse('RetrievePassword:message.html.yui@User');
    }

    /**
     * @param $token
     * @return UserRetrievePassword | null
     */
    protected function check($token)
    {
        $a = explode('.', base64_decode($token));
        if (count($a) == 2) {
            list($uid, $token) = $a;
            $record = UserRetrievePassword::find($uid);
            if ($record && $token == $record->token) {
                if (Y_TIME - $record->create_time < Y::config('user:retrieve.password.expire', 48) * 86400) {
                    return $record;
                }
            }
        }
    }
}