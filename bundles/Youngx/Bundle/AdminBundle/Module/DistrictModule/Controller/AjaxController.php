<?php

namespace Youngx\Bundle\AdminBundle\Module\DistrictModule\Controller;

use Youngx\MVC\Context;

class AjaxController
{
    public function cxselectAction(Context $context)
    {
        $key = "entity.district.cxselect";
        if (false === ($json = $context->cache()->fetch($key))) {
            $sql = "SELECT id, label, parent_id FROM y_district ORDER BY sort_num ASC, id ASC";
            $data = $json = $parents  = array();
            foreach ($context->db()->query($sql)->fetchAll() as $row) {
                $parents[$row['parent_id']][] = $row['id'];
                $data[$row['id']] = $row;
            }
            $this->parseDistrictData($json, $data, $parents);
            $json = json_encode($json);
            $context->cache()->save($key, $json);
        }

        return $context->response($json ?: '');
    }

    protected function parseDistrictData(array &$json, array &$data, array &$parents, $currentId = 0)
    {
        if (isset($parents[$currentId])) {
            foreach ($parents[$currentId] as $id) {
                $row = array(
                    'n' => $data[$id]['label'],
                    'v' => $data[$id]['id']
                );
                if (isset($parents[$id])) {
                    $row['s'] = array();
                    $this->parseDistrictData($row['s'], $data, $parents, $id);
                }
                $json[] = $row;
            }
        }
    }
}