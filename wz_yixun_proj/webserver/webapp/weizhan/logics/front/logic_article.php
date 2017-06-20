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

class Logic_article extends Wz_logic {

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
     * search 获取首页所需信息
     * 
     * @param mixed $post_data 
     * @access public
     * @return mixed
     */
    function index($data) {
        $result = array();
        //检查站长id是否合法
        if(empty($data['id']) || !is_numeric($data['id']) || $data['id'] < 0){
            throw new Exception('传递的站长id参数错误, 站长id：'.empty($data['id'])? "":$data['id'], $this->config->item('parameter_err_no', 'err_no'));
        }
        $page = empty($data['page']) ? 1 : $data['page'];
        $pagesize = empty($data['pagesize']) ? $this->config->item('index_page_size') : $data['pagesize'];
        $webMasterInfo = array();
        $webSiteInfo = array();
        $articleinfo = array();
        //店长信息查询条件
        $masterId = $data['id'];
        //微店信息查询条件
        $webSiteCondition = array();
        $webSiteCondition['webMasterId'] = $data['id'];
        //文章信息查询条件
        $articleCondition = array();
        $articleCondition['conditions'] = $data['conditions'];
        //投放id查询条件
        $productCondition = array();
        try{
            //获取站长信息
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

            //获取微站信息
            $webSiteInfo = $this -> api->call_api('base', 'base_api','public/model_website','search',array($webSiteCondition));
            $webSiteInfo['results'] = reset($webSiteInfo['results']);
            if(empty($webSiteInfo['results'])){
                throw new Exception('站长id: '.$data['id'].'，微站信息为空呢。', $this->config->item('wz_not_exit', 'err_no'));
            }

            //获取文章信息
            $articleCondition['wzId'] = $webSiteInfo['results']['id'];
            $articleinfo = $this -> api->call_api('base', 'base_api','articleinfo/Model_articleinfo','search',array($articleCondition,$page,$pagesize));
            $articleinfo['results'] =  array_values($articleinfo['results']);
            if(empty($articleinfo['results'])){
                $this->log->info('站长id: '.$data['id'].', 微站id: '.$articleCondition['wzId'].',获取文章信息为空。');
                $articleinfo =array('num'=>0,'results'=>array());
            }
            //转换上架时间为前台所需的格式,并且赋值分类信息
            $articleIdList = array();
            $articleinfo = $this->_articleLoop($articleinfo,$articleIdList);
            $articleIdList = implode(',',$articleIdList);
            //获取商品信息
            $productCondition['wzId'] = $webSiteInfo['results']['id'];
            $tfInfo = $this -> api ->call_api('base', 'base_api', 'product/Model_product', 'search',array($productCondition));
            if(!empty($tfInfo) && !empty($tfInfo['results'])){
                $tfInfoResult = array_values($tfInfo['results']);
                $tfId = intval($tfInfoResult[0]['tfId']);
                $productInfo = $this->_getProductInfo($tfId,$page,$pagesize);
                if(empty($productInfo)){
                    $this->log->info('投放id: '.empty($tfId) ? "":$tfId.', 微站id: '.$webSiteInfo['results']['id'].',获取商品信息为空。');
                    $productInfo =array();
                }
            }else{
                $productInfo = array();
            }

            //获取js，css等版本号，版本号由前端同学维护
            $versionTimeStamp = $this->config->item('versionTimeStamp');
            if(empty($versionTimeStamp)){
                $versionTimeStamp = '';
            }
        }catch (Exception $e){
            throw $e;
        }
        $result['data'] = array('master'=>$webMasterInfo,'site'=>$webSiteInfo,'article'=>$articleinfo, 'versionTimeStamp'=>$versionTimeStamp,'product'=>$productInfo,
                                'articleIdList'=>$articleIdList,);
        return $result;
    }

