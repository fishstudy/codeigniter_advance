<?php
/**
 * Model_articleinfo
 * 
 * @uses Wd
 * @uses _Model
 * @package 
 * @version $1.0$
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author fishxyu <fishxyu@yixun.com> 
 * @license 
 */
class Model_articleinfo extends Bs_model {
    const ID_CACHE_NUM=500;
    protected $_has_one  = array();
    protected $_has_many = array();
    protected $_model    = '';
    protected $_cache_keys = array(
                 'Bs_Model_articleinfo_wzId:%d_condition:%d:_category:%d' => array('wzId','conditions','categories'),
                 'Bs_Model_articleinfo_wzId:%d_conditions:%d' => array('wzId','conditions'),
                 'Bs_Model_articleinfo_wzId:%d_category:%d' => array('wzId','categories'),
                 'Bs_Model_articleinfo_wzId:%d_kindId:%d' => array('wzId','kindId'),
                 'Bs_Model_articleinfo_category:%d' => array('categories'),
                 'Bs_Model_articleinfo_kindId:%d' => array('kindId'),
                 'Bs_Model_articleinfo' => array(),     
              );
    protected $_equal_search_items = array('wzId'=>'t','conditions'=>'t','categories'=>'t','kindId'=>'t');
        
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
     * 根据文章ID 获取文章在改微站的顺序
     */
    function get_website_article_list($websiteid=0, $articleid=0, $kindId=0){
        $return = array();
        $dao_param = array('active_group'=>$this->config->item('active_group'), 'id'=>0);
        if(empty($websiteid)&& !empty($articleid)){
            $detail = $this->fetch_one_by_id($articleid);
            if(!empty($detail)) {
                $websiteid = $detail['wzId'];
            } else {
                return array();
            }
        }else if(empty($websiteid) && empty($articleid)){
            return array();
        }
        
        $redis_key = sprintf("Bs_Model_articleinfo_wzId:%d_kindId:%d", $websiteid, $kindId); 
        //$articleinfos = $this->cache->redis->del($redis_key);
        $articleinfos = $this->cache->redis->get($redis_key);
        if (empty($articleinfos)) {
            $param['page']     = 1;
            $param['pagesize'] = self :: ID_CACHE_NUM;
            $param['wzId'] = $websiteid;
            $param['kindId'] = $kindId;
            $param['conditions'] = 1;
            $param['ordersort']     =  'orderNum ASC, hitShelveTime DESC';//文章排序，上架时间
            $articleinfos = $this->dao('articleinfo/Dao_articleinfo', $dao_param )->search($param);
            $serilize = serialize($articleinfos);
            if (!$this->cache->redis->save($redis_key, $articleinfos)){
                $this->log->warn(sprintf('set %s to redis by key:%s failure..', $this->_model, $redis_key));
            }
        }else{
            $this->log->info(sprintf('search  %s from redis by key:%s success.', $this->_model, $redis_key));
        }
        
        //如果该ID不存，将该ID的随机插入列表中， 待完成
        $return = array_keys($articleinfos['results']);
        if(empty($return)) {
            return $return;
        }
        
        if(in_array($articleid,$return)) {
            
        } else {
            $len = count($return);
            $random = ceil($len*4/5);
            $return[$random] = $articleid;
            $articleinfos['results'][$random]['id'] = $articleid;
            //$this->cache->redis->del($redis_key);
            if (!$this->cache->redis->save($redis_key, $articleinfos)){
                $this->log->warn(sprintf('set %s to redis by key:%s failure..', $this->_model, $redis_key));
            }
            
        }
        
        return $return;
    }
    
