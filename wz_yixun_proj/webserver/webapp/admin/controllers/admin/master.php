<?php
/**
 * Admin后台站长管理
 * 
 * @uses Wd
 * @uses _Controller
 * @package 
 * @version $1.0$
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author fishxyu <fishxyu@yixun.com> 
 * @license 
 */
class Master extends Adm_controller {
    /**
     * _format  返回格式
     *
     * @var string
     * @access protected
     */
    protected  $_format         = 'json';
    /**
     * _intval_items  列表项 搜索条件
     *
     * @var int
     * @access protected
     */
    protected  $_intval_items            = array('mobile','iccard','authentication','conditions');
    /**
     * _com_items 检索项  搜索条件
     *
     * @var string
     * @access protected
     */
    protected  $_com_items           = array('username');
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
     * 后台登陆
     * 
     * @access public
     * @return mixed
     */
    function login() {
    	try {
    		
    		$this->_response['results'] = array();
    		
    		$this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
    				__FUNCTION__, $this->input->ip_address())
    		);
    	}catch (Exception $e) {
    		$this->_response['success'] = FALSE;
    		$this->_response['err_no']  = $e->getCode();
    		$this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
    		$this->log->warn(sprintf('%s:%s process error err_msg:%s. ip:%s', $e->getFile(),
    				$e->getLine(), $e->getMessage(), $this->input->ip_address())
    		);
    	}
    }
    
    /**
     * 后台登陆
     *
     * @access public
     * @return mixed
     */
    function savelogin() {
    	$form_name= 'c';
    	$this->load->helper(array('form', 'url'));
    	$this->load->library('form_validation');
    	$this->log->debug(var_export($this->input->post(), TRUE));
    	try {
    		$post_data  = $this->input->post($form_name);
    		$results = $this->logic('admin/Logic_master')->savelogin($post_data);
    		if ($results == false) {
    			$domain = $this->config->item("admindomain");
    			$url = $domain."admin/master/login";
    			header("location: " . $url);
    			exit;
    		}
    		$this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
    				__FUNCTION__, $this->input->ip_address())
    		);
    		exit;
    	}catch (Exception $e) {
    		$this->_response['success'] = FALSE;
    		$this->_response['err_no']  = $e->getCode();
    		$this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
    		$this->log->warn(sprintf('%s:%s process error err_msg:%s. ip:%s', $e->getFile(),
    				$e->getLine(), $e->getMessage(), $this->input->ip_address())
    		);
    	}
    }
    
    /**
     * 后台退出
     *
     * @access public
     * @return mixed
     */
    function logout() {
    	try {
    		setcookie('wzadminuid','10000',time()-1,'/','admin.wz.icson.com','');
    		setcookie('wzadminname','admin',time()-1,'/','admin.wz.icson.com','');
    		$domain = $this->config->item("admindomain");
    		$url = $domain."admin/master/login";
    		header("location: " . $url);
    		exit;
    	}catch (Exception $e) {
    		$this->_response['success'] = FALSE;
    		$this->_response['err_no']  = $e->getCode();
    		$this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
    		$this->log->warn(sprintf('%s:%s process error err_msg:%s. ip:%s', $e->getFile(),
    				$e->getLine(), $e->getMessage(), $this->input->ip_address())
    		);
    	}
    }
    
    /**
     * admin首页
     * 
     * @access public
     * @return mixed
     */
    function index() {
    	try {
    		$this->_response['success'] = true;
    		$this->_response['results'] = array();
    	}catch (Exception $e) {
    		$this->_response['success'] = FALSE;
    		$this->_response['err_no']  = $e->getCode();
    		$this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
    		$this->log->warn(sprintf('%s:%s process error err_msg:%s. ip:%s', $e->getFile(),
    				$e->getLine(), $e->getMessage(), $this->input->ip_address())
    		);
    	}
    }
    
    /**
     * 管理微站页面
     * 
     * @access public
     * @return mixed
     */
    function masterList() {
    	try {
    		$page = 1;
    		$pagesize = 10;
    		foreach ($this->_intval_items as $search_item) {
    			$search_item_value = intval($this->input->get($search_item));
    			if ($search_item_value>0)
    				$this->_search_conditions[$search_item]  =  $search_item_value;
    		}
    		foreach ($this->_com_items as $search_item) {
    			$search_item_value = $this->input->get($search_item);
    			if (!empty($search_item_value))
    				$this->_search_conditions[$search_item]  =  $search_item_value;
    		}
    		$master= $this->logic('admin/Logic_master')->search($this->_search_conditions, $page, $pagesize);
    		
    		$this->_response['success'] 			= true;
    		$this->_response['num']                 = $master['num'];
    		$this->_response['results'] 			= $master;
    		$this->_search_conditions['page']       = $page;
    		$this->_search_conditions['pagesize']   = $pagesize;
    		$this->_response['search_conditions']   = $this->_search_conditions;
    		$this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
    				__FUNCTION__, $this->input->ip_address())
    		);
    	}catch (Exception $e) {
    		$this->_response['success'] = FALSE;
    		$this->_response['err_no']  = $e->getCode();
    		$this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
    		$this->log->warn(sprintf('%s:%s process error err_msg:%s. ip:%s', $e->getFile(),
    				$e->getLine(), $e->getMessage(), $this->input->ip_address())
    		);
    	}
    }
    
    /**
     * 查询微站站长列表
     *
     * @access public
     * @return mixed
     */
    function search() {
        try {
            $page       = max(intval($this->input->get('page')), 1);
            $pagesize   = intval($this->input->get('pagesize'))>0 ?
                intval($this->input->get('pagesize')) : $this->config->item('pagesize');
            foreach ($this->_intval_items as $search_item) {
                $search_item_value = intval($this->input->get($search_item));
                if ($search_item_value>0)
                    $this->_search_conditions[$search_item]  =  $search_item_value;
            }
            foreach ($this->_com_items as $search_item) {
                $search_item_value = $this->input->get($search_item);
                if (!empty($search_item_value))
                    $this->_search_conditions[$search_item]  =  $search_item_value;
            }
            
            $master= $this->logic('admin/Logic_master')->search($this->_search_conditions, $page, $pagesize);
            
            //分页
            $this->load->library('pagination');
            $config['total_rows'] = $master['num'];//总数
         	$config['per_page'] = 10;//分页数
            $this->pagination->initialize($config);
            $pages = $this->pagination->create_links();
            
            $this->_response['num']                 = $master['num'];
            $this->_response['results']             = $master['results'];
            $this->_response['pages']             	= $pages;
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
     * 查询已认证的微站站长列表
     *
     * @access public
     * @return mixed
     */
    function searchMaster() {
    	try {
    		$page       = max(intval($this->input->get('page')), 1);
    		$pagesize   = intval($this->input->get('pagesize'))>0 ?
    		intval($this->input->get('pagesize')) : $this->config->item('pagesize');
    		foreach ($this->_intval_items as $search_item) {
    			$search_item_value = intval($this->input->get($search_item));
    			if ($search_item_value>0)
    				$this->_search_conditions[$search_item]  =  $search_item_value;
    		}
    		foreach ($this->_com_items as $search_item) {
    			$search_item_value = $this->input->get($search_item);
    			if (!empty($search_item_value))
    				$this->_search_conditions[$search_item]  =  $search_item_value;
    		}
    		//已认证
    		$this->_search_conditions['conditions'] = 1;
    
    		$master= $this->logic('admin/Logic_master')->search($this->_search_conditions, $page, $pagesize);
    
    		//分页
    		$this->load->library('pagination');
    		$config['total_rows'] = $master['num'];//总数
    		$this->pagination->initialize($config);
    		$pages = $this->pagination->create_links();
    
    		$this->_response['num']                 = $master['num'];
    		$this->_response['results']             = $master['results'];
    		$this->_response['pages']             	= $pages;
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
     * create 新增页面
     *
     * @access public
     * @return mixed
     */
    function create() {
        try {
          
            //$this->_response['results'] = $this->logic('article/Logic_webmaster')->create();
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
                $id         = $this->logic('article/Logic_webmaster') ->save($post_data);
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
     * 微站详细信息
     * 
     * @access public
     * @return mixed
     */
    function detail( $wzid = 0 ) {
        try {
//             $wzid = intval($this->input->get('wzid'));
            $wzid = intval($wzid);
            $c = $this->logic('admin/Logic_master')->detail($wzid);
            $this->_response['results'] = $c['webmaster'];
            $this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
                        __FUNCTION__, $this->input->ip_address())
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
     * 微站详细信息
     *
     * @access public
     * @return mixed
     */
    function detailMaster( $msid = 0 ) {
    	try {
    		$msid = intval($msid);//站长id
    		$c = $this->logic('admin/Logic_master')->detail($msid);
    		$this->_response['results'] = $c['webmaster'];
    		$this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
    				__FUNCTION__, $this->input->ip_address())
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
     * save 保存
     *
     * @access public
     * @return mixed
     */
    function saveProduct() {
    	$form_name= 'c';
    	$this->load->helper(array('form', 'url'));
    	$this->load->library('form_validation');
    	$this->log->debug(var_export($this->input->post(), TRUE));
    	try {
    		$post_data  = $this->input->post($form_name);
    		$result = $this->logic('admin/Logic_master')->saveProduct($post_data);
    		$this->_response['success'] = $result;
    		$this->_response['results'] = $post_data ;//修改的微站数据
    		
    		$this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
    				__FUNCTION__, $this->input->ip_address())
    		);
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
     * 通过审核
     * 
     * @access public
     * @return mixed
     */
    function pass( ) {
    	try {
    		$form_name= 'c';
    		$this->load->helper(array('form', 'url'));
    		$this->load->library('form_validation');
    		$this->log->debug(var_export($this->input->post(), TRUE));
    	
    		$post_data = $this->input->post($form_name);
    		$result = $this->logic('admin/Logic_master')->pass($post_data);
    	
    		$this->_response['success'] = $result;
    		$this->_response['results'] = $post_data ;//修改的微站数据
    	
    		$this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
    				__FUNCTION__, $this->input->ip_address())
    		);
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
     * 拒绝审核
     * 
     * @access public
     * @return mixed
     */
    function refuse() {
    	try {
    		$form_name= 'c';
    		$this->load->helper(array('form', 'url'));
    		$this->load->library('form_validation');
    		$this->log->debug(var_export($this->input->post(), TRUE));
    		 
    		$post_data = $this->input->post($form_name);
    		$result = $this->logic('admin/Logic_master')->refuse($post_data);
    		 
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
     * 关闭微站
     * 
     * @access public
     * @return mixed
     */
    function close() {
    	try {
    		$form_name= 'c';
    		$this->load->helper(array('form', 'url'));
    		$this->load->library('form_validation');
    		$this->log->debug(var_export($this->input->post(), TRUE));
    		 
    		$post_data = $this->input->post($form_name);
    		$result = $this->logic('admin/Logic_master')->close($post_data);
    		 
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
     * edit 修改
     *
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
            $c = $this->logic('article/Logic_webmaster')->edit($id);
            $this->_response['results'] = $c;
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
            if ($this->form_validation->run() === FALSE) {
                $post_data = $this->input->post($form_name);
                $this->_validate_error($form_name, $post_data);
                throw new Exception(sprintf('%s input parameters  must not be entire.', __FUNCTION__),
                        $this->config->item('parameter_err_no', 'err_no')
                       );
            } else {
                $post_data = $this->input->post($form_name);
                $id = $this->logic('article/Logic_webmaster') ->set($post_data);
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
            $this->logic('article/Logic_webmaster')->delete(array('is_deleted'=>$is_deleted), $id);
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