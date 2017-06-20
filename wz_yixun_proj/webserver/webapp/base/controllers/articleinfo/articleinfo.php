<?php
/**
 * Articleinfo
 * 
 * @uses Wd
 * @uses _Controller
 * @package 
 * @version $1.0$
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author fishxyu <fishxyu@yixun.com> 
 * @license 
 */
class Articleinfo extends Bs_controller {
    /**
     * _format  返回格式
     *
     * @var string
     * @access protected
     */
    protected  $_format         		= 'json';
    /**
     * _ilist_items  列表项 搜索条件
     * 管理工具后台，按照文章类型来搜索文章
     * @var array
     * @access protected
     */
    protected  $_ilist_items            = array('kindId','categories','conditions','wzId');
    /**
     * _ilist_items2 检索项  搜索条件
     * 管理工具后台，按照文章标题来搜索文章
     * @var array
     * @access protected
     */
    protected  $_ilist_items2           = array('title');
    /**
     * _search_conditions  搜索条件
     *
     * @var array
     * @access protected
     */
    protected  $_search_conditions      = array();
    
    /**
     * __construct
     *
     * @access public
     * @return mixed
     */
    public function __construct() {
        parent :: __construct();
        
        $this->_format = $this->input->get('format');
        $this->_tn = $this->input->get('tn') ? sprintf('_%s',$this->input->get('tn')) : '';
    }
    
    /**
     * 获取文章的分类数据
     * 
     * @access public
     * @return mixed
     */
    function category() {
    	$category = $this->config->item('article_category');//文章分类
    	if ( is_array($category) && !empty($category) ) {
    		$this->_response['category'] = $category;
    	}else {
    		$err_code = $this->config->item('iscategory_err_no', 'err_no');
    		$this->_response['success'] = FALSE;
    		$this->_response['results'] = '';
    		$this->_response['err_no']  = $err_code;
    		$this->_response['err_msg'] = $this->config->item($err_code, 'err_msg');
    		$this->log->warn(sprintf('category error. err_msg:%s. ip:%s', 
    				$this->config->item($err_code, 'err_msg'), $this->input->ip_address())
    		);
    	}
    }
    
