<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Form;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\FactoryEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class FactoryAdminForm extends Form
{
    /**
     * @var UserEntity
     */
    protected $user;
    /**
     * @var FactoryEntity
     */
    protected $factory;
    protected $status;
    protected $name;
    protected $industries;
    protected $telephone;
    protected $mobilephone;
    protected $district_id = 0;
    protected $address;
    protected $corporation;
    protected $description;
    protected $established_at;
    protected $area;
    protected $capacity;
    protected $payments;

    public function id()
    {
        return 'factory-admin';
    }

    protected function fields()
    {
        return array(
            'name', 'status', 'industries', 'telephone', 'mobilephone', 'district_id',
            'address', 'corporation', 'description',
            'established_at', 'area', 'capacity', 'payments'
        );
    }

    protected function registerValidators()
    {
        return array(
            'name' => array(
                'required' => '工厂名称不能为空'
            )
        );
    }

    protected function submit(GetResponseEvent $event)
    {
        $factory = $this->factory ?: $this->context->repository()->create('factory');
        $factory->set('uid', $this->user ? $this->user->getUid() : 0);
        $factory->set($this->toArray());
        $factory->save();

        $this->factory = $factory;

        $this->context->flash()->add('success', sprintf('工厂 <i>%s</i> 信息保存成功！', $factory->getName()));

        $event->setResponse($this->context->redirectResponse(
                $this->context->request()->getUri()
            ));
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($form = $this->context->widget('Form', array(
                    '#form' => $this,
                    '#skin' => 'vertical',
                    'cancel' => $this->context->generateUrl('factory-admin'),
                )))->addVariable('#subtitle', $this->user ? array(
                    '卖家 <i>' .  $this->user->getName() . '</i>', '工厂信息'
                ) : array('基础工厂', '工厂信息'));

        $form->render('admin/factory.html.yui@Zhuiwei:Seller');
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $corporation
     */
    public function setCorporation($corporation)
    {
        $this->corporation = $corporation;
    }

    /**
     * @return mixed
     */
    public function getCorporation()
    {
        return $this->corporation;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $district_id
     */
    public function setDistrictId($district_id)
    {
        $this->district_id = $district_id;
    }

    /**
     * @return mixed
     */
    public function getDistrictId()
    {
        return $this->district_id;
    }

    /**
     * @param mixed $industries
     */
    public function setIndustries($industries)
    {
        $this->industries = $industries;
    }

    /**
     * @return mixed
     */
    public function getIndustries()
    {
        return $this->industries;
    }

    /**
     * @param mixed $mobilephone
     */
    public function setMobilephone($mobilephone)
    {
        $this->mobilephone = $mobilephone;
    }

    /**
     * @return mixed
     */
    public function getMobilephone()
    {
        return $this->mobilephone;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
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
    }

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $area
     */
    public function setArea($area)
    {
        $this->area = $area;
    }

    /**
     * @return mixed
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param mixed $capacity
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
    }

    /**
     * @return mixed
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * @param mixed $established_at
     */
    public function setEstablishedAt($established_at)
    {
        if (is_numeric($established_at)) {
            $this->established_at = intval($established_at);
        } else {
            $this->established_at = strtotime($established_at);
        }
    }

    /**
     * @return mixed
     */
    public function getEstablishedAt()
    {
        return $this->established_at ? date('Y-m-d', $this->established_at) : '';
    }

    public function setFactory(FactoryEntity $factory)
    {
        $this->factory = $factory;
        $this->set($factory->toArray());
        $user = $factory->getUser();
        if ($user) {
            $this->setUser($user);
        }
    }

    /**
     * @return FactoryEntity
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @param mixed $payments
     */
    public function setPayments($payments)
    {
        $this->payments = $payments;
    }

    /**
     * @return mixed
     */
    public function getPayments()
    {
        return $this->payments;
    }
}