<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * API数据接口类
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Logging
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/errors.html
 */
class CI_Api {

    public $_app_path;
    public $_api_app_path;
	
	/**
	 * Constructor
	 */
	public function __construct() {
        $this->_app_path = '/data/release/webserver/webapp/';
	}


    public function call_api($app, $model, $app_path, $function ,$params = array()) {
        try{
            $this->start_api($app);
            $return = $this->get_api($model, $app_path, $function ,$params);
            $this->end_api($app);
        } catch(Exception $e) {
            $this->end_api($app);
            throw $e; 
        }
        return $return;
    }
    
    /**
     * 准备调用路径
     * @param unknown_type $app
     */
    public function start_api($app) {
        global $_API_APP_START_PATH;
        $_API_APP_START_PATH = $this->_app_path . $app . '/';
    }

    public function end_api()
    {
        unset($GLOBALS['_API_APP_START_PATH']);
        $CI =& get_instance();
        $CI->config->load();
        if ($CI->config->config['base_url'] == '') {
            if (isset($_SERVER['HTTP_HOST'])) {
                $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
                $base_url .= '://'. $_SERVER['HTTP_HOST'];
                $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
            } else {
                $base_url = 'http://localhost/';
            }
            $CI->config->set_item('base_url', $base_url);
        }
    }

    /**
     * 从相应应用获取
     * @param unknown_type $model
     * @param unknown_type $function
     * @param unknown_type $params
     */
    public function get_api($model,$app_path, $function ,$params) {	
        global $_API_APP_START_PATH;
        log_message('debug', sprintf('%s:%d call get_api %s:%s %s', __FILE__, __LINE__, 
                    $model, $function, var_export($params, TRUE))
                );
		$path = '';

		if (($last_slash = strrpos($model, '/')) !== FALSE)
		{
			$path = substr($model, 0, $last_slash + 1);
			$model = substr($model, $last_slash + 1);
		}
		$model = strtolower($model);
		if ( ! file_exists($_API_APP_START_PATH . 'apis/' . $path . $model . '.php'))
		{
            log_message('warn', sprintf('%s:%d call get_api not exist %s:%s %s', __FILE__, __LINE__, 
                    $model, $function, var_export($params, TRUE))
                );
			exit;
		} 
		
		if ( ! class_exists('CI_Model'))
		{
			load_class('Model', 'core');
        }
		if ( ! class_exists('CI_Dao'))
		{
			load_class('Dao', 'core');
		}
        include($_API_APP_START_PATH.'config/config.php');
        $db_path = $_API_APP_START_PATH.'config/'.ENVIRONMENT.'/common.db.php';
        if (file_exists($db_path)) {
            require_once($db_path);
        }
        $mymodel = $_API_APP_START_PATH.'core/'.$config['subclass_prefix'].'model.php';
        if (file_exists($mymodel)) {
            require_once($mymodel);
        }
        $mydao = $_API_APP_START_PATH.'core/'.$config['subclass_prefix'].'dao.php';
        if (file_exists($mydao)) {
            require_once($mydao);
        }
        $CI =& get_instance();
        $CI->config->load('','','',TRUE);
		require_once($_API_APP_START_PATH . 'apis/' . $path . $model . '.php');
		$nmobel = ucfirst($model);
		$CI->$model = new $nmobel();
		$args['path']  =  $app_path;
		$args['params'] = is_array($params) ? $params : '';;
        $res = $CI->$model->$function($args);
      
		return $res;
    }
}
