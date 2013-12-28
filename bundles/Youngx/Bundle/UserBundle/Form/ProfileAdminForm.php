<?php

namespace Youngx\Bundle\UserBundle\Form;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\UserBundle\Entity\UserProfileEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class ProfileAdminForm extends Form
{
    /**
     * @var UserEntity
     */
    protected $user;
    /**
     * @var UserProfileEntity
     */
    protected $profile;

    protected $full_name;
    protected $qq;
    protected $msn;
    protected $skype;
    protected $telephone;

    public function id()
    {
        return 'user-admin-profile';
    }

    protected function fields()
    {
        return array(
            'full_name', 'qq', 'msn', 'skype', 'telephone'
        );
    }

    protected function initRequest()
    {
        if (!$this->user) {
            throw new Form\FormErrorException('未指定用户！');
        }
    }

    protected function submit(GetResponseEvent $event)
    {
        $this->profile->set($this->toArray());
        $this->profile->save();

        $this->context->flash()->add('success', sprintf('用户 <i>%s</i> 的个人资料保存成功！', $this->user->getName()));

        $event->setResponse($this->context->redirectResponse(
                $this->context->request()->getUri()
            ));
    }

    protected function render(RenderableResponse $response)
    {
        if (!$this->profile) {
            $this->profile = $this->user->getProfile();
            if ($this->profile) {
                $this->set($this->profile->toArray());
            }
        }

        $response->setContent($this->context->widget('Form', array(
                    '#form' => $this,
                    '#skin' => 'horizontal'
                )));

        $response->addVariable('#subtitle', array(
                '用户 <i>'.$this->user->getName().'</i>',
                '个人资料'
            ));
    }

    public function renderFormWidget(FormWidget $widget)
    {
        $widget->addField('full_name')->label('姓名')->text();
        $widget->addField('qq')->label('QQ')->text();
        $widget->addField('msn')->label('MSN')->text();
        $widget->addField('skype')->label('Skype')->text();
        $widget->addField('telephone')->label('固定电话')->text();
    }

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $full_name
     */
    public function setFullName($full_name)
    {
        $this->full_name = $full_name;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * @param mixed $msn
     */
    public function setMsn($msn)
    {
        $this->msn = $msn;
    }

    /**
     * @return mixed
     */
    public function getMsn()
    {
        return $this->msn;
    }

    /**
     * @param mixed $qq
     */
    public function setQq($qq)
    {
        $this->qq = $qq;
    }

    /**
     * @return mixed
     */
    public function getQq()
    {
        return $this->qq;
    }

    /**
     * @param mixed $skype
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
    }

    /**
     * @return mixed
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param UserEntity $user
     */
    public function setUser(UserEntity $user)
    {
        $this->user = $user;
        $profile = $this->context->repository()->load('user-profile', $user->getUid());
        if (!$profile) {
            $profile = $this->context->repository()->create('user-profile', array(
                    'uid' => $this->user->getUid()
                ));
        }
        $this->profile = $profile;
        $this->set($profile->toArray());
    }
}