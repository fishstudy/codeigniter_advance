<?php 
/**
 * Logic__articleinfo
 * 
 * @uses Wd
 * @uses _Logic
 * @package 
 * @version $id$
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author fishxyu <fishxyu@yixun.com> 
 * @license 
 */

class Logic_product extends Wz_logic {

    /**
     * __construct 
     * 
     * @access protected
     * @return mixed
     */
    function __construct() {
        parent::__construct();
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
        return call_user_func_array(array($this->model('articleinfo/Model_articleinfo'), $func), $args);
    }
    
     protected function _adjust(&$post_data) {
         $post_data['updateTime'] = time();
     }

    /**
     * search 获取首页所需信息
     * 
     * @param mixed $post_data 
     * @access public
     * @return mixed
     */
    function lists($data) {
        $result = array();
        //检查站长id是否合法
        if(empty($data['id']) || !is_numeric($data['id']) || $data['id']<0){
            throw new Exception('传递的站长id参数错误, 站长id：'.empty($data['id'])? "":$data['id'], $this->config->item('parameter_err_no', 'err_no'));
        }
        //检查微店id是否合法
        if(empty($data['wzId']) || !is_numeric($data['wzId']) || $data['wzId']<0){
            throw new Exception('传递的微店id参数错误, 微店id：'.empty($data['wzId'])? "":$data['wzId'], $this->config->item('parameter_err_no', 'err_no'));
        }
        $page = empty($data['page']) ? 1 : $data['page'];
        $pagesize = empty($data['pagesize']) ? $this->config->item('pagesize') : $data['pagesize'];
        $webMasterInfo = array();
        $webSiteInfo = array();
        $productRes = array();
        //店长信息查询条件
        $masterId = $data['id'];
        //微店信息查询条件
        $wzId = $data['wzId'];
        //投放id查询条件
        $tfSearchCondition = array();
        $tfSearchCondition['wzId'] = $wzId;
        try{
            //如果没有输入tf id，则根据微站id，查询对应的tf ID
            $tfId = empty($data['tfId']) ? 0 : $data['tfId'];
            if(empty($tfId) || !is_numeric($tfId) || $tfId<0){
                $tfInfo = $this -> api ->call_api('base', 'base_api', 'product/Model_product', 'search', array($tfSearchCondition));
                if(!empty($tfInfo) && !empty($tfInfo['results'])){
                    $tfInfoResult = array_values($tfInfo['results']);
                    $tfId = intval($tfInfoResult[0]['tfId']);
                }
            }
            //查询商品信息
            $param = array('tfId'=>$tfId);
            $productRes = $this -> model('front/Model_products') -> get_product_by_id($param);
            $productRes = unserialize($productRes);
            if(empty($productRes)){
                $this->log->info('投放id: '.$tfId.', 微站id: '.$wzId.',获取商品信息为空。');
                $productRes =array();
                $productNum = 0;
            }else{
                $productNum = count($productRes);
                $offset = ($page-1)*$pagesize;
                $productRes = array_slice($productRes,$offset,$pagesize);
            }
            //查询站长信息
            $webMasterInfo = $this -> api->call_api('base', 'base_api', 'public/model_webmaster', 'fetch_one_by_id', array($masterId));
            $masterAuthen = intval($webMasterInfo['authentication']);
            $masterCondition = intval($webMasterInfo['conditions']);
            if(in_array($masterAuthen, $this->config->item('masterInvalid')) || in_array($masterCondition,$this->config->item('masterInCondition'))){
                if($masterCondition == $this->config->item('masterRefuse')){
                    throw new Exception('站长id: '.$data['id'].'，微站被拒绝。', $this->config->item('wz_refuse', 'err_no'));
                }else if($masterCondition == $this->config->item('masterAuthit')){
                    throw new Exception('站长id: '.$data['id'].'，微站还在审核中。', $this->config->item('wz_authit', 'err_no'));
                }else if($masterCondition == $this->config->item('masterClose')){
                    throw new Exception('站长id: '.$data['id'].'，微站被关闭。', $this->config->item('wz_closed', 'err_no'));
                }else{
                    throw new Exception('站长id: '.$data['id'].'，站长待认证哦。', $this->config->item('master_invalid', 'err_no'));
                }
            }
            //查询微站信息
            $webSiteInfo = $this -> api->call_api('base', 'base_api','public/model_website','fetch_one_by_id',array($wzId));
            if(empty($webSiteInfo)){
                throw new Exception('站长id: '.$data['id'].'，微站信息为空呢。', $this->config->item('wz_not_exit', 'err_no'));
            }
            //js，css版本等
            $versionTimeStamp = $this->config->item('versionTimeStamp');
            if(empty($versionTimeStamp)){
                $versionTimeStamp = '';
            }
        }catch (Exception $e){
            throw $e;
        }
        $result['data'] = array('master'=>$webMasterInfo,'site'=>$webSiteInfo,'product'=>$productRes, 'versionTimeStamp'=>$versionTimeStamp,'productNum'=>$productNum,);
        return $result;
    }

