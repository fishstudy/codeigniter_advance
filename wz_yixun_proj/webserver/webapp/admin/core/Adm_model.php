<?php 
/**
 * wd_model
 *
 * @uses CI
 * @uses _Model
 * @package 
 * @version $id$
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author fishxyu <fishxyu@yixun.com> 
 * @license 
 */
class Adm_model extends CI_Model{
    const ID_CACHE_NUM=10000;
    /**
     * __construct 
     *         
     * @access protected
     * @return mixed
     */ 
    function __construct(){
        parent::__construct();
    }          
    /**
     * dao 
     *  
     * @param mixed $dao 
     * @access public_id 17      
     * @return mixed 18      */ 
    public function dao($dao, $param=array('active_group'=>'weidian', 'id'=>0)){
        $this->load->dao($dao, '', $param);
        if (($last_slash = strrpos($dao, '/')) !== FALSE){
            $dao = substr($dao, $last_slash + 1);
        }
        return $this->$dao;
    }
    function inTransaction(){
        return $this->db->conn_id->inTransaction();
    }
    function beginTransaction(){
        return $this->db->conn_id->beginTransaction();
    }
    function commit(){
        return $this->db->conn_id->commit();
    }
    function rollback(){
        return $this->db->conn_id->rollback();
    }
    protected function _remove_operator($str){
        preg_match_all("/([a-zA-Z0-9_]+)/i", $str, $matches);
        return $matches[1][0];
    }
    function _get_cache_key($param=array()) {
        $new_param = array();
        foreach($param as $k=>$v) {
            if (isset($this->_equal_search_items[$k])) $new_param[$k] = $v;
        }
        foreach ($this->_cache_keys as  $k=>$v){
            $diff = array_diff($v, array_keys($new_param));
            if (empty($diff)){
                $temp = array();
                foreach($v as $item){
                    $temp [$item] =   $new_param[$item];
                }
                return vsprintf($k, $temp);
            }
        }
        return FALSE;
    }
    /**
     * _diff 
     * 
     * @param mixed $old 
     * @param mixed $new 
     * @param mixed $keys 
     * @param array $filter_keys 
     * @access protected
     * @return mixed
     */
    function _diff($old, $new, $keys, $filter_keys=array()) {
        $diff = array();
        foreach ($keys as $key) {
            if (in_array($key, array('updated_at', 'user_id', 'user_name', 'id'))) continue;
            if (in_array($key, $filter_keys)) continue;
            if (!isset($new[$key])){
                $diff[$key] =  sprintf('%s: %s =>', $key, $old[$key]); 
            }elseif(!isset($old[$key])) {
                $diff[$key] =  sprintf('%s: => %s', $key,  $new[$key]); 
            }elseif ($old[$key] != $new[$key]){
                $diff[$key] =  sprintf('%s: %s =>%s', $key, $old[$key], $new[$key]); 
            }
        }
        return $diff;
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
}
