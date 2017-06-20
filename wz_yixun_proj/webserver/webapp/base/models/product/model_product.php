<?php
/**
 * Model_product
 * 
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author weidian team <fishxyu@yixun.com> 
 * @license 
 */
class Model_product extends Bs_model {
    protected $_model    = 'product';
    protected $_cache_keys = array(
              'Bs_Model_product_wzId:%d' => array('wzId'),
              'Bs_Model_product_tfId:%d' => array('tfId'),
              'Bs_Model_product' => array(),            
              );
    protected $_equal_search_items = array('id'=>'t','wzId'=>'t','tfId'=>'t','status'=>'t');
    protected $_dao_param = array('id'=>'t','wzId'=>'t','tfId'=>'t','status'=>'t');
    
    /**
     * __construct
     *
     * @access protected
     * @return mixed
     */
    public function __construct() {
        parent :: __construct();
        $this->_model = substr(__CLASS__, 6);
        $this->_dao_param = array('active_group'=>$this->config->item('active_group'), 'id'=>0);
    }

    /**
     * get_article_by_id
     *
     * @param int $id
     * @param int $user_id
     * @access public
     * @return mixed
     * $param['id'] =10;
     */
    function get_product_by_id($tfId) {
        //检查参数
        if(empty($tfId) || !is_array($tfId)){
            throw new Exception('parameter：tfId can not empty。function：'.__FUNCTION__, $this->config->item('input_validate_err_no', 'err_no'));
        }
        //获取缓存key值
        $key = $this->_get_cache_key($tfId);
        //从缓存获取内容
        $productRes = $this->cache->redis->get($key);
        //如果从缓存获取失败，则从值得买拉取
        if(empty($productRes)) {
            $tfUrl = $this->config->item('tfurl');
            $url = $tfUrl.$tfId['tfId'].'&type=json';
            $cookieStr = '';
            foreach($_COOKIE as $k=>$v){
                $cookieStr = $cookieStr.$k."=".$v."; ";
            }
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl,CURLOPT_COOKIE,$cookieStr);
            $res = curl_exec($curl);
            curl_close($curl);
            $productRes = json_decode($res,true);
            //检查返回的商品信息是否正确
            if(empty($productRes['data'])){
                $this->log->warn('投放id: '.$tfId['tfId'].'错误代码：'.$productRes['iRet'].', 信息：'.$productRes['msg'].'，获取商品数据出现异常。');
                return $productRes='';
                //throw new Exception('投放id: '.$tfId['tfId'].'错误代码：'.$productRes['iRet'].', 信息：'.$productRes['msg'].'，获取商品数据出现异常。', $this->config->item('get_product_failed', 'err_no'));
            }
            //将获取的商品信息放入缓存
            $productSerialize = serialize($productRes['data']['POS_1']);
            if(!$this->cache->redis->save($key,$productSerialize,$this->config->item('productCache'))){
                $this->log->warn(sprintf('set %s to redis by key:%s failure.. tfId:%s', $this->_model, $key, $tfId['tfId']));
            }
            $productRes = $productSerialize;
        }else{
            $this->log->info(sprintf('get %s from redis by key:%s success.. tfId:%s', $this->_model, $key, $tfId['tfId']));
        }
        return $productRes;
    }
    
    /**
     * 列表页
     *
     * @param array $param
     * @return mixed
     */
    public function search($param = array(), $page=1, $pagesize=20) {
        $products = array('num'=>0, 'results'=>array());
        $key = $this->_get_cache_key($param);
        $param['ordersort']     =  'createTime DESC';
        if ($key === FALSE || ($page * $pagesize) > self :: ID_CACHE_NUM ){            $products = $this->dao('product/Dao_product', $this->_dao_param )->search($param);
        }else{ //缓存
            $products = $this->cache->redis->get($key);
            if (empty($products)) {
                $param['page']     = 1;
                $param['pagesize'] = self :: ID_CACHE_NUM;
                $products = $this->dao('product/Dao_product', $this->_dao_param )->search($param);
            	if (!$this->cache->redis->save($key, $products)){
                    $this->log->warn(sprintf('set %s to redis by key:%s failure..', $this->_model, $key));
                }
            }else{
                $this->log->info(sprintf('search  %s from redis by key:%s success.', $this->_model, $key));
            }
            if ($page > 0 && $pagesize > 0) {
                // array_slice 获取需要的数据
                $limit = $pagesize;
                if ($products['num'] < $page* $pagesize){
                    $limit = $products['num'] - ($page-1) * $pagesize;
                }
                $products['results'] = array_slice($products['results'], ($page-1) * $pagesize, $limit, TRUE);
            }
        }
    
        if ( $products['num'] > 0) {
            $items = $this->dao('product/Dao_product', $this->_dao_param)->get_multi(array_keys($products['results']));
            foreach ($items as $item) {
                $products['results'][$item['id']] = $item;
            }
        }
        return  $products;
    }
    
    /**
     * 新增
     *
     * @param int $id
     * @return mixed
     */
    public function create($id = 0) {
        $new_product  = array();
        if ($id > 0) {
            $new_product['product'] = $this->dao('product/Dao_product', $this->_dao_param)->fetch_one_by_id($id);
        } else {
            $new_product['product']  = $this->dao('product/Dao_product', $this->_dao_param)->new_one();
        }
        
        return $new_product;
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

        $product = $param['product'];
        $product_id = isset($product['id']) ? $product['id'] : 0;
        $is_update = FALSE;
        if ($product_id > 0) {
            unset($product['id']);
//             $product = $this->dao('product/Dao_product', $this->_dao_param)->fetch_one_by_id($product_id);
            $this->dao('product/Dao_product', $this->_dao_param)->update($product, $product_id);
            $is_update = TRUE;
        } else {
            $product_id = $this->dao('product/Dao_product', $this->_dao_param)->insert($product);
        }

        //清除缓存
        foreach ($this->_cache_keys as $key_pattern => $keys){
            $temp = array();
            $has_key = TRUE;
            foreach($keys as $key){
                if (!isset($product[$key])){
                    $has_key = FALSE;
                    break;
                }
                $temp[$key] = $product[$key];
            }
            if (!$has_key) continue;
            $key  = vsprintf($key_pattern, $temp);
            if (!$this->cache->redis->del($key)){
                $this->log->warn(sprintf('%s: delete key:%s from redis failure.', __FUNCTION__, $key));
            }
        }
        
        return $product_id;
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
        $product = $this->dao('product/Dao_product', $this->_dao_param)->fetch_one_by_id($id);
        if ($user_id >0 && $product['user_id'] != $user_id){
            throw new Exception(sprintf('function: %s, op:%d has no permission to delete user_id:%d', __FUNCTION__,
                        $user_id, $product['user_id']),
                    $this->config->item('permission_err_no', 'err_no'));
        }
        $this->dao('product/Dao_product', $this->_dao_param)->delete_one_by_id($id);

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
        $product = $this->dao('product/Dao_product', $this->_dao_param)->fetch_one_by_id($id);
        $this->dao('product/Dao_product', $this->_dao_param)->update($param, $id);
        //清除缓存
        foreach (array($product, array_merge($product, $param)) as $v){
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
        return call_user_func_array(array($this->dao('product/Dao_product',  $this->_dao_param), $func), $args);
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
        $id  = $this->dao(sprintf('product/Dao_%s', 'product'), $this->_dao_param)->update_by_unique($param);
        if (intval($id)<=0){
            $id    = $param['id'] = $this->dao('public/Dao_id_allocator')->allocate(array('ns'=>'Bs','table_name'=>'product'));
            $param = array_merge($this->dao(sprintf('product/Dao_%s', 'product'), $this->_dao_param)->new_one(), $param);
            $this->dao(sprintf('product/Dao_%s', 'product'), $this->_dao_param)->insert($param);
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
