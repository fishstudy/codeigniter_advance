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
class Masterinfo extends Wz_controller {
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
    protected  $_ilist_items            = array('wzId','id','conditions','categories','webMasterId','authentication');
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
     * 获取达人资料页所需信息
     * @access public
     * @return mixed
     */
    function masterinfor($id=0) {
        try {
            $this->_search_conditions['id'] = intval($id);
            //调用logic查询
            $articleinfo= $this->logic('front/Logic_master_info')
                ->masterinfo($this->_search_conditions);
            $this->_response['master_results']      =  $articleinfo['data']['master'];
            $this->_response['timeStamp']           =  $articleinfo['data']['versionTimeStamp'];
            $this->_response['site_results']      =  $articleinfo['data']['site'];
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
     * 新微站-home页面
     * 
     * 
     */
    public function home($id=0){
    	try {
    		$this->_search_conditions['id'] = intval($id);
    		//
    		$articleinfo= $this->logic('front/Logic_master_info')
    		->wzinfo($this->_search_conditions);
                   unset($this->_search_conditions['id']);
                  //print_r($articleinfo);
                  //商品列表
                  $this->_search_conditions['wzId'] 	= intval($id);//微站id
    		$this->_search_conditions['type'] 	= 1;//达人推荐类型为1
    		//$this->_search_conditions['scId'] 	= intval($scId);
    		$this->_search_conditions['page'] 	= 1;
    		$this->_search_conditions['pagesize'] = 100;
    		//查询达人推荐信息
    		$recommand = $this->logic('front/Logic_product')->newlists($this->_search_conditions);
                  //本期抢购
                  $this->_search_conditions['type']    = 2;//达人抢购类型为2
                   $qiang = $this->logic('front/Logic_product')->newlists($this->_search_conditions); 
                  //热销爆品
                  $this->_search_conditions['type']    = 3;//热销爆品类型为3
                  
                   $hot = $this->logic('front/Logic_product')->newlists($this->_search_conditions); 
                   //热销爆品类目
                   $shopcatalog = $this->logic('front/Logic_master_info')->shopcatalog($this->_search_conditions['wzId']); 
    		$this->_response['master_results']   =  $articleinfo['data']['master'];
    		$this->_response['timeStamp']         =  $articleinfo['data']['versionTimeStamp'];
    		$this->_response['site_results']     =  $articleinfo['data']['site'];
    		$this->_response['recommand']         = array_values($recommand['data']['product']);
    		$this->_response['qiang']             = array_values($qiang['data']['product']);
    		$this->_response['hot']               = array_values($hot['data']['product']);

    		$this->_response['shopcatalog']         = $shopcatalog['results'];
    		$this->_response['search_conditions']  = $this->_search_conditions;
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
}