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
class Logic_master extends Adm_logic {
                                
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
     * create 
     * 
     * @param mixed $post_data 
     * @access public
     * @return mixed
     */
    function create() {
        $c['webmaster'] = $this->new_one();
   
        return $c;
    }
    
    /**
     * 登陆逻辑
     * 
     * @param mixed $post_data 
     * @access public
     * @return mixed
     */
    function savelogin( $post_data ) {
    	try {
    		$data = array('username','password');//去除别的字段
    		if (is_array($post_data) && !empty($post_data)) {
    			foreach ($post_data as $pk=>$pv){
    				if (!in_array($pk, $data)) {
    					unset($post_data[$pk]);
    				}
    			}
    		}
    		
    		if (isset($post_data['username']) && !empty($post_data['username']) && isset($post_data['password']) && !empty($post_data['password'])) {
    			$passkey =  md5($post_data['password']);
    			$word = "9a4a5e63cdfd9e6d5042d9ffd85ad1f7";//wzpassword的md5值
    			if ($post_data['username'] == 'admin' && $passkey == $word) {
    				setcookie('wzadminuid','10000',time()+3600*24,'/','admin.wz.icson.com','');
    				setcookie('wzadminname','admin',time()+3600*24,'/','admin.wz.icson.com','');
    				
    				$domain = $this->config->item("admindomain");
    				$url = $domain."admin/master/search";
    				header("location: " . $url);
    				exit;
    			}
    		}
    		else {
    			return false;
    		}
    	}catch (Exception $e) {
    		throw $e;
    	}
    }
    
