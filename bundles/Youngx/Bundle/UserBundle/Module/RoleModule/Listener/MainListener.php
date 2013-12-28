<?php

namespace Youngx\Bundle\UserBundle\Module\RoleModule\Listener;

use Youngx\EventHandler\Event\GetValueEvent;
use Youngx\MVC\Context;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Request;
use Youngx\MVC\User\Identity;
use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\EventHandler\Registration;

class MainListener implements Registration
{
    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function roles(UserEntity $user)
    {
        if ($user->getUid()) {
            $roles = $this->context->get('user.role')->getUserRoles($user->getId());
        } else {
            $roles = array();
        }
        return $roles;
    }

    public function checkAccessForPermission($event, $request, $access)
    {
        $identity = $this->context->identity();
        if ($identity->getId() == 1 || $identity->hasRole(Identity::ROLE_ADMINISTRATOR)) {
            return true;
        }

        if ($access) {
            return $this->context->permit($access);
        }

        return false;
    }

    public function permit(array $roles, $permission)
    {
        if ($roles) {
            if (isset($roles[Identity::ROLE_ADMINISTRATOR])) {
                return true;
            }
            return $this->context->get('user.role')->permit($roles, $permission);
        } else {
            return false;
        }
    }

    public static function registerListeners()
    {
        return array(
            'kernel.access' => 'checkAccessForPermission',
            'kernel.entity#user.field.roles' => 'roles',
            'kernel.permit' => 'permit'
        );
    }
}