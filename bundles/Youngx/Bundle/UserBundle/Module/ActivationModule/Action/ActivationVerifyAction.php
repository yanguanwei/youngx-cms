<?php

namespace Youngx\Bundle\UserBundle\Module\ActivationModule\Action;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\UserBundle\Module\ActivationModule\Entity\UserActivationEntity;
use Youngx\MVC\Action;

class ActivationVerifyAction extends Action
{
    const RESULT_SUCCESS = 0;
    const RESULT_EXPIRED = 1;
    const RESULT_INVALID = 2;

    protected $token;
    protected $type = 0;
    protected $result;
    protected $expire = 48;

    /**
     * @var UserEntity
     */
    protected $user;

    public function id()
    {
        return 'activation-verify';
    }

    protected function doGetRequest()
    {
        $decoding = base64_decode($this->token);
        if (strpos($decoding, '.') !== false) {
            list($uid, $token) = explode('.', $decoding, 2);
            $activation = $this->context->repository()->query('user-activation')
                ->where("uid=:uid AND type=:type AND is_activated='0'")->order('created_at DESC')
                ->one(array(
                        ':uid' => $uid,
                        ':type' => $this->type,
                    ));

            if ($activation && $activation instanceof UserActivationEntity) {
                if ($activation->getToken() != $token) {
                    $this->result = self::RESULT_INVALID;
                } else {
                    if (Y_TIME - $activation->getCreatedAt() > ($this->expire * 3600)) {
                        $this->result = self::RESULT_EXPIRED;
                    } else {
                        $activation->setIsActivated(1);
                        $activation->save();

                        $this->user = $activation->getUser();
                        $this->result = self::RESULT_SUCCESS;
                    }
                }
            } else {
                $this->result = self::RESULT_INVALID;
            }
        }

        return $this->renderResponse();
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }
}