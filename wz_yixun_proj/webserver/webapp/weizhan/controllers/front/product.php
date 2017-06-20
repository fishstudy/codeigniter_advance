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
class Product extends Wz_controller {
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
    protected  $_ilist_items            = array('tfId','wzId','scId','type');
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
     * 微站-商品列表
     *
     * @access public
     * @return mixed
     */
    function lists($wzId=0, $masterId=0, $page = 1, $pagesize=0, $tfId = '') {
    	try {
    		$pagesize   = intval($pagesize)>0 ? intval($pagesize) : $this->config->item('pagesize');
            $page       = max(intval($page), 1);
            $this->_search_conditions['wzId'] 	= intval($wzId);
            $this->_search_conditions['id'] 	= intval($masterId);
            $this->_search_conditions['tfId'] 	= intval($tfId);
            $this->_search_conditions['page'] 	= $page;
            $this->_search_conditions['pagesize'] = $pagesize;
            //查询信息
    		$productsInfo= $this->logic('front/Logic_product')->lists($this->_search_conditions);
            //返回信息
            $this->_response['master_results'] 		= $productsInfo['data']['master'];
            $this->_response['product_num'] 		= $productsInfo['data']['productNum'];
            $this->_response['product_results'] 	= $productsInfo['data']['product'];
            $this->_response['site_results'] 		= $productsInfo['data']['site'];
            $this->_response['timeStamp'] 			= $productsInfo['data']['versionTimeStamp'];       //js，css的版本号
    		$this->_response['search_conditions'] 	= $this->_search_conditions;
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
     * 微站-商品列表 json 格式
     */
    function productListJson($wzId=0, $page=1, $pagesize=0, $tfId = ''){
        try {
            $pagesize   = intval($this->input->get('pagesize'))>0 ? intval($this->input->get('pagesize')) : $this->config->item('pagesize');
            $page       = max(intval($this->input->get('page')), 1);
            foreach ($this->_ilist_items as $search_item) {
                $search_item_value = intval($this->input->get($search_item));
                if ($search_item_value>0)
                    $this->_search_conditions[$search_item]  =  $search_item_value;
            }
            $this->_search_conditions['page'] = $page;
            $this->_search_conditions['pagesize'] = $pagesize;
            
            //查询信息
            $productsInfo= $this->logic('front/Logic_product')->productListJson($this->_search_conditions);
            //返回信息
            $this->_response['product_num'] = $productsInfo['data']['productNum'];
            $this->_response['product_results'] =  $productsInfo['data']['product'];
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
     * 新微站-商品列表
     * 
     * $wzId 微站id
     * $type 商品类型id: 1达人推荐2抢购3热销爆品4精选商品
     * $scId 精选爆品类目id，可以为空
     * $page 页数
     * $pagesize 分页数
     */
    public function newlists($wzId=0, $type=0, $scId=0, $page=1, $pagesize=10){
    	try {
    		$pagesize   = intval($pagesize)>0 ? intval($pagesize) : $this->config->item('pagesize');
    		$page       = max(intval($page), 1);
    		$this->_search_conditions['wzId'] 	= intval($wzId);		//微站id
    		$this->_search_conditions['type'] 	= intval($type);
    		$this->_search_conditions['scId'] 	= intval($scId);
    		$this->_search_conditions['page'] 	= $page;
    		$this->_search_conditions['pagesize'] = $pagesize;
    		//查询信息
    		$productsInfo= $this->logic('front/Logic_product')->newlists($this->_search_conditions);
    		//返回信息
    		$this->_response['master_results'] 	= $productsInfo['data']['master'];//站长信息
    		$this->_response['site_results'] 	= $productsInfo['data']['site'];//微站信息
    		$this->_response['product_num'] 	= $productsInfo['data']['productNum'];
    		$this->_response['results'] 		= array_values($productsInfo['data']['product']);
    		$this->_response['timeStamp'] 		= $productsInfo['data']['versionTimeStamp'];       //js，css的版本号
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
     * 新微站一期 --- 商品列表 json 格式
     * $wzId 微站id
     * $scId 精选爆品类目id
     * $type 推荐商品类型id
     * $page 页数
     * $pagesize 分页数
     * 
     */
    function newlistsJson($wzId=0, $scId=0, $type=0, $page=1, $pagesize=0){
    	try {
    		$this->_format = 'json';	//输出格式
    		$pagesize   = intval($this->input->get('pagesize'))>0 ? intval($this->input->get('pagesize')) : $this->config->item('pagesize');
    		$page       = max(intval($this->input->get('page')), 1);
    		foreach ($this->_ilist_items as $search_item) {
    			$search_item_value = intval($this->input->get($search_item));
    			if ($search_item_value>0)
    				$this->_search_conditions[$search_item]  =  $search_item_value;
    		}
    		$this->_search_conditions['page'] = $page;
    		$this->_search_conditions['pagesize'] = $pagesize;
    		//查询信息
    		$productsInfo= $this->logic('front/Logic_product')->newListsJson($this->_search_conditions);
    		//返回信息
    		$this->_response['product_num'] = $productsInfo['data']['productNum'];
    		$this->_response['results'] =  $productsInfo['data']['product'];
    		$this->_response['search_conditions']   = $this->_search_conditions;
    		$this->log->info(sprintf('%s:%s ip:%s process ok. search productlist wzid:$d', __CLASS__,
    				__FUNCTION__, $this->input->ip_address(), $wzId)
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