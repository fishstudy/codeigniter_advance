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
    protected  $_format         = 'json';
    /**
     * _ilist_items  列表项 搜索条件
     *
     * @var array
     * @access protected
     */
    protected  $_ilist_items            = array();
    /**
     * _ilist_items2 检索项  搜索条件
     *
     * @var string
     * @access protected
     */
    protected  $_ilist_items2           = array();
            /**
     * _search_conditions  搜索条件
     *
     * @var array
     * @access protected
     */
    protected  $_search_conditions      = array(
            );

    /*
     *  首页文章数量
     */
    protected $home_Page_Article;
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
     * index 列表
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
            $articleinfo= $this->logic('articleinfo/Logic_articleinfo')
                ->search($this->_search_conditions, $page, $pagesize);
            $this->_response['num']                 =  $articleinfo['num'];
            $this->_response['results']             =  $articleinfo['results'];
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
            $c = $this->logic('articleinfo/Logic_articleinfo')->detail($id);
            $this->_response['results'] = $c;
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
            $c = $this->logic('articleinfo/Logic_articleinfo')->edit($id);
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
                $id = $this->logic('articleinfo/Logic_articleinfo') ->set($post_data);
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

    /*
     * 微店首页查询
     */
    function homePage(){
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
            $articleinfo= $this->logic('articleinfo/Logic_articleinfo')
                ->search($this->_search_conditions, $page, $pagesize);
            $this->_response['num']                 =  $articleinfo['num'];
            $this->_response['results']             =  $articleinfo['results'];
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
}
?>