    /*
     * 查询文章列表
     * 参数：微站id-wzId，状态-conditions，类别-categories(如果为空则为全部)
     * 返回值：文章列表
     */
    function lists($data) {
        $page = empty($data['page']) ? 1 : $data['page'];
        $pagesize = empty($data['pagesize']) ? $this->config->item('pagesize') : $data['pagesize'];
        //检查站长id是否合法
        if(empty($data['id']) || !is_numeric($data['id']) || $data['id'] < 0){
            throw new Exception('传递的站长id参数为空或错误，id: '.empty($data['id']) ? '':$data['id'], $this->config->item('parameter_err_no', 'err_no'));
        }
        //检查微站id是否合法
        if(empty($data['wzId']) || !is_numeric($data['wzId']) || $data['wzId'] < 0){
            throw new Exception('传递的微站id参数错误, wzId: '.empty($data['wzId']) ? '':$data['wzId'], $this->config->item('parameter_err_no', 'err_no'));
        }
        $articleListInfo = array();
        $masterInfo = array();
        $result = array();
        //站长信息查询条件
        $masterId = $data['id'];
        //文章列表信息查询条件
        $articleSearchConditions = array();
        $articleSearchConditions['wzId'] = $data['wzId'];
        $articleSearchConditions['conditions'] = $data['conditions'];
        if(!empty($data['categories'])){
            $articleSearchConditions['categories'] = $data['categories'];
        }
        try{
            //获取站长信息
            $masterInfo = $this -> api->call_api('base', 'base_api','public/model_webmaster','fetch_one_by_id', array($masterId));
            $masterAuthen = intval($masterInfo['authentication']);
            $masterCondition = intval($masterInfo['conditions']);
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
            //查询文章列表信息
            $articleListInfo = $this -> api->call_api('base', 'base_api','articleinfo/Model_articleinfo', 'search',array($articleSearchConditions, $page, $pagesize));
            $articleListInfo['results'] =  array_values($articleListInfo['results']);
            if(empty($articleListInfo['results'])){
                $this->log->info('wzId: '.$data['wzId'].', conditions: '.$articleSearchConditions['conditions'].', category: '.(empty($articleSearchConditions['categories'])? '':$articleSearchConditions['categories']).'，获取文章列表信息为空。');
                $articleListInfo =array('num'=>0,'results'=>array());
            }
            //获取文章分类信息
            $articleCategory = $this ->config->item('article_category');
            //转换上架时间为前台所需的格式，并且赋值分类信息
            $articleIdList = array();
            $articleListInfo = $this->_articleLoop($articleListInfo,$articleIdList);
            $articleIdList = implode(',',$articleIdList);
            //获取js，css等版本号，版本号由前端同学维护
            $versionTimeStamp = $this->config->item('versionTimeStamp');
            if(empty($versionTimeStamp)){
                $versionTimeStamp = '';
            }
            //当前页的category
            $currentCategoryId = empty($data['categories']) ? 'n':$data['categories'];
            $currentCategoryText = empty($articleCategory[$currentCategoryId]) ? '':$articleCategory[$currentCategoryId];
            $currentCategory = array('id'=>$currentCategoryId,'text'=>$currentCategoryText);
        }catch (Exception $e){
            throw $e;
        }
        $wzId = array('id'=>$data['wzId']);
        $result['data'] = array('master' =>$masterInfo, 'article'=>$articleListInfo, 'category'=>$articleCategory, 'site'=>$wzId,
            'currentCategory'=>$currentCategory, 'versionTimeStamp'=>$versionTimeStamp, 'articleIdList'=>$articleIdList,);
        return $result;
    }