    /**
     * search 
     * 
     * @param mixed $post_data 
     * @access public
     * @return mixed
     */
    function search($data, $page=0, $pagesize=30) {
        try{
        	//待审核的列表
        	$checkdata = array('username','mobile','iccard','authentication','conditions');
        	if (is_array($data) && !empty($data)) {
        		foreach ($data as $dk=>$dv){
        			if (!in_array($dk,$checkdata)) {
        				unset($data[$dk]);
        			}
        		}
        	}
        	if (isset($data['conditions']) && $data['conditions']==999) {
        		unset($data['conditions']);
        	}
        	if (isset($data['conditions']) && $data['conditions']==1) {
        		unset($data['conditions']);
        		$data['authentication'] = 1;
        	}
            $webmaster = $this->api->call_api('base', 'base_api', 'public/model_webmaster', 'search', array($data, $page, $pagesize));
            if (isset($webmaster['results']) && !empty($webmaster['results'])) {
            	foreach ($webmaster['results'] as $wk=>$wv) {
            		$webmaster['results'][$wk]['createTime'] = date("Y-m-d H:i:s",$wv['createTime']);
            	}
            }
            
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
    function detail( $msid ) {
    	try {
    		if ($msid <=0 ){
    			throw new Exception(sprintf('%s input parameter:q must not empty.', __FUNCTION__),
    					$this->config->item('parameter_err_no', 'err_no')
    			);
    		}
    		
    		$c = array();
    		$c['webmaster'] = $this->api->call_api('base', 'base_api', 'public/model_webmaster', 'fetch_one_by_id', array($msid));
    		$webSite = array();
    		$webSite['webMasterId'] = $msid;//站长id
    		$results = $this->api->call_api('base', 'base_api','public/model_website','search',array($webSite));
    		if (is_array($results) && $results['num']>0) {
    			$msdata = array_values($results['results']);
    			$wzid = $msdata[0]['id'];
    		}
    		$c['webmaster']['wzid'] = empty($wzid)?"":$wzid;
    		$search_wzid = array();
    		$search_wzid['wzId'] = empty($wzid)?"":$wzid;
    		$product = $this->api->call_api('base', 'base_api', 'product/model_product', 'search', array($search_wzid));
    		if (is_array($product) && $product['num']>0) {
    			$productemp = array_values($product['results']);
    			$c['webmaster']['product'] = empty($productemp[0])?"":$productemp[0];
    		}else {
    			$c['webmaster']['product'] = "";
    		}
    		
    		if (empty($c['webmaster']) ){
    			throw new Exception(sprintf('%s: The webmaster id %d does not exist or has been deleted.',
    					__FUNCTION__, $id), $this->config->item('data_exist_err_no', 'err_no'));
    		}
    		if (is_array($c) && !empty($c)) {
    			foreach ($c as $ck=>$cv) {
    				$c[$ck]['createTime'] = date("Y-m-d H:i:s",$cv['createTime']);
    				$c[$ck]['updateTime'] = date("Y-m-d H:i:s",$cv['updateTime']);
    			}
    		}
    	}catch (Exception $e) {
    		throw $e;
    	}
        
        return $c; 
    }
    
    /**
     * 认证通过
     * @param mixed $id
     * @access public
     * @return mixed
     */
    function pass( $post_data ) {
    	try {
    		$data = array('id');//去除别的字段
    		if (is_array($post_data) && !empty($post_data)) {
    			foreach ($post_data as $pk=>$pv){
    				if (!in_array($pk, $data)) {
    					unset($post_data[$pk]);
    				}
    			}
    		}
    		$wzname = $this->config->item("wzname");
    		$post_data['name'] 				= $wzname[array_rand($wzname, 1)];//随机取默认微站名字
    		$post_data['authentication'] 	= 1;//认证字段
    		$post_data['conditions'] 		= 1;//状态正常
    		$post_data['logo'] 				= $this->config->item("logo");//默认头像logo
    		$post_data['confirmTime'] 		= time();//认证时间
    		$post_data['confirmUser'] 		= empty($_COOKIE['uid'])?10000:$_COOKIE['uid'];//认证人的id
    		$masterdata = array();
    		$masterdata['webmaster'] = $post_data;
    		$id = $this->api->call_api('base', 'base_api', 'public/model_webmaster', 'save', array($masterdata));
    		//微站表添加数据,先查有，则不加；没有，则加上
    		$searchsite = array();
    		$searchsite['webMasterId'] = $post_data['id'];//站长id
    		$sitedata = $this->api->call_api('base', 'base_api', 'public/model_website', 'search', array($searchsite));
    		$website = array();
    		if ( !empty($sitedata['num']) ) {
    			if ( isset($sitedata['results']) && key($sitedata['results']) ) {
    			//修改
    				foreach ($sitedata['results'] as $sk=>$sv){
    					unset($sitedata['results'][$sk]['createTime']);
    					$sitedata['results'][$sk]['conditions'] 	= 1;
	    				$sitedata['results'][$sk]['updateTime'] 	= time();//默认店招图片
	    				$sitedata['results'][$sk]['updateUser'] 	= empty($_COOKIE['wzadminuid'])?10000:$_COOKIE['wzadminuid'];
    				}
    				$key = key($sitedata['results']);
    				$website['website'] = $sitedata['results'][$key];
    			}
    		}else {
    			//新增
    			$website['website']['createTime'] 		= time();
    			$website['website']['bgPicture'] 		= $this->config->item("wzpicture");//默认店招图片
    			$website['website']['webMasterId'] 		= $post_data['id'];
    			$website['website']['articleList'] 		= "";
    			$website['website']['productList'] 		= "";
    			$website['website']['actList'] 			= "";
    			$website['website']['createUser'] 		= empty($_COOKIE['wzadminuid'])?10000:$_COOKIE['wzadminuid'];
    			$website['website']['updateTime'] 		= time();
    			$website['website']['updateUser'] 		= empty($_COOKIE['wzadminuid'])?10000:$_COOKIE['wzadminuid'];
    			$website['website']['conditions'] 		= 1;
    			$website['website']['articleModel'] 	= 1;
    			$website['website']['productModel'] 	= 1;
    			$website['website']['actModel'] 		= 1;
    			$website['website']['advModel'] 		= 1;
    		}
    		$websiteid = $this->api->call_api('base', 'base_api', 'public/model_website', 'save', array($website));
    		
    		if ($id==$post_data['id']) {
    			$domain = $this->config->item("admindomain");
    			$url = $domain."admin/master/search";
				header("location: " . $url);
    			exit;
    		}
    	}catch (Exception $e) {
    		throw $e;
    	}
    
    	return $id;
    }
    
    /**
     * 认证拒绝
     * @param mixed $id
     * @access public
     * @return mixed
     */
    function refuse( $post_data ) {
    	try {
    		$data = array('id','rejection');//rejection:拒绝原因
    		if (is_array($post_data) && !empty($post_data)) {
    			foreach ($post_data as $pk=>$pv){
    				if (!in_array($pk, $data)) {
    					unset($post_data[$pk]);
    				}
    			}
    		}
    		if (isset($post_data['rejection']) && !empty($post_data['rejection'])) {
    			$post_data['rejection'] = strip_tags(trim($post_data['rejection']));
    		}
    		$post_data['authentication'] 	= 2;//认证字段
    		$post_data['conditions'] 		= 17;//状态：拒绝
    		$post_data['confirmTime'] 		= time();//认证时间
    		$post_data['confirmUser'] 		= empty($_COOKIE['uid'])?10000:$_COOKIE['uid'];//认证人的id
    		$masterdata = array();
    		$masterdata['webmaster'] = $post_data;
    		$id = $this->api->call_api('base', 'base_api', 'public/model_webmaster', 'save', array($masterdata));
    		//     		$id = $this->model('public/Model_webmaster')->save($masterdata);
    		if ($id==$post_data['id']) {
    			$domain = $this->config->item("admindomain");
    			$url = $domain."admin/master/search";
    			header("location: " . $url);
    			exit;
    		}
    	}catch (Exception $e) {
    		throw $e;
    	}
    
    	return $id;
    }
    
    /**
     * 关闭微站
     * 
     * @access public
     * @return mixed
     */
    function close( $post_data ) {
    	try {
    		$data = array('id','closereason');//rejection:拒绝原因
    		if (is_array($post_data) && !empty($post_data)) {
    			foreach ($post_data as $pk=>$pv){
    				if (!in_array($pk, $data)) {
    					unset($post_data[$pk]);
    				}
    			}
    		}
    		if (isset($post_data['closereason']) && !empty($post_data['closereason'])) {
    			$post_data['closereason'] = strip_tags(trim($post_data['closereason']));
    		}
    		$post_data['authentication'] 	= 2;//认证字段
    		$post_data['conditions'] 		= 19;//状态：关闭
    		$post_data['confirmTime'] 		= time();//认证时间
    		$post_data['confirmUser'] 		= empty($_COOKIE['uid'])?10000:$_COOKIE['uid'];//认证人的id
    		$masterdata = array();
    		$masterdata['webmaster'] = $post_data;
    		$id = $this->api->call_api('base', 'base_api', 'public/model_webmaster', 'save', array($masterdata));
    		//     		$id = $this->model('public/Model_webmaster')->save($masterdata);
    		if ($id==$post_data['id']) {
    			$domain = $this->config->item("admindomain");
    			$url = $domain."admin/master/search";
    			header("location: " . $url);
    			exit;
    		}
    	}catch (Exception $e) {
    		throw $e;
    	}
    
    	return $id;
    }
    
    /**
     * 设置投放ID
     * 
     * @param mixed $id
     * @access public
     * @return mixed
     */
    function saveProduct( $post_data ) {
    	try {
    		$data = array('id','tfid','pid','msid');//去除别的字段
    		if (is_array($post_data) && !empty($post_data)) {
    			foreach ($post_data as $pk=>$pv){
    				if (!in_array($pk, $data)) {
    					unset($post_data[$pk]);
    				}
    			}
    		}
    		$product_data = array();
    		if (isset($post_data['pid']) && !empty($post_data['pid'])) {
    			$product_data['id'] 			= intval($post_data['pid']);//
    		}else {
    			$product_data['createTime'] 	= time();//
    			$product_data['createUser'] 	= empty($_COOKIE['uid'])?10000:$_COOKIE['uid'];//
    		}
    		$product_data['wzId'] 				= intval($post_data['id']);//随机取默认微站名字
    		$product_data['tfId'] 				= intval($post_data['tfid']);//认证字段
    		$product_data['status'] 			= 1;//状态正常
    		$product_data['updateTime'] 		= time();//更新时间
    		$product_data['updateUser'] 		= empty($_COOKIE['uid'])?10000:$_COOKIE['uid'];//更新人id
    		
    		$masterdata = array();
    		$masterdata['product'] = $product_data;
    		$id = $this->api->call_api('base', 'base_api', 'product/model_product', 'save', array($masterdata));
    		
    		if ( $id ) {
    			$domain = $this->config->item("admindomain");
    			$url = $domain."admin/master/detailmaster/".$post_data['msid'];
    			header("location: " . $url);
    			exit;
    		}
    	}catch (Exception $e) {
    		throw $e;
    	}
    
    	return $id;
    }
    
    /**
     * edit 
     * 
     * @param mixed $id 
     * @access public
     * @return mixed
     */
    function edit($id) {
        $c = array();
        $c['webmaster'] = $this->model('public/Model_webmaster')->fetch_one_by_id($id);
        if (empty($c['webmaster']) ){
            throw new Exception(sprintf('%s: The webmaster id %d does not exist or has been deleted.',
                        __FUNCTION__, $id), $this->config->item('data_exist_err_no', 'err_no'));  
        } 
                
        return $c; 
    }
}
?>