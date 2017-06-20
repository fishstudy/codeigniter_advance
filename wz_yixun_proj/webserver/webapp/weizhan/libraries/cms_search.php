<?php
/**
 * 走搜索的逻辑
 * Enter description here ...
 * @author yaoc
 *
 */
class Cms_search
{
	//worker 对应的名字
	private $_work_name = array('search'=>'esearch','suggestion'=>'eservlet');
	//搜索对应的文件夹
	private $_search_dirname = array('persons'=>'person','articles'=>'article','corporations'=>'corporation');
	
    public function __construct($params = array())
    {
        $this->CI =& get_instance();
    }
    /**
     * 普通搜索
     * Enter description here ...
     * @param $para
     */
   public function search($para) {
   	$results = array();
   	$data = $this->dowith_search($para);
   	$results['num'] = empty($data['totalcount']) ?  0 : $data['totalcount'];
   	$results['results'] = empty($data['list']) ?  array() : $data['list'];
   	
    return $results;
   }
  
   /**
    * 处理search的请求参数
    * Enter description here ...
    */
    function dowith_search($para) {
    	$results = array();
        $data['header'] = $this->help_get_header();
        $data['request']['c'] = 'list';
        $data['request']['m'] = 'index';
        $data['request']['p'] =  $this->help_dowith_qest($para);
       
        $work_name_alis= $this->_work_name['search'];
        $results = $this->sync_entry($data,$work_name_alis);
        
        return $results;
    }
    
    /**
    * 自动提醒
    * 
    */
   public function  suggestion($para) {
    $results = array();
    $data = $this->dowith_suggestion($para);
    //刘志这边返回的格式和王林不一样 返回总数
    $results['num'] = count($data);
    $results['results'] = $data;
    log_message('debug', sprintf("search_result: suggestion %s",print_r($results,1)));
    
    return $results;
   
   }
    /**
    * 处理自动提醒的请求参数
    * Enter description here ...
    */
    function dowith_suggestion($para){
        $results = array();
        $data['header'] = $this->help_get_header();
        $data['request']['c'] = 'list';
        $data['request']['m'] = 'index';
        $data['request']['p'] =  $this->help_dowith_qest_suggest($para);
        $work_name_alis= $this->_work_name['suggestion'];
        $results = $this->sync_entry($data,$work_name_alis);
        //刘志这边返回的格式和王林不一样 去除空数组list
        unset($results['list']);
        
        return $results;
      
    }
   
    /**
     * 同步文章的函数
     * Enter description here ...
     * @param $param
     * @param $work_name
     * @param $method
     */
     function sync_entry($data,$work_name_alis='', $server='esearch_host'){
     	$result = array();
        $worker =   $this->CI->config->item('worker');
        $work_name = $worker[$work_name_alis];
        log_message('debug', sprintf("%s post data is:%s ",$work_name, print_r($data['request'],1)));
        $gc = sprintf('gc_%s',$server);
        $this->CI->load->library('Gearman_Client','' ,$gc);
        $this->CI->{$gc}->gearman_client($server,50000,50000000);
        $results = $this->CI->{$gc}->do_job_foreground($work_name, $data['request'],$data['header']);
        if(empty($results['response']) || $results['response']['err_no'] > 1 ) {
        	log_message('warn', sprintf("%s results is:%s ",$work_name, serialize($results)));
        } else {
        	$result = $results['response']['results'];
        	log_message('debug', sprintf("%s results is:%s ",$work_name, serialize($results)));
        }
        $result['list'] = empty($result['list'] ) ? array() : array_flip($result['list']);
        //由于model层多是用array_keys
       log_message('debug', sprintf("%s results is:%s ",$work_name, print_r($result['list'],1)));
       
        return $result;
    }
    
    /**
     * 返回gearman的请求头数组
     * Enter description here ...
     */
    function help_get_header(){
         return  $data = array(
                  'version'=>'0.1',
                  'signid'=>'tag007',
                  'provider'=>'gmfront',
                  'uid'     => strval($this->CI->uid[getmypid()]),
                  'uname'   => $this->CI->user_info[getmypid()]['user_name'],
                  'ip'      => $this->help_local_server_ip(),
               );
    
    }
    
    /**
     * 处理原来的q参数的数据
     * Enter description here ...
     */
    function help_dowith_qest($para, $type = 'search') {
    	$str = $para['q'];
    	$temp = explode(' ', $str);
    	foreach($temp as $val){
    		if(!empty($val)){
	    	   $tmp = explode(':', $val);
	    	   $tmp[0] = strval($tmp[0]);
	    	   $tmp[1] = strval($tmp[1]);
	    	   $results[$tmp[0]] = $tmp[1];
    		} else {
    		  //empty
    		}
    	}
    	$start = ($para['page']-1)*$para['pagesize'];
    	$results['start'] = strval($start);
    	$results['count'] = strval($para['pagesize']);
    	$table = ($type=='search') ? 'm' : 'table';
    	$results[$table] = $this->_search_dirname[$para['_ft_']];
    	
        return  $results;
    }
    
/**
     * 处理原来的q参数的数据
     * Enter description here ...
     */
    function help_dowith_qest_suggest($para) {
        $str = $para['q'];
        $temp = explode(' ', $str);
         foreach($temp as $val){
            if(!empty($val)){
               $tmp = explode(':', $val);
               $tmp[0] = strval($tmp[0]);
               $tmp[1] = strval($tmp[1]);
               //$results['columns'] = $tmp[0];
               $results['q'] = $tmp[1];
            } else {
              //empty
            }
        }
     
        $results['count'] = strval($para['pagesize']);
        $table =  'table';
        if($para['_ft_'] != 'corporations'){//
             $results[$table] = 'cms_'.$this->_search_dirname[$para['_ft_']].'_'.$tmp[0];
             $results['is_reviewed'] = 'Y';
        } else {//公司的特殊处理
            $results[$table] = $this->_search_dirname[$para['_ft_']];
            $results['bundle'] = 'bdlist';
            $results['status'] = '1';
        }
        $results['handle'] = 'suggestion';
       
        
        return  $results;
    }
    /**
     * 获取IP
     */
    function help_local_server_ip(){
        $this->CI->load->helper('common_helper');
        $ip = get_local_ip();
        $ip = ip2num($ip);
        $ip = strval($ip);
        
        return $ip;
    }
    
    
}