    /**
     * 新微站一期--查询文章列表
     * 
     * 参数：微站id-wzId，状态-conditions，类别-kindId(如果为空则为全部)
     * 返回值：文章列表
     */
    function newlists($data) {
    	$page = empty($data['page']) ? 1 : $data['page'];
    	$pagesize = empty($data['pagesize']) ? $this->config->item('pagesize') : $data['pagesize'];
    	//检查站长id是否合法
    	if(empty($data['id']) || !is_numeric($data['id']) || $data['id'] < 0){
    		throw new Exception('传递的站长id参数为空或错误，id: '.empty($data['id']) ? '':$data['id'], $this->config->item('masterid_exist_err_no', 'err_no'));
    	}
    	//检查微站id是否合法
    	if(empty($data['wzId']) || !is_numeric($data['wzId']) || $data['wzId'] < 0){
    		throw new Exception('传递的微站id参数错误, wzId: '.empty($data['wzId']) ? '':$data['wzId'], $this->config->item('parameter_err_no', 'err_no'));
    	}
    	$articleListInfo = array();
    	$masterInfo = array();
    	$result = array();
    	//站长信息查询条件
    	$masterId = $data['id'];
    	//文章列表信息查询条件
    	$articleSearchConditions = array();
    	$articleSearchConditions['wzId'] = $data['wzId'];
    	$articleSearchConditions['conditions'] = $data['conditions'];
    	if(!empty($data['kindId'])){
    		$articleSearchConditions['kindId'] = $data['kindId'];
    	}
    	try{
    		//获取站长信息
    		$masterInfo = $this->api->call_api('base', 'base_api', 'public/model_webmaster', 'fetch_one_by_id', array($masterId));
    		$masterAuthen = intval($masterInfo['authentication']);
    		$masterCondition = intval($masterInfo['conditions']);
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
    		//查询文章列表信息
    		$articleListInfo = $this->api->call_api('base', 'base_api', 'articleinfo/Model_articleinfo', 'search', array($articleSearchConditions, $page, $pagesize));
    		$articleListInfo['results'] = array_values($articleListInfo['results']);
    		if(empty($articleListInfo['results'])){
    			$this->log->info('wzId: '.$data['wzId'].', conditions: '.$articleSearchConditions['conditions'].', kindId: '.(empty($articleSearchConditions['kindId'])? '':$articleSearchConditions['kindId']).'，获取文章列表信息为空。');
    			$articleListInfo =array('num'=>0,'results'=>array());
    		}
    		//获取文章类目信息
    		$articlekindSearch = array();
    		$articlekindSearch['wzId'] = $data['wzId'];
    		$articlekind = $this->api->call_api('base', 'base_api', 'articleinfo/Model_articlekind', 'search', array($articlekindSearch, $page, $pagesize));
    		$articleCategory = array();
    		if ($articlekind['num']>0) {
    			foreach ($articlekind['results'] as $atk=>$atv){
    				$articleCategory[$atk] = $atv['kindName'];
    			}
    		}
//     		$articleCategory['n'] = "所有文章";
//     		$articleCategory = $this ->config->item('article_category');
    		//转换上架时间为前台所需的格式，并且赋值分类信息
    		$articleIdList = array();
    		$articleListInfo = $this->_articleLoop($articleListInfo, $articleIdList);
    		$articleIdList = implode(',', $articleIdList);
    		//获取js，css等版本号，版本号由前端同学维护
    		$versionTimeStamp = $this->config->item('versionTimeStamp');
    		if(empty($versionTimeStamp)){
    			$versionTimeStamp = '';
    		}
    		//当前页的category
    		$currentCategoryId = empty($data['kindId']) ? '':$data['kindId'];
    		$currentCategoryText = empty($articleCategory[$currentCategoryId]) ? '':$articleCategory[$currentCategoryId];
    		$currentCategory = array('id'=>$currentCategoryId,'text'=>$currentCategoryText);
    	}catch (Exception $e){
    		throw $e;
    	}
    	$wzId = array('id'=>$data['wzId']);
    	$result['data'] = array('master' =>$masterInfo, 'article'=>$articleListInfo, 'kindId'=>$articleCategory, 'site'=>$wzId,
    			'currentKindId'=>$currentCategory, 'versionTimeStamp'=>$versionTimeStamp, 'articleIdList'=>$articleIdList,);
    	return $result;
    }
    
