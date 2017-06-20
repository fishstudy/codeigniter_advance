<?php 
/**
 * Logic__webmaster
 * 
 * @uses Wd
 * @uses _Logic
 * @package 
 * @version $id$
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author fishxyu <fishxyu@yixun.com> 
 * @license 
 */
class Logic_webmaster extends Bs_logic {
                                
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
        return call_user_func_array(array($this->model('public/Model_webmaster'), $func), $args);
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
        return $this->model('public/Model_webmaster')->save($post_data);
    }
    
    protected function _adjust(&$post_data) {
    	$post_data['createTime'] = time();
        $post_data['updateTime'] = time();
    }
    
    /**
     * 微站-建站工具首页概况
     * @access public
     * @return mixed
     */
    function index() {
    	try {
    		$uid = $_COOKIE['uid'];
    		$master = $this->searchMaster($uid);
    		$masterinfo = empty($master['webmasterinfo'])?array():$master['webmasterinfo'];//站长信息
    		
    		if (empty($masterinfo)) {
    			$domain = $this->config->item("basedomain");
    			$url 	= $domain."/webmaster/master";
    			header("location: " . $url);
    			exit;
    		}elseif (is_array($masterinfo) && !empty($masterinfo) ) {
    			if (array_key_exists($masterinfo['conditions'], $this->config->item('master_close'))) {
    				$masterinfo['isclosed'] = true;
    			}else {
    				$masterinfo['isclosed'] = false;
    			}
				if (isset($master['websiteinfo']['actLogo'])) {
					$masterinfo['actLogo'] = $master['websiteinfo']['actLogo'];
				}else {
					$masterinfo['actLogo'] = '';	//默认图片
				}
    			if (array_key_exists($masterinfo['conditions'], $this->config->item('master_novalid'))) {
    				$domain = $this->config->item("basedomain");
	    			$url 	= $domain."/webmaster/master";
		    		header("location: " . $url);
	    			exit;
    			}
    		}
    	}catch (Exception $e) {
    		throw $e;
    	}
    	
    	return $masterinfo;
    }
    /**
     * 申请开通微站页面
     * @access public
     * @return mixed
     */
    function create() {
    	try {
    		$uid = $_COOKIE['uid'];
    		$master = $this->searchMaster($uid);
    		$masterinfo = empty($master['webmasterinfo'])? array():$master['webmasterinfo'];
   			//已开通，跳转我的首页，其他的都可以重新申请
    		if (is_array($masterinfo) && !empty($masterinfo) && 
    			array_key_exists($masterinfo['conditions'], $this->config->item('master_valid'))) {
    			
    			$this->log->info(sprintf('%s:%s ip:%s uid:%s masterinfo-conditions :%s ',  __CLASS__,
    					__FUNCTION__, $this->input->ip_address(), $uid, $masterinfo['conditions'])
    			);
    			$domain = $this->config->item("basedomain");
    			$url 	= $domain."/webmaster/index";
    			header("location: " . $url);
    			exit;
			}elseif (is_array($masterinfo) && !empty($masterinfo) && 
    			array_key_exists($masterinfo['conditions'],$this->config->item('master_invalid'))){
				$domain = $this->config->item("basedomain");
				$url 	= $domain."/webmaster/master";
				header("location: " . $url);
				exit;
			}
    	}catch (Exception $e) {
    		throw $e;
    	}
    	return array();
    }

    /**
     * 根据登陆用户的不同状态来跳转或渲染模板
     * 
     * @access public
     * @return mixed
     */
    function masterStatus() {
    	try {
    		$uid = $_COOKIE['uid'];
    		$master = $this->searchMaster($uid);
    		$masterinfo = empty($master['webmasterinfo'])?array():$master['webmasterinfo'];//站长信息
    		$data = array();
    		if (empty($masterinfo)) {
    			$data['url'] = "webmaster/master";//展示微站首页模板
    		}else if (is_array($masterinfo) && !empty($masterinfo)) {
	    		switch ( $masterinfo['authentication'] ){
	    			case 1:
			    		//1已开通，跳转到我的微站首页
	    				$domain = $this->config->item("basedomain");
	    				$url 	= $domain."/webmaster/index";
	    				header("location: " . $url);
	    				exit;
	    				break;
	    			case 2:
	    				//未认证，渲染不同模板
	    				switch ($masterinfo['conditions']){
	    					case 17://已拒绝
	    						$data['url'] 		= "webmaster/invalidmaster";
	    						$data['rejection']  = $masterinfo['rejection'];
	    						break;
	    					case 18://审核中
	    						$data['url'] 		= "webmaster/checkmaster";
	    						break;
	    					case 19://已关闭
	    						$domain = $this->config->item("basedomain");
			    				$url 	= $domain."/webmaster/index";
			    				header("location: " . $url);
			    				exit;
	    						break;
	    					default://申请首页
	    						$this->index();
	    						break;
	    				}
	    		}
    		}
    		
    	}catch (Exception $e) {
    		throw $e;
    	}
    	
    	return $data;
    }
    
    /**
     * search 
     * 
     * @param mixed $post_data 
     * @access public
     * @return mixed
     */
    function search($data, $page=0, $pagesize=30) {
        $page = empty($page) ? 1 : $page;
        $pagesize = empty($pagesize) ? $this->config->item('pagesize') : $pagesize;
        try{
            $webmaster = $this->model('public/Model_webmaster')->search($data, $page, $pagesize);
        }catch(Exception $e){
            throw $e;
        }
   
        return $webmaster;
    }
    
    /**
     * detail 
     * 微站详情
     * @param mixed $id 
     * @access public
     * @return mixed
     */
    function detail($id) {
        $c = array();
        $c['webmaster'] = $this->model('public/Model_webmaster')->fetch_one_by_id($id);
        if (empty($c['webmaster']) ){
            throw new Exception(sprintf('%s: The webmaster id %d does not exist or has been deleted.',
                        __FUNCTION__, $id), $this->config->item('data_exist_err_no', 'err_no'));  
        }
        
        return $c; 
    }
    
    /**
     * edit获取微站资料 
     * 
     * @param mixed $id 
     * @access public
     * @return mixed
     */
    function edit() {
        try {
	        $c = array();
	        $uid = $_COOKIE['uid'];
	        $masterinfo = $this->searchMaster($uid);	//Bs_logic.php
	        if ( !empty($masterinfo) ) {
	        	$is_auth = array_key_exists($masterinfo['webmasterinfo']['authentication'], $this->config->item('master_valid'));
	        	if ($is_auth) {
		        	$masterid = intval($masterinfo['webmasterinfo']['id']);//站长id
		        	if ($masterid <=0 ){
		        		throw new Exception('站长id不存在', $this->config->item('masterid_exist_err_no', 'err_no'));
		        	}
		        	$c['webmaster'] = $this->model('public/Model_webmaster')->fetch_one_by_id($masterid);
		        	if (empty($c['webmaster']) ){
		        		throw new Exception('站长数据不存在', $this->config->item('data_exist_err_no', 'err_no'));
		        	}
		        	//一期，招牌图片放这里
		        	$c['webmaster']['bgPicture'] = empty($masterinfo['websiteinfo']['bgPicture'])?"":$masterinfo['websiteinfo']['bgPicture'];
		//         	$this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
		//         			__FUNCTION__, $this->input->ip_address())
		//         	);
	        	}
	        }else {
	        	//此用户没有开通微站
	        	throw new Exception('您的账号未开通微站', $this->config->item('ismaster_err_no', 'err_no'));
	        }
        }catch (Exception $e) {
        	throw $e;
        }        
        return $c; 
    }
    
    /**
     * save
     * 保存
     * @param mixed $post_data
     * @access public
     * @return mixed
     */
    function savewebmaster( $post_data ) {
    	try {
    		$data = array('username','iccard','mobile');//规定要保存的字段
    		if (is_array($post_data) && !empty($post_data)) {
    			foreach ($post_data as $pk=>$pv){
    				if (!in_array($pk, $data)) {
    					unset($post_data[$pk]);
    				}
    			}
    		}
    		if ( empty($post_data['username']) || empty($post_data['iccard']) || empty($post_data['mobile']) ) {
    			throw new Exception('参数不能为空', $this->config->item('parameter_err_no'));
    		}
    		$post_data['name'] 				= "";
    		$post_data['username'] 			= strip_tags($post_data['username']);
    		$post_data['createTime'] 		= time();
    		$post_data['createUser'] 		= intval($_COOKIE['uid']);
    		$post_data['updateUser'] 		= intval($_COOKIE['uid']);
    		$post_data['updateTime'] 		= time();
    		$post_data['authentication'] 	= 2;//默认，未认证
    		$post_data['conditions'] 		= 18;//默认，待审核
    		$post_data['logo'] 				= '';//默认头像
    		$post_data['rejection'] 		= '';//拒绝原因
    		$post_data['closereason'] 		= '';//关闭原因
    		$uid = $_COOKIE['uid'];
    		$masterinfo = $this->searchMaster($uid);
    		if ( !empty($masterinfo) && array_key_exists($masterinfo['webmasterinfo']['conditions'], $this->config->item('master_valid'))) {
    			//已开通微站
    			throw new Exception('已开通微站并且是正常状态', $this->config->item('isvalid_err_no'));
    		}
    		$masterdata = array();
    		$masterdata['webmaster'] = $post_data;
    		$id = $this->model('public/Model_webmaster')->save($masterdata);
    		
    		$this->log->info('create webmasterinfo username ='.$post_data['username'].' iccard='.$post_data['iccard'].' mobile='.$post_data['mobile']);
    		$this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
    				__FUNCTION__, $this->input->ip_address())
    		);
    	}catch (Exception $e) {
    		throw $e;
    	}
    	 
    	return $id;
    }
    
    /**
     * save 保存
     * 修改微站资料-一期包括修改微站招牌（2个表）
     * @access public
     * @return mixed
     * 
     */
    function saveMaster( $data ) {
    	try {
	    	$uid = intval($_COOKIE['uid']);
	    	$masterinfo = $this->searchMaster($uid);	//Bs_logic.php
	    	//先查当前登陆用户的微站信息，微站开通状态
	    	if ( empty($masterinfo) || array_key_exists($masterinfo['webmasterinfo']['conditions'], $this->config->item('master_novalid'))) {
	    		throw new Exception('您的账号未开通微站', $this->config->item('ismaster_err_no', 'err_no'));
	    	}
	    	$wzid = intval($masterinfo['websiteinfo']['id']);//微站id
	    	if ($wzid <=0) {
	    		throw new Exception('微站id不存在', $this->config->item('wzid_exist_err_no', 'err_no'));
	    	}
	    	$masterid = intval($masterinfo['webmasterinfo']['id']);//站长id
	    	if ($masterid <=0 ){
	    		throw new Exception('站长id不存在', $this->config->item('masterid_exist_err_no', 'err_no'));
	    	}
	    	$insert_data = array('name', 'introduction', 'qq', 'weibo');//规定要更新的字段,'sex','logo','bgPicture'拿掉了 
	    	if (is_array($data) && !empty($data)) {
	    		foreach ($data as $pk=>$pv){
	    			if (!in_array($pk, $insert_data)) {
	    				unset($data[$pk]);
	    			}
	    		}
	    	}
// 	    	$data['introduction'] = htmlspecialchars($data['introduction']);
	    	//图片没更新，则不做操作
// 	    	$wzdata = array();
// 	    	$wzdata['bgPicture'] 	= $data['bgPicture'];//只修改店招图片
// 	    	$wzdata['updateTime'] 	= time();
// 	    	$wzdata['updateUser'] 	= $uid;
// 	    	$update_wzid = $this->model('public/Model_website')->update_by_id($wzdata,$wzid);
// 	    	unset($data['bgPicture']);
	    	//微站站长logo头像
	    	$update_masterid = $this->model('public/Model_webmaster')->update_by_id($data, $masterid);
	    	if ( $update_masterid == $masterid ) {
	    		return true;
	    	}else
	    		return false;
    	}catch (Exception $e) {
    		throw $e;
    	}
    }
}
?>