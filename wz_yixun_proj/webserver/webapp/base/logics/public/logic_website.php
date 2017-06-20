<?php 
/**
 * Logic__website
 * 
 * @uses Wd
 * @uses _Logic
 * @package 
 * @version $id$
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author fishxyu <fishxyu@yixun.com> 
 * @license 
 */
class Logic_website extends Bs_logic {
                                
    /**
     * __construct 
     * 
     * @access protected
     * @return mixed
     */
    function __construct() {
        parent::__construct();
    }
    
    /**
     * __call 
     * 
     * @param mixed $func 
     * @param mixed $args 
     * @access protected
     * @return mixed
     */
    function __call($func, $args) {
        return call_user_func_array(array($this->model('public/Model_website'), $func), $args);
    }
    
    /**
     * save 
     * 
     * @param mixed $post_data 
     * @access public
     * @return mixed
     */
    function save($post_data) {
        $this->_adjust($post_data);
        return $this->model('public/Model_website')->save($post_data);
    }
    
    /**
     * 热销活动-基本设置保存
     *
     * @param mixed $post_data
     * @access public
     * @return mixed
     */
    function savebasic($post_data) {
    	try {
    		$insertdata = array('wzid', 'actLogo','actPic','actTitle','actCoupon');//规定要保存的字段
    		if (is_array($post_data) && !empty($post_data)) {
    			foreach ($post_data as $pk=>$pv){
    				if (!in_array($pk, $insertdata)) {
    					unset($post_data[$pk]);
    				}
    			}
    		}
    		$wzid = $post_data['wzid'];//微站id
    		if ($post_data['actLogo'] =='') {
    			throw new Exception('微站logo不能为空', $this->config->item('wzlogo_exist_err_no', 'err_no'));
    		}
    		if ($post_data['actPic'] =='') {
    			throw new Exception('微站背景图不能为空', $this->config->item('wzpic_exist_err_no', 'err_no'));
    		}
    		$actdata = array();
    		$actdata['actLogo'] 	= $post_data['actLogo'];			//基本设置-logo
    		$actdata['actPic'] 		= $post_data['actPic'];				//基本设置-背景图
    		$actdata['actTitle']  	= $post_data['actTitle'];			//基本设置-标题
    		$actdata['actCoupon'] 	= intval($post_data['actCoupon']);	//基本设置-是否设置优惠券：1是2否(默认)
    		$update_wzid = $this->model('public/Model_website')->update_by_id($actdata, $wzid);
    		if ( $update_wzid > 0 ) {
    			return true;
    		}else
    			return false;
    	}catch (Exception $e){
    		throw $e;
    	}
    }
    
    /**
     * 
     * @param unknown $post_data
     */
    protected function _adjust(&$post_data) {
        //$post_data['createTime'] = time();
    	
        $post_data['website']['updateTime'] = time();
    }
    
    /**
     * create 
     * 
     * @param mixed $post_data 
     * @access public
     * @return mixed
     */
    function create() {
        $c['website'] = $this->new_one();
   
        return $c;
    }
    
    /**
     * search 
     * 
     * @param mixed $post_data 
     * @access public
     * @return mixed
     */
    function search($data,$page=0,$pagesize=30) {
        $page = empty($data['page']) ? 1 : $data['page'];
        $pagesize = empty($data['pagesize']) ? $this->config->item('pagesize') : $data['pagesize'];
        try{
            $website = $this->model('public/Model_website')
                ->search($data,$page,$pagesize);
        }catch(Exception $e){
            throw $e;
        }
   
        return $website;
    }
    
    /**
     * detail 
     * 
     * @param mixed $id 
     * @access public
     * @return mixed
     */
    function detail($id) {
        $c = array();
        $c['website'] = $this->model('public/Model_website')->fetch_one_by_id($id);
        if (empty($c['website']) ){
            throw new Exception(sprintf('%s: The website id %d does not exist or has been deleted.',
                        __FUNCTION__, $id), $this->config->item('data_exist_err_no', 'err_no'));  
        } 
                
        return $c; 
    }
    
    /**
     * edit 
     * 修改微站属性表
     * @param mixed $id 
     * @access public
     * @return mixed
     */
    function edit($id) {
        $c = array();
        $c['website'] = $this->model('public/Model_website')->fetch_one_by_id($id);
        if (empty($c['website']) ){
            throw new Exception(sprintf('%s: The website id %d does not exist or has been deleted.',
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
        $id = $post_data['id'];
        unset($post_data['id']);
        return $this->model('public/Model_website') ->update_by_id($post_data['website'],$id);
    }
}
?>