    /**
     * 根据文章ID 获取多篇文章
     * @param unknown_type $ids
     * @return multitype:
     */
    function get_multi_ids($ids) {
        $return  = array();
        if(empty($ids) || !is_array($ids)) {
            return array();
        }
        $results = $this->get_multi($ids);
        $articleValid = $this->config->item('article_valid');
        foreach($ids as $id) {
            //过滤掉不可用的文章
            if(!empty($results[$id]) && !in_array($results[$id]['conditions'],$articleValid)){
                unset ($results[$id]);
            }
            $return[$id] = isset($results[$id]) ? $results[$id] : array();
        }
        
        return $return;
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
        $new_articleinfo  = array();
        if ($id > 0) {
            $new_articleinfo['articleinfo'] = $this->dao('articleinfo/Dao_articleinfo', $dao_param)->fetch_one_by_id($id);
        } else {
            $new_articleinfo['articleinfo']  = $this->dao('articleinfo/Dao_articleinfo', $dao_param)->new_one();
        }
                return $new_articleinfo;
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
        $articleinfo = $param['articleinfo'];
        $articleinfo_id = isset($articleinfo['id']) ? $articleinfo['id'] : 0;
        $is_update = FALSE;
        if ($articleinfo_id > 0) {
            unset($articleinfo['id']);
        //    $article_src = $this->dao('articleinfo/Dao_articleinfo', $dao_param)->fetch_one_by_id($articleinfo_id);
            $this->dao('articleinfo/Dao_articleinfo', $dao_param)->update($articleinfo, $articleinfo_id);
//           $article_new = $this->dao('articleinfo/Dao_articleinfo', $dao_param)->fetch_one_by_id($articleinfo_id);
//             print_r($article_src);
//             print_r($article_new);
//             $article_diff= array_diff($article_src,$article_new);
//             print_r($article_diff);
//             die();
            $is_update = TRUE;
        } else {
            $articleinfo_id = $this->dao('articleinfo/Dao_articleinfo', $dao_param)->insert($articleinfo);
        }
        if($is_update) {
            //$article_diff= array_diff($article_src,$article_new);
        }
        //清除缓存
        foreach ($this->_cache_keys as $key_pattern => $keys){
            $temp = array();
            $has_key = TRUE;
            foreach($keys as $key){
                if (!isset($articleinfo[$key])){
                    $has_key = FALSE;
                    break;
                }
                $temp[$key] = $articleinfo[$key];
            }
            if (!$has_key) continue;
            $key  = vsprintf($key_pattern, $temp);
            if (!$this->cache->redis->del($key)){
                $this->log->warn(sprintf('%s: delete key:%s from redis failure.', __FUNCTION__, $key));
            }
        }
        
        return $articleinfo_id;
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
        $articleinfos = array('num'=>0, 'results'=>array());
        if ($page > 0 && $pagesize > 0) {
            $param['page']      = $page;
            $param['pagesize']  = $pagesize;
        }
        $key = $this->_get_cache_key($param);
        $param['ordersort']     =  'orderNum ASC, hitShelveTime DESC';//文章排序，上架时间
        if ($key === FALSE || ($page * $pagesize) > self :: ID_CACHE_NUM ){
            //    $param['ordersort']     =  'createTime DESC';
            $articleinfos = $this->dao('articleinfo/Dao_articleinfo', $dao_param )->search($param);
        }else{ //缓存
            $articleinfos = $this->cache->redis->get($key);
            if (empty($articleinfos)) {
                $param['page']     = 1;
                $param['pagesize'] = self :: ID_CACHE_NUM;
                $articleinfos = $this->dao('articleinfo/Dao_articleinfo', $dao_param )->search($param);
                if (!$this->cache->redis->save($key, $articleinfos)){
                    $this->log->warn(sprintf('set %s to redis by key:%s failure..', $this->_model, $key));
                }
            }else{
                $this->log->info(sprintf('search  %s from redis by key:%s success.', $this->_model, $key));
            }
            if ($page > 0 && $pagesize > 0) {
                // array_slice 获取需要的数据
                $limit = $pagesize;
                if ($articleinfos['num'] < $page* $pagesize){
                    $limit = $articleinfos['num'] - ($page-1) * $pagesize;
                }
                $articleinfos['results'] = array_slice($articleinfos['results'], ($page-1) * $pagesize, $limit, TRUE);
            }
        }
        
        if ( $articleinfos['num'] > 0) {
            $items = $this->dao('articleinfo/Dao_articleinfo', $dao_param)->get_multi(array_keys($articleinfos['results']));
            foreach ($items as $item) {
                $articleinfos['results'][$item['id']] = $item;
            }
        }
        return  $articleinfos;
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
        $articleinfo = $this->dao('articleinfo/Dao_articleinfo', $dao_param)->fetch_one_by_id($id);
        if ($user_id >0 && $articleinfo['wzId'] != $user_id){
            throw new Exception(sprintf('function: %s, op:%d has no permission to delete user_id:%d', __FUNCTION__,
                        $user_id, $articleinfo['wzId']),
                    $this->config->item('permission_err_no', 'err_no'));
        }
        $this->dao('articleinfo/Dao_articleinfo', $dao_param)->delete_one_by_id($id);
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
        $articleinfo = $this->dao('articleinfo/Dao_articleinfo', $dao_param)->fetch_one_by_id($id);
        $this->dao('articleinfo/Dao_articleinfo', $dao_param)->update($param, $id);
        //清除缓存
        foreach (array($articleinfo, array_merge($articleinfo, $param)) as $v){
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
     * __call 
     * 
     * @param mixed $func 
     * @param mixed $args 
     * @access protected
     * @return mixed
     */
    function __call($func, $args) {
        $dao_param = array('active_group'=>$this->config->item('active_group'), 'id'=>0);
        return call_user_func_array(array($this->dao('articleinfo/Dao_articleinfo',  $dao_param), $func), $args);
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
                $id  = $this->dao(sprintf('articleinfo/Dao_%s', 'articleinfo'), $dao_param)->update_by_unique($param);
                if (intval($id)<=0){
            $id    = $param['id'] = $this->dao('public/Dao_id_allocator')->allocate(array('ns'=>'Wd','table_name'=>'articleinfo'));
            $param = array_merge($this->dao(sprintf('articleinfo/Dao_%s', 'articleinfo'), $dao_param)->new_one(), $param);
            $this->dao(sprintf('articleinfo/Dao_%s', 'articleinfo'), $dao_param)->insert($param);
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