    function productListJson($data) {
        $result = array();
        //检查微店id是否合法
        if(empty($data['wzId']) || !is_numeric($data['wzId']) || $data['wzId']<0){
            throw new Exception('传递的微店id参数错误, 微店站长id：'.empty($data['wzId'])? "":$data['wzId'], $this->config->item('parameter_err_no', 'err_no'));
        }
        $page = empty($data['page']) ? 1 : $data['page'];
        $pagesize = empty($data['pagesize']) ? $this->config->item('pagesize') : $data['pagesize'];
        $productRes = array();
        //投放id查询条件
        $tfSearchCondition = array();
        $tfSearchCondition['wzId'] = $data['wzId'];
        try{
            $tfId = empty($data['tfId']) ? 0 : $data['tfId'];
            if(empty($tfId) || !is_numeric($tfId) || $tfId<0){
                $tfInfo = $this -> api ->call_api('base', 'base_api', 'product/Model_product', 'search', array($tfSearchCondition));
                if(!empty($tfInfo) && !empty($tfInfo['results'])){
                    $tfInfoResult = array_values($tfInfo['results']);
                    $tfId = intval($tfInfoResult[0]['tfId']);
                }
            }
            //查询商品信息
            $param = array('tfId'=>$tfId);
            $productRes = $this -> model('front/Model_products') -> get_product_by_id($param);
            $productRes = unserialize($productRes);
            if(empty($productRes)){
                $this->log->info('投放id: '.$tfId.', 微站id: '.$data['wzId'].',获取商品信息为空。');
                $productRes =array();
                $productNum = 0;
            }else{
                $productNum = count($productRes);
                $offset = ($page-1)*$pagesize;
                $productRes = array_slice($productRes,$offset,$pagesize);
            }
        }catch (Exception $e){
            throw $e;
        }
        $result['data'] = array('product'=>$productRes,'productNum'=>$productNum,);
        return $result;
    }