    /**
     * 文章列表页接口
     * 
     * @access public
     * @return mixed
     */
    function search() {
        try {
            $pagesize   = intval($this->input->get('pagesize'))>0 ?
                intval($this->input->get('pagesize')) : $this->config->item('pagesize'); 
            $page       = max(intval($this->input->get('page')), 1);
            foreach ($this->_ilist_items as $search_item) {
                $search_item_value = intval($this->input->get($search_item));
                if ($search_item_value>0)
                    $this->_search_conditions[$search_item]  =  $search_item_value;
            }
            foreach ($this->_ilist_items2 as $search_item) {
                $search_item_value = $this->input->get($search_item);
                if (!empty($search_item_value))
                    $this->_search_conditions[$search_item]  =  $search_item_value;
            }
            //var_dump($this->_search_conditions);
            $articleinfo = $this->logic('articleinfo/Logic_articleinfo')->search($this->_search_conditions, $page, $pagesize);
            $this->_response['num']                 = $articleinfo['num'];
            $this->_response['results']             = array_values($articleinfo['results']);
            $this->_search_conditions['page']       = $page;
            $this->_search_conditions['pagesize']   = $pagesize;
            $this->_response['search_conditions']   = $this->_search_conditions;
            $this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
                        __FUNCTION__, $this->input->ip_address())
                    );
        } catch (Exception $e) {
            $this->_response['success'] = FALSE;
            $this->_response['err_no']  = $e->getCode();
            $this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
            $this->log->warn(sprintf('%s:%s process error err_msg:%s. ip:%s', $e->getFile(),
                        $e->getLine(), $e->getMessage(), $this->input->ip_address())
                    );
        }
    }
    
    /**
     * 用户管理工具的文章列表页面渲染模板
     *
     * @access public
     * @return mixed
     */
    function zdmlist() {
        try {
            $this->_search_conditions = array('group1' => 0, 'group2' => 0, 'group3' => 0, 'topic' => 0,);
            $this->_search_conditions['$pagesize'] = intval($this->input->get('pagesize')) > 0 ?
                    intval($this->input->get('pagesize')) : $this->config->item('pagesize');
            $this->_search_conditions['page'] = max(intval($this->input->get('page')), 1);
            $this->_search_conditions['column'] = $this->input->get('category') ? $this->input->get('category') : 0;
            $articelid = $this->input->get('artid') ? $this->input->get('artid') : 0;
            $lists = $this->logic('articleinfo/Logic_articleinfo')->zdmlist($this->_search_conditions, $articelid);
                if(is_array($lists['lists'][0])){
                foreach($lists['lists'] as $_k=>$_v){
                    //设置搜索的站长id
                    $param['wzId'] = $this->_response['wzid'];
                    $param['secretid']  = $_v['id'];
                    $param['conditions']  	= 1;//文章可用，17已删除
                    $bool = $this->logic('articleinfo/Logic_articleinfo')->searchArticle($param);
                    if($bool == TRUE){
                        $lists['lists'][$_k]['isIn'] = TRUE;
                    }else{
                        $lists['lists'][$_k]['isIn'] = FALSE;
                    }
                }
                 
            }
            $this->_response['num'] = $lists['total'];
            $this->_response['results'] = array_values($lists['lists']);
            $this->_search_conditions['page'] = $lists['current_page'];
            $this->_search_conditions['pagesize'] = $lists['pernum'];
            $this->_search_conditions['total_page'] = $lists['total_page'];
            $this->_response['search_conditions'] = $this->_search_conditions;
            $this->_response['category'] = $this->config->item('article_category');
            //设置搜索的站长id
            $_search_conditions['wzId'] = $this->_response['wzid'];
            //读取分类信息
            $articlekind = $this->logic('articleinfo/Logic_articlekind')
                    ->masterSearch($_search_conditions);
            $this->_response['articlekind'] = array_values($articlekind['results']);
            //类目名称读取
            $kindId = intval($this->input->get('kindId'));
            if ($kindId > 0 && is_int($kindId)) {
                $this->_response['kindName'] = isset($articlekind['results'][$kindId]['kindName'])?$articlekind['results'][$kindId]['kindName']:'';
            }else{
                 $this->_response['kindName'] = '';
            }
        } catch (Exception $e) {
            $this->_response['success'] = FALSE;
            $this->_response['err_no'] = $e->getCode();
            $this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
            $this->log->warn(sprintf('%s:%s process error err_msg:%s. ip:%s', $e->getFile(), $e->getLine(), $e->getMessage(), $this->input->ip_address())
            );
        }
    }

    /**
     * 用户管理工具的文章列表页面渲染模板
     *
     * @access public
     * @return mixed
     */
    function articlelist() {
    		//登陆状态检查，放到要跳转的数组里，统一做跳转
    		$this->category();//返回分类数据
        try {
            //设置搜索的站长id
            $this->_search_conditions['wzId'] = $this->_response['wzid'];
            $articlekind= $this->logic('articleinfo/Logic_articlekind')
                ->masterSearch($this->_search_conditions);
            
            $this->_response['articlekind']          = array_values($articlekind['results']);
            $this->_response['search_conditions']   = $this->_search_conditions;
            //var_dump($this->_response);
            $this->log->debug(sprintf('%s:%s ip:%s process ok.', __CLASS__,
                        __FUNCTION__, $this->input->ip_address())
                    );
        } catch (Exception $e) {
            $this->_response['success'] = FALSE;
            $this->_response['err_no']  = $e->getCode();
            $this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
            $this->log->warn(sprintf('%s:%s process error err_msg:%s. ip:%s', $e->getFile(),
                        $e->getLine(), $e->getMessage(), $this->input->ip_address())
                    );
        }
                
    }
    
    /**
     * create 新增页面
     *
     * @access public
     * @return mixed
     */
    function create() {
        try {
            $this->_response['results'] = $this->logic('articleinfo/Logic_articleinfo')->create();
            $this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
                        __FUNCTION__, $this->input->ip_address())
                    );
        } catch(Exception $e) {
            $this->_response['success'] = FALSE;
            $this->_response['err_no']  = $e->getCode();
            $this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
            $this->log->warn(sprintf('%s:%s process error err_msg:%s. ip:%s', $e->getFile(),
                        $e->getLine(), $e->getMessage(), $this->input->ip_address())
                    );
        }
    }
    
    /**
     * save 保存
     *
     * @access public
     * @return mixed
     */
    function save() {
        $form_name= 'c';
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->log->debug(var_export($this->input->post(), TRUE));
        try {
            if ($this->form_validation->run() === FALSE) {
                $post_data = $this->input->post($form_name);
                $this->_validate_error($form_name, $post_data);
                throw new Exception(sprintf('%s input parameters  must not be entire.', __FUNCTION__),
                        $this->config->item('parameter_err_no', 'err_no')
                       );
            } else {
                $post_data  = $this->input->post($form_name);
                $id         = $this->logic('articleinfo/Logic_articleinfo') ->save($post_data);
                $this->_response['id'] = $id;
            }
            $this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
                        __FUNCTION__, $this->input->ip_address())
                    );
        } catch(Exception $e) {
            $this->_response['success'] = FALSE;
            $this->_response['err_no']  = $e->getCode();
            $this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
            $this->log->warn(sprintf('%s:%s process error err_msg:%s.', $e->getFile(),
                        $e->getLine(), $e->getMessage(), $this->input->ip_address())
                    );
        }
    }
    
    /**
     * 文章删除 | 批量删除
     * 
     * @access public
     * @return mixed
     */
    function saveStatus() {
    	$form_name= 'c';
    	$this->load->helper(array('form', 'url'));
    	$this->load->library('form_validation');
    	$this->log->debug(var_export($this->input->post(), TRUE));
    	try {
			$artid = $this->input->post('artid');
			$result = $this->logic('articleinfo/Logic_articleinfo')->saveStatus($artid);
			
			$this->_response['success'] = true;
			$this->_response['results'] = $result;
			
    	} catch(Exception $e) {
    		$this->_response['success'] = FALSE;
    		$this->_response['err_no']  = $e->getCode();
    		$this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
    		$this->log->warn(sprintf('%s:%s process error err_msg:%s.', $e->getFile(),
    				$e->getLine(), $e->getMessage(), $this->input->ip_address())
    		);
    	}
    }
    
    /**
     * 批量插入值得买文章
     * @throws Exception
     */
    function multiSaveArticle() {
        try { 
            $this->_format = 'json';
            $uid = $_COOKIE['uid'];
            $masterinfo = $this->logic('articleinfo/Logic_articleinfo')->searchMaster($uid);//Bs_logic.php
            if (is_array($masterinfo) && !empty($masterinfo) ) {
                if (array_key_exists($masterinfo['webmasterinfo']['conditions'], $this->config->item('master_invalid'))) {
                    //未认证用户不给导入文章
                    throw new Exception(sprintf('%s the uid:'.$uid.'is not auth', __FUNCTION__),
                                    $this->config->item('website_auth_error', 'err_no'));
                }
           } else {
                    //此用户没有开通微站
                    throw new Exception(sprintf('%s the uid:'.$uid.'is not exist', __FUNCTION__),
                                    $this->config->item('websit_not_exist', 'err_no'));
            }
            
            $websiteid = intval($masterinfo['websiteinfo']['id']);
            $artids = $this->input->get('artids');//值得买文章加密字符串
            $articleids = explode(",", $artids);
            foreach($articleids as $articleid) {
                $param = array();
                $param['wzId'] 			= $masterinfo['websiteinfo']['id'];
                $param['secretid']  	= $articleid;
                $param['conditions']  	= 1;//文章可用，17已删除
                $bool = $this->logic('articleinfo/Logic_articleinfo')->searchArticle($param);
                if ($bool === true) {
                   throw new Exception(sprintf('%s the uid:'.$uid.'is not auth', __FUNCTION__),
                                    $this->config->item('article_exist_err', 'err_no'));
                }
                $results = $this->logic('articleinfo/Logic_articleinfo')->curlArticle($articleid, $uid, $websiteid);
            }
            $this->_response['id'] 		= 1;//插入的文章id
            $this->_response['results'] = array();//值得买文祥信息
            $this->_response['success'] = true;
        } catch(Exception $e) {
                $this->_response['success'] = FALSE;
                $this->_response['err_no']  = $e->getCode();
                $this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
                $this->log->warn(sprintf('%s:%s process error err_msg:%s.', $e->getFile(),
                                $e->getLine(), $e->getMessage(), $this->input->ip_address())
                );
           }
    }
    
    /**
     * 从值得买导入文章接口
     * @access public
     * @return mixed
     */
    function saveArticle() {
        try {
            //文章导入强迫json格式
            $this->_format = 'json'; 
            //先支持加密字符串方式导入：
            $encryptionId = $this->input->post('artid');//值得买文章加密字符串
            $kindId = $this->input->post('kindId');//值得买文章加密字符串
            $uid = $_COOKIE['uid'];
            //先查当前用户的微站信息，微站开通状态
            $masterinfo = $this->logic('articleinfo/Logic_articleinfo')->searchMaster($uid);//Bs_logic.php
            if (is_array($masterinfo) && !empty($masterinfo) ) {
                if (array_key_exists($masterinfo['webmasterinfo']['conditions'], $this->config->item('master_invalid'))) {
                    //未认证用户不给导入文章
                    throw new Exception(sprintf('%s the uid:'.$uid.'is not auth', __FUNCTION__),
                                    $this->config->item('website_auth_error', 'err_no'));
                }
                //再查当前用户是否加入过此文章
                $param = array();
                $param['wzId'] 			= $masterinfo['websiteinfo']['id'];
                $param['secretid']  	= $encryptionId;
                $param['conditions']  	= 1;//文章可用，17已删除
                $bool = $this->logic('articleinfo/Logic_articleinfo')->searchArticle($param);
                if ($bool === true) {
                    //找到则返回
                    throw new Exception(sprintf('%s the uid:'.$uid.'is not auth', __FUNCTION__),
                                    $this->config->item('article_exist_err', 'err_no'));
                }
                $websiteid = intval($masterinfo['websiteinfo']['id']);
            }else {
                //此用户没有开通微站
                throw new Exception(sprintf('%s the uid:'.$uid.'is not exist', __FUNCTION__),
                                $this->config->item('websit_not_exist', 'err_no'));
            } 
            $results = $this->logic('articleinfo/Logic_articleinfo')->curlArticle($encryptionId, $uid, $websiteid,$kindId);
            $this->_response['id'] 		= $results['id'];//插入的文章id
            $this->_response['results'] = array();//值得买文祥信息
            $this->_response['success'] = true;
            } catch(Exception $e) {
                //var_dump($e);
                $this->_response['success'] = FALSE;
                $this->_response['err_no']  = $e->getCode();
                $this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
                $this->log->warn(sprintf('%s:%s process error err_msg:%s.', $e->getFile(),
                                $e->getLine(), $e->getMessage(), $this->input->ip_address())
                );
           }
    }
    
    /**
     * detail 查看
     * 微站管理工具文章详细
     * @access public
     * @return mixed
     */
    function detail() {
        try {
            $artid = intval($this->input->get('artid'));
            if ($artid <=0 ){
                throw new Exception(sprintf('%s input parameter:q must not empty.', __FUNCTION__),
                        $this->config->item('parameter_err_no', 'err_no')
                        );
            }
            $c = $this->logic('articleinfo/Logic_articleinfo')->detail($artid);
            $this->_response['results'] = $c['articleinfo'];
            $this->log->info(sprintf('%s:%s ip:%s process ok. detail artid:%d', __CLASS__,
                        __FUNCTION__, $this->input->ip_address(), $artid)
                    );
        } catch(Exception $e) {
            $this->_response['success'] = FALSE;
            $this->_response['err_no']  = $e->getCode();
            $this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
            $this->log->warn(sprintf('%s:%s process error err_msg:%s., ip:%s', $e->getFile(),
                        $e->getLine(), $e->getMessage(), $this->input->ip_address())
                    );
        }
    }
    
    /**
     * 修改文章的标题和简介|渲染模板
     * @access public
     * @return mixed
     */
    function edit() {
        try {
            $id = intval($this->input->get('id'));
            if ($id <=0 ){
                throw new Exception(sprintf('%s input parameter:q must not empty.', __FUNCTION__),
                        $this->config->item('parameter_err_no', 'err_no')
                        );
            }
            $c = $this->logic('articleinfo/Logic_articleinfo')->edit($id);
            $this->_response['results'] = $c['articleinfo'];
            $this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
                        __FUNCTION__, $this->input->ip_address())
                    );
        } catch(Exception $e) {
            $this->_response['success'] = FALSE;
            $this->_response['err_no']  = $e->getCode();
            $this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
            $this->log->warn(sprintf('%s:%s process error err_msg:%s.ip:%s', $e->getFile(),
                        $e->getLine(), $e->getMessage(), $this->input->ip_address())
                    );
        }
    }
    
    /**
     * 保存文章的标题和简介
     * @access public
     * @return mixed
     */
    function savetitle() {
    	$form_name= 'c';
    	$this->load->helper(array('form', 'url'));
    	$this->load->library('form_validation');
    	$this->log->debug(var_export($this->input->post(), TRUE));
    	try {
    		$post_data  = $this->input->post($form_name);
    		$result = $this->logic('articleinfo/Logic_articleinfo')->savetitle($post_data);
    		
    		$this->_response['success'] = $result;
    		$this->_response['results'] = $post_data;
    		
    	} catch(Exception $e) {
    		$this->_response['success'] = FALSE;
    		$this->_response['err_no']  = $e->getCode();
    		$this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
    		$this->log->warn(sprintf('%s:%s process error err_msg:%s.', $e->getFile(),
    				$e->getLine(), $e->getMessage(), $this->input->ip_address())
    		);
    	}
    }
    
    /**
     * 保存文章排序
     * @access public
     * @return mixed
     */
    function saveOrder() {
    	$form_name= 'c';
    	$this->load->helper(array('form', 'url'));
    	$this->load->library('form_validation');
    	$this->log->debug(var_export($this->input->post(), TRUE));
    	try {
	    	$post_data  = $this->input->post($form_name);
	    	$result = $this->logic('articleinfo/Logic_articleinfo')->saveorder($post_data);
	    	
    	    $this->_response['success'] = $result;
    	    $this->_response['results'] = $post_data;
    	} catch(Exception $e) {
    		$this->_response['success'] = FALSE;
    		$this->_response['err_no']  = $e->getCode();
    		$this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
    		$this->log->warn(sprintf('%s:%s process error err_msg:%s.', $e->getFile(),
    				$e->getLine(), $e->getMessage(), $this->input->ip_address())
    		);
    	}
    }
    
    /**
     * set 设置
     *
     * @access public
     * @return mixed
     */
    function set() {
        $form_name= 'c';
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->log->debug(var_export($this->input->post(), TRUE));
        try {
            if ($this->form_validation->run() === true) {
                $post_data = $this->input->post($form_name);
                $this->_validate_error($form_name, $post_data);
                throw new Exception(sprintf('%s input parameters must not be entire.', __FUNCTION__),
                        $this->config->item('parameter_err_no', 'err_no')
                       );
            } else {
                $post_data = $this->input->post($form_name);
                $id = $this->logic('articleinfo/Logic_articleinfo') ->set($post_data);
                $this->_response['id'] = $id;
                $this->_response['success'] = true;
                $this->_response['results'] = $post_data;
            }
            $this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
                        __FUNCTION__, $this->input->ip_address())
                    );
        } catch(Exception $e) {
            $this->_response['success'] = FALSE;
            $this->_response['err_no']  = $e->getCode();
            $this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
            $this->log->warn(sprintf('%s:%s process error err_msg:%s.', $e->getFile(),
                        $e->getLine(), $e->getMessage(), $this->input->ip_address())
                    );
        }
    }
    
    /**
     * delete 删除
     * 
     * @access public
     * @return mixed
     */
    function delete() {
        try {
        	//用户登陆状态，及删除权限
        	$userinfo = array();
        	
            $id = $this->input->post('id');
            if ($id <=0 ){
                throw new Exception(sprintf('input parameter id:%s must be integer', $id),
                        $this->config->item('input_parameter_err_no', 'err_no')
                        );
            }
            $is_deleted_map = $this->config->item('is_deleted','dictionaries');
            $is_deleted = $this->input->post('is_deleted');
            if (!isset($is_deleted_map[$is_deleted])) {
                throw new Exception(sprintf('input parameter is_deleted:%s must be Y OR N', $is_deleted),
                        $this->config->item('input_parameter_err_no', 'err_no'));
            }
            $this->logic('articleinfo/Logic_articleinfo')->delete(array('is_deleted'=>$is_deleted), $id);
            $this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
                        __FUNCTION__, $this->input->ip_address())
                    );
        } catch(Exception $e) {
            $this->_response['success'] = FALSE;
            $this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
            $this->_response['err_no']  = $e->getCode();
            $this->log->warn(sprintf('page %s:%s load failure. err_no:%d err_msg:%s ip:%s',
                        __CLASS__, __FUNCTION__,  $e->getCode(), $e->getMessage(), $this->input->ip_address())
                    );
        }
    }
}
?>