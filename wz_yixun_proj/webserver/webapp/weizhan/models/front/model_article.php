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
class Model_article extends Wz_model {
    protected $_has_one  = array();
    protected $_has_many = array();
    protected $_model    = '';
    protected $_mkeys = array(
                 'Wd_Model_article_detail_id:%s' => array('zdmArticleId'),
              );
//    protected $_ttl    = 100;
    protected $_equal_search_items = array('zdmArticleId'=>'t',);
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
    function get_article_by_id($zdmArticleId) {
        //检查参数
        if(empty($zdmArticleId) || !is_array($zdmArticleId)){
            throw new Exception('parameter：zdm article id must be string and not empty。function：'.__FUNCTION__, $this->config->item('input_validate_err_no', 'err_no'));
        }
        //获取缓存key值
		$key = $this->_get_cache_key($zdmArticleId);
        //从缓存获取内容
        $articleinfo = $this->cache->redis->hgetall($key);
        //如果从缓存获取失败，则从值得买拉取
		if(empty($articleinfo)) {
			$url = $this->config->item('zdm_article_url').$zdmArticleId['zdmArticleId'].'&callback=';
//             $url = 'http://api.zdm.yixun.com/v1/article/detail?artid='.$zdmArticleId['zdmArticleId'].'&callback=';
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $res = curl_exec($curl);
            curl_close($curl);
            $zdmRes = json_decode($res,true);
            //检查返回的文章信息数据是否正确
            if($zdmRes['code'] != 0){
                throw new Exception('zdmArticleId：'.$zdmArticleId.', zdmErrCode: '.$zdmRes['code'].', 从值得买获取文章信息出现异常.', $this->config->item('data_err_no', 'err_no'));
            }
            //检查文章内容是否为空
            if(empty($zdmRes['data'])){
                throw new Exception('zdmArticleId：'.$zdmArticleId.', zdmErrCode: '.$zdmRes['code'].', 从值得买获取文章信息为空.', $this->config->item('data_exist_err_no', 'err_no'));
            };
            //将从值得买获取的内容放入缓存
            if(!$this->cache->redis->hmset($key,$zdmRes['data'])){
                $this->log->warn(sprintf('set %s to redis by key:%s failure.. zdmArticleId:%s', $this->_model, $key, $zdmArticleId['zdmArticleId']));
            }else{
                $this->cache->redis->setTimeout($key,$this->config->item('articleCache'));   //设置缓存时间
            }
            $articleinfo = $zdmRes['data'];
        }else{
            $this->log->info(sprintf('get %s from redis by key:%s success.. zdmArticleId:%s', $this->_model, $key, $zdmArticleId['zdmArticleId']));
        }
        return $articleinfo;
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
}
?>
