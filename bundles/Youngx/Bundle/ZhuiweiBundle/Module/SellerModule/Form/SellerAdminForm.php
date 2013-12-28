<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Form;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\SellerEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class SellerAdminForm extends Form
{
    /**
     * @var UserEntity
     */
    protected $user;
    /**
     * @var
     */
    protected $seller;
    protected $status = 0;
    protected $type;
    protected $started_at;
    protected $ended_at;
    protected $logo;

    public function id()
    {
        return 'seller-vip-admin';
    }

    protected function fields()
    {
        return array(
            'status', 'type', 'started_at', 'ended_at'
        );
    }

    protected function submit(GetResponseEvent $event)
    {
        $seller = $this->seller ?: $this->context->repository()->create('seller', array(
                'uid' => $this->user->getUid()
            ));

        $seller->set($this->toArray());
        $seller->save();

        $this->seller = $seller;

        $this->context->flash()->add('success', sprintf('卖家 <i>%s</i> 的信息保存成功！', $this->user->getName()));

        $event->setResponse($this->context->redirectResponse($this->context->generateUrl('seller-admin-vip')));
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Form', array(
                    '#form' => $this,
                    '#skin' => 'horizontal',
                    'cancel' => $this->context->generateUrl('seller-admin-vip')
                )))->addVariable('#subtitle', array(
                    sprintf('卖家 <i>%s</i>', $this->user->getName()),
                    '卖家信息'
                ));
    }

    public function renderFormWidget(FormWidget $widget)
    {
        $widget->addField('status')->label('状态')->radio(array(
                '#options' => array('未付款', '已付款')
            ));

        $widget->addField('type')->label('VIP类型')->select(array(
                '#options' => array(0 => '无') + (array) $this->context->value('seller-vip-types')
            ));

        $widget->addField('started_at')->label('起始日期')->datepicker();
        $widget->addField('ended_at')->label('截止日期')->datepicker();

        $widget->addField('logo')->label('Logo')->image_uploader(array(
                '#url' => $this->context->generateUrl('seller-logo-upload', array(
                        'user' => $this->user->getUid()
                    ))
            ));
    }

    /**
     * @param UserEntity $user
     */
    public function setUser(UserEntity $user)
    {
        $this->user = $user;
        $seller = $this->context->repository()->load('seller', $user->getUid());
        if ($seller && $seller instanceof SellerEntity) {
            $this->setSeller($seller);
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
     * @param mixed $ended_at
     */
    public function setEndedAt($ended_at)
    {
        if (is_numeric($ended_at)) {
            $this->ended_at = intval($ended_at);
        } else {
            $this->ended_at = strtotime($ended_at);
        }
    }

    /**
     * @return mixed
     */
    public function getEndedAt()
    {
        return $this->ended_at ? date('Y-m-d', $this->ended_at) : '';
    }

    /**
     * @param mixed $started_at
     */
    public function setStartedAt($started_at)
    {
        if (is_numeric($started_at)) {
            $this->started_at = intval($started_at);
        } else {
            $this->started_at = strtotime($started_at);
        }
    }

    /**
     * @return mixed
     */
    public function getStartedAt()
    {
        return $this->started_at ? date('Y-m-d', $this->started_at) : '';
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
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
     * @param SellerEntity $seller
     */
    public function setSeller(SellerEntity $seller)
    {
        $this->seller = $seller;
        $this->set($seller->toArray());
    }

    /**
     * @return SellerEntity
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }
}