<?php
/**
 * Articleinfo
 * 
 * @uses Wd
 * @uses _Controller
 * @package 
 * @version $1.0$
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author fishxyu <fishxyu@yixun.com> 
 * @license 
 */
class Article extends Wz_controller {
    /**
     * _format  返回格式
     *
     * @var string
     * @access protected
     */
    protected  $_format         = 'json';
    /**
     * _ilist_items  列表项 搜索条件
     *
     * @var array
     * @access protected
     */
    protected  $_ilist_items            = array('wzId','id','kindId','conditions','categories','wzArticleId','authentication');
    /**
     * _ilist_items2 检索项  搜索条件
     *
     * @var string
     * @access protected
     */
    protected  $_ilist_items2           = array();
            /**
     * _search_conditions  搜索条件
     *
     * @var array
     * @access protected
     */
    protected  $_search_conditions      = array(
            );

    /**
     * __construct
     *
     * @access public
     * @return mixed
     */
    public function __construct() {
        parent :: __construct();
        $this->_format = $this->input->get('format');
        $this->_tn = $this->input->get('tn') ? sprintf('_%s',$this->input->get('tn')) : '';
    }
    
    /**
     * 微站首页
     *
     * @access public
     * @return mixed
     */
    function index($id=0,$page=1,$pagesize=0,$articleCondition=0) {
        try {
            $pagesize   = intval($pagesize)>0 ? intval($pagesize) : $this->config->item('index_page_size');
            $page       = max(intval($page), 1);
            $this->_search_conditions['id'] = intval($id);
            $this->_search_conditions['page'] = $page;
            $this->_search_conditions['pagesize'] = $pagesize;
            $redis = $this->config->item('redis');
           
            //查询conditions为1的文章
            $this->_search_conditions['conditions'] = intval($articleCondition)>0 ? intval($articleCondition) : $this->config->item('articleCondition');
            //调用logic查询
            $results= $this->logic('front/Logic_article')->index($this->_search_conditions);
            //返回信息提供个模板
            $this->_response['master_results']   =  $results['data']['master'];
            $this->_response['site_num']         =  $results['data']['site']['num'];
            $this->_response['site_results']     =  $results['data']['site']['results'];
            $this->_response['article_num']      =  $results['data']['article']['num'];
            $this->_response['article_results'] =  $results['data']['article']['results'];
            $this->_response['product_results'] =  $results['data']['product'];
            $this->_response['timeStamp']        =  $results['data']['versionTimeStamp'];    //js，css等的版本号
            $this->_response['articleIdList']    = $results['data']['articleIdList'];
            $this->_response['search_conditions']   = $this->_search_conditions;
            $this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
                    __FUNCTION__, $this->input->ip_address())
            );
        } catch (Exception $e) {
            $this->_response['success'] = FALSE;
            $this->_response['err_no']  = $e->getCode();
            $this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
            $this->log->warn(sprintf('%s:%s process error err_msg:%s. ip:%s', $e->getFile(),
                        $e->getLine(), $e->getMessage(), $this->input->ip_address())
                    );
            echo $this -> load -> view('common/errors',$this->_response,true);
            exit;
        }
    }
    
    /**
     * 微站-文章列表
     *
     * @access public
     * @return mixed
     */
    function lists($wzId=0, $id=0, $category = '', $page=1, $pagesize=0, $articleCondition=0) {
    	try {
    		$pagesize   = intval($pagesize)>0 ? intval($pagesize) : $this->config->item('pagesize');
            $page       = max(intval($page), 1);
            $this->_search_conditions['wzId'] = intval($wzId);
            $this->_search_conditions['id'] = intval($id);
            $this->_search_conditions['page'] = $page;
            $this->_search_conditions['pagesize'] = $pagesize;
            //condition存在则采用，不存在则查询默认conditon的文章
            $this->_search_conditions['conditions'] = intval($articleCondition)>0 ? intval($articleCondition) : $this->config->item('articleCondition');
            $this->_search_conditions['categories'] = intval($category);
            //查询信息
    		$articleinfo= $this->logic('front/Logic_article')
    		->lists($this->_search_conditions);
            //返回信息
            $this->_response['master_results'] =  $articleinfo['data']['master'];
            $this->_response['article_num'] =  $articleinfo['data']['article']['num'];
            $this->_response['article_results'] =  $articleinfo['data']['article']['results'];
            $this->_response['category_results'] =  $articleinfo['data']['category'];
            $this->_response['site_results'] = $articleinfo['data']['site'];
            $this->_response['timeStamp'] =  $articleinfo['data']['versionTimeStamp'];       //js，css的版本号
            $this->_response['currentCategory'] =  $articleinfo['data']['currentCategory']; //当前类别
            $this->_response['articleIdList']    = $articleinfo['data']['articleIdList'];
    		$this->_response['search_conditions']   = $this->_search_conditions;
    		$this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
    				__FUNCTION__, $this->input->ip_address())
    		);
    	} catch (Exception $e) {
    		$this->_response['success'] = FALSE;
    		$this->_response['err_no']  = $e->getCode();
    		$this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
    		$this->log->warn(sprintf('%s:%s process error err_msg:%s. ip:%s', $e->getFile(),
    				$e->getLine(), $e->getMessage(), $this->input->ip_address())
    		);
            echo $this -> load -> view('common/errors',$this->_response,true);
            exit;
    	}
    }

    /**
     * 新微店一期-文章详情页
     * edit by allenwsun@yixun.com at 2015-09-02
     * 输入：文章id; 微站id; 站长id; 文章分类id
     * 返回：相关信息
     */
    function detail($wzArticleId=0, $wzId=0, $masterId=0, $kindId=0) {
        try {

            $this->_search_conditions['wzArticleId'] 	= intval($wzArticleId);
            $this->_search_conditions['wzId'] 			= intval($wzId);
            $this->_search_conditions['id'] 			= intval($masterId);
            $this->_search_conditions['kindId'] 		= intval($kindId);
            //condition存在则采用，不存在则查询默认conditon的文章
//            $this->_search_conditions['conditions'] = isset($this->_search_conditions['conditions'])?
//                $this->_search_conditions['conditions']:$this->config->item('articleCondition');
            //查询信息
            $articleinfo= $this->logic('front/Logic_article')->detail($this->_search_conditions);
            $this->_response['master_results']      =  $articleinfo['data']['master'];
            $this->_response['article_results']     =  $articleinfo['data']['article'];
            $this->_response['site_id'] = $articleinfo['data']['site'];
            $this->_response['timeStamp'] =  $articleinfo['data']['versionTimeStamp'];   //js，css版本
            $this->_response['search_conditions']   =  $this->_search_conditions;
            $this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
                    __FUNCTION__, $this->input->ip_address())
            );
        } catch (Exception $e) {
            $this->_response['success'] = FALSE;
            $this->_response['err_no']  = $e->getCode();
            $this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
            $this->log->warn(sprintf('%s:%s process error err_msg:%s. ip:%s', $e->getFile(),
                    $e->getLine(), $e->getMessage(), $this->input->ip_address())
            );
            echo $this -> load -> view('common/errors',$this->_response,true);
            exit;
        }
    }

    /*
     * 微站-文章列表 json 格式
     */
    function articleListJson(){
        try {
            $pagesize   = intval($this->input->get('pagesize'))>0 ? intval($this->input->get('pagesize')) : $this->config->item('pagesize');
            $page       = max(intval($this->input->get('page')), 1);
            foreach ($this->_ilist_items as $search_item) {
                $search_item_value = intval($this->input->get($search_item));
                if ($search_item_value>0)
                    $this->_search_conditions[$search_item]  =  $search_item_value;
            }
            foreach ($this->_ilist_items2 as $search_item) {
                $search_item_value = $this->input->get($search_item);
                if (!empty($search_item_value))
                    $this->_search_conditions[$search_item]  =  $search_item_value;
            }
            $this->_search_conditions['page'] = $page;
            $this->_search_conditions['pagesize'] = $pagesize;
            //condition存在则采用，不存在则查询默认conditon的文章
            $this->_search_conditions['conditions'] = empty($this->_search_conditions['conditions']) ?
                $this->config->item('articleCondition') : $this->_search_conditions['conditions'];
            //查询信息
            $articleinfo= $this->logic('front/Logic_article')->articleListJson($this->_search_conditions);
            //返回信息
            $this->_response['article_num'] =  $articleinfo['data']['article']['num'];
            $this->_response['article_results'] =  $articleinfo['data']['article']['results'];
            $this->_response['articleIdList']    = $articleinfo['data']['articleIdList'];
            $this->_response['search_conditions']   = $this->_search_conditions;
            $this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
                    __FUNCTION__, $this->input->ip_address())
            );
        } catch (Exception $e) {
            $this->_response['success'] = FALSE;
            $this->_response['err_no']  = $e->getCode();
            $this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
            $this->log->warn(sprintf('%s:%s process error err_msg:%s. ip:%s', $e->getFile(),
                    $e->getLine(), $e->getMessage(), $this->input->ip_address())
            );
        }
    }
    
    /**
     * 新微站一期-文章列表
     * 
     * $wzId 微站id
     * $id 站长id
     * $kindId 文章类目id,为空则显示所有类目下的文章
     * $page 分页
     * $pagesize 分页数
     * $articleCondition 文章状态
     */
	function newlists($wzId=0, $id=0, $kindId='', $page=1, $pagesize=0, $articleCondition=0){
		try {
			$pagesize   = intval($pagesize)>0 ? intval($pagesize) : $this->config->item('pagesize');
            $page       = max(intval($page), 1);
            $this->_search_conditions['wzId'] 		= intval($wzId);
            $this->_search_conditions['id'] 		= intval($id);
            $this->_search_conditions['page'] 		= $page;
            $this->_search_conditions['pagesize'] 	= $pagesize;
            //condition存在则采用，不存在则查询默认conditon的文章
            $this->_search_conditions['conditions'] = intval($articleCondition)>0 ? intval($articleCondition) : $this->config->item('articleCondition');
            $kindId = intval($kindId);
            if ($kindId) {
	            $this->_search_conditions['kindId'] = intval($kindId);
            }
            //查询信息
    		$articleinfo= $this->logic('front/Logic_article')->newlists($this->_search_conditions);
            //返回信息
            $this->_response['master_results'] 		= $articleinfo['data']['master'];
            $this->_response['article_num'] 		= $articleinfo['data']['article']['num'];
            $this->_response['article_results'] 	= $articleinfo['data']['article']['results'];
            $this->_response['kindId_results'] 		= $articleinfo['data']['kindId'];//文章类目
            $this->_response['site_results'] 		= $articleinfo['data']['site'];
            $this->_response['timeStamp'] 			= $articleinfo['data']['versionTimeStamp'];		//js，css的版本号
            $this->_response['currentKindId'] 		= $articleinfo['data']['currentKindId']; 		//当前类别
            $this->_response['articleIdList']    	= $articleinfo['data']['articleIdList'];
    		$this->_response['search_conditions']   = $this->_search_conditions;
    		$this->log->info(sprintf('%s:%s ip:%s process ok. article num:%d ', __CLASS__,
    				__FUNCTION__, $this->input->ip_address(), $articleinfo['data']['article']['num'])
    		);
		} catch (Exception $e) {
			$this->_response['success'] = FALSE;
			$this->_response['err_no']  = $e->getCode();
			$this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
			$this->log->warn(sprintf('%s:%s process error err_msg:%s. ip:%s', $e->getFile(),
					$e->getLine(), $e->getMessage(), $this->input->ip_address())
			);
			echo $this -> load -> view('common/errors',$this->_response,true);
			exit;
		}
	}
	
	/**
	 * 新微站一期-文章列表 json 格式
	 * $pagesize 分页数
	 * $page 分页
	 * $wzId 微站id
	 * $id 	  站长id
	 * $kindId 文章类目id
	 */
	function newlistsJson(){
		try {
			$pagesize   = intval($this->input->get('pagesize'))>0 ? intval($this->input->get('pagesize')) : $this->config->item('pagesize');
			$page       = max(intval($this->input->get('page')), 1);
			foreach ($this->_ilist_items as $search_item) {
				$search_item_value = intval($this->input->get($search_item));
				if ($search_item_value>0)
					$this->_search_conditions[$search_item]  =  $search_item_value;
			}
			foreach ($this->_ilist_items2 as $search_item) {
				$search_item_value = $this->input->get($search_item);
				if (!empty($search_item_value))
					$this->_search_conditions[$search_item]  =  $search_item_value;
			}
			$this->_search_conditions['page'] = $page;
			$this->_search_conditions['pagesize'] = $pagesize;
			//condition存在则采用，不存在则查询默认conditon的文章
			$this->_search_conditions['conditions'] = empty($this->_search_conditions['conditions']) ? 
				$this->config->item('articleCondition') : $this->_search_conditions['conditions'];
			//查询信息
			$articleinfo= $this->logic('front/Logic_article')->articleNewListJson($this->_search_conditions);
			//返回信息
			$this->_response['article_num'] =  $articleinfo['data']['article']['num'];
			$this->_response['article_results'] =  $articleinfo['data']['article']['results'];
			$this->_response['articleIdList']    = $articleinfo['data']['articleIdList'];
			$this->_response['search_conditions']   = $this->_search_conditions;
			$this->log->info(sprintf('%s:%s ip:%s process ok. articlelists json num:%d.', __CLASS__,
					__FUNCTION__, $this->input->ip_address(),$articleinfo['data']['article']['num'])
			);
		} catch (Exception $e) {
			$this->_response['success'] = FALSE;
			$this->_response['err_no']  = $e->getCode();
			$this->_response['err_msg'] = $this->config->item($e->getCode(), 'err_msg');
			$this->log->warn(sprintf('%s:%s process error err_msg:%s. ip:%s', $e->getFile(),
					$e->getLine(), $e->getMessage(), $this->input->ip_address())
			);
		}
	}
}