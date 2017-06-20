<?php 
/**
 * 建站工具-文章管理逻辑
 * 
 * @uses Wd
 * @uses _Logic
 * @package 
 * @version $id$
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author fishxyu <fishxyu@yixun.com> 
 * @license 
 */
class Logic_articleinfo extends Bs_logic {
                                
    /**
     * __construct 
     * 
     * @access protected
     * @return mixed
     */
    const ARTICLE_INTRO_LENGTH_NO_COVER = 100;
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
    
    /**
     * save 
     * 保存
     * @param mixed $post_data 
     * @access public
     * @return mixed
     */
    function save($post_data) {
//         $this->_adjust($post_data);
        return $this->model('articleinfo/Model_articleinfo')->save($post_data);
    }
    
    /**
     * save
     * 保存标题和简介
     * @param mixed $post_data
     * @access public
     * @return mixed
     */
    function savetitle($post_data) {
    	try {
    		$uid = $_COOKIE['uid'];
    		
    		$data = array('id','title','brief');//只能修改标题和简介
    		if (is_array($post_data) && !empty($post_data)) {
	    		foreach ($post_data as $pk=>$pv){
	    			if (!in_array($pk, $data)) {
	    				unset($post_data[$pk]);
	    			}
	    		}
    		}
    		if (empty($post_data['title'])) {
    			throw new Exception('文章标题不能为空', $this->config->item('istitle_err_no', 'err_no'));
    		}
    		$post_data['title'] = strip_tags(trim($post_data['title']));
    		$post_data['brief'] = strip_tags(trim($post_data['brief']));
    		
    		$artid_arr = explode(',', $post_data['id']);//文章id
    		$status	   = $this->getArticleStatus($uid, $artid_arr);
    		if ($status===false) {
    			throw new Exception('您的账号不能修改此文章标题简介', $this->config->item('editarticle_err_no', 'err_no'));
    		}
    		$articleinfo = array();
    		$articleinfo['articleinfo'] = $post_data;
    		$id = $this->model('articleinfo/Model_articleinfo')->save($articleinfo);
    		
    		$this->log->info('masterid='.$uid.' edit article_id='.$id.' title'.$post_data['title'].' brief'.$post_data['brief']);
    		$this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
    				__FUNCTION__, $this->input->ip_address())
    		);
    	}catch (Exception $e) {
    		throw $e;
    	}
    	
    	return ($id==$post_data['id'])?true:false;
    }
    
    /**
     * save
     * 保存标题和简介
     * @param mixed $post_data
     * @access public
     * @return mixed
     */
    function saveorder($post_data) {
    	try {
    		$uid = $_COOKIE['uid'];
    		$data = array('id','orderNum');//规定要更新的字段
    		if (is_array($post_data) && !empty($post_data)) {
	    		foreach ($post_data as $pk=>$pv){
	    			if (!in_array($pk, $data)) {
	    				unset($post_data[$pk]);
	    			}
	    		}
    		}
    		if ($post_data['orderNum'] <= 0 || !is_numeric($post_data['orderNum'])) {
    			$post_data['orderNum'] = 0;
    		}elseif ($post_data['orderNum'] >9999){
    			throw new Exception('文章排序值为0-9999，请重新填写！', $this->config->item('ismax_err_no', 'err_no'));
    		}
    		$artid_arr = explode(',', $post_data['id']);//文章id
    		$status = $this->getArticleStatus($uid, $artid_arr);
    		if ($status===false) {
    			throw new Exception('您的账号不能修改此文章排序！', $this->config->item('editarticleorder_err_no', 'err_no'));
    		}
    		$articles = array();
    		$articles['articleinfo'] = $post_data;
    		$id = $this->model('articleinfo/Model_articleinfo')->save($articles);
    		$this->log->info('masterid='.$uid.' edit article_id='.$id.' orderNum,the new orderNum is '.$post_data['orderNum']);
    		$this->log->info(sprintf('%s:%s ip:%s process ok.', __CLASS__,
    				__FUNCTION__, $this->input->ip_address())
    		);
    	}catch (Exception $e) {
    		throw $e;
    	}
    	
    	return ($id==$post_data['id'])?true:false;
    }
    
    /**
     * 文章删除、批量删除
     * @param mixed $post_data
     * @access public
     * @return mixed
     */
    function saveStatus( $artid ) {
    	try {
    		if ( empty($artid) ) {
    			throw new Exception('删除文章的id不能为空', $this->config->item('isartid_err_no', 'err_no'));
    		}
    		$artid_arr = explode(',', $artid);//增加批量删除
    		
    		$uid = $_COOKIE['uid'];
    		//判断此文章id是当前登陆用户的
    		$status = $this->getArticleStatus($uid, $artid_arr);
    		if ($status===false) {
    			throw new Exception('您的账号不能删除此文章', $this->config->item('deletearticle_err_no', 'err_no'));
    		}
    		
    		$post_data = array();
    		$post_data['conditions'] = 17;//标记已删除
    		$backid = array();
    		if (is_array($artid_arr) && !empty($artid_arr)) {
    			foreach ($artid_arr as $aid){
    				$backid[] = $this->model('articleinfo/Model_articleinfo')->update_by_id($post_data, $aid);
    			}
    		}
    		$this->log->info(sprintf('%s:%s ip:%s process ok. delete successed. article id:%s by userid:%d ', __CLASS__,
    				__FUNCTION__, $this->input->ip_address(), $artid, $uid)
    		);
    	}catch (Exception $e) {
    		throw $e;
    	}
    	
    	return $backid;
    }
    /**
     * 
     * @param unknown $post_data
     */
    protected function _adjust(&$post_data) {
    	
    	//$post_data['createTime'] = time();
        $post_data['updateTime'] = time();
    }
    /**
     * create 
     * 
     * @param mixed $post_data 
     * @access public
     * @return mixed
     */
    function create() {
        $c['articleinfo'] = $this->new_one();
   
        return $c;
    }
    
    /**
     * search 
     * 
     * @param mixed $post_data 
     * @access public
     * @return mixed
     */
    function search($data, $page=0, $pagesize=30) {
        $page = empty($page) ? 1 : $page;
        $pagesize = empty($pagesize) ? $this->config->item('pagesize') : $pagesize;
        try{
        	//已删除的默认不显示，可传参显示
        	if ( !isset($data['conditions']) || !is_numeric($data['conditions']) ) {
        		$data['conditions'] = 1;
        	}

        	$uid = $_COOKIE['uid'];
        	//先查当前用户的微站信息，微站开通状态
        	$masterinfo = $this->searchMaster($uid);//Bs_logic.php
        	if ( !empty($masterinfo) ) {
	        	$is_auth = array_key_exists($masterinfo['webmasterinfo']['authentication'], $this->config->item('master_valid'));
	        	if ($is_auth) {
	        		$data['wzId'] = intval($masterinfo['websiteinfo']['id']);//微站id
	        	}
        	}else {
        		//此用户没有开通微站
        		$err_code = $this->config->item('ismaster_err_no', 'err_no');
        		throw new Exception($this->config->item($err_code, 'err_msg'), $this->config->item('ismaster_err_no', 'err_no'));
        	}
            $articleinfo = $this->model('articleinfo/Model_articleinfo')->search($data, $page, $pagesize);
            if (is_array($articleinfo) && !empty($articleinfo)) {
            	foreach ($articleinfo['results'] as $ak=>$av){
            		$articleinfo['results'][$ak]['masterid']       = $masterinfo['webmasterinfo']['id'];//站长id
            		$articleinfo['results'][$ak]['createTime']     = date("Y-m-d H:i:s",$av['createTime']);
            		$articleinfo['results'][$ak]['updateTime']     = date("Y-m-d H:i:s",$av['updateTime']);
            		$articleinfo['results'][$ak]['hitShelveTime']  = date("Y-m-d H:i:s",$av['hitShelveTime']);
            	}
            }
        }catch(Exception $e){
            throw $e;
        }
   
        return $articleinfo;
    }
    
    /**
     * detail 
     * 
     * @param mixed $id 
     * @access public
     * @return mixed
     */
    function detail($id) {
        $c = array();
        $c['articleinfo'] = $this->model('articleinfo/Model_articleinfo')->fetch_one_by_id($id);
        if (empty($c['articleinfo']) ){
            throw new Exception(sprintf('%s: The articleinfo id %d does not exist or has been deleted.',
                        __FUNCTION__, $id), $this->config->item('data_exist_err_no', 'err_no'));  
        } 
                
        return $c; 
    }
    
    /**
     * 根据微站id 和 值得买文章id 查询文章表是否有此文章
     * @param array $param 
     * @access public
     * @return boolean
     */
    function searchArticle($param = array()) {
    	if (is_array($param) && !empty($param)) {
	    	$result = $this->model('articleinfo/Model_articleinfo')->fetch($param);
	    	if (is_array($result) && !empty($result)) {
	    		return true;
	    	}else 
	    		return false;
    	}else 
    		return false;
    }
    
    /**
     * 根据用户微站id和文章id，查询此文章是否存在
     * 
     */
    function getArticleStatus($uid = '', $artid='') {
    	$uid = intval($uid);
    	//先查当前用户的微站信息，微站开通状态
    	$masterinfo = $this->searchMaster($uid);//Bs_logic.php
    	if ( !empty($masterinfo) ) {
    		$is_auth = array_key_exists($masterinfo['webmasterinfo']['authentication'], $this->config->item('master_valid'));
    		if ( $is_auth ) {
	    		$param = array();
	    		if (is_array($artid) && !empty($artid)) {
	    			foreach ($artid as $aid){
			    		$param['id'] 	= $aid;//文章id
			    		$param['wzId'] 	= $masterinfo['websiteinfo']['id'];//当前用户微站id
			    		$result = $this->model('articleinfo/Model_articleinfo')->fetch($param);
	    			}
		    		if (is_array($result) && !empty($result)) {
			    		return true;
			    	}else 
			    		return false;
	    		}
    		}
    	}else {
    		//此用户没有开通微站
    		return false;
    	}
    }
    
    /**
     * edit 
     * 
     * @param mixed $id 
     * @access public
     * @return mixed
     */
    function edit($id) {
        $c = array();
        $c['articleinfo'] = $this->model('articleinfo/Model_articleinfo')->fetch_one_by_id($id);
        if (empty($c['articleinfo']) ){
            throw new Exception(sprintf('%s: The articleinfo id %d does not exist or has been deleted.',
                        __FUNCTION__, $id), $this->config->item('data_exist_err_no', 'err_no'));  
        } 
                
        return $c; 
    }
    
    /**
     * 根据微站id 和 值得买文章id 查询文章表是否有此文章
     * @param array $param
     * @access public
     * @return boolean
     */
    function zdmlist($data,$articelid) {
        if(!empty($articelid)) {
            $url = $this->config->item('zdm_article_url').$articelid.'&callback=';
        }else {
            $url = $this->config->item('zdm_articlelist_url').'?callback=';
            foreach($data as $key=>$val) {
                $url .='&'.$key.'='.$val;
            }
        }
        //echo $url;exit;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        curl_close($curl);
        if($res === FALSE) {
            $this->log->warn('articleinfo/articleinfo.php line:'.__LINE__.', articleId: '.$encryptionId
                            .' art_id: '.$zdmResults['data']['art_id'].', zdmErrCode: '.$zdmResults['code'].', 获取文章信息出现异常');
        }
        $zdmResults = json_decode($res,true);
//        print_r($zdmResults);exit;
        if(!empty($articelid)) {
            $zdmResults['data'] = isset($zdmResults['data'])?$zdmResults['data']:'';
            $temp = $zdmResults['data'];
            unset ($zdmResults['data']);
            $zdmResults['data']['total'] = 1;
            $zdmResults['data']['pernum'] = 10;
            $zdmResults['data']['current_page'] = 1;
            $zdmResults['data']['total_page']   = 1;
            
            $zdmResults['data']['lists'][0] = $temp;
            //var_dump($temp['content']);
            if(isset($temp['content']))
            $zdmResults['data']['lists'][0]['descript'] = $this->_getArticleDes($temp['content']);
           
        }

        return  $zdmResults['data'];
    }
    
    /**
     * curl 获取值得买文章
     */
     function curlArticle($encryptionId, $uid, $websiteid,$kindId) {
        $results = array(
                        'id' => 0,
                        'success'=> FALSE,
                        );
        $url = $this->config->item('zdm_article_url').$encryptionId.'&callback=';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        curl_close($curl);
        $zdmResults = json_decode($res,true);
        if($zdmResults['code'] != 0){
            $this->log->warn(__FILE__.'line:'.__LINE__.', articleId: '.$encryptionId
                            .' art_id: '.$zdmResults['data']['art_id'].', zdmErrCode: '.$zdmResults['code'].', 获取文章信息出现异常');
            throw new Exception(sprintf('%s curl get article code!=0', __FUNCTION__),
                            $this->config->item('get_article_error', 'err_no'));
        }else {
            if(empty($zdmResults['data'])){
                $this->log->info(__FILE__.' line:'.__LINE__.', articleId: '.$encryptionId
                                .' art_id: '.$zdmResults['data']['art_id'].', zdmErrCode: '.$zdmResults['code'].', 获取文章信息为空');
                throw new Exception(sprintf('%s curl get article empty', __FUNCTION__),
                                $this->config->item('get_article_empty', 'err_no'));
            }else {
               $id =  $this->beforeSave($zdmResults['data'], $uid, $websiteid,$kindId);
               $results['id'] = $id;//插入的文章id
               $results['success'] = true;
            }
        }
        
        return $results;
    }
    
    /**
     * 根据值得买的文章组织数据保存入库
     */
      function beforeSave (&$zdmdata, $uid, $websiteid,$kindId) {
        $post_data = array();
        $post_data['articleinfo']['articleId'] 		= $zdmdata['art_id'];//值得买文章id
        $post_data['articleinfo']['kindId'] 		= $kindId;//分类id
        $post_data['articleinfo']['authorid'] 		= $zdmdata['author_id'];//值得买文章作者id
        $post_data['articleinfo']['secretid'] 		= $zdmdata['id'];//加密字符串
        $post_data['articleinfo']['wzId'] 			= $websiteid;//微站id 用户站长信息里有
        $post_data['articleinfo']['title'] 			= htmlspecialchars_decode($zdmdata['title_text']);//微站去样式的标题，仅文字
        $post_data['articleinfo']['zdmTitle'] 		= htmlspecialchars_decode($zdmdata['title_text']);//微站原标题，仅文字,从值得买导入
        $content = strip_tags(htmlspecialchars_decode($zdmdata['content']));
        $post_data['articleinfo']['brief'] 			= mb_substr($content,0,50);//简介是截取的
        $post_data['articleinfo']['microUrl'] 		=  "http://img10.360buyimg.com/yixun_zdm/s200x200_".$zdmdata['cover'];//文章封面
        //$post_data['articleinfo']['microUrl'] 		= "http://img10.360buyimg.com/yixun_zdm/s200x200_".$zdmdata['wz_img'];//文章封面
        $post_data['articleinfo']['readNum'] 		= $zdmdata['read_num'];//文章阅读数
        $post_data['articleinfo']['praiseNum'] 		= $zdmdata['good_num'];//点赞数
        $post_data['articleinfo']['conditions'] 	= 1;//文章状态默认1
        $post_data['articleinfo']['hitShelveTime']  = time();//上架时间
        $post_data['articleinfo']['createTime'] 	= time();//创建时间
        $post_data['articleinfo']['updateTime'] 	= time();//更新时间
        $post_data['articleinfo']['updateUser'] 	= $uid;//更新文章的用户
        $post_data['articleinfo']['categories'] 	= $zdmdata['column'];//文章分类 对应值得买的栏目
        $id	= $this->save($post_data);
        
        return $id;
      
    }
    /**
     * 获取文章列表的简介
     * @param      $content
     * @param int  $num
     * @param bool $is_del
     * @return string
     */
    private function _getArticleDes($content, $num = self::ARTICLE_INTRO_LENGTH_NO_COVER, $is_del = false) {
        $content = str_replace('&amp;' , '&' , $content);
        if ($is_del) {
            return self::ARTICLE_DEL_CONTENT;
        } else {
            $search = array(
                '&nbsp;',
                '　',
                ' ',
                chr(194).chr(160),
            );
            $to = array(
                '',
                '',
                '',
                '',
            );
            return self::limit_chars(str_replace($search, $to, strip_tags(html_entity_decode(stripslashes($content)))), $num);
        }
    }

	/**
	 * Limits a phrase to a given number of characters.
	 *
	 *     $text = Text::limit_chars($text);
	 *
	 * @param   string  $str            phrase to limit characters of
	 * @param   integer $limit          number of characters to limit to
	 * @param   string  $end_char       end character or entity
	 * @param   boolean $preserve_words enable or disable the preservation of words while limiting
	 * @return  string
	 * @uses    UTF8::strlen
	 */
	public static function limit_chars($str, $limit = 100, $end_char = NULL, $preserve_words = FALSE)
	{
		$end_char = ($end_char === NULL) ? '…' : $end_char;

		$limit = (int) $limit;

		if (trim($str) === '' OR mb_strlen($str) <= $limit)
			return $str;

		if ($limit <= 0)
			return $end_char;

		if ($preserve_words === FALSE)
			return rtrim(mb_substr($str, 0, $limit)).$end_char;

		// Don't preserve words. The limit is considered the top limit.
		// No strings with a length longer than $limit should be returned.
		if ( ! preg_match('/^.{0,'.$limit.'}\s/us', $str, $matches))
			return $end_char;

		return rtrim($matches[0]).((strlen($matches[0]) === strlen($str)) ? '' : $end_char);
	}
}
?>