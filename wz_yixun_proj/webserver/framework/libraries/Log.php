<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Logging Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Logging
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/errors.html
 */
class CI_Log {
    const LOG_SPACE = "\10";
    const PAGE_SIZE = 256;
    protected $_enabled	= TRUE;
    protected $_levels	= array('FATAL'=>1,'ERROR'=>'2','WARN'=>'3', 'INFO' =>'4', 'TRACE'=>5, 'DEBUG' => '6', 'ALL' => '8');
    protected $logger;
    protected $initialized = FALSE;
    protected $_threshold  = 1;
    protected $arr_basic   = array();
    static $basic_fields   = array (
            'signid',
            'ip',
            'uid',
            'uname',
            'm',
            'c',
            'provider',
            'uri',
            'worker',
            );
    protected $basic_info;
    protected $log_str;
    protected $info_str;
    public function add_basic_info($arr_basic_info)
    {
        $this->arr_basic = array_merge($this->arr_basic, $arr_basic_info);
        $this->gen_basic_info();
    }
    public function gen_basic_info() {
        $this->basic_info = '';
        foreach (self::$basic_fields as $key) {
            if (!empty($this->arr_basic[$key])) {
                $this->basic_info .= $this->gen_log_part("$key:".$this->arr_basic[$key]) . " ";
            }
        }
    }
    public function push_info($format, $arr_data)
    {
        $this->info_str .= " " .$this->gen_log_part(vsprintf($format, $arr_data));
    }

    public function clear_info()
    {
        $this->info_str = '';
    }
    private function gen_log_part($str)
    {
        return "[ " . self::LOG_SPACE . $str . " ". self::LOG_SPACE . "]";
    }
    /**
     * Constructor
     */
    public function __construct()
    {

        if ($this->initialized === false) {
            $config =& get_config();
            if (is_numeric($config['log_threshold'])){
                $this->_threshold = $config['log_threshold'];
            }
            $this->initialized = true;
            $config_file = APPPATH . 'config/log4php_.properties';
            if ( defined('ENVIRONMENT') && file_exists( APPPATH . 'config/' . ENVIRONMENT . '/log4php_.properties' ) ) {
                $config_file = APPPATH . 'config/' . ENVIRONMENT . '/log4php_.properties';
            }
            require_once THIRDPATH .'apache-log4php/src/main/php/Logger.php';
            Logger::configure($config_file);
            $this->logger = Logger::getRootLogger();
        }

    }

    // --------------------------------------------------------------------

    /**
     * Write Log File
     *
     * Generally this function will be called using the global log_message() function
     *
     * @param	string	the error level
     * @param	string	the error message
     * @param	bool	whether the error is a native PHP error
     * @return	bool
     */
    public function write_log($level = 'error', $msg, $php_error = FALSE)
    {
        if ($this->_enabled === FALSE) {
            return FALSE;
        }

        $level = strtoupper($level);

        if ( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold) ) {
            return FALSE;
        }
        if (strlen($msg)> self :: PAGE_SIZE){
            $msg = mb_substr($msg, 0, self :: PAGE_SIZE, 'UTF-8'). ' ... truncated'; 
        }
        switch ($level) {
            case 'ERROR':
                $this->log_str = $this->basic_info. $this->info_str .$msg;
                $this->logger->error($msg);
                $this->clear_info();
                break;
            case 'INFO':
                $this->log_str = $this->basic_info. $this->info_str .$msg;
                $this->logger->info($this->log_str);
                $this->clear_info();
                break;
            case 'DEBUG':
                $this->logger->debug($msg);
                break;
            default:
                $this->logger->debug($msg);
                break;
        }

        return TRUE;
    }
    public function warn($msg){
        $this->log_str = $this->basic_info. $this->info_str .$msg;
        $this->write_log('warn', $this->log_str);
        //$this->clear_info();
    }
    public function debug($msg){
        $this->log_str = $this->basic_info. $this->info_str .$msg;
        $this->write_log('debug', $this->log_str);
    }
    public function error($msg){
        $this->log_str = $this->basic_info. $this->info_str .$msg;
        $this->write_log('error', $this->log_str);
        //$this->clear_info();
    }
    public function fatal($msg){
        $this->log_str = $this->basic_info. $this->info_str .$msg;
        $this->write_log('fatal', $msg);
       // $this->clear_info();
    }
    public function trace($msg){
        $this->log_str = $this->basic_info. $this->info_str .$msg;
        $this->write_log('trace', $this->log_str);
    }
    public function info($msg){
        $this->log_str = $this->basic_info. $this->info_str .$msg;
        $this->write_log('info', $this->log_str);
        //$this->clear_info();
    }

}
// END Log Class

/* End of file Log.php */
/* Location: ./CodeIgniter/libraries/Log.php */
