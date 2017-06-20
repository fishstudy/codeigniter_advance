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
class Bs_controller extends CI_Controller {
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
	 * 用户登陆判断缓存前缀
	 */
    protected $_pre_userlogin = 'pre_user_login_';
    
    /**
     * 需要登陆跳转的和保存数据的action数组,其余的返回JSON数据
     * 
     * class_method 渲染页面都需要做判断
     * @var array
     * @access protected
     */
    protected $redirect_action = array(
    		'articleinfo_articlelist',		//微站-文章列表
    		'articleinfo_zdmlist',			//微站-导入文章列表-易选文章列表
    		'articleinfo_savearticle',		//微站-导入文章到我的文章
    		'webmaster_edit',				//资料管理
    		'webmaster_index',				//微站后台首页
    		'webmaster_create',				//申请微站页
    		'webmaster_master', 			//跳转到不同的审核结果页面
    		'webmaster_savemaster', 		//保存微站资料
    		'shopproduct_recommandSearch', 	//
    		'website_actbasic',				//基本设置
    		'website_savebasic',			//保存基本设置
    		'articleinfo_search',			//文章分类搜索文章
            'shopproduct_search',  			//商品列表
            'shopproduct_hotSearch',  		//热销爆品列表
            'shopproduct_hotSearchData',  	//热销爆品列表数据读取
            'shopproduct_qiangSearch',  	//抢购商品数据
            'shopproduct_qiangSearchJson',  //抢购商品数据读取
            'shopproduct_selectionSearch',  //精选商品
            'shopproduct_selectionSearchJson',  //精选商品
            'shopproduct_save',  			//商品保存
            'shopproduct_qiangSave',  		//抢购商品保存
            'shopproduct_hotSave',  		//热销商品保存
            'shopproduct_selectionSave',  	//精选商品保存
            'shopproduct_recommandSave',  	//达人推荐保存
            'shopcatalog_save',  			//商品类目保存
            'shopcatalog_search',  			//商品类目保存
            'articlekind_search',  			//文章类目保存
            'articlekind_save',  			//文章类目保存
    );
    
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
        $this->_response['basedomain'] =  $this->config->item('basedomain');
        $this->_response['frontdomain'] =  $this->config->item('frontdomain');
        $RTR =& load_class('Router', 'core');
        $temp_path = $RTR->fetch_class()."_".$RTR->fetch_method();
        //统一登陆
        $format = $this->input->get('format');
        if ($this->is_login(ENVIRONMENT) === false) {
            if(in_array($temp_path, $this->redirect_action)){
                $backurl = $this->nologin_redirect( $format );
            }
            
        	$this->_response['success'] = FALSE;
        	$this->_response['login_url'] = $backurl;
        	$err_code = $this->config->item('islogin_err_no', 'err_no');
        	$this->_response['err_no']  = $err_code;
        	$this->_response['err_msg'] = $this->config->item($err_code, 'err_msg');
        	header("Content-type: application/json");
        	echo json_encode($this->_response);
        	exit;
        }else {
        	$masterinfo = $this->logic('public/Logic_webmaster')->searchMaster($_COOKIE['uid']);
        	$msid = empty($masterinfo['webmasterinfo']['id'])?"":$masterinfo['webmasterinfo']['id'];	//站长id
        	$wzid = empty($masterinfo['websiteinfo']['id'])?"":$masterinfo['websiteinfo']['id'];	//微站id
        	$this->_response['success'] = true;
        	$this->_response['msid']  = $msid;//站长id
        	$this->_response['wzid']  = $wzid;//微站id
        }
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
     * 判断用户是否登陆，并根据不同环境做不同的处理方式
     * dev 开发环境| gamma 测试环境 | idc 生成环境
     * 
     * @access puclic 
     * @return mixed
     */
    function is_login( $format = ENVIRONMENT ){
    	$result = false;
    	if ($format == ENVIRONMENT) {
    		//skey,wg_skey,ptskey,uid
    		if ( !empty($_COOKIE['uid']) || !empty($_COOKIE['skey']) ) {
    			$online = $this->isOnline();//登陆超时处理
    			if ( $online ) {
	    			$this->uid = $_COOKIE['uid'];
	    			$this->user_info = array('uid'=> $_COOKIE['uid']);
	    			$result = true;
    			}else {    				
    				$result = false;
    			}
    		}else {
    			$result = false;
    		}
    	}elseif ($format == 'gamma'){
    		//skey,wg_skey,ptskey,uid
    		if ( !empty($_COOKIE['uid']) || !empty($_COOKIE['skey']) ) {
    			$online = $this->isOnline();//登陆超时处理
    			if ( $online ) {
	    			$this->uid = $_COOKIE['uid'];
	    			$this->user_info = array('uid'=> $_COOKIE['uid']);
	    			$result = true;
    			}else {    				
    				$result = false;
    			}
    		}else {
    			$result = false;
    		}
    		$result = false;
    	}elseif ($format == 'idc'){
    		//skey,wg_skey,ptskey,uid
    		if ( !empty($_COOKIE['uid']) || !empty($_COOKIE['skey']) ) {
    			$online = $this->isOnline();//登陆超时处理
    			if ( $online ) {
	    			$this->uid = $_COOKIE['uid'];
	    			$this->user_info = array('uid'=> $_COOKIE['uid']);
	    			$result = true;
    			}else {    				
    				$result = false;
    			}
    		}else {
    			$result = false;
    		}
    		$result = false;
    	}else {
    		//
    		$result = false;
    	}
    	return $result;
    }
    
