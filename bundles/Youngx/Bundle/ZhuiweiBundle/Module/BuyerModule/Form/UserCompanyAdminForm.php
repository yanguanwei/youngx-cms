<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Form;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Entity\CompanyEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class UserCompanyAdminForm extends Form
{
    /**
     * @var UserEntity
     */
    protected $user;
    /**
     * @var CompanyEntity
     */
    protected $company;
    protected $company_id;

    public function id()
    {
        return 'buyer-admin-user-company';
    }

    protected function registerValidators()
    {
        return array(
            'company_id' => array(
                'required' => '请选择所属机构'
            )
        );
    }

    protected function validate(Form\FormErrorHandler $feh)
    {
        $company = $this->context->repository()->load('company', $this->company_id);
        if (!$company || $company->get('uid') == 0) {
            $feh->add('company_id', '无效的公司名称！');
        } else {
            $this->company = $company;
        }
    }

    protected function submit(GetResponseEvent $event)
    {
        $this->user->setCompanyId($this->company->getId());
        $this->user->save();

        $this->context->flash()->add('success', sprintf('成功保存买家 <i>%s</i> 所属公司为 <i>%s</i>', $this->user->getName(), $this->company->getName()));

        $event->setResponse($this->context->redirectResponse($this->context->request()->getUri()));
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Form', array(
                    '#form' => $this,
                    '#skin' => 'horizontal'
                )))->addVariable('#subtitle', '买家所属机构');
    }

    public function renderFormWidget(FormWidget $widget)
    {
        $widget->addField('company_id')->label('所属机构')->select_company();
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
     * @param CompanyEntity $company
     */
    public function setCompany(CompanyEntity $company)
    {
        $this->company = $company;
    }

    /**
     * @return CompanyEntity
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company_id
     */
    public function setCompanyId($company_id)
    {
        $this->company_id = $company_id;
    }

    /**
     * @return mixed
     */
    public function getCompanyId()
    {
        return $this->company_id;
    }
}