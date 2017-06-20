<?php
/**
 * Shopproduct
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author weidian team <fishxyu@yixun.com> 
 * @license 
 */
class Shopproduct extends Bs_controller {
    /**
     * _format  返回格式
     */
    protected  $_format         = 'json';
    /**
     * _intval_lists  整数 搜索条件
     */
    protected  $_intval_lists            = array('page','pagesie','type','wzId','scId',);
    /**
     * _com_lists 非整数  搜索条件
     */
    protected  $_com_lists           = array();
         
    /**
     * _search_conditions  搜索条件
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
        $this->_search_conditions['page'] = $this->config->item('page');
        $this->_search_conditions['pagesize'] = $this->config->item('pagesize');
    }
    
    /**
     *  列表
     *
     * @access public
     * @return mixed
     */
    public function search() {
        try {
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
            
            $shopproduct= $this->logic('product/Logic_shopproduct')
                ->search($this->_search_conditions);
            $this->_response['num']                 =  $shopproduct['num'];
            $this->_response['results']             =  $shopproduct['results'];
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
     *  是否有相同商品
     *
     * @access public
     * @return mixed
     */
    protected function sameSearch($type,$pId,$id) {

            //设置搜索的站长id
            $this->_search_conditions['wzId']  =  $this->_response['wzid'];
            $this->_search_conditions['type']  =  $type;//类型
            $this->_search_conditions['pId']  =  $pId;//商品编号
            $shopproduct= $this->logic('product/Logic_shopproduct')
                ->search($this->_search_conditions);
             $shopproduct['results'] = array_values($shopproduct['results']);
            if($shopproduct['num']>0){
            if($id != $shopproduct['results'][0]['id'] || $shopproduct['num']>1){
                                throw new Exception(sprintf('%s the pId:'.$pId.'product already exist', __FUNCTION__),
                                    $this->config->item('product_already_exist', 'err_no'));   
            }
            }
    
        
    }
    /**
     *  热销爆品列表
     *
     * @access public
     * @return mixed
     */
    public function hotSearch() {
        //类目名称读取
        $wzId = intval($this->input->get('catalogId'));
        //设置搜索的站长id
        $this->_search_conditions['wzId'] = $this->_response['wzid'];
        $shopcatalog = $this->logic('product/Logic_shopcatalog')
                ->wzSearch($this->_search_conditions);
        $this->_response['num'] = $shopcatalog['num'];
        $this->_response['results'] = array_values($shopcatalog['results']);
        if ($wzId > 0 && is_int($wzId)) {
            $shopcatalog = $this->logic('product/Logic_shopcatalog')->detail($wzId);
            //var_dump($shopcatalog);
            $this->_response['catalogName'] = $shopcatalog['shopcatalog']['catalogName'];
        }
    }

    /**
     *  热销爆品列表
     *
     * @access public
     * @return mixed
     */
    public function hotSearchData() {
        try {
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
            //设置搜索的站长id
            $this->_search_conditions['wzId']  =  $this->_response['wzid'];
            //设置类型商品类型为2抢购商品
            $this->_search_conditions['type']  = 3;            
            $shopproduct= $this->logic('product/Logic_shopproduct')
                ->search($this->_search_conditions);
            $this->_response['num']                  =  $shopproduct['num'];
            $this->_response['results']             =  $shopproduct['results'];
            $this->_response['search_conditions'] = $this->_search_conditions;
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
    
    public function qiangSearch() {
    
        
    }
    /**
     *  抢购商品数据读取
     *
     * @access public
     * @return mixed
     */
    public function qiangSearchJson() {
        $this->_format = 'json';
        try {
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
            //设置搜索的站长id
            $this->_search_conditions['wzId']  =  $this->_response['wzid'];
            //设置类型商品类型为2抢购商品
            $this->_search_conditions['type']  = 2;
            $shopproduct= $this->logic('product/Logic_shopproduct')
                ->search($this->_search_conditions);
            
            $this->_response['num']                 =  $shopproduct['num'];
            $this->_response['results']             =  $shopproduct['results'];
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
     *  达人推荐商品
     *
     * @access public
     * @return mixed
     */
    public function recommandSearch() {
        try {
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
            //设置搜索的站长id
            $this->_search_conditions['wzId']  =  $this->_response['wzid'];
           //设置类型商品类型为1达人推荐
            $this->_search_conditions['type']  = 1;
            $shopproduct= $this->logic('product/Logic_shopproduct')
                ->recommandSearch($this->_search_conditions);
            $this->_response['num']                 =  $shopproduct['num'];
            $this->_response['results']             =  array_values($shopproduct['results']);
            $this->_response['search_conditions']   = $this->_search_conditions;
            //微站信息
            $tmp =$this->logic('public/Logic_website')->detail($this->_response['wzid'] );
           // print_r($tmp['website']['recommend']);exit;
            $this->_response['recommend'] = $tmp['website']['recommend'];
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
     *  精选商品
     *
     * @access public
     * @return mixed
     */
    public function selectionSearch() {
        
    } 
    /**
     *  精选商品
     *
     * @access public
     * @return mixed
     */
    public function selectionSearchJson() {
        $this->_format = 'json';
        try {
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
            //设置搜索的站长id
            $this->_search_conditions['wzId']  =  $this->_response['wzid'];
           //设置类型商品类型为4 精选商品
            $this->_search_conditions['type']  = 4;
//                        var_dump($this->_search_conditions);
            $shopproduct= $this->logic('product/Logic_shopproduct')
                ->search($this->_search_conditions);
//            var_dump($shopproduct);
            $this->_response['num']                 =  $shopproduct['num'];
            $this->_response['results']             =  array_values($shopproduct['results']);
            $this->_response['search_conditions']   = $this->_search_conditions;
            //微站信息
            $tmp =$this->logic('public/Logic_website')->detail($this->_response['wzid'] );
            $this->_response['recommend'] = $tmp['website']['recommend'];
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
            $this->_response['results'] = $this->logic('product/Logic_shopproduct')->create();
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
                $id         = $this->logic('product/Logic_shopproduct') ->save($post_data);
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
     *  抢购商品保存
     *
     * @access public
     * @return mixed
     */
    public function qiangSave() {
        $this->_format = 'json';
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
                //设置微站id
                $post_data['shopproduct']['wzId'] = $this->_response['wzid'];
                $this->sameSearch(2,$post_data['shopproduct']['pId'],$post_data['shopproduct']['id']);//是否已经添加过相同商品
                $id         = $this->logic('product/Logic_shopproduct') ->qiangSave($post_data);
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
     *  热销商品保存
     *
     * @access public
     * @return mixed
     */
    public function hotSave() {
        $this->_format = 'json';
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
                //设置微站id
                $post_data['shopproduct']['wzId'] = $this->_response['wzid'];
                $this->sameSearch(3,$post_data['shopproduct']['pId'],$post_data['shopproduct']['id']);//是否已经添加过相同商品
                $id         = $this->logic('product/Logic_shopproduct') ->hotSave($post_data);
                $this->_response['id'] = $id;
            }
            $this->log->debug(sprintf('%s:%s ip:%s process ok.', __CLASS__,
                        __FUNCTION__, $this->input->ip_address())
                    );
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
     *  精选商品保存
     *
     * @access public
     * @return mixed
     */
    public function selectionSave() {
        $this->_format = 'json';
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
                //设置微站id
                $post_data['shopproduct']['wzId'] = $this->_response['wzid'];
                $this->sameSearch(4,$post_data['shopproduct']['pId'],$post_data['shopproduct']['id']);//是否已经添加过相同商品
                $id         = $this->logic('product/Logic_shopproduct') ->selectionSave($post_data);
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
     *  达人推荐保存
     *
     * @access public
     * @return mixed
     */
    public function recommandSave() {
        $form_name= 'c';
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->log->debug(var_export($this->input->post(), TRUE));
        $data = $this->input->post($form_name);
        //搜索是否已经有达人推荐商品
        //设置搜索的站长id
        $this->_search_conditions['wzId']  =  $this->_response['wzid'];
       //设置类型商品类型为1达人推荐
        $this->_search_conditions['type']  = 1;
        $shopproduct = $this->logic('product/Logic_shopproduct')
                ->search($this->_search_conditions);
        if( $shopproduct['num'] == 1){
             $a = array_shift($shopproduct['results']);
             $data[0]['shopproduct']['id'] = $a['id'];
             $data[1]['shopproduct']['id'] = 0;
        }elseif($shopproduct['num'] >= 2){
             $a = array_shift($shopproduct['results']);
             $data[0]['shopproduct']['id'] = $a['id'];
             $b = array_shift($shopproduct['results']);
             $data[1]['shopproduct']['id'] = $b['id'];
        }else{
             $data[0]['shopproduct']['id'] = 0;
             $data[1]['shopproduct']['id'] = 0;
        }
        //设置商品类型
        $data[0]['shopproduct']['type'] = 1;
        $data[1]['shopproduct']['type'] = 1;
        //设置微站id
        $data[0]['shopproduct']['wzId'] = $this->_response['wzid'];
        $data[1]['shopproduct']['wzId'] = $this->_response['wzid'];

        //var_dump($post_data);
        try {
            if ($this->form_validation->run() === FALSE) {

               $this->_validate_error($form_name, $data);
               throw new Exception(sprintf('%s input parameters  must not be entire.', __FUNCTION__),
                       $this->config->item('parameter_err_no', 'err_no')
                      );
            } else {
                    foreach($data as $post_data){
                    $this->sameSearch(1,$post_data['shopproduct']['pId'],$post_data['shopproduct']['id']);//是否已经添加过相同商品
                    $id         = $this->logic('product/Logic_shopproduct') ->save($post_data);
                    $this->_response['id'] = $id;
                }
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
            $c = $this->logic('product/Logic_shopproduct')->detail($id);
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
            $c = $this->logic('product/Logic_shopproduct')->edit($id);
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
                $id = $this->logic('product/Logic_shopproduct') ->set($post_data);
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
         $this->_format = 'json';
        try {
            $id = $this->input->post('id');
            if ($id <=0 ){
                throw new Exception(sprintf('input parameter id:%s must be integer', $id),
                        $this->config->item('input_parameter_err_no', 'err_no')
                        );
            }
            $uid =  $this->_response['wzid'];//var_dump($id);var_dump($uid);
           
            $this->logic('product/Logic_shopproduct')->del($id,$uid);
            $this->log->debug(sprintf('%s:%s ip:%s process ok.', __CLASS__,
                        __FUNCTION__, $this->input->ip_address())
                    );
        } catch(Exception $e) {
            print_r($e);
            die();
            $this->_response['success'] = FALSE;
            $this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
            $this->_response['err_no']  = $e->getCode();
            $this->log->warn(sprintf('page %s:%s load failure. err_no:%d err_msg:%s ip:%s',
                        __CLASS__, __FUNCTION__,  $e->getCode(), $e->getMessage(), $this->input->ip_address())
                    );
        }
    }
}