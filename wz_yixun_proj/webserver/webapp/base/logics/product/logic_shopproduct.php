<?php 
/**
 * Logic__shopproduct
 * 
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author weidian team <fishxyu@yixun.com> 
 * @license 
 */
class Logic_shopproduct extends Bs_logic {
                                
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
    public function search(&$data) {
        try{
            $shopproduct = $this->model('product/Model_shopproduct')
            ->search($data);
            
        }catch(Exception $e){
            throw $e;
        }
         
        return $shopproduct;
    }
     /**
     * 热销商品列表页
     *
     * @param mixed $post_data
     * @access public
     * @return mixed
     */
    public function recommandSearch(&$data) {
        try{
            $shopproduct = $this->search($data);

            //没有创建2条空数据
            if(empty($shopproduct['results'])){
                $tmp = $this->create();
                //var_dump($tmp);
                $shopproduct['results'][0] = $tmp['shopproduct'];

                $shopproduct['results'][1] = $tmp['shopproduct'];
                $shopproduct['num'] = 2 ;
            }
        }catch(Exception $e){
            throw $e;
        }
        //var_export($shopproduct);
        return $shopproduct;
    }   
    /**
     * 新增
     *
     * @param mixed $post_data
     * @access public
     * @return mixed
     */
    public function create() {
        $c = $this->model('product/Model_shopproduct')->create();
         
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
        return $this->model('product/Model_shopproduct') ->save($post_data);
    }
    /**
     *抢购商品插入或者保存 
     * 
     * @param mixed $post_data 
     * @access public
     * @return mixed
     */
    public function qiangSave($post_data) {
        $post_data['shopproduct']['type'] = 2;
        $this->_adjust($post_data);
        return $this->model('product/Model_shopproduct') ->save($post_data);
    }
    /**
     *热销商品插入或者保存 
     * 
     * @param mixed $post_data 
     * @access public
     * @return mixed
     */
    public function hotSave($post_data) {
        $post_data['shopproduct']['type'] = 3;
        $this->_adjust($post_data);
        return $this->model('product/Model_shopproduct') ->save($post_data);
    }    
    /**
     *精选商品插入或者保存 
     * 
     * @param mixed $post_data 
     * @access public
     * @return mixed
     */
    public function selectionSave($post_data) {
        $post_data['shopproduct']['type'] = 4;
        $this->_adjust($post_data);
        return $this->model('product/Model_shopproduct') ->save($post_data);
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
        $c['shopproduct'] = $this->model('product/Model_shopproduct')->fetch_one_by_id($id);
        if (empty($c['shopproduct']) ){
            throw new Exception(sprintf('%s: The shopproduct id %d does not exist or has been deleted.',
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
        $c['shopproduct'] = $this->model('product/Model_shopproduct')->fetch_one_by_id($id);
        if (empty($c['shopproduct']) ){
            throw new Exception(sprintf('%s: The shopproduct id %d does not exist or has been deleted.',
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
    public function set($post_data = array()) {
        $this->_adjust($post_data);
        return $this->model('product/Model_shopproduct') ->update_by_id($post_data,$post_data['id']);
    }
    
    /**
     * 删除（真删）
     * id 要删除的主键ID
     * $user_id > 0 判断删除的item的创建用户的所属，否则直接删除
     */
    public function del($id=0, $user_id=0) {
        //var_dump($id);var_dump($user_id);exit;
        return $this->model('product/Model_shopproduct') ->delete_one_by_id($id, $user_id);
    }
    
    /**
     * 魔术函数 当调用的函数不存在或权限被控制，此方法会自动被调用
     *
     * @param mixed $func
     * @param mixed $args
    
     * @return mixed
     */
    public function __call($func, $args) {
        return call_user_func_array(array($this->model('product/Model_shopproduct'), $func), $args);
    }
    
    /**
     * 自动添加属性，如修改时间，修改用户ID等
     */
    protected function _adjust(&$post_data) {
        //$post_data['createTime'] = time();
        $post_data['shopproduct']['updateTime'] = time();
        $post_data['shopproduct']['id']  = empty($post_data['shopproduct']['id'])   ? 0 : $post_data['shopproduct']['id'];
       $post_data['shopproduct']['scId']  = empty($post_data['shopproduct']['scId'])   ? 0 : $post_data['shopproduct']['scId'];
        $post_data['shopproduct']['title'] =  empty($post_data['shopproduct']['title']) ? '' : $post_data['shopproduct']['title'];
        $post_data['shopproduct']['info'] =  empty($post_data['shopproduct']['info']) ? '' : $post_data['shopproduct']['info'];
        $post_data['shopproduct']['pic'] =  empty($post_data['shopproduct']['pic']) ? '' : $post_data['shopproduct']['pic'];
        if(empty($post_data['shopproduct']['pic'])&& $post_data['shopproduct']['id']){
            unset($post_data['shopproduct']['pic']);
        }
        if(empty($post_data['shopproduct']['id'])){
            $post_data['shopproduct']['createTime'] = time();    
        }
    }
      
}