    /**
     * 新微站一期---获取商品列表
     *
     * @param mixed $data
     * @access public
     * @return mixed
     */
    function newlists($data) {
    	$result = array();
    	//检查站长id是否合法
//     	if(empty($data['id']) || !is_numeric($data['id']) || $data['id']<0){
//     		throw new Exception('传递的站长id参数错误, 站长id：'.empty($data['id'])? "":$data['id'], $this->config->item('parameter_err_no', 'err_no'));
//     	}
    	//检查微店id是否合法
    	if(empty($data['wzId']) || !is_numeric($data['wzId']) || $data['wzId']<0){
    		throw new Exception('传递的微店id参数错误, 微店id：'.empty($data['wzId'])? "":$data['wzId'], $this->config->item('parameter_err_no', 'err_no'));
    	}
    	$page = empty($data['page']) ? 1 : $data['page'];
    	$pagesize = empty($data['pagesize']) ? $this->config->item('pagesize') : $data['pagesize'];
    	$webMasterInfo = array();	//站长信息
    	$webSiteInfo = array();		//微站信息
    	$productRes = array();		//商品信息
    	//微店信息查询条件
    	$wzId = $data['wzId'];
    	//查询条件
    	$SearchCondition = array();
    	$searchCondition['wzId'] = $data['wzId'];
    	$searchCondition['type'] = $data['type'];
    	$searchCondition['page'] = $page;
    	$searchCondition['pagesize'] = $pagesize;
    	
    	try{
    		if (!empty($data['scId'])) {
	   			$searchCondition['scId'] = $data['scId'];
    		}
    		$productInfo = $this->api->call_api('base', 'base_api', 'product/Model_shopproduct', 'search', array($searchCondition));
    		//查询商品信息
    		if(empty($productInfo['results'])){
    			$this->log->info('微站id: '.$data['wzId'].',获取商品信息为空。');
    			$productRes =array();
    			$productNum = 0;
    		}else{
    			$productNum = $productInfo['num'];
    			$productRes = $productInfo['results'];
    		}
    		//查询微站信息
    		$webSiteInfo = $this->api->call_api('base', 'base_api', 'public/model_website', 'fetch_one_by_id', array($wzId));
    		if(empty($webSiteInfo)){
    			throw new Exception('站长id: '.$webSiteInfo['webMasterId'].'，微站信息为空呢。', $this->config->item('wz_not_exit', 'err_no'));
    		}
    		//查询站长信息
    		$webMasterInfo = $this->api->call_api('base', 'base_api', 'public/model_webmaster', 'fetch_one_by_id', array($webSiteInfo['webMasterId']));
    		$masterAuthen = intval($webMasterInfo['authentication']);
    		$masterCondition = intval($webMasterInfo['conditions']);
    		if(in_array($masterAuthen, $this->config->item('masterInvalid')) || in_array($masterCondition,$this->config->item('masterInCondition'))){
    			if($masterCondition == $this->config->item('masterRefuse')){
    				throw new Exception('站长id: '.$webMasterInfo['id'].'，微站被拒绝。', $this->config->item('wz_refuse', 'err_no'));
    			}else if($masterCondition == $this->config->item('masterAuthit')){
    				throw new Exception('站长id: '.$webMasterInfo['id'].'，微站还在审核中。', $this->config->item('wz_authit', 'err_no'));
    			}else if($masterCondition == $this->config->item('masterClose')){
    				throw new Exception('站长id: '.$webMasterInfo['id'].'，微站被关闭。', $this->config->item('wz_closed', 'err_no'));
    			}else{
    				throw new Exception('站长id: '.$webMasterInfo['id'].'，站长待认证哦。', $this->config->item('master_invalid', 'err_no'));
    			}
    		}
    		//js，css版本等
    		$versionTimeStamp = $this->config->item('versionTimeStamp');
    		if(empty($versionTimeStamp)){
    			$versionTimeStamp = '';
    		}
    	}catch (Exception $e){
    		throw $e;
    	}
    	$result['data'] = array('master'=>$webMasterInfo,'site'=>$webSiteInfo,'product'=>$productRes, 'versionTimeStamp'=>$versionTimeStamp, 'productNum'=>$productNum);
    	return $result;
    }
    
    /**
     * 新微站一期---商品列表
     * 
     * @param  $data
     * @throws Exception
     * @return $result array
     */
    function newListsJson($data) {
    	$result = array();
    	//检查微店id是否合法
    	if(empty($data['wzId']) || !is_numeric($data['wzId']) || $data['wzId']<0){
    		throw new Exception('传递的微店id参数错误, 微店站长id：'.empty($data['wzId'])? "":$data['wzId'], $this->config->item('parameter_err_no', 'err_no'));
    	}
    	if(empty($data['type']) || !is_numeric($data['type']) || $data['type']<0){
    		throw new Exception('传递的商品类型type参数错误, 商品类型：'.empty($data['type'])? "":$data['type'], $this->config->item('parameter_err_no', 'err_no'));
    	}
    	$page = empty($data['page']) ? 1 : max($data['page'], 1);
    	$pagesize = empty($data['pagesize']) ? $this->config->item('pagesize') : $data['pagesize'];
    	
    	//检查类目是否在当前的微站里
    	
    	$productRes = array();
    	//查询条件
    	$searchCondition = array();
    	$searchCondition['wzId'] = $data['wzId'];
    	$searchCondition['type'] = $data['type'];
    	$searchCondition['page'] = $page;
    	$searchCondition['pagesize'] = $pagesize;
    	
    	try{
    		$scId = empty($data['scId']) ? 0 : $data['scId'];
    		$searchCondition['scId'] = $scId;
    		$productInfo = $this->api->call_api('base', 'base_api', 'product/Model_shopproduct', 'search', array($searchCondition));
//     		if( $productInfo['results'] > 0 ){
//     			$productInfoResult = array_values($productInfo['results']);
//     		}
    		//商品信息
    		if(empty($productInfo['results'])){
    			$this->log->info('微站id: '.$data['wzId'].',获取商品信息为空。');
    			$productRes =array();
    			$productNum = 0;
    		}else{
    			$productNum = $productInfo['num'];
    			$productRes = $productInfo['results'];
    		}
    	}catch (Exception $e){
    		throw $e;
    	}
    	$result['data'] = array('product'=>$productRes,'productNum'=>$productNum);
    	return $result;
    }
}