    /*
     * 文章详情页
     * 参数：微店id-wzId，文章id，站长id，类目id
     * 返回：对应信息
     */
    function detail($data) {
        //检查微站id
        if(empty($data['wzId']) || !is_numeric($data['wzId']) || $data['wzId'] < 0){
            throw new Exception('传递的微站id参数错误, wzId: '.empty($data['wzId']) ? "":$data['wzId'], $this->config->item('parameter_err_no', 'err_no'));
        }
        //站长id为空则根据wzId来获取
        if(empty($data['id'])){
            $siteInfo = $this -> api->call_api('base','base_api','public/model_website','fetch_one_by_id',array($data['wzId']));
            $masterIdBySite = intval($siteInfo['webMasterId']);
        }else if(!is_numeric($data['id'])){
            throw new Exception('传递的站长id参数错误, id: '.empty($data['id']) ? "":$data['id'], $this->config->item('parameter_err_no', 'err_no'));
        }
        //检查微站文章id
        if(empty($data['wzArticleId']) || !is_numeric($data['wzArticleId'])){
            throw new Exception('传递的微站id参数错误, wzId: '.$data['wzId'].', wzArticleId: '.empty($data['wzArticleId']) ? "":$data['wzArticleId'], $this->config->item('parameter_err_no', 'err_no'));
        }
        $articleDeatailInfo = array();
        $prevArtileInfo = array();
        $nextArtileInfo = array();
        $result = array();
        //站长信息查询条件
        $masterId = empty($masterIdBySite) ? $data['id']:$masterIdBySite;
        //文章信息查询条件
        $wzId = $data['wzId'];	//微站id
        $currentArticleId = $data['wzArticleId'];          //文章详情id
        $kindId = $data['kindId'];	//文章类目id
        try{
            //获取站长信息
            $masterInfo = $this ->  api->call_api('base', 'base_api', 'public/model_webmaster','fetch_one_by_id', array($masterId));
            $masterAuthen = intval($masterInfo['authentication']);
            $masterCondition = intval($masterInfo['conditions']);
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
            //获取上下篇文章id
            $idList = $this->api->call_api('base', 'base_api', 'articleinfo/Model_articleinfo', 'get_website_article_list', array($wzId, $currentArticleId, $kindId));
            if(empty($idList) || !is_array($idList)){
                throw new Exception('微站id: '.$wzId.', 微站文章id：'.$currentArticleId.'，获取上下篇文章id列表信息为空。', $this->config->item('data_exist_err_no', 'err_no'));
            }
            $totalNum = count($idList);  //获取总个数
            if(in_array($currentArticleId,$idList)){
                $detailIndex = array_keys($idList,$currentArticleId);
                $detailIndex = $detailIndex[0];
                if($detailIndex == 0){
                    $prevId = '';
                    $nextId = $idList[$detailIndex + 1];
                }else if($detailIndex == $totalNum-1){
                    $prevId = $idList[$detailIndex - 1];
                    $nextId = '';
                }else{
                    $prevId = $idList[$detailIndex - 1];
                    $nextId = $idList[$detailIndex + 1];
                }
            }else{
                throw new Exception('微站id: '.$wzId.', 微站文章id：'.$currentArticleId.'，文章id不在上下篇文章id列表中。', $this->config->item('data_exist_err_no', 'err_no'));
            }
            //组合当前和上下篇文章id
            if($prevId == ''){
                $prevCurrentNextId = array($currentArticleId,$nextId);
            }else if($nextId == ''){
                $prevCurrentNextId = array($prevId,$currentArticleId);
            }else{
                $prevCurrentNextId = array($prevId,$currentArticleId,$nextId);
            }
            //获取当前和上下篇文章信息
            $infoList = $this -> api->call_api('base', 'base_api', 'articleinfo/Model_articleinfo', 'get_multi_ids', array($prevCurrentNextId));
            if(empty($infoList) || !isset($infoList) || count($infoList) <= 0){
                throw new Exception('微站id: '.$wzId.', 微站文章id：'.$currentArticleId.'，文章详情页从文章获取文章信息为空(包括上下篇文章信息)。', $this->config->item('data_exist_err_no', 'err_no'));
            }
            //获取值得买文章id和上下篇文章对应的信息
            foreach($infoList as $i=>$k){
                if(!empty($k)){
                    if($k['id'] == $currentArticleId){
                            $zdmArticleId = $k['secretid'];
                            $articleDeatailInfo = $k;
                    }else if($k['id'] == $prevId){
                        $prevArtileInfo = $k;
                    }else{
                        $nextArtileInfo = $k;
                    }
                }
            }
            //当前文章信息不能为空
            if(empty($articleDeatailInfo) || !isset($articleDeatailInfo)){
                throw new Exception('微站id: '.$wzId.', 微站文章id：'.$currentArticleId.'，文章信息为空。', $this->config->item('data_exist_err_no', 'err_no'));
            }
            //获取的文章对应的值得买加密id不能为空
            if(empty($zdmArticleId)){
                throw new Exception('微站id: '.$wzId.', 微站文章id：'.$currentArticleId.'，文章对应的zdm加密id为空。', $this->config->item('parameter_err_no', 'err_no'));
            }
            //根据值得买文章id获取文章内容
            $param = array('zdmArticleId'=>$zdmArticleId);
            $zdmRes = $this -> model('front/Model_article') -> get_article_by_id($param);
            //对比文章标题是否一致
            if(strcmp($articleDeatailInfo['title'],$zdmRes['title_text']) == 0){
                $isTitleChange = false;
            }else{
                $isTitleChange = true;
            }
            //将文章内容和原标题等放入数组
            $articleDeatailInfo['zdmTitle'] = $zdmRes['title_text'];   //文章标题
            $articleDeatailInfo['content'] = htmlspecialchars_decode($zdmRes['content']);       //文章内容
            $articleDeatailInfo['titleChange'] = $isTitleChange;        //文章标题是否相同
            $articleDeatailInfo['prevArticle'] = $prevArtileInfo;       //上篇
            $articleDeatailInfo['nextArticle'] = $nextArtileInfo;       //下篇
            $articleDeatailInfo['author'] = $zdmRes['author'];         //作者
            $articleDeatailInfo['author_id'] = $zdmRes['author_id'];  //作者id
            //处理文章类型
            $categoryInfo = $this->config->item('article_category');
            if(empty($categoryInfo) || !is_array($categoryInfo) || empty($categoryInfo[$articleDeatailInfo['categories']])){
                $articleDeatailInfo['categoryTest'] = '';
            }else{
                $articleDeatailInfo['categoryTest'] = $categoryInfo[$articleDeatailInfo['categories']];
            }
            //转换上架时间为前台所需的格式
            $articleDeatailInfo['hitShelveTime'] = $this->_unixTimeToString($articleDeatailInfo['hitShelveTime']);
            //获取js，css等版本号，版本号由前端同学维护
            $versionTimeStamp = $this->config->item('versionTimeStamp');
            if(empty($versionTimeStamp)){
                $versionTimeStamp = '';
            }
            //对比文章作者id和创建者id是否相同，来确定是否是原作者
            $createUser = intval($masterInfo['createUser']);
            $author = intval($articleDeatailInfo['authorid']);
            $originalAuthor = ($createUser == $author ? 0:1);
            $articleDeatailInfo['originalAuthor'] = $originalAuthor;
        }catch (Exception $e){
            throw $e;
        }
        $result['data'] = array('master' =>$masterInfo, 'article'=>$articleDeatailInfo, 'versionTimeStamp'=>$versionTimeStamp,'site'=>$wzId);
        return $result;
    }

