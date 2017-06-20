<?php
/**
 * Model_{{$name}}
 * 
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author weidian team <fishxyu@yixun.com> 
 * @license 
 */
class Model_{{$name}} extends {{$prefix}}_model {
    protected $_model    = '{{$name}}';
    protected $_cache_keys = array(
            {{foreach $mkeys as $key=>$mkey}}'{{$prefix}}_Model_{{$name}}_{{$key}}' => array({{foreach $mkey as $v}}'{{$v}}', {{/foreach}}),
            {{/foreach}} {{if !in_array($name, array('census_day', 'census_week'))}}'{{$prefix}}_Model_{{$name}}' => array(),{{/if}}
            );
    protected $_equal_search_items = array({{foreach $equal_search_items as $v}}'{{$v}}'=>'t',{{/foreach}});
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
        ${{$name}}s = array('num'=>0, 'results'=>array());
        $key = $this->_get_cache_key($param);
        $param['ordersort']     =  'createTime DESC';
        if ($key === FALSE || ($page * $pagesize) > self :: ID_CACHE_NUM ){
            ${{$name}}s = $this->dao('{{$dir}}/Dao_{{$name}}', $this->_dao_param )->search($param);
        }else{ //缓存
            ${{$name}}s = $this->cache->redis->get($key);
            if (empty(${{$name}}s)) {
                $param['page']     = 1;
                $param['pagesize'] = self :: ID_CACHE_NUM;
                ${{$name}}s = $this->dao('{{$dir}}/Dao_{{$name}}', $this->_dao_param )->search($param);
                $listids = ${{$name}}s;
                $this->cache->redis->lpush_multi($key, $listids);
                $this->log->debug(sprintf('set %s to redis by key:%s ..', $this->_model, $key));
            }else{
                $listids = lrange($key, 0, -1);
                $this->log->info(sprintf('search  %s from redis by key:%s success.', $this->_model, $key));
                 
            }
    
            if ($page > 0 && $pagesize > 0) {
                // array_slice 获取需要的数据
                $limit = $pagesize;
                if (${{$name}}s['num'] < $page* $pagesize){
                    $limit = ${{$name}}s['num'] - ($page-1) * $pagesize;
                }
                ${{$name}}s['results'] = array_slice(${{$name}}s['results'], ($page-1) * $pagesize, $limit, TRUE);
            }
        }
    
        if ( ${{$name}}s['num'] > 0) {
            $items = $this->dao('{{$dir}}/Dao_{{$name}}', $this->_dao_param)->get_multi(array_keys(${{$name}}s['results']));
            foreach ($items as $item) {
                ${{$name}}s['results'][$item['id']] = $item;
            }
        }
    
        return  ${{$name}}s;
    }
    
    /**
     * 新增
     *
     * @param int $id
     * @return mixed
     */
    public function create($id = 0) {
        $new_{{$name}}  = array();
        if ($id > 0) {
            $new_{{$name}}['{{$name}}'] = $this->dao('{{$dir}}/Dao_{{$name}}', $this->_dao_param)->fetch_one_by_id($id);
        } else {
            $new_{{$name}}['{{$name}}']  = $this->dao('{{$dir}}/Dao_{{$name}}', $this->_dao_param)->new_one();
        }
        
        return $new_{{$name}};
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

        ${{$name}} = $param['{{$name}}'];
        ${{$name}}_id = isset(${{$name}}['id']) ? ${{$name}}['id'] : 0;
        $is_update = FALSE;
        if (${{$name}}_id > 0) {
            unset(${{$name}}['id']);
            ${{$name}} = $this->dao('{{$dir}}/Dao_{{$name}}', $this->_dao_param)->fetch_one_by_id($id);
            $this->dao('{{$dir}}/Dao_{{$name}}', $this->_dao_param)->update(${{$name}}, ${{$name}}_id);
            $is_update = TRUE;
        } else {
            ${{$name}}_id = $this->dao('{{$dir}}/Dao_{{$name}}', $this->_dao_param)->insert(${{$name}});
        }

        //清除缓存
        foreach ($this->_cache_keys as $key_pattern => $keys){
            $temp = array();
            $has_key = TRUE;
            foreach($keys as $key){
                if (!isset(${{$name}}[$key])){
                    $has_key = FALSE;
                    break;
                }
                $temp[$key] = ${{$name}}[$key];
            }
            if (!$has_key) continue;
            $key  = vsprintf($key_pattern, $temp);
            if (!$this->cache->redis->del($key)){
                $this->log->warn(sprintf('%s: delete key:%s from redis failure.', __FUNCTION__, $key));
            }
        }
        
        return ${{$name}}_id;
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
        ${{$name}} = $this->dao('{{$dir}}/Dao_{{$name}}', $this->_dao_param)->fetch_one_by_id($id);
        if ($user_id >0 && ${{$name}}['user_id'] != $user_id){
            throw new Exception(sprintf('function: %s, op:%d has no permission to delete user_id:%d', __FUNCTION__,
                        $user_id, ${{$name}}['user_id']),
                    $this->config->item('permission_err_no', 'err_no'));
        }
        $this->dao('{{$dir}}/Dao_{{$name}}', $this->_dao_param)->delete_one_by_id($id);

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
        ${{$name}} = $this->dao('{{$dir}}/Dao_{{$name}}', $this->_dao_param)->fetch_one_by_id($id);
        $this->dao('{{$dir}}/Dao_{{$name}}', $this->_dao_param)->update($param, $id);
        //清除缓存
        foreach (array(${{$name}}, array_merge(${{$name}}, $param)) as $v){
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
        return call_user_func_array(array($this->dao('{{$dir}}/Dao_{{$name}}',  $this->_dao_param), $func), $args);
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
        $id  = $this->dao(sprintf('{{$dir}}/Dao_%s', '{{$name}}'), $this->_dao_param)->update_by_unique($param);
        if (intval($id)<=0){
            $id    = $param['id'] = $this->dao('public/Dao_id_allocator')->allocate(array('ns'=>'{{$prefix}}','table_name'=>'{{$name}}'));
            $param = array_merge($this->dao(sprintf('{{$dir}}/Dao_%s', '{{$name}}'), $this->_dao_param)->new_one(), $param);
            $this->dao(sprintf('{{$dir}}/Dao_%s', '{{$name}}'), $this->_dao_param)->insert($param);
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
