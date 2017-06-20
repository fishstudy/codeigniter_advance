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
class Model_products extends Wz_model {
    protected $_has_one  = array();
    protected $_has_many = array();
    protected $_model    = '';
    protected $_mkeys = array(
                 'Wd_Model_tf_product_detail_id:%s' => array('tfId'),
              );
    protected $_equal_search_items = array('tfId'=>'t',);
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

//    /**
//     * __call
//     *
//     * @param mixed $func
//     * @param mixed $args
//     * @access protected
//     * @return mixed
//     */
//    function __call($func, $args) {
//        $dao_param = array('active_group'=>$this->config->item('active_group'), 'id'=>0);
//        return call_user_func_array(array($this->dao('articleinfo/Dao_articleinfo',  $dao_param), $func), $args);
//    }
}
?>
