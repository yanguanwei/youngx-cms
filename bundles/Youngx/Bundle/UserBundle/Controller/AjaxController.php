<?php

namespace Youngx\Bundle\UserBundle\Controller;

use Youngx\MVC\Context;

class AjaxController
{
    public function autocompleteAction(Context $context)
    {
        $keyword = trim($context->request()->get('keyword'));
        $count = intval($context->request()->get('count'));

        $users = array();

        if ($keyword && preg_match('/[a-zA-Z0-9_]/', $keyword) && $count) {
            $db = $context->db();
            $sql = "SELECT uid, name FROM y_user WHERE name LIKE '%{$db->quoteLike($keyword)}%' LIMIT {$count}";
            foreach ($context->db()->query($sql)->fetchAll() as $data) {
                $users[] = array(
                    'id' => $data['uid'],
                    'name' => $data['name']
                );
                //$users[] = $data['name'];
            }
        }
        return $context->response(json_encode($users));
    }
}