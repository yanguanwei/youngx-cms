<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Controller;

use Youngx\MVC\Context;

class AjaxController
{
    public function autocompleteAction(Context $context)
    {
        $keyword = trim($context->request()->get('keyword'));
        $count = intval($context->request()->get('count'));

        $companies = array();

        if ($keyword && $count) {
            $db = $context->db();
            $sql = "SELECT id, name FROM zw_company WHERE uid>0 AND name LIKE '%{$db->quoteLike($keyword)}%' LIMIT {$count}";
            foreach ($context->db()->query($sql)->fetchAll() as $data) {
                $companies[] = array(
                    'id' => $data['id'],
                    'name' => $data['name']
                );
            }
        }
        return $context->response(json_encode($companies));
    }
}