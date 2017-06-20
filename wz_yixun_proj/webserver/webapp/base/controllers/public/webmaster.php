<?php
/**
 * Webmaster
 * 
 * @uses Wd
 * @uses _Controller
 * @package 
 * @version $1.0$
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author fishxyu <fishxyu@yixun.com> 
 * @license 
 */
class Webmaster extends Bs_controller {
    /**
     * _format  返回格式
     *
     * @var string
     * @access protected
     */
    protected  $_format         		= 'json';
    
    /**
     * _ilist_items  列表项 搜索条件
     * 搜索微站的状态
     * @var array
     * @access protected
     */
    protected  $_ilist_items            = array('conditions');
    
    /**
     * _ilist_items2 检索项  搜索条件
     *
     * @var string
     * @access protected
     */
    protected  $_ilist_items2           = array('username','mobile','iccard');
    
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
     * 微站-建站工具首页概况
     * @access public
     * @return mixed
     */
    function index(){
		try {
    		$masterinfo = $this->logic('public/Logic_webmaster')->index();
    		$this->_response['success'] = true;
    		$this->_response['results'] = $masterinfo;
    	}catch (Exception $e){
    		$this->_response['success'] = FALSE;
    		$this->_response['err_no']  = $e->getCode();
    		$this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
    		$this->log->warn(sprintf('%s:%s process error err_msg:%s. ip:%s', $e->getFile(),
    				$e->getLine(), $e->getMessage(), $this->input->ip_address())
    		);
    	}
    }
    
    /**
     * 微站申请首页 | 入口
     * 地址 http://dev.base.wz.yixun.com/webmaster/master
     * 
     */
    function master() {
    	try {
    		$data =  $this->logic('public/Logic_webmaster')->masterStatus();
    		
    		$this->_response['success'] = true;
    		$this->_response['results'] = $data;
    		 
    		echo $this->load->view($data['url'], $this->_response, true);
    		exit;
    	}catch (Exception $e){
    		$this->_response['success'] = FALSE;
    		$this->_response['err_no']  = $e->getCode();
    		$this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
    		$this->log->warn(sprintf('%s:%s process error err_msg:%s. ip:%s', $e->getFile(),
    				$e->getLine(), $e->getMessage(), $this->input->ip_address())
    		);
    	}
    }
    
    /**
     * 微站站长列表
     * 后台管理系统用
     * @access public
     * @return mixed
     */
    function searchMaster() {
        try {
            $pagesize   = intval($this->input->get('pagesize'))>0 ?
                intval($this->input->get('pagesize')) : $this->config->item('pagesize');
            $page       = max(intval($this->input->get('page')), 1);
            //int条件搜索
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
            $webmaster= $this->logic('public/Logic_webmaster')->search($this->_search_conditions, $page, $pagesize);
            
            $this->_response['num']                 =  $webmaster['num'];
            $this->_response['results']             =  $webmaster['results'];
            $this->_search_conditions['page']       = $page; 
            $this->_search_conditions['pagesize']   = $pagesize; 
            $this->_response['search_conditions']   = $this->_search_conditions;
            $this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__, __FUNCTION__, $this->input->ip_address()));
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
     * create 新增页面 | 渲染页面模板
     *	申请开通微站
     * @access public
     * @return mixed
     */
    function create() {
        try {
        	$data = $this->logic('public/Logic_webmaster')->create();
        	$this->_response['success'] = true;
			$this->_response['results'] = $data;
			
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
     * 保存申请开通微站的信息
     * @access public
     * @return mixed
     */
    function save() {
        $form_name= 'c';
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
//         $this->log->debug(var_export($this->input->post(), TRUE));
        try {
        	$post_data  = $this->input->post($form_name);
            $result = $this->logic('public/Logic_webmaster')->savewebmaster($post_data);
                        
            $this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
                        __FUNCTION__, $this->input->ip_address())
                    );
            //跳转到成功之后的页面
            $domain = $this->config->item("basedomain");
            $url 	= $domain."/webmaster/master";
            header("location: " . $url);
            exit;
            
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
     * detail 查看
     *
     * @access public
     * @return mixed
     */
    function detail() {
        try {
            $id = intval($this->input->get('id'));
            if ($id <=0 ){
                throw new Exception(sprintf('%s input parameter:q must not empty.', __FUNCTION__),
                        $this->config->item('parameter_err_no', 'err_no')
                        );
            }
            $c = $this->logic('public/Logic_webmaster')->detail($id);
            $this->_response['results'] = $c['webmaster'];
            $this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
                        __FUNCTION__, $this->input->ip_address())
                    );
        } catch(Exception $e) {
            $this->_response['success'] = FALSE;
            $this->_response['err_no']  = $e->getCode();
            $this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
            $this->log->debug(sprintf('%s:%s process error err_msg:%s., ip:%s', $e->getFile(),
                        $e->getLine(), $e->getMessage(), $this->input->ip_address())
                    );
        }
    }
    
    /**
     * 修改微站资料-获取站长信息
     * 页面地址 http://base.wz.yixun.com/webmaster/edit
     * @access public
     * @return mixed
     */
    function edit() {
        try {
        	//获取站长资料和微站资料
        	$c = $this->logic('public/Logic_webmaster')->edit();
        	$this->_response['success'] = true;
        	$this->_response['results'] = $c['webmaster'];
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
     * 修改微站热销活动基本资料
     * 页面地址 http://base.wz.yixun.com/webmaster/hotActEdit
     * @access public
     * @return mixed
     */
    function hotActEdit() {
        try {
        	//获取站长资料和微站资料
        	$c = $this->logic('public/Logic_webmaster')->edit();
        	$this->_response['success'] = true;
        	$this->_response['results'] = $c['webmaster'];
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
     * save 保存
     * 修改微站资料-一期包括修改微站招牌（2个表）| 接口
     * @access public
     * @return mixed
     */
    function saveMaster() {
    	try {
    		$form_name= 'c';
    		$this->load->helper(array('form', 'url'));
    		$this->load->library('form_validation');
    		$this->log->debug(var_export($this->input->post(), TRUE));
    		
    		$post_data = $this->input->post($form_name);
    		$result = $this->logic('public/Logic_webmaster')->saveMaster($post_data);
    		
    		$this->_response['success'] = $result;
    		$this->_response['results'] = $post_data ;//修改的微站数据
    		
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
            $this->logic('public/Logic_webmaster')->delete(array('is_deleted'=>$is_deleted), $id);
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