    /*
         * 查询文章列表
         * 参数：微站id-wzId，状态-conditions，类别-categories(如果为空则为全部)
         * 返回值：文章列表 json格式.
         */
    function articleListJson($data) {
        $page = empty($data['page']) ? 1 : $data['page'];
        $pagesize = empty($data['pagesize']) ? $this->config->item('pagesize') : $data['pagesize'];
        //检查微站id是否合法
        if(empty($data['wzId']) || !is_numeric($data['wzId']) || $data['wzId'] < 0){
            throw new Exception('传递的微站id参数错误, wzId: '.empty($data['wzId']) ? '':$data['wzId'], $this->config->item('parameter_err_no', 'err_no'));
        }
        $articleListInfo = array();
        $result = array();
        //文章列表信息查询条件
        $articleSearchConditions = array();
        $articleSearchConditions['wzId'] = $data['wzId'];
        $articleSearchConditions['conditions'] = $data['conditions'];
        if(!empty($data['categories'])){
            $articleSearchConditions['categories'] = $data['categories'];
        }
        try{
            //查询文章列表信息
            $articleListInfo = $this -> api->call_api('base', 'base_api','articleinfo/Model_articleinfo', 'search',array($articleSearchConditions, $page, $pagesize));
            $articleListInfo['results'] =  array_values($articleListInfo['results']);
            if(empty($articleListInfo['results'])){
                $articleListInfo =array('num'=>0,'results'=>array());
            }
            //转换上架时间为前台所需的格式
            $articleIdList = array();
            $articleListInfo = $this->_articleLoop($articleListInfo,$articleIdList);
            $articleIdList = implode(',',$articleIdList);
        }catch (Exception $e){
            throw $e;
        }
        $wzId = array('id'=>$data['wzId']);
        $result['data'] = array('article'=>$articleListInfo,'articleIdList'=>$articleIdList,);
        return $result;
    }

