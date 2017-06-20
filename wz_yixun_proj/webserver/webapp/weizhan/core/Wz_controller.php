<?php

/**
 * My_controller2.0
 *
 * @uses CI
 * @uses _Controller
 * @package
 * @version $id$
 * @copyright Copyright (c) 2012-2013 Yicheng Co. All Rights Reserved.
 * @author Guojing Liu <liuguojing@51chance.com.cn>
 * @license
 */
class Wz_controller extends CI_Controller {
    /**
     * uid
     *
     * @var float
     * @access public
     */
    public $uid=FALSE;
    /**
     * title
     *
     * @var string
     * @access public
     */
    public $title = 'weidian';
    /**
     * user_info
     *
     * @var array
     * @access public
     */
    public $user_info=array();
    /**
     * test
     *
     * @var mixed
     * @access public
     */
    public $test = FALSE; // 是否开发环境
    /**
     * ver
     *
     * @var string
     * @access public
     */
    public $ver = '0.58'; // 版本号
    /**

     * _response
     *
     * @var string
     * @access protected
     */
    protected $_response = array('err_no'=>0, 'err_msg'=>'', 'results'=>array(), 'success'=>TRUE);
    /**
     * _format
     *
     * @var string
     * @access protected
     */
    protected $_format = '';
    /**
     * _tpl
     *
     * @var string
     * @access protected
     */
    protected $_tpl    = 'default_json';
    /**
     * _tn
     *
     * @var string
     * @access protected
     */
    protected $_tn     = '';
    /**
     * _is_login
     *
     * @var mixed
     * @access protected
     */
    protected $_is_login     = TRUE;
    protected $our_dictionary = array();
    
    public $_top_industries = array();
    
    public $_my_sub_industries = array();
    
    public $all_industries = array();
    
    public $_cms_top_industry_id = 0;
    
    public $_cms_sub_industry_id = 0;
    
    private  $_default_industry = array(1=>1,2=>2,3=>5);
    /**
     * 继承父类构造函数
     */
    /**
     * __construct
     *
     * @access protected
     * @return mixed
     */
    function __construct() {
        parent::__construct();
        $this->_response['frontdomain'] =  $this->config->item('frontdomain');
    }
   
    /**
     * logic
     *
     * @param mixed $model
     * @access public
     * @return mixed
     */
    function logic($logic){
        $this->load->logic($logic);
        if (($last_slash = strrpos($logic, '/')) !== FALSE) {
            $logic = substr($logic, $last_slash + 1);
        }
        return $this->$logic;
    }

    /**
     * 判断编码是否为UTF8
     * @param $str
     */
    function isUtf81($str) {
        return 1 == preg_match('%^(?:[\x09\x0A\x0D\x20-\x7E]'.  // ASCII
                    '| [\xC2-\xDF][\x80-\xBF]'.             //non-overlong 2-byte
                    '| \xE0[\xA0-\xBF][\x80-\xBF]'.         //excluding overlongs
                    '| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}'.  //straight 3-byte
                    '| \xED[\x80-\x9F][\x80-\xBF]'.         //excluding surrogates
                    '| \xF0[\x90-\xBF][\x80-\xBF]{2}'.      //planes 1-3
                    '| [\xF1-\xF3][\x80-\xBF]{3}'.          //planes 4-15
                    '| \xF4[\x80-\x8F][\x80-\xBF]{2}'.      //plane 16
                    ')*$%xs', $str);
    }

    /**
     * 检测邮箱
     * @param char $email
     * @return bool
     */
    function check_email($email) {
        $this->load->helper('email');
        if (! valid_email($email)) {
            return false;
        }
        return true;
    }

    /**
     * 检测手机号
     * @param char $phone
     * @return bool
     */
    function check_phone($phone) {
        $pattern="/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|18[0-9]{9}$/";
        if(! preg_match($pattern,$phone)) {
            Return false;
        }
        return true;
    }

    /**
     * json_encode show
     * @param array $data
     */
    function message_encode($data) {
        //echo json_encode($data);
        $this->load->view('default_json', array('json_data'=>json_encode($data)));
        exit;
    }

