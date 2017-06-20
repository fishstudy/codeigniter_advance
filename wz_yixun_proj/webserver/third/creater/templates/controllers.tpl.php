<?php
/**
 * {{$NAME}}
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author weidian team <fishxyu@yixun.com> 
 * @license 
 */
class {{$NAME}} extends {{$prefix}}_controller {
    /**
     * _format  返回格式
     */
    protected  $_format         = 'json';
    /**
     * _intval_lists  整数 搜索条件
     */
    protected  $_intval_lists            = array({{if !empty($INTVAL_LISTS)}}{{foreach $INTVAL_LISTS as $k}}'{{$k}}',{{/foreach}}{{/if}});
    /**
     * _com_lists 非整数  搜索条件
     */
    protected  $_com_lists           = array({{if !empty($COM_ILISTS)}}{{foreach $COM_ILISTS as $k}}'{{$k}}',{{/foreach}}{{/if}});
    {{if !empty($SEARCH_INTVAL_ITEM)}}
    /**
     * _search_intval_items  搜索项 intval转换
     *
     * @var string
     * @access protected
     */
    protected  $_search_intval_items    = array({{foreach $SEARCH_INTVAL_ITEM as $k}}'{{$k}}',{{/foreach}});
    {{/if}}
    {{if !empty($SEARCH_ITEM)}}
    /**
     * _search_items 搜索项 不需要intval
     *
     * @var string
     * @access protected
     */
    protected  $_search_items           = array({{foreach $SEARCH_ITEM as $k}}'{{$k}}',{{/foreach}});
    {{/if}}
 
    /**
     * _search_conditions  搜索条件
     */
    protected  $_search_conditions      = array({{foreach $SEARCH_CONDITIONS as $k => $v }}'{{$k}}' => '{{$v}}',{{/foreach}});
    
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
     *  列表
     *
     * @access public
     * @return mixed
     */
    public function search() {
        try {
            $pagesize   = intval($this->input->get('pagesize'))>0 ?
                intval($this->input->get('pagesize')) : $this->config->item('pagesize');
            $page       = max(intval($this->input->get('page')), 1);
            foreach ($this->_intval_lists as $search_item) {
                $search_item_value = intval($this->input->get($search_item));
                if ($search_item_value>0) 
                    $this->_search_conditions[$search_item]  =  $search_item_value;
            }
            foreach ($this->_com_lists as $search_item) {
                $search_item_value = $this->input->get($search_item);
                if (!empty($search_item_value))
                    $this->_search_conditions[$search_item]  =  $search_item_value;
            }
            ${{$CONTROLLER}}= $this->logic('{{$LOGIC}}')
                ->search($this->_search_conditions, $page, $pagesize);
            $this->_response['num']                 =  ${{$CONTROLLER}}['num'];
            $this->_response['results']             =  ${{$CONTROLLER}}['results'];
            $this->_search_conditions['page']       = $page; 
            $this->_search_conditions['pagesize']   = $pagesize; 
            $this->_response['search_conditions']   = $this->_search_conditions;
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
     *  新增页面
     *
     * @access public
     * @return mixed
     */
    public function create() {
        try {
            $this->_response['results'] = $this->logic('{{$LOGIC}}')->create();
            $this->log->debug(sprintf('%s:%s ip:%s process ok.', __CLASS__,
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
     *  保存
     *
     * @access public
     * @return mixed
     */
    public function save() {
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
                $id         = $this->logic('{{$LOGIC}}') ->save($post_data);
                $this->_response['id'] = $id;
            }
            $this->log->debug(sprintf('%s:%s ip:%s process ok.', __CLASS__,
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
     *  查看详情
     *
     * @access public
     * @return mixed
     */
    public function detail() {
        try {
            $id = intval($this->input->get('id'));
            if ($id <=0 ){
                throw new Exception(sprintf('%s input parameter:q must not empty.', __FUNCTION__),
                        $this->config->item('parameter_err_no', 'err_no')
                        );
            }
            $c = $this->logic('{{$LOGIC}}')->detail($id);
            $this->_response['results'] = $c;
            $this->log->debug(sprintf('%s:%s ip:%s process ok.', __CLASS__,
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
     * 修改
     *
     * @access public
     * @return mixed
     */
    public function edit() {
        try {
            $id = intval($this->input->get('id'));
            if ($id <=0 ){
                throw new Exception(sprintf('%s input parameter:q must not empty.', __FUNCTION__),
                        $this->config->item('parameter_err_no', 'err_no')
                        );
            }
            $c = $this->logic('{{$LOGIC}}')->edit($id);
            $this->_response['results'] = $c;
            $this->log->debug(sprintf('%s:%s ip:%s process ok.', __CLASS__,
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
     * set 设置 修改部分字段
     *
     * @access public
     * @return mixed
     */
    public function set() {
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
                if($post_data['id']) {
                   throw new Exception(sprintf('input parameter id:%s must be integer', $post_data['id']),
                        $this->config->item('input_parameter_err_no', 'err_no')
                        );
                }
                $id = $this->logic('{{$LOGIC}}') ->set($post_data);
                $this->_response['id'] = $id;
            }
            $this->log->debug(sprintf('%s:%s ip:%s process ok.', __CLASS__,
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
     * 删除（真删）
     *
     * @access public
     * @return mixed
     */
    public function del() {
        try {
            $id = $this->input->post('id');
            if ($id <=0 ){
                throw new Exception(sprintf('input parameter id:%s must be integer', $id),
                        $this->config->item('input_parameter_err_no', 'err_no')
                        );
            }
            $this->logic('{{$LOGIC}}')->delete($id,$uid);
            $this->log->debug(sprintf('%s:%s ip:%s process ok.', __CLASS__,
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
