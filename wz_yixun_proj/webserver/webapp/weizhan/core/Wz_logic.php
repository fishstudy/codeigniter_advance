<?php
/**
 * wd_logic
 *
 * @uses CI
 * @uses _logic
 * @package 
 * @version $id$
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author fishxyu <fishxyu@yixun.com> 
 * @license 
 */
class Wz_logic extends CI_Logic {
   
    function __construct() {
        parent::__construct();
    }

    /**
     * model
     *
     * @param mixed $model
     * @access public
     * @return mixed
     */
    public function model($model){
        $this->load->model($model);

        if (($last_slash = strrpos($model, '/')) !== FALSE)
        {
            $model = substr($model, $last_slash + 1);
        }

        return $this->$model;
    }

	 /**
     * model
     *
     * @param mixed $model
     * @access public
     * @return mixed
     */
    public function api($model){
        $this->load->model($model);

        if (($last_slash = strrpos($model, '/')) !== FALSE)
        {
            $model = substr($model, $last_slash + 1);
        }

        return $this->$model;
    }
  
    /**
     * 获取IP
     */
    function get_local_server_ip(){
        $this->load->helper('common_helper');
        $ip = get_local_ip();
        $ip = ip2num($ip);
        return $ip;
    }
    
    /**
     * get_microtime 
     * 获取执行时间
     * 
     * @param string $time 
     * @param string $c 
     * @access public
     * @return mixed
     */
    function get_microtime($time = '', $c = '') {
        list($usec, $sec) = explode(' ', microtime()); 
        $t = ((float)$usec + (float)$sec); 
        if($time == ''){
            return $t;
        } else {
            var_dump($c . ': ' . round(($t - $time) * 1000, 1). '毫秒');
        }
    }
    
    //根据获取扩展名
    public function extension($binaryData) {
        $bin  = substr($binaryData, 0, 2);        
        $str  = unpack('C2chars', $bin);
        $code = intval($str['chars1'] . $str['chars2']);
        $ext  = '';
        switch ($code) {
            case 255216:
                $ext = 'jpg';
                break;
            case 7173:
                $ext = 'gif';
                break;
            case 6677:
                $ext = 'bmp';
                break;
            case 13780:
                $ext = 'png';
                break;
        }
        if ($str['chars1'] == '-1' && $str['chars2'] == '-40') {
            $ext = 'jpg';
        }
        elseif ($str['chars1'] == '-119' && $str['chars2'] == '80') {
            $ext = 'png';
        }
    
        return $ext;
    }
}
