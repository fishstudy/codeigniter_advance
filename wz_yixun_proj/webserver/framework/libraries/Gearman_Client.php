<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed gearman');
/**
 * Class to utilize Gearman http://gearman.org/
 * @author Aniruddha Kale
 * @author Sunil Sadasivan <sunil@fancite.com>
 */
class CI_Gearman_Client
{

    private   $CI;
    protected $errors = array();
    private   $client;
    protected $_read_timeout_us   = 5000000;
    protected $_server_key        = 'default';
    protected $_client_timeout_ms = 5000;

    /**
     * Constructor
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }
    /**
     * Function to create a gearman client
     * @access public
     * @return void
     */
    public function gearman_client($server_key='default',$client_timeout_ms=5000, $read_timeout_us=5000000)
    {
        $this->client      = new GearmanClient();
        $this->_server_key = $server_key;
        $this->_client_timeout_ms  = $client_timeout_ms;
        $this->_read_timeout_us    = $read_timeout_us;
        $this->client->setTimeout($this->_client_timeout_ms);
        $this->_auto_connect();
        $this->errors = array();
    }
    /**
     * Perform a job in background for a client
     * @access public
     * @param string
     * @param string
     * @return void
     */
    public function do_job_background($function,$param, $header)
    {
        if (isset($this->CI->session)){
            $header['token'] = $this->CI->session->userdata('token');
            $header['auth']  = $this->CI->session->userdata('auth');
        }
        $this->client->doBackground($function, msgpack_pack(array('request'=>$param, 'header'=>$header)));
        log_message('debug', "Gearman Library: Performed task with function $function with parameter");
    }

    /**
     * Perform a job in foreground for a client
     * @access public
     * @param string
     * @param string
     * @return string  
     */
    public function do_job_foreground($function, $param, $header) 
    {
        log_message('debug', "Gearman Library: Performed task with function $function with parameter");
        $tm_start    = gettimeofday();
        do {
            $result = $this->client->doNormal($function, msgpack_pack(array('request'=>$param, 'header'=>$header)));
            switch($this->client->returnCode()) {
                case GEARMAN_WORK_FAIL:
                    log_message('warn', "Gearman Library:  $function Failed");
                    throw new Exception(sprintf("Gearman Library:  $function Failed"), $this->CI->config->item('gearman_work_fail', 'err_no'));
                case GEARMAN_SUCCESS:
                    log_message('info', "Gearman Library:  $function Success");
                    break;
                case GEARMAN_WORK_DATA:
                case GEARMAN_WORK_STATUS:
                    break; 
                default:
                    log_message('warn', "Gearman Library:  $function Failed RET:".$this->client->returnCode());
                    throw new Exception(sprintf("Gearman Library:  $function Failed RET: %s", $this->client->returnCode()),
                            $this->CI->config->item('do_job_foreground_err', 'err_no')
                            );
            }
            $tm_current = gettimeofday();
            $intUSGone = ($tm_current['sec'] - $tm_start['sec']) * 1000000
                + ($tm_current['usec'] - $tm_start['usec']);
            if ($intUSGone > $this->_read_timeout_us) {
                throw new Exception(sprintf("Gearman Library:  $function timeout: %u", $intUSGone),
                        $this->CI->config->item('do_job_foreground_timeout', 'err_no')
                        );
            }
        }while($this->client->returnCode() != GEARMAN_SUCCESS);

        return msgpack_unpack($result);
    }

    /**
     * Runs through all of the servers defined in the configuration and attempts to connect to each
     * @param object
     * @return void
     */
    private function _auto_connect()
    {
        if (defined('ENVIRONMENT') AND file_exists(APPPATH.'config/'.ENVIRONMENT.'/gearman.php')){
            include APPPATH.'config/'.ENVIRONMENT.'/gearman.php' ;
            $servers = $gearman_config['GearmanManager'][$this->_server_key == 'default' ? 'host' : $this->_server_key];
        }else{
            $servers = implode(',', $this->CI->config->item($this->_server_key == 'default' ? 'host' : $this->_server_key, 'mgr_config'));
        }
        $servers = implode(',', $servers); 

        if(!$this->client->addServers($servers))
        {
            $this->errors[] = "Gearman Library: Could not connect to the server named $servers";
            log_message('error', 'Gearman Library: Could not connect to the server named "'.$servers.'"');
        }
        else
        {
            log_message('debug', 'Gearman Library: Successfully connected to the server named "'.$servers.'"');
        }
    }
    /**
     *  Returns worker error
     *  @access public
     *  @return void
     *         
     */
    function error()
    {
        return empty($this->errors) ? $this->client->error() : implode(';',$this->errors);
    }

}
?> 
