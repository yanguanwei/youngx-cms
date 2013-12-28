<?php

namespace Youngx\Bundle\UserBundle\Module\RoleModule\Listener;

use Youngx\Bundle\UserBundle\Form\AccountAdminForm;
use Youngx\Bundle\UserBundle\Module\RoleModule\Service\UserRoleService;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Context;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\User\Identity;
use Youngx\MVC\Widget\FormWidget;

class AccountListener implements Registration
{
    /**
     * @var Context
     */
    private $context;
    /**
     * @var UserRoleService
     */
    private $userRoleService;

    public function __construct(UserRoleService $userRoleService)
    {
        $this->userRoleService = $userRoleService;
    }

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function init(AccountAdminForm $form)
    {
        $form->propertyAccess()->attach('roles');
    }

    public function initGetRequest(AccountAdminForm $form)
    {
        $form->set('roles', $form->getUser() ? array_keys($form->getUser()->getRoles()) : array());
    }

    public function render(RenderableResponse $response)
    {
        $widget = $response->getContent();
        if ($widget instanceof FormWidget) {
            $roles =  $this->userRoleService->getRoles();
            unset($roles[Identity::ROLE_ANONYMOUS], $roles[Identity::ROLE_REGISTERED]);

            $widget->addField('roles')->label('角色')->select(array(
                    '#options' => $roles,
                    '#chosen' => true,
                    'data-placeholder' => '请选择角色',
                    'multiple' => true
                ));
        }

    }

    public function submit(AccountAdminForm $form)
    {
        $roles = (array) $form->get('roles');
        $this->userRoleService->saveRoles($form->getUser()->getId(), $roles);
    }

    public static function registerListeners()
    {
        return array(
            'kernel.form.render#user-admin-account' => 'render',
            'kernel.form.init#user-admin-account' => 'init',
            'kernel.form.get#user-admin-account' => 'initGetRequest',
            'kernel.form.submit#user-admin-account' => 'submit'
        );
    }
}