<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Form;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Entity\BuyerEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Entity\CompanyEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;

class CompanyAdminForm extends Form
{
    /**
     * @var UserEntity
     */
    protected $user;
    /**
     * @var CompanyEntity
     */
    protected $company;
    protected $uid = 0;
    protected $type;
    protected $name;
    protected $industries;
    protected $telephone;
    protected $mobilephone;
    protected $district_id = 0;
    protected $address;
    protected $corporation;
    protected $description;

    public function id()
    {
        return 'company-admin';
    }

    protected function fields()
    {
        return array(
            'name', 'uid', 'type', 'industries', 'telephone', 'mobilephone', 'district_id',
            'address', 'corporation', 'description',
        );
    }

    protected function registerValidators()
    {
        return array(
            'name' => array(
                'required' => '公司名称不能为空'
            )
        );
    }

    protected function submit(GetResponseEvent $event)
    {
        $company = $this->company ?: $this->context->repository()->create('company');
        $company->set($this->toArray());
        $company->save();

        if ($this->user && $this->user->getUid() !== $this->uid) {
            $buyer = $this->context->repository()->load('buyer', $this->user->getUid());
            if ($buyer && $buyer instanceof BuyerEntity) {
                $buyer->setType(BuyerEntity::TYPE_STAFF);
            }
        }

        $this->company = $company;

        $this->context->flash()->add('success', sprintf('公司 <i>%s</i> 信息保存成功！', $company->getName()));

        $event->setResponse($this->context->redirectResponse(
                $this->context->request()->getUri()
            ));
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($form = $this->context->widget('Form', array(
                    '#form' => $this,
                    '#skin' => 'vertical',
                    'cancel' => $this->context->generateUrl('company-admin')
                )))->addVariable('#subtitle', $this->user ? array(
                    '买家 <i>' .  $this->user->getName() . '</i>', '公司信息'
                ) : array('基础公司', '公司信息'));

        $form->render('admin/company.html.yui@Zhuiwei:Buyer');
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
        $this->setUid($user->getUid());
    }

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->user;
    }


    public function setCompany(CompanyEntity $company)
    {
        $this->company = $company;
        $this->set($company->toArray());
        $user = $company->getUser();
        if ($user) {
            $this->setUser($user);
        }
    }

    /**
     * @return CompanyEntity
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }
}