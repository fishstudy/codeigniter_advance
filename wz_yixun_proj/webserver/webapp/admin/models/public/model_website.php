<?php
/**
 * Model_website
 * 
 * @uses Wd
 * @uses _Model
 * @package 
 * @version $1.0$
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author fishxyu <fishxyu@yixun.com> 
 * @license 
 */
class Model_website extends Wd_model {
    protected $_has_one  = array();
    protected $_has_many = array();
    protected $_model    = '';
    protected $_cache_keys = array(
             'Wd_Model_website' => array(),            );
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
        $new_website  = array();
        if ($id > 0) {
            $new_website['website'] = $this->dao('public/Dao_website', $dao_param)->fetch_one_by_id($id);
        } else {
            $new_website['website']  = $this->dao('public/Dao_website', $dao_param)->new_one();
        }
                return $new_website;
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
                    $website = $param['website'];
            $website_id = isset($website['id']) ? $website['id'] : 0;
            if ($website_id <= 0) {
                $website['id'] = $this->dao('public/Dao_id_allocator')->allocate(array('ns'=>'Wd','table_name'=>substr(__CLASS__, 6)));
            }
                        $is_update = FALSE;
            if ($website_id > 0) {
                unset($website['id']);
                $this->dao('public/Dao_website', $dao_param)->update($website, $website_id);
                $is_update = TRUE;
            } else {
                $website_id = $this->dao('public/Dao_website', $dao_param)->insert($website);
            }

                        
            
            //清除缓存
            foreach ($this->_cache_keys as $key_pattern => $keys){
                $temp = array();
                $has_key = TRUE;
                foreach($keys as $key){
                    if (!isset($website[$key])){
                        $has_key = FALSE;
                        break;
                    }
                    $temp[$key] = $website[$key];
                }
                if (!$has_key) continue;
                $key  = vsprintf($key_pattern, $temp);
                if (!$this->cache->redis->del($key)){
                    $this->log->warn(sprintf('%s: delete key:%s from redis failure.', __FUNCTION__, $key));
                }
            }
            return $website_id;
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
        $websites = array('num'=>0, 'results'=>array());
        if ($page > 0 && $pagesize > 0) {
            $param['page']      = $page;
            $param['pagesize']  = $pagesize;
        }
        $key = $this->_get_cache_key($param);     
                $param['ordersort']     =  'created_at DESC';
                
        if ($key === FALSE || ($page * $pagesize) > self :: ID_CACHE_NUM ){
            //    $param['ordersort']     =  'created_at DESC';
            $websites = $this->dao('public/Dao_website', $dao_param )->search($param);
        }else{ //缓存
            $websites = $this->cache->redis->get($key);
            if (empty($websites)) {
                $param['page']     = 1;
                $param['pagesize'] = self :: ID_CACHE_NUM;
                $websites = $this->dao('public/Dao_website', $dao_param )->search($param);
                if (!$this->cache->redis->save($key, $websites)){
                    $this->log->warn(sprintf('set %s to redis by key:%s failure..', $this->_model, $key));
                }
            }else{
                $this->log->info(sprintf('search  %s from redis by key:%s success.', $this->_model, $key));
            }

            if ($page > 0 && $pagesize > 0) {
                // array_slice 获取需要的数据
                $limit = $pagesize;
                if ($websites['num'] < $page* $pagesize){
                    $limit = $websites['num'] - ($page-1) * $pagesize;
                }
                $websites['results'] = array_slice($websites['results'], ($page-1) * $pagesize, $limit, TRUE);
            }
        }
        
        if ( $websites['num'] > 0) {
            $items = $this->dao('public/Dao_website', $dao_param)->get_multi(array_keys($websites['results']));
            foreach ($items as $item) {
                $websites['results'][$item['id']] = $item;
            }
        }
        return  $websites;
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
        $website = $this->dao('public/Dao_website', $dao_param)->fetch_one_by_id($id);
        if ($user_id >0 && $website['user_id'] != $user_id){
            throw new Exception(sprintf('function: %s, op:%d has no permission to delete user_id:%d', __FUNCTION__,
                        $user_id, $website['user_id']),
                    $this->config->item('permission_err_no', 'err_no'));
        }
                $this->dao('public/Dao_website', $dao_param)->delete_one_by_id($id);
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
        $website = $this->dao('public/Dao_website', $dao_param)->fetch_one_by_id($id);
                $this->dao('public/Dao_website', $dao_param)->update($param, $id);
        //清除缓存
        foreach (array($website, array_merge($website, $param)) as $v){
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
        return call_user_func_array(array($this->dao('public/Dao_website',  $dao_param), $func), $args);
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
                $id  = $this->dao(sprintf('public/Dao_%s', 'website'), $dao_param)->update_by_unique($param);
                if (intval($id)<=0){
            $id    = $param['id'] = $this->dao('public/Dao_id_allocator')->allocate(array('ns'=>'Wd','table_name'=>'website'));
            $param = array_merge($this->dao(sprintf('public/Dao_%s', 'website'), $dao_param)->new_one(), $param);
            $this->dao(sprintf('public/Dao_%s', 'website'), $dao_param)->insert($param);
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