    /**
     * 判断登陆用户是否超时处理
     * 
     * @return bool
     */
    function isOnline() {
    	$this->load->driver('cache', NULL, 'cache');
    	$login_key = sprintf("%s_%d", $this->_pre_userlogin, $_COOKIE['uid']);
    	$login_res = $this->cache->redis->get($login_key);
    	if ($login_res) {
    		return true;
    	}else {
	    	if (empty($_COOKIE['skey'])) {
	    		return false;
	    	}
	    	//算法 skey: ic4F19E3C2  change: 1785325346  
	    	$ckey = $_COOKIE['skey'];
	    	$clength = strlen($ckey);
	    	for ( $i=0, $c=5381; $i < $clength; ++$i) {
	    		$ikey = substr($ckey, $i, 1);
	    		$c += ($c << 5) + ord($ikey);
	    	}
	    	$skey = $c & 2147483647;
	    	
	    	$pro_login_url = $this->config->item('baselogin_url');
	    	$url = $pro_login_url."login/getuserinfo?e_appid=1&isseller=0&encoding=gbk&g_tk=".$skey."&g_ty=jq";
	    	$cookie = '';
	    	foreach ($_COOKIE as $ck=>$cv){
	    		$cookie .= "$ck=$cv;";
	    	}
	    	$curl = curl_init();
	    	curl_setopt($curl, CURLOPT_URL, $url);
	    	curl_setopt($curl, CURLOPT_COOKIE, $cookie);//把浏览器里的cookie全部带上
	    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	    	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    	$curl_res = curl_exec($curl);
	    	curl_close($curl);
	    	$back_res = iconv("GBK", "UTF-8//IGNORE", $curl_res);
	    	$wzRes = json_decode($back_res, true);
	    	if ( $wzRes['errno']==0 && !empty($wzRes['data']['uid']) ) {
	    		$this->cache->redis->set($login_key, $curl_res);
	    		$this->cache->redis->setTimeout($login_key, $this->config->item('wzbase_login_cache')); //设置缓存失效时间
	    		return true;
	    	}else {
	    		return false;
	    	}
    	}	
    }
    
    /**
     * 判断编码是否为UTF8
     * @param $str
     */
    function isUtf8($str) {
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
    
    public static function nologin_redirect( $format = '' ) {
        $redirectUrl = 'login.html';
        $protocol = 'https';
        $currentUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $domainName = (false !== strpos($_SERVER['HTTP_HOST'], '.yixun.com')) ? 'yixun.com' : '51buy.com';
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: max-age=0');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        $url = $protocol .'://base.'.$domainName.'/'.$redirectUrl.'?url=' . urlencode($currentUrl);
        if ($format != 'json') {
	        header("location: " . $url);
        }

        return $url;
    }
    
    /**
     * print_debug 
     * 
     * @param array $params 
     * @access public
     * @return mixed
     */
    function print_debug($params = array()) {
        echo "<pre>";
        print_r($params);
        echo "</pre>";
    }

    function __destruct() {
       
    }
}
