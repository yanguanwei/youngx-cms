<?php

namespace Youngx\Bundle\UserBundle\Module\RoleModule\Service;

use Youngx\MVC\Context;

class UserRoleService
{
    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function getRoles()
    {
        $roles = array();
        foreach ($this->context->db()->query("SELECT id, label FROM y_role ORDER BY id ASC") as $role) {
            $roles[$role['id']] = $role['label'];
        }
        return $roles;
    }

    public function getUserRoles($uid)
    {
        $roles = array();
        foreach ($this->context->db()->query("SELECT r.id, r.label FROM y_user_roles ur LEFT JOIN y_role r ON ur.role_id=r.id WHERE ur.uid=:uid ORDER BY ur.role_id ASC", array(
                ':uid' => $uid
            ))->fetchAll() as $row) {
            $roles[$row['id']] = $row['label'];
        }
        return $roles;
    }

    public function permit($roles, $permission)
    {
        $roles = "'" . implode("', '", array_keys((array)$roles)) . "'";
        return (Boolean) $this->context->db()->query("SELECT role_id FROM y_role_permissions WHERE role_id IN ({$roles}) AND permission=:permission LIMIT 1", array(
                ':permission' => $permission
            ))->fetchColumn(0);
    }

    public function saveRoles($uid, array $roles)
    {
        $this->context->db()->exec('DELETE FROM y_user_roles WHERE uid=:uid', array(':uid' => $uid));
        $data = array();
        foreach ($roles as $role_id) {
            $data[] = array(
                'uid' => $uid,
                'role_id' => $role_id
            );
        }
        if ($data) {
            $this->context->db()->insertMultiple('y_user_roles', $data);
        }
    }
}