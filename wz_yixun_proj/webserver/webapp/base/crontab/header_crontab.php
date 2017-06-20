<?php
/**
 * 定时同步header和footer
 * 易迅的头和尾文件
 * http://www.yixun.com/sinclude/common/v1/utf8/header.html
 * http://www.yixun.com/sinclude/common/v1/utf8/foot.html
 */
set_time_limit ( 0 );
error_reporting ( E_ALL );

class HeaderCrontab extends CI_Controller 
{
	public $_log;
	protected $_ini;
	protected $_table;
	
	/**
	 * model
	 *
	 * @param mixed $model        	
	 * @access public
	 * @return mixed
	 */
	public function model($model) {
		$this->load->model ( $model );
		
		if (($last_slash = strrpos ( $model, '/' )) !== FALSE) {
			$model = substr ( $model, $last_slash + 1 );
		}
		
		return $this->$model;
	}
	
	/**
	 * __construct
	 *
	 * @param string $config        	
	 * @param string $output_dir        	
	 * @access protected
	 * @return mixed
	 */
	function __construct($config = "", $output_dir = "", $db = "") {
		parent::__construct ();
		$this->load->database ( '', FALSE, TRUE );
		$this->db->conn_id->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$this->db->conn_id->query ( 'set names utf8' );
	}
	
	/**
	 * execute
	 *
	 * @access public
	 * @return mixed
	 */
	function execute($updated_at = FALSE) {
		try {
			//抓取header
			$this->cronheader();
			//抓取footer
			$this->cronfooter();
		} catch ( Exception $e ) {
			$this->_log->warn ( sprintf ( '%s:%d :%s', $e->getFile (), $e->getLine (), $e->getMessage () ) );
		}
	}
	
	/**
	 * 抓取yixun.com主站的header
	 */
	function cronheader() {
		$cache_log = $this->config->item ( 'cache_path' );
		var_dump($cache_log);exit;
		if (! is_dir ( $cache_log )) {
		var_dump(! is_dir ( $cache_log ));exit;
			$res = mkdir ( $cache_log, 0777, true );
		} else {
			// empty
		}
		$sql = "SELECT count(*) as cnt FROM weidian.articleinfo WHERE status='1' ";
		echo $sql;
		die ();
		$res = $this->get_one ( $sql );
		$count = $res ['cnt'];
		$size = '1000';
		$this->_log->debug ( sprintf ( "need chance_article total num is:%d", $count ) );
		$c = $count / $size;
		$n = 1;
		$periodical_ids = array ();
		for($m = 0; $m <= $c; $m ++) {
			$page = $m * $size;
			$t = array ();
			$upsql = '';
			$sql_e = sprintf ( "select id,published_at,periodical_id from cms.articles where status='50' limit %d,%d", $page, $size );
			$result = $this->get_all ( $sql_e );
			foreach ( $result ['results'] as $a ) {
				if ($a ['id'] == '0') {
					continue;
				}
				if (time () > strtotime ( $a ['published_at'] )) {
					$t [$a ['id']] = $a ['id'];
					$periodical_ids [$a ['periodical_id']] = $a ['periodical_id'];
				}
			}
			$this->update_tables ( $t, $periodical_ids );
		}
	}
	
	/**
	 * 抓取yixun.com主站的header
	 */
	function cronfooter() {
		
		echo "ccc";exit;
		
		$cache_log = $this->config->item ( 'cache_path' );
		if ( !is_dir( $cache_log ) ) {
			$res = mkdir ( $cache_log, 0777, true );
		}
		
		
		$sql = "SELECT count(*) as cnt FROM weidian.articleinfo WHERE status='1' ";
		echo $sql;
		die ();
		$res = $this->get_one ( $sql );
		$count = $res ['cnt'];
		$size = '1000';
		$this->_log->debug ( sprintf ( "need chance_article total num is:%d", $count ) );
		$c = $count / $size;
		$n = 1;
		$periodical_ids = array ();
		for($m = 0; $m <= $c; $m ++) {
			$page = $m * $size;
			$t = array ();
			$upsql = '';
			$sql_e = sprintf ( "select id,published_at,periodical_id from cms.articles where status='50' limit %d,%d", $page, $size );
			$result = $this->get_all ( $sql_e );
			foreach ( $result ['results'] as $a ) {
				if ($a ['id'] == '0') {
					continue;
				}
				if (time () > strtotime ( $a ['published_at'] )) {
					$t [$a ['id']] = $a ['id'];
					$periodical_ids [$a ['periodical_id']] = $a ['periodical_id'];
				}
			}
			$this->update_tables ( $t, $periodical_ids );
		}
	}
	
	/**
	 * 更新数据表
	 * Enter description here .
	 * ..
	 * 
	 * @param unknown_type $t        	
	 * @param unknown_type $periodical_ids        	
	 */
	function update_tables(&$t, &$periodical_ids) {
		try {
			// $this->model("article/Model_article")->beginTransaction();
			if (! empty ( $t )) {
				foreach ( $t as $aid ) {
					$data ['status'] = 55;
					$up_res = $this->model ( 'article/Model_article' )->update_by_id ( $data, $aid );
					if (! res) {
						$this->_log->warn ( sprintf ( "the article_id_%d change status failer", $aid ) );
					}
				}
			} else {
				$this->_log->warn ( sprintf ( "there is no article to chance status" ) );
			}
			
			if (! empty ( $periodical_ids )) {
				foreach ( $periodical_ids as $periodical_id ) {
					$data ['status'] = 20;
					$up_res = $this->model ( 'article/Model_periodical' )->update_by_id ( $data, $periodical_id );
					if (! res) {
						$this->_log->warn ( sprintf ( "the periodical_%d change status failer", $periodical_id ) );
					}
				}
			} else {
				$this->_log->warn ( sprintf ( "there is no periodical to chance status" ) );
			}
			// $this->model("article/Model_article")->commit();
		} catch ( Exception $e ) {
			$this->_log->warn ( sprintf ( "Exception" . serialize ( $e ) ) );
			// $this->model("article/Model_article")->rollBack();
			throw $e;
		}
	}
	/**
	 * 获取单条记录
	 * Enter description here .
	 * ..
	 */
	function get_one($sql) {
		$results = array ();
		$res = $this->db->query ( $sql );
		if ($res->num_rows > 0) {
			foreach ( $res->result_array () as $ary ) {
				$results = $ary;
			}
		} else {
		}
		return $results;
	}
	
	/**
	 * 获取多条记录
	 * Enter description here .
	 * ..
	 */
	function get_all($sql) {
		$results = array (
				'num' => 0,
				'results' => array () 
		);
		$res = $this->db->query ( $sql );
		$results ['num'] = $res->num_rows;
		if ($res->num_rows > 0) {
			foreach ( $res->result_array () as $ary ) {
				$results ['results'] [] = $ary;
			}
		}
		return $results;
	}
}