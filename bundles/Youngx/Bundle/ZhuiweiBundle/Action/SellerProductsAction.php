<?php

namespace Youngx\Bundle\ZhuiweiBundle\Action;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\MVC\Action;
use Youngx\MVC\RenderableResponse;

class SellerProductsAction extends Action
{
    /**
     * @var UserEntity
     */
    protected $user;

    protected $page = 1;
    protected $pagesize = 9;

    protected $products = array();
    protected $total = 0;

    protected function initRequest()
    {
        if (!$this->user) {
            $this->user = $this->context->identity()->getUserEntity();
        }
    }

    /**
     * @return \Youngx\Database\Query
     */
    protected function query()
    {
        $db = $this->context->db();
        $select = $db->select(array('zw_index_product', 'zwip'), 'product_id')
            ->leftJoin(array('zw_index_product_user', 'zwipu'), 'zwipu.product_id=zwip.product_id')
            ->where('zwipu.uid=?', $this->user->getUid());

        $sql = (string) $select;
        $table = $this->context->db()->createTemporary("SELECT product_id FROM ({$sql}) t");

        if ($table) {
            $select = $db->select($table, 'product_id')->paging($this->page, $this->pagesize);
            $this->total = intval($db->query($select->toTotalCountSQL())->fetchColumn(0));
            $ids = array();
            foreach ($db->query($select)->fetchAll() as $row) {
                $ids[] = $row['product_id'];
            }
            $this->products = $this->context->repository()->loadMultiple('product', $ids);
        }
    }

    protected function render(RenderableResponse $response)
    {
        $this->query();

        $response->setFile('seller/products.html.yui@Zhuiwei')->addVariables(array(
                'products' => $this->products,
                'total' => $this->total,
                'page' => $this->page,
                'pagesize' => $this->pagesize,
            ))->addVariables(array(
                    '#subtitle' => '产品列表'
                ));
    }

    public function id()
    {
        return 'seller-products';
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = intval($page);
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
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
}