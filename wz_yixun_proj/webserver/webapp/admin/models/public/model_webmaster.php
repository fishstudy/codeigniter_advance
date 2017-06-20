<?php
/**
 * Model_webmaster
 * 
 * @uses Wd
 * @uses _Model
 * @package 
 * @version $1.0$
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author fishxyu <fishxyu@yixun.com> 
 * @license 
 */
class Model_webmaster extends Adm_model {
    protected $_has_one  = array();
    protected $_has_many = array();
    protected $_model    = '';
    protected $_cache_keys = array(
             'Wd_Model_webmaster' => array(),            );
    protected $_equal_search_items = array();
        
    /**
     * __construct
     *
     * @access protected
     * @return mixed
     */
    function __construct() {
        parent :: __construct();
        $this->_model = substr(__CLASS__, 6);
    }
    
    /**
     * new_c
     *
     * @param int $id
     * @access public
     * @return mixed
     */
    function new_c($id = 0) {
        $dao_param      = array('active_group'=>$this->config->item('active_group'), 'id'=>0);
        $new_webmaster  = array();
        if ($id > 0) {
            $new_webmaster['webmaster'] = $this->dao('public/Dao_webmaster', $dao_param)->fetch_one_by_id($id);
        } else {
            $new_webmaster['webmaster']  = $this->dao('public/Dao_webmaster', $dao_param)->new_one();
        }
                return $new_webmaster;
    }
                
    /**
     * save
     *
     * @param array $param
     * @access public
     * @return mixed
     */
    function save($param = array()) {
        $this->log->debug(var_export($param, TRUE));
        if (empty($param) || !is_array($param)) {
            throw new Exception(sprintf('%s:%s parameters not array()', __CLASS__, __FUNCTION__),
                    $this->config->item('parameter_err_no', 'err_no')
                    );
        }
        if (!isset($param[$this->_model])) {
            throw new Exception(sprintf('%s:%s input parameters not exists.', __CLASS__, __FUNCTION__),
                    $this->config->item('parameter_err_no', 'err_no')
                    );
            $model  = $this->_model;
            $$model = $param[$this->_model];
        }
                        $dao_param = array('active_group'=>$this->config->item('active_group'), 'id'=>0);
                    $webmaster = $param['webmaster'];
            $webmaster_id = isset($webmaster['id']) ? $webmaster['id'] : 0;
            if ($webmaster_id <= 0) {
                $webmaster['id'] = $this->dao('public/Dao_id_allocator')->allocate(array('ns'=>'Wd','table_name'=>substr(__CLASS__, 6)));
            }
                        $is_update = FALSE;
            if ($webmaster_id > 0) {
                unset($webmaster['id']);
                $this->dao('public/Dao_webmaster', $dao_param)->update($webmaster, $webmaster_id);
                $is_update = TRUE;
            } else {
                $webmaster_id = $this->dao('public/Dao_webmaster', $dao_param)->insert($webmaster);
            }

                        
            
            //清除缓存
            foreach ($this->_cache_keys as $key_pattern => $keys){
                $temp = array();
                $has_key = TRUE;
                foreach($keys as $key){
                    if (!isset($webmaster[$key])){
                        $has_key = FALSE;
                        break;
                    }
                    $temp[$key] = $webmaster[$key];
                }
                if (!$has_key) continue;
                $key  = vsprintf($key_pattern, $temp);
                if (!$this->cache->redis->del($key)){
                    $this->log->warn(sprintf('%s: delete key:%s from redis failure.', __FUNCTION__, $key));
                }
            }
            return $webmaster_id;
                }
        
    /**
     * search
     *
     * @param array $param
     * @param int $page
     * @param int $pagesize
     * @access public
     * @return mixed
     */
    function search($param = array(), $page = 0, $pagesize = 0) {
        $dao_param = array('active_group'=>$this->config->item('active_group'), 'id'=>0);
        $webmasters = array('num'=>0, 'results'=>array());
        if ($page > 0 && $pagesize > 0) {
            $param['page']      = $page;
            $param['pagesize']  = $pagesize;
        }
        $key = $this->_get_cache_key($param);     
                $param['ordersort']     =  'created_at DESC';
                
        if ($key === FALSE || ($page * $pagesize) > self :: ID_CACHE_NUM ){
            //    $param['ordersort']     =  'created_at DESC';
            $webmasters = $this->dao('public/Dao_webmaster', $dao_param )->search($param);
        }else{ //缓存
            $webmasters = $this->cache->redis->get($key);
            if (empty($webmasters)) {
                $param['page']     = 1;
                $param['pagesize'] = self :: ID_CACHE_NUM;
                $webmasters = $this->dao('public/Dao_webmaster', $dao_param )->search($param);
                if (!$this->cache->redis->save($key, $webmasters)){
                    $this->log->warn(sprintf('set %s to redis by key:%s failure..', $this->_model, $key));
                }
            }else{
                $this->log->info(sprintf('search  %s from redis by key:%s success.', $this->_model, $key));
            }

            if ($page > 0 && $pagesize > 0) {
                // array_slice 获取需要的数据
                $limit = $pagesize;
                if ($webmasters['num'] < $page* $pagesize){
                    $limit = $webmasters['num'] - ($page-1) * $pagesize;
                }
                $webmasters['results'] = array_slice($webmasters['results'], ($page-1) * $pagesize, $limit, TRUE);
            }
        }
        
        if ( $webmasters['num'] > 0) {
            $items = $this->dao('public/Dao_webmaster', $dao_param)->get_multi(array_keys($webmasters['results']));
            foreach ($items as $item) {
                $webmasters['results'][$item['id']] = $item;
            }
        }
        return  $webmasters;
    }
        
