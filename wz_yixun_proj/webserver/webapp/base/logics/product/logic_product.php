<?php 
/**
 * Logic__product
 * 
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author weidian team <fishxyu@yixun.com> 
 * @license 
 */
class Logic_product extends Bs_logic {
                                
    /**
     * __construct 
     * 
     * @access protected
     * @return mixed
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * 列表页
     *
     * @param mixed $post_data
     * @access public
     * @return mixed
     */
    public function search($data) {
        try{
            $product = $this->model('product/Model_product')->search($data);
            $pagesize = $data['pagesize'];
            $page = $data['page'];
        	if(!empty($product['num'])) {
        	    //查询商品信息
        	    foreach( $product["results"] as $result) {
        	        $tfId = $result["tfId"];
        	        break;
        	    }
        	    $param = array('tfId'=>$tfId);
        	    
        	    $productRes = $this -> model('product/Model_product') -> get_product_by_id($param);
        	    $productRes = unserialize($productRes);
        	    if(empty($productRes)){
        	        $this->log->info('投放id: '.$tfId.', 微站id: '.$wzId.',获取商品信息为空。');
        	        $productRes =array();
        	        $productNum = 0;
        	    }else{
        	        $productNum = count($productRes);
        	        $offset = ($page-1)*$pagesize;
        	        $productRes = array_slice($productRes,$offset,$pagesize);
        	    }
        	} else {
        	    $productRes =array();
        	    $productNum = 0;
        	}
        	//js，css版本等
        	$versionTimeStamp = $this->config->item('versionTimeStamp');
        	if(empty($versionTimeStamp)){
        	    $versionTimeStamp = '';
        	}
        	
        }catch(Exception $e){
            throw $e;
        }
        $result['data'] = array('product'=>$productRes, 'versionTimeStamp'=>$versionTimeStamp,'productNum'=>$productNum,);
        
        return $result;
    }
    
    /**
     * 新增
     *
     * @param mixed $post_data
     * @access public
     * @return mixed
     */
    public function create() {
        $c['product'] = $this->model('product/Model_product')->create();
         
        return $c;
    }
    
    /**
     * 插入或者保存 
     * 
     * @param mixed $post_data 
     * @access public
     * @return mixed
     */
    public function save($post_data) {
        $this->_adjust($post_data);
        return $this->model('product/Model_product') ->save($post_data);
    }

    /**
     * 查看详情
     * 
     * @param mixed $id 
     * @access public
     * @return mixed
     */
    public  function detail($id) {
        $c = array();
        $c['product'] = $this->model('product/Model_product')->fetch_one_by_id($id);
        if (empty($c['product']) ){
            throw new Exception(sprintf('%s: The product id %d does not exist or has been deleted.',
                        __FUNCTION__, $id), $this->config->item('data_exist_err_no', 'err_no'));  
        } 
                
        return $c; 
    }
    
    /**
     * 编辑页面
     * 
     * @param mixed $id 
     * @access public
     * @return mixed
     */
    public function edit($id) {
        $c = array();
        $c['product'] = $this->model('product/Model_product')->fetch_one_by_id($id);
        if (empty($c['product']) ){
            throw new Exception(sprintf('%s: The product id %d does not exist or has been deleted.',
                        __FUNCTION__, $id), $this->config->item('data_exist_err_no', 'err_no'));  
        } 
                
        return $c; 
    }
    
    /**
     * 修改少量字段
     *
     * @param mixed $post_data
     * @access public
     * @return mixed
     */
    public function set($param = array()) {
        $this->_adjust($post_data);
        return $this->model('product/Model_product')->update_by_id($post_data,$post_data['id']);
    }
    
    /**
     * 删除（真删）
     * id 要删除的主键ID
     * $user_id > 0 判断删除的item的创建用户的所属，否则直接删除
     */
    public function del($id=0, $user_id=0) {
        return $this->model('product/Model_product') ->delete_one_by_id($id=0, $user_id=0);
    }
    
    /**
     * 自动添加属性，如修改时间，修改用户ID等
     */
    protected function _adjust(&$post_data) {
        //$post_data['createTime'] = time();
        $post_data['updateTime'] = time();
    }
    
    /**
     * 魔术函数 当调用的函数不存在或权限被控制，此方法会自动被调用
     *
     * @param mixed $func
     * @param mixed $args
     * @return mixed
     */
    public function __call($func, $args) {
        return call_user_func_array(array($this->model('product/Model_product'), $func), $args);
    }
}
?>