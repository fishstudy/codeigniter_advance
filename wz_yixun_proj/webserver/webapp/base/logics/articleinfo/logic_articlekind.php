<?php 
/**
 * Logic__articlekind
 * 
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author weidian team <fishxyu@yixun.com> 
 * @license 
 */
class Logic_articlekind extends Bs_logic {
    protected $_kindNum = 3;
                                
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
            $articlekind = $this->model('articleinfo/Model_articlekind')
            ->search($data);
        }catch(Exception $e){
            throw $e;
        }
         
        return $articlekind;
    }
    
    /**
     * 列表页
     *
     * @param mixed $post_data
     * @access public
     * @return mixed
     */
    public function masterSearch(&$data) {
        try{
            $articlekind = $this->model('articleinfo/Model_articlekind')
            ->search($data);
            //如果没有赋值为空
            if(empty($articlekind['num'])) {
                $articlekind['num'] = $this->_kindNum;
                $temp = $this->create();
                for($i = 0; $i < $this->_kindNum; $i++) {
                    $articlekind['results'][$i] = $temp['articlekind'];
                }
            }
            
        }catch(Exception $e){
            throw $e;
        }
         
        return $articlekind;
    }
    
    /**
     * 新增
     *
     * @param mixed $post_data
     * @access public
     * @return mixed
     */
    public function create() {
        $c = $this->model('articleinfo/Model_articlekind')->create();
         
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
            if($post_data[$i]['articlekind']['id']<=0){
                $post_data[$i]['articlekind']['createTime'] = time();
            }
            //var_dump($post_data[$i]);
            $ret = $this->model('articleinfo/Model_articlekind') ->save($post_data[$i]);
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
        $c['articlekind'] = $this->model('articleinfo/Model_articlekind')->fetch_one_by_id($id);
        if (empty($c['articlekind']) ){
            throw new Exception(sprintf('%s: The articlekind id %d does not exist or has been deleted.',
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
        $c['articlekind'] = $this->model('articleinfo/Model_articlekind')->fetch_one_by_id($id);
        if (empty($c['articlekind']) ){
            throw new Exception(sprintf('%s: The articlekind id %d does not exist or has been deleted.',
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
        return $this->model('articleinfo/Model_articlekind') ->update_by_id($post_data,$post_data['id']);
    }
    
    /**
     * 删除（真删）
     * id 要删除的主键ID
     * $user_id > 0 判断删除的item的创建用户的所属，否则直接删除
     */
    public function del($id=0, $user_id=0) {
        return $this->model('articleinfo/Model_articlekind') ->delete_one_by_id($id, $user_id);
    }
    
    /**
     * 魔术函数 当调用的函数不存在或权限被控制，此方法会自动被调用
     *
     * @param mixed $func
     * @param mixed $args
    
     * @return mixed
     */
    public function __call($func, $args) {
        return call_user_func_array(array($this->model('articleinfo/Model_articlekind'), $func), $args);
    }
    
    /**
     * 自动添加属性，如修改时间，修改用户ID等
     */
    protected function _adjust(&$post_data) {
        //$post_data['createTime'] = time();
        $post_data['articlekind']['updateTime'] = time();
    }
      
}