    /**
     * delete_one_by_id 
     * 
     * @param int $id 
     * @param int $user_id 
     * @access public
     * @return mixed
     */
    function delete_one_by_id($id=0, $user_id=0) {
        $dao_param = array('active_group'=>$this->config->item('active_group'), 'id'=>0);
        $id = intval($id);
        if ($id <= 0) {
            throw new Exception(sprintf('function: %s, parameter: id must greater than 0', __FUNCTION__),
                    $this->config->item('parameter_err_no', 'err_no'));

        }
        $webmaster = $this->dao('public/Dao_webmaster', $dao_param)->fetch_one_by_id($id);
        if ($user_id >0 && $webmaster['user_id'] != $user_id){
            throw new Exception(sprintf('function: %s, op:%d has no permission to delete user_id:%d', __FUNCTION__,
                        $user_id, $webmaster['user_id']),
                    $this->config->item('permission_err_no', 'err_no'));
        }
                $this->dao('public/Dao_webmaster', $dao_param)->delete_one_by_id($id);
        return TRUE;
            }
    
    /**
     * update_by_id
     * 
     * @param array $param 
     * @param int $id 
     * @access public
     * @return mixed
     */
    function update_by_id($param = array(), $id = 0) {
        $dao_param = array('active_group'=>$this->config->item('active_group'), 'id'=>0);
        $id = intval($id);
        if ($id <= 0) {
            throw new Exception(sprintf('function: %s, parameter: id must greater than 0', __FUNCTION__),
                    $this->config->item('parameter_err_no', 'err_no')
                    );

        }
        $webmaster = $this->dao('public/Dao_webmaster', $dao_param)->fetch_one_by_id($id);
                $this->dao('public/Dao_webmaster', $dao_param)->update($param, $id);
        //清除缓存
        foreach (array($webmaster, array_merge($webmaster, $param)) as $v){
            foreach ($this->_cache_keys as $key_pattern => $keys){
                $temp = array();
                foreach($keys as $key){
                    $temp[$key] = $v[$key];
                }
                $key  = vsprintf($key_pattern, $temp);
                if (!$this->cache->redis->del($key)){
                    $this->log->warn(sprintf('%s: delete key:%s from redis failure.', __FUNCTION__, $key));
                }
            }
        }
            }
    
    /**
     * __call 
     * 
     * @param mixed $func 
     * @param mixed $args 
     * @access protected
     * @return mixed
     */
    function __call($func, $args) {
        $dao_param = array('active_group'=>$this->config->item('active_group'), 'id'=>0);
        return call_user_func_array(array($this->dao('public/Dao_webmaster',  $dao_param), $func), $args);
    }

                
    /**
     * update_by_unique 
     * 
     * @param mixed $param 
     * @access public
     * @return mixed
     */
    function update_by_unique($param) {
        $dao_param = array('active_group'=>$this->config->item('active_group'), 'id'=>0);
        unset($param['id']);
                $id  = $this->dao(sprintf('public/Dao_%s', 'webmaster'), $dao_param)->update_by_unique($param);
                if (intval($id)<=0){
            $id    = $param['id'] = $this->dao('public/Dao_id_allocator')->allocate(array('ns'=>'Wd','table_name'=>'webmaster'));
            $param = array_merge($this->dao(sprintf('public/Dao_%s', 'webmaster'), $dao_param)->new_one(), $param);
            $this->dao(sprintf('public/Dao_%s', 'webmaster'), $dao_param)->insert($param);
        }else{
            $param['id'] = $id;

        }
        foreach ($this->_cache_keys as $key_pattern => $keys){
            $temp = array();  
            foreach($keys as $key){
                $temp[$key] = $param[$key];
            }                 
            $key  = vsprintf($key_pattern, $temp);
            if (!$this->cache->redis->del($key)){
                $this->log->warn(sprintf('%s: delete key:%s from redis failure.', __FUNCTION__, $key));
            }                 
        }
        return $id;
    }
}
?>
