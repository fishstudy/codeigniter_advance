<?php
/**
 * Model_articlekind
 * 
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author weidian team <fishxyu@yixun.com> 
 * @license 
 */
class Model_articlekind extends Bs_model {
    protected $_model    = 'articlekind';
    protected $_cache_keys = array(
            'Bs_Model_articlekind_wzId:%d' => array('wzId', ),
             'Bs_Model_articlekind' => array(),            
            );
    protected $_equal_search_items = array('wzId'=>'t',);
    protected $_dao_param = array();
    
    /**
     * __construct
     *
     * @access protected
     * @return mixed
     */
    public function __construct() {
        parent :: __construct();
        //$this->_model = substr(__CLASS__, 6);
        $this->_dao_param = array('active_group'=>$this->config->item('active_group'), 'id'=>0);
    }

    /**
     * 列表页
     *
     * @param array $param
     * @return mixed
     */
    public function search($param = array()) {
        $articlekinds = array('num'=>0, 'results'=>array());
        $pagesize = empty($param['pagesize']) ? 10 : $param['pagesize'];
        $key = $this->_get_cache_key($param);
        $param['ordersort']     =  'createTime DESC';
        if ($key === FALSE || ($page * $pagesize) > self :: ID_CACHE_NUM ){
            $articlekinds = $this->dao('articleinfo/Dao_articlekind', $this->_dao_param )->search($param);
        }else{ //缓存
            $articlekinds = $this->cache->redis->get($key);
            if (empty($articlekinds)) {
                $param['page']     = 1;
                $param['pagesize'] = self :: ID_CACHE_NUM;
                $articlekinds = $this->dao('articleinfo/Dao_articlekind', $this->_dao_param )->search($param);
                /*$listids = $articlekinds;
                $this->cache->redis->lpush_multi($key, $listids);
                $this->log->debug(sprintf('set %s to redis by key:%s ..', $this->_model, $key));*/
                if(!$this->cache->redis->save($key, $articlekinds)) {
                    $this->log->warn(sprintf('set %s to redis by key:%s failure..', $this->_model, $key));
                }
            }else{
                $this->log->info(sprintf('search  %s from redis by key:%s success.', $this->_model, $key));
            }
    
            if ($page > 0 && $pagesize > 0) {
                // array_slice 获取需要的数据
                $limit = $pagesize;
                if ($articlekinds['num'] < $page* $pagesize){
                    $limit = $articlekinds['num'] - ($page-1) * $pagesize;
                }
                $articlekinds['results'] = array_slice($articlekinds['results'], ($page-1) * $pagesize, $limit, TRUE);
            }
        }
    
        if ( $articlekinds['num'] > 0) {
            $items = $this->dao('articleinfo/Dao_articlekind', $this->_dao_param)->get_multi(array_keys($articlekinds['results']));
            foreach ($items as $item) {
                $articlekinds['results'][$item['id']] = $item;
            }
        }
    
        return  $articlekinds;
    }
    
    /**
     * 新增
     *
     * @param int $id
     * @return mixed
     */
    public function create($id = 0) {
        $new_articlekind  = array();
        if ($id > 0) {
            $new_articlekind['articlekind'] = $this->dao('articleinfo/Dao_articlekind', $this->_dao_param)->fetch_one_by_id($id);
        } else {
            $new_articlekind['articlekind']  = $this->dao('articleinfo/Dao_articlekind', $this->_dao_param)->new_one();
        }
        
        return $new_articlekind;
    }

    /**
     * save
     *
     * @param array $param
     * @return mixed
     */
    public function save($param = array()) {
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

        $articlekind = $param['articlekind'];
        $articlekind_id = isset($articlekind['id']) ? $articlekind['id'] : 0;
        $is_update = FALSE;
        if ($articlekind_id > 0) {
            unset($articlekind['id']);
            $this->dao('articleinfo/Dao_articlekind', $this->_dao_param)->update($articlekind, $articlekind_id);
            $is_update = TRUE;
        } else {
            $articlekind_id = $this->dao('articleinfo/Dao_articlekind', $this->_dao_param)->insert($articlekind);
        }

        //清除缓存
        foreach ($this->_cache_keys as $key_pattern => $keys){
            $temp = array();
            $has_key = TRUE;
            foreach($keys as $key){
                if (!isset($articlekind[$key])){
                    $has_key = FALSE;
                    break;
                }
                $temp[$key] = $articlekind[$key];
            }
            if (!$has_key) continue;
            $key  = vsprintf($key_pattern, $temp);
            if (!$this->cache->redis->del($key)){
                $this->log->warn(sprintf('%s: delete key:%s from redis failure.', __FUNCTION__, $key));
            }
        }
        
        return $articlekind_id;
    }
    
    /**
     * 根据ID删除记录 真删
     * 
     * @param int $id 
     * @param int $user_id 
     * @return mixed
     */
    public function delete_one_by_id($id=0, $user_id=0) {
        $id = intval($id);
        if ($id <= 0) {
            throw new Exception(sprintf('function: %s, parameter: id must greater than 0', __FUNCTION__),
                    $this->config->item('parameter_err_no', 'err_no'));

        }
        $articlekind = $this->dao('articleinfo/Dao_articlekind', $this->_dao_param)->fetch_one_by_id($id);
        if ($user_id >0 && $articlekind['user_id'] != $user_id){
            throw new Exception(sprintf('function: %s, op:%d has no permission to delete user_id:%d', __FUNCTION__,
                        $user_id, $articlekind['user_id']),
                    $this->config->item('permission_err_no', 'err_no'));
        }
        $this->dao('articleinfo/Dao_articlekind', $this->_dao_param)->delete_one_by_id($id);

        return TRUE;
    }
    
    /**
     * 根据ID修改内容，方便修改部分字段（set）
     * 
     * @param array $param 
     * @param int $id 
     * @access public
     * @return mixed
     */
    public function update_by_id($param = array(), $id = 0) {
        $id = intval($id);
        if ($id <= 0) {
            throw new Exception(sprintf('function: %s, parameter: id must greater than 0', __FUNCTION__),
                    $this->config->item('parameter_err_no', 'err_no')
                    );

        }
        $articlekind = $this->dao('articleinfo/Dao_articlekind', $this->_dao_param)->fetch_one_by_id($id);
        $this->dao('articleinfo/Dao_articlekind', $this->_dao_param)->update($param, $id);
        //清除缓存
        foreach (array($articlekind, array_merge($articlekind, $param)) as $v){
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

		return $id;
    }
    
    /**
     * 魔术函数 当调用的函数不存在或权限被控制，此方法会自动被调用 
     * 
     * @param mixed $func 
     * @param mixed $args 
     * @return mixed
     */
    public function __call($func, $args) {
        return call_user_func_array(array($this->dao('articleinfo/Dao_articlekind',  $this->_dao_param), $func), $args);
    }

    /**
     * update_by_unique 
     * 
     * @param mixed $param 
     * @access public
     * @return mixed
     */
    public function update_by_unique($param) {
        unset($param['id']);
        $id  = $this->dao(sprintf('articleinfo/Dao_%s', 'articlekind'), $this->_dao_param)->update_by_unique($param);
        if (intval($id)<=0){
            $id    = $param['id'] = $this->dao('public/Dao_id_allocator')->allocate(array('ns'=>'Bs','table_name'=>'articlekind'));
            $param = array_merge($this->dao(sprintf('articleinfo/Dao_%s', 'articlekind'), $this->_dao_param)->new_one(), $param);
            $this->dao(sprintf('articleinfo/Dao_%s', 'articlekind'), $this->_dao_param)->insert($param);
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