    /**
     * _validate_error
     *
     * @param mixed $form
     * @param mixed $post_data
     * @param mixed $this->_response
     * @access protected
     * @return mixed
     */
    protected function _validate_error($form, $post_data){
        if (empty($form) || !is_array($post_data)){
            throw new Exception(sprintf('function: %s, parameter: post_data must be array.', __FUNCTION__),
                    $this->config->item('parameter_err_no', 'err_no')
                    );
        }
        $errors = $this->form_validation->error_array();
        foreach ($errors as $k=>$v){
            preg_match_all('/\[([a-zA-Z_0-9]+)\]/', $k, $matches);
            list($table, $column) =  $matches[1];
            if (!empty($v)){
                $this->_response['err_no'] = $this->config->item('input_validate_err_no', 'err_no');
                $this->_response['results'][$table][$column]['err_no']  =
                    $this->config->item('input_validate_err_no', 'err_no');
                $this->_response['results'][$table][$column]['err_msg'] =  $v;
            }else{
                $this->_response['results'][$table][$column] = array('err_no'=>0, 'err_msg'=>'');
            }

        }
        if ($this->_response['err_no'] != 0){
            throw new Exception(sprintf('validate input data error:%d',$this->_response['err_no']), $this->_response['err_no']);
        }
    }
    /**
     * _output
     *
     * @access protected
     * @return mixed
     */
    function _output($output) {
        $this->load->helper('my_mystr');
        if ($this->_format == 'json'){
            $callback = $this->input->get('callback');
            preg_match('/^((\w|\.)+)$/', $callback, $matches);
            if (empty($matches)) $callback = '';
            header('Content-Type: application/json;charset=utf-8', TRUE);
            if ($this->_tn != '' && $this->_response['err_no'] ==0){
                $RTR =& load_class('Router', 'core');
                $this->_tpl = sprintf('%s/%s%s', $RTR->fetch_class(), $RTR->fetch_method(), $this->_tn);
                $this->_response['cur_num'] = sizeof( $this->_response['results']);
                if (!in_array($RTR->fetch_method(), array('edit', 'create')))
                    array_walk_recursive($this->_response, 'my_htmlspecialchars');
                $this->_response['results'] = $this->load->view($this->_tpl, $this->_response, TRUE);
            }elseif ('default_json' != $this->_tpl){
                $this->_response['results'] = $this->load->view($this->_tpl, $this->_response, TRUE);
            }
            echo $this->load->view('default_json', array('json_data'=>json_encode($this->_response), 'callback'=>$callback), TRUE);
        }else{
            header('content-type:text/html;charset=utf-8');
            $RTR =& load_class('Router', 'core');
            if ('default_json' == $this->_tpl){
                
                $this->_tpl = sprintf('%s/%s%s', $RTR->fetch_class(), $RTR->fetch_method(), $this->_tn);
                if (!in_array($RTR->fetch_method(), array('edit', 'create')))
                    array_walk_recursive($this->_response, 'my_htmlspecialchars');
                $inc_title = isset($this->_response['results']['html_title']) ?  $this->_response['results']['html_title'] : '';
                $this->load->vars('title', sprintf('%s_%s', $inc_title , $this->title));
                echo $this->load->view($this->_tpl, $this->_response, TRUE);
        }
        }
    }
    /**
     * check_permission
     *
     * @access public
     * @return mixed
     */
    protected function _check_permission($model=0, $id=0) {
        if ($id<=0 || strlen($model) ==0){
            throw new Exception(sprintf('%s input parameter:uid:%s must not be greater than 0 and model:%s\'length must be greater than 0.',
                        __FUNCTION__, $id, $model),$this->config->item('parameter_err_no', 'err_no')
                    );
        }

        $raw_data = $this->model($model)->fetch_one_by_id($id);
        if ($this->uid[getmypid()] == 1 || $raw_data['user_id'] == $this->uid[getmypid()] || $raw_data['bd_consultants'] == $this->uid[getmypid()] ||
                ($raw_data['is_reviewed'] == 'I' && $raw_data['bd_consultants']<=0)){
        }else{
            throw new Exception(sprintf('%s login_user:%d has no permission to edit:%d [user_id:%d].', __FUNCTION__,
                        $this->uid[getmypid()], $id, $raw_data['user_id']),
                    $this->config->item('permission_err_no', 'err_no')
                    );
        }
    }
    
    /**
     * print_debug 
     * 
     * @param array $params 
     * @access public
     * @return mixed
     */
    function print_debug($params = array()) {
        var_dump($params);
    }

    function __destruct() {
       
    }
}
