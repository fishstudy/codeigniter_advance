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

class Logic_master_info extends Wz_logic {
    //默认查询的文章状态
    protected $articleConditions = 1;
    protected $authentication = 2;
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
         //$post_data['createTime'] = time();
         $post_data['updateTime'] = time();
     }
     /**
      *  通过微站id调取商品类目
      */
     function shopcatalog($wzId){
         try {
            //微站id不能为空
            if (empty($wzId) || !is_numeric($wzId) || $wzId < 0) {
                throw new Exception('传递的微站id参数错误', $this->config->item('input_validate_err_no', 'err_no'));
            }
            //查询微站类目信息
            $shopcatalog = $this->api->call_api('base', 'base_api', 'product/model_shopcatalog', 'search', array(array('wzId'=>$wzId)));
            return $shopcatalog;
        } catch (Exception $e) {
            throw $e;
        }
     }
     /**
     * 通过微站id获得微站信息
     */
    function wzinfo($data) {
        try {
            //微站id不能为空
            if (empty($data['id']) || !is_numeric($data['id']) || $data['id'] < 0) {
                throw new Exception('传递的站长id参数错误', $this->config->item('input_validate_err_no', 'err_no'));
            }
            $wzId = $data['id'];
            //查询微站信息
            $wzInfo = $this->api->call_api('base', 'base_api', 'public/model_website', 'fetch_one_by_id', array($wzId));
//            var_dump($wzInfo['webMasterId']);
            if (isset($wzInfo['webMasterId'])) {
                return $this->masterinfo(array('id' => $wzInfo['webMasterId']));
            } else {
                throw new Exception('传递的微站id不存在', $this->config->item('input_validate_err_no', 'err_no'));
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * search 获取达人资料页所需信息
     * 
     * @param mixed $post_data 
     * @access public
     * @return mixed
     */
    function masterinfo($data) {
        $result = array();
        //站长id不能为空
        if(empty($data['id']) || !is_numeric($data['id']) || $data['id'] < 0){
            throw new Exception('传递的站长id参数错误', $this->config->item('input_validate_err_no', 'err_no'));
        }
        $webMasterInfo = array();
        //店长信息查询条件
        $masterId = $data['id'];
        try{
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
            //根据站长id，获取微站id，前端用于统计
            $siteSearchCondition = array('webMasterId'=>intval($webMasterInfo['id']));
            $siteInfo = $this -> api -> call_api('base','base_api','public/model_website','search',array($siteSearchCondition));
            $webSiteInfo['results'] = reset($siteInfo['results']);
            if(empty($webSiteInfo['results'])){
                throw new Exception('站长id: '.$data['id'].'，微站信息为空呢。', $this->config->item('wz_not_exit', 'err_no'));
            }
            //获取js，css等版本号
            $versionTimeStamp = $this->config->item('versionTimeStamp');
            if(empty($versionTimeStamp)){
                $versionTimeStamp = '';
            }
        }catch (Exception $e){
            throw $e;
        }

        $result['data'] = array('master'=>$webMasterInfo,'versionTimeStamp'=>$versionTimeStamp,'site'=>$webSiteInfo['results'],);
        return $result;
    }
}
?>
