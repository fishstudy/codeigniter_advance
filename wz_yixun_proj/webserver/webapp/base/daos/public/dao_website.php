<?php 
/**
 * Dao_website
 * 
 * @uses Wd
 * @uses _Dao
 * @package 
 * @version $id$
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author fishxyu <fishxyu@yixun.com> 
 * @license 
 */
class Dao_website extends Bs_dao {
    /**
     * _table 
     * 
     * @var mixed
     * @access protected
     */
    protected $_table;
    /**
     * _fields 
     * 
     * @var string
     * @access protected
     */
    protected $_insert_fields = array('webMasterId', 'bgPicture', 'articleList', 'productList', 'actList', 'createTime', 'createUser', 'updateTime', 'updateUser', 'conditions', 'articleModel', 'productModel', 'actModel', 'advModel');
    protected $_update_fields = array('webMasterId', 'bgPicture', 'articleList', 'productList', 'actList', 'createUser', 'updateTime', 'updateUser', 'conditions', 'articleModel', 'productModel', 'actModel', 'advModel', 'recommend', 'actLogo', 'actPic', 'actTitle', 'actCoupon');
    protected $_select_fields = array('id', 'webMasterId', 'bgPicture', 'articleList', 'productList', 'actList', 'createTime', 'createUser', 'updateTime', 'updateUser', 'conditions', 'articleModel', 'productModel', 'actModel', 'advModel','recommend', 'actLogo', 'actPic', 'actTitle', 'actCoupon');
    protected $_primary_key = 'id';
    protected $_unique_fields = array('id');
    protected $_index_fields = array();
    protected $_dao = '';
    protected $_auto_increment  = TRUE;
    /**
     * __construct 
     * 
     * @access protected
     * @return mixed
     */
    public function __construct($param=array()) {
        parent::__construct($param['active_group']);
        $this->_table = $this->get_table_name(array('virtual_table'=>__CLASS__, 'id'=>$param['id']));
        $this->_dao = substr(__CLASS__, 4);
    }
    
    /**
     * search 
     * 
     * @param array $param 
     * @param int $page 
     * @param int $pagesize 
     * @access public
     * @return mixed
     */
    function search($param = array()) {
        $res = array('num'=>0, 'results'=>array());
        $equal_search_items = array('id'=>'t','conditions'=>'t','webMasterId'=>'t');
        $like_search_items  = array();
        $like2_search_items = array();

        foreach ($param as $column=>$v) {
            if (!isset($equal_search_items[$this->_remove_operator($column)])) continue;
            $this->db->where($column, $v);
        }
        foreach ($like_search_items as $column=>$t) {
            if (!isset($param[$column])) continue;
            $this->db->like($column, $param[$column]);
        }
        isset($param['group_by']) ? $this->db->group_by($param['group_by']) : '';
        isset($param['distinct']) ? $this->db->distinct($param['distinct']) : '';
        $res['num'] = $this->db->count_all_results($this->_table);
        if ($res['num'] > 0) {
            $res['results'] = array();
            foreach ($param as $column=>$v) {
                if (!isset($equal_search_items[$this->_remove_operator($column)])) continue;
                $this->db->where($column, $v);
            }
            foreach($like_search_items as $column=>$t) {
                if (!isset($param[$column])) continue;
                $this->db->like($column, $param[$column]);
            }
            if (isset($param['page']) && $param['page'] >0 && isset($param['pagesize']) && $param['pagesize']>0) {
                $page       = $param['page'];
                $pagesize   = $param['pagesize'];
                $start      = (min(max(ceil($res['num']*1.0/$pagesize), 1), $page)-1)*$pagesize;
                $this->db->limit($pagesize, $start);
            }

            isset($param['ordersort']) ? $this->db->order_by($param['ordersort']) : '';
            isset($param['group_by']) ? $this->db->group_by($param['group_by']) : '';
            isset($param['select']) ? $this->db->select(sprintf('%s', $param['select'])) :  $this->db->select($this->_primary_key);

            $q = $this->get($this->_table);
            foreach ($q->result_array() as $result) {
                $res['results'][$result[$this->_primary_key]] = $result;
            }
        }
        return $res;
    }

}
?>