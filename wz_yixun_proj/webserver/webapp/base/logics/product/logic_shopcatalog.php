<?php 
/**
 * Logic__shopcatalog
 * 
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author weidian team <fishxyu@yixun.com> 
 * @license 
 */
class Logic_shopcatalog extends Bs_logic {
                                
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
     * 站长读取4个列表
     */
    public function wzSearch(&$data) {
        try {
            $shopcatalog = $this->search($data);
            //没有创建4条空数据
            $shopcatalog['results'] = array_values($shopcatalog['results']);
            //var_dump($shopcatalog);
            $tmp = $this->create();
            for($i=0;$i<4;$i++){
            if (empty($shopcatalog['results'][$i])) {
                //var_dump($tmp);
                $shopcatalog['results'][$i] = $tmp['shopcatalog'];
            }
            }
            $shopcatalog['num'] = 4;
        }
        catch (Exception $e) {
            throw $e;
        }
        return $shopcatalog;
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
            $shopcatalog = $this->model('product/Model_shopcatalog')
            ->search($data);
        }catch(Exception $e){
            throw $e;
        }
         
        return $shopcatalog;
    }
    
    /**
     * 新增
     *
     * @param mixed $post_data
     * @access public
     * @return mixed
     */
    public function create() {
        $c = $this->model('product/Model_shopcatalog')->create();
         
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
        for($i=0;$i<count($post_data);$i++){
            $this->_adjust($post_data[$i]);
            //var_dump($post_data[$i]);
            //添加创建时间
            if($post_data[$i]['shopcatalog']['id']<=0){
                $post_data[$i]['shopcatalog']['createTime'] = time();
            }
            //var_dump($post_data[$i]);
            $ret = $this->model('product/Model_shopcatalog') ->save($post_data[$i]);
            //var_dump($ret);
        }
        return $ret;
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
        $c['shopcatalog'] = $this->model('product/Model_shopcatalog')->fetch_one_by_id($id);
        if (empty($c['shopcatalog']) ){
            throw new Exception(sprintf('%s: The shopcatalog id %d does not exist or has been deleted.',
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
        $c['shopcatalog'] = $this->model('product/Model_shopcatalog')->fetch_one_by_id($id);
        if (empty($c['shopcatalog']) ){
            throw new Exception(sprintf('%s: The shopcatalog id %d does not exist or has been deleted.',
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
        return $this->model('product/Model_shopcatalog') ->update_by_id($post_data,$post_data['id']);
    }
    
    /**
     * 删除（真删）
     * id 要删除的主键ID
     * $user_id > 0 判断删除的item的创建用户的所属，否则直接删除
     */
    public function del($id=0, $user_id=0) {
        return $this->model('product/Model_shopcatalog') ->delete_one_by_id($id=0, $user_id=0);
    }
    
    /**
     * 魔术函数 当调用的函数不存在或权限被控制，此方法会自动被调用
     *
     * @param mixed $func
     * @param mixed $args
    
     * @return mixed
     */
    public function __call($func, $args) {
        return call_user_func_array(array($this->model('product/Model_shopcatalog'), $func), $args);
    }
    
    /**
     * 自动添加属性，如修改时间，修改用户ID等
     */
    protected function _adjust(&$post_data) {
        //$post_data['shopcatalog']['createTime'] = time();
        $post_data['shopcatalog']['updateTime'] = time();
    }
      
}