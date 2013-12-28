<?php

namespace Youngx\Bundle\UserBundle\Module\RoleModule\Form;

use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\User\Identity;
use Youngx\MVC\User\PermissionCollection;

class PermissionAdminForm extends Form
{
    protected $rolePermissions = array();

    public function setRolePermissions(array $rolePermissions)
    {
        $this->rolePermissions = $rolePermissions;
    }

    public function getRolePermissions()
    {
        return $this->rolePermissions ?: array();
    }

    protected function initGetRequest()
    {
        $rolePermissions = array();
        foreach ($this->context->db()->query("SELECT role_id, permission FROM y_role_permissions")->fetchAll() as $row) {
            $rolePermissions[$row['role_id']][] = $row['permission'];
        }
        $this->rolePermissions = $rolePermissions;
    }

    protected function render(RenderableResponse $response)
    {
        $userRoleService = $this->context->get('user.role');
        $this->context->handler()->trigger('kernel.permission.collect', $collection = new PermissionCollection());
        $roles = $this->context->repository()->loadMultiple('roles', $userRoleService->getRoles());
        unset($roles[Identity::ROLE_ADMINISTRATOR]);

        $response->setFile('admin/permission.html.yui@User:Role')
            ->addVariable('roles', $roles)
            ->addVariable('collection', $collection)
            ->addVariable('form', $this)
            ->addVariable('#subtitle', array('用户', '权限'));
    }

    protected function submit(GetResponseEvent $event)
    {
        $db = $this->context->db();
        $db->delete('y_role_permissions');
        foreach ($this->rolePermissions as $role_id => $permissions) {
            $data = array();
            foreach ($permissions as $permission) {
                $data[] = array(
                    'role_id' => $role_id,
                    'permission' => $permission
                );
            }
            $db->insertMultiple('y_role_permissions', $data);
        }

        $this->context->flash()->add('success', '权限保存成功');

        $event->setResponse($this->context->redirectResponse(
                $this->context->request()->getUri()
            ));
    }

    public function id()
    {
        return 'user-role-permissions';
    }
}