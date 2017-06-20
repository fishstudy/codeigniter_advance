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
class Bs_logic extends CI_Logic {
   
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
     * 获取IP
     */
    function get_local_server_ip(){
        $this->load->helper('common_helper');
        $ip = get_local_ip();
        $ip = ip2num($ip);
        return $ip;
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
    
    /**
     * 根据易迅用户id查询站长信息和微站信息
     * 
     * @param mixed $post_data
     * @access public
     * @return mixed
     */
    function searchMaster( $uid = '') {
    	$uid = intval($uid);
    	$searchdata = array();
    	$searchdata['createUser'] = $uid;
    	$webmaster_info = $this->model('public/Model_webmaster')->fetch( $searchdata );
    	$data = array();
    	if ( is_array($webmaster_info) && !empty($webmaster_info)) {
    		$data['webmasterinfo'] = $webmaster_info[0];
    		$searchsite = array();
    		$searchsite['webMasterId'] = $webmaster_info[0]['id'];//站长id
    		$website_info = $this->model('public/Model_website')->fetch( $searchsite );
    		$data['websiteinfo'] = empty($website_info[0])?array():$website_info[0];
    		return $data;
    	}else {
    		return array();
    	}
    }
    
}
