<?php

namespace Youngx\Bundle\UserBundle\Listener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\UserBundle\Input\SelectUserInput;
use Youngx\EventHandler\Event\GetValueEvent;
use Youngx\MVC\Context;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Event\GetResponseForRoutingEvent;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Exception\HttpException;
use Youngx\MVC\User\Identity;

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

    public function accessForUserLogin()
    {
        if ($this->context->identity()->isLogged()) {
            return false;
        }
        return true;
    }

    public function accessForUserLogout()
    {
        if (!$this->context->identity()->isLogged()) {
            return false;
        }
        return true;
    }


    public function accessForUserRegister()
    {
        if ($this->context->identity()->isLogged()) {
            return false;
        }
        return true;
    }

    public function accessForRegistered()
    {
        if ($this->context->identity()->isLogged()) {
            return true;
        } else {
            return false;
        }
    }

    public function redirectUserLoginResponse(GetResponseEvent $event)
    {
        if (!$this->context->identity()->isLogged()) {
            $event->setResponse($this->context->redirectResponse($this->context->generateUrl('user-login', array(
                            'returnUrl' => $this->context->request()->getUri()
                        ))));
        }
    }

    public function selectUserInput(array $attributes)
    {
        $text = new SelectUserInput($this->context, array_merge(array(
            'placeholder' => '请输入用户名'
        ), $attributes));

        $hidden = $text->getHiddenHtml();

        $this->context->assets()->registerPackage('bootstrap.autocomplete');
        $url = $this->context->generateUrl('user-ajax-autocomplete');

        $code = <<<code
$('#{$text->getId()}').autocomplete({
		source:function(query,process){
			var matchCount = this.options.items;
			$.getJSON("{$url}",{"keyword":query,"count":matchCount},function(respData){
				return process(respData);
			});
		},
		formatItem:function(item){
			return item["name"] + " (" + item["id"] + ")";
		},
		setValue:function(item, i){
		    if (i == 0) {
		        $('#{$hidden->getId()}').val(item["id"]);
		     }
			return {'data-value':item["name"] + " (" + item["id"] + ")",'real-value':item["id"]};
		},
		updater: function(item, realVal) {
		    $('#{$hidden->getId()}').val(realVal);
		    return item;
		}
	});
$('#{$text->getId()}').change(function() {
    if ($(this).val() == '') {
        $('#{$hidden->getId()}').val(0);
    }
});
code;
        $this->context->assets()->registerScriptCode($text->getId(), $code);

        return $text;
    }

    public function getRoleLabels()
    {
        $roles = array();
        foreach ($this->context->db()->query("SELECT id, label FROM y_role ORDER BY id ASC") as $role) {
            $roles[$role['id']] = $role['label'];
        }
        return $roles;
    }

    public function checkAccess()
    {
        if ($this->context->identity()->getId() == 1) {
            return true;
        }
    }

    public function deleteUserEntity(UserEntity $user)
    {
        $user->getProfile()->delete();
        $this->context->db()->exec('DELETE FROM y_user_roles WHERE uid=:uid', array(':uid' => $user->getUid()));
    }

    public function insertUserEntity(UserEntity $user)
    {
        $this->updateUserRoles($user);
    }

    public function updateUserEntity(UserEntity $user)
    {
        $unChangedRoles = $user->unchangedEntity()->getRoles();
        $shouldUpdate = false;
        foreach ($user->getRoles() as $role_id) {
            if (false === (array_search($role_id, $unChangedRoles))) {
                $shouldUpdate = true;
                break;
            }
        }

        if ($shouldUpdate) {
            $this->updateUserRoles($user);
        }
    }

    protected function updateUserRoles(UserEntity $user)
    {
        $db = $this->context->db();
        $db->exec('DELETE FROM y_user_roles WHERE uid=:uid', array(':uid' => $user->getUid()));
        $data = array();
        foreach ($user->getRoles() as $role_id) {
            $data[] = array(
                'uid' => $user->getUid(),
                'role_id' => $role_id
            );
        }
        if ($data) {
            $db->insertMultiple('y_user_roles', $data);
        }
    }

    public static function registerListeners()
    {
        return array(
            'kernel.access' => 'checkAccess',
            'kernel.access#user-login' => 'accessForUserLogin',
            'kernel.access#user-logout' => 'accessForUserLogout',
            'kernel.access#user-register' => 'accessForUserRegister',
            'kernel.access#registered' => 'accessForRegistered',
            'kernel.access.deny#user-logout' => 'redirectUserLoginResponse',
            'kernel.access.deny' => 'redirectUserLoginResponse',
            'kernel.input#select-user' => 'selectUserInput',
            'kernel.value#role-labels' => 'getRoleLabels',
            'kernel.entity.delete#user' => 'deleteUserEntity',
            'kernel.entity.beforeUpdate#user' => 'updateUserEntity',
            'kernel.entity.insert#user' => 'insertUserEntity'
        );
    }
}