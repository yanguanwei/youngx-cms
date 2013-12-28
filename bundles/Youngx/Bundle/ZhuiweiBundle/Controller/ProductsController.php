<?php

namespace Youngx\Bundle\ZhuiweiBundle\Controller;

use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductEntity;
use Youngx\MVC\Action;
use Youngx\MVC\RenderableResponse;

class ProductsController extends Action
{
    protected $category_id;
    protected $page = 1;
    protected $keyword;
    protected $pagesize = 16;

    protected $products = array();
    protected $total = 0;

    public function id()
    {
        return 'products';
    }

    public function indexAction()
    {
        return $this->run();
    }

    /**
     * @return \Youngx\Database\Query
     */
    protected function query()
    {
        $db = $this->context->db();
        $select = $db->select(array('zw_index_product', 'zwip'), 'product_id')
            ->innerJoin(array('zw_index_product_status', 'zwips'), 'zwips.product_id=zwip.product_id')
            ->where("zwips.status=?", ProductEntity::STATUS_ON);

        if ($this->category_id) {
            $select->innerJoin(array('zw_index_product_category', 'zwipc'), 'zwipc.product_id=zwip.product_id');
            $select->where('zwipc.category_id=?', $this->category_id);
        }

        if ($this->keyword) {
            $title = $this->context->db()->quoteLike($this->keyword);
            $select->innerJoin(array('zw_index_product_title', 'zwipt'), 'zwipt.product_id=zwip.product_id')->where("zwipt.title LIKE '%{$title}%'");
        }

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

        $response->setFile('products.html.yui@Zhuiwei')->addVariables(array(
                'products' => $this->products,
                'total' => $this->total,
                'page' => $this->page,
                'pagesize' => $this->pagesize,
                'category' => $this->category_id ? $this->context->repository()->load('category', $this->category_id) : null
            ));
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = intval($category_id);
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
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
     * @param mixed $keyword
     */
    public function setKeyword($keyword)
    {
        $this->keyword = trim($keyword);
    }

    /**
     * @return mixed
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * @param int $pagesize
     */
    public function setPagesize($pagesize)
    {
        $this->pagesize = $pagesize;
    }

    /**
     * @return int
     */
    public function getPagesize()
    {
        return $this->pagesize;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }
}