    /**
     * 新微站一期-文章列表
     * 
     * 参数：微站id-wzId，状态-conditions，文章类目id-kindId(如果为空则为全部)
     * 返回值：文章列表 json格式.
     */
    function articleNewListJson($data) {
    	$page = empty($data['page']) ? 1 : $data['page'];
    	$pagesize = empty($data['pagesize']) ? $this->config->item('pagesize') : $data['pagesize'];
    	//检查微站id是否合法
    	if(empty($data['wzId']) || !is_numeric($data['wzId']) || $data['wzId'] < 0){
    		throw new Exception('传递的微站id参数错误, wzId: '.empty($data['wzId']) ? '':$data['wzId'], $this->config->item('parameter_err_no', 'err_no'));
    	}
    	$articleListInfo = array();
    	$result = array();
    	//文章列表信息查询条件
    	$articleSearchConditions = array();
    	$articleSearchConditions['wzId'] = $data['wzId'];
    	$articleSearchConditions['conditions'] = $data['conditions'];
    	if(!empty($data['kindId'])){
    		$articleSearchConditions['kindId'] = $data['kindId'];//类目为空，则查询所有的文章
    	}
    	try{
//     		var_dump($articleSearchConditions, $page, $pagesize);
    		//查询文章列表信息
    		$articleListInfo = $this->api->call_api('base', 'base_api', 'articleinfo/Model_articleinfo', 'search', array($articleSearchConditions, $page, $pagesize));
    		$articleListInfo['results'] =  array_values($articleListInfo['results']);
    		if(empty($articleListInfo['results'])){
    			$articleListInfo =array('num'=>0,'results'=>array());
    		}
    		//转换上架时间为前台所需的格式
    		$articleIdList = array();
    		$articleListInfo = $this->_articleNewLoop($articleListInfo, $articleIdList);
    		$articleIdList = implode(',', $articleIdList);
    	}catch (Exception $e){
    		throw $e;
    	}
    	$wzId = array('id'=>$data['wzId']);
    	$result['data'] = array('article'=>$articleListInfo, 'articleIdList'=>$articleIdList,);
    	return $result;
    }
    
    /*
     * 转换日期，以便符合前端需求
     * $unixTime unix时间戳
     * 返回字符串 如果是今天：'时分'，今天以前："日期"，今年以前：'年份'
     */
    function _unixTimeToString($unixTime){
        $result = '';
        //判断是否是今年
        if(date('Y') == date('Y',$unixTime)){
            //判读是否是今天
            if(date('Y-m-d') == date('Y-m-d',$unixTime)){
                $result = date('H:i',$unixTime);
            }else{
                $result = date('m-d',$unixTime);
            }
        }else{
            $result = date('Y-m-d',$unixTime);
        }
        return $result;
    }
    
    /**
     * 修改前台时间，分类的信息
     */
    function _articleLoop($data, &$articleIdList){
        $articleCategory = $this ->config->item('article_category');
        //转换上架时间为前台所需的格式，并且赋值分类信息
        foreach($data['results'] as $i=>$k){
            $articleIdList[] = $k['articleId'];
            $timeStr = $this->_unixTimeToString($k['hitShelveTime']);
            $data['results'][$i]['hitShelveTime'] = $timeStr;
            $data['results'][$i]['categoryText'] =
                empty($articleCategory[$data['results'][$i]['categories']])? "":$articleCategory[$data['results'][$i]['categories']];
        }
        return $data;
    }

    /**
     * 新微站一期
     * 修改前台时间，分类的信息
     */
    function _articleNewLoop($data, &$articleIdList){
    	$articleCategory = $this ->config->item('article_category');
    	//转换上架时间为前台所需的格式，并且赋值分类信息
    	foreach($data['results'] as $i=>$k){
    		$articleIdList[] = $k['articleId'];
    		$timeStr = $this->_unixTimeToString($k['hitShelveTime']);
    		$data['results'][$i]['hitShelveTime'] = $timeStr;
    		$data['results'][$i]['categoryText'] =
    		empty($articleCategory[$data['results'][$i]['categories']])? "":$articleCategory[$data['results'][$i]['categories']];
    	}
    	return $data;
    }
    
    /*
     * 根据tf id查询商品信息
     */
    function _getProductInfo($tfId,$page,$pageSize){
        $param = array('tfId'=>$tfId);
        $productRes = $this -> model('front/Model_products') -> get_product_by_id($param);
        $productRes = unserialize($productRes);
        if(!empty($productRes)){
            $productRes = array_slice($productRes,$page-1,$pageSize);
        }
        return $productRes;
    }
}
