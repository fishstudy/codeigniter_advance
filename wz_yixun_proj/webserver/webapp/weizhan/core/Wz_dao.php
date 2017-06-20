<?php
/**
 * My_dao
 *
 * @uses CI
 * @uses _Dao
 * @package 
 * @version $id$
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author fishxyu <fishxyu@yixun.com> 
 * @license 
 */
class Wz_dao extends CI_Dao {
    protected $_table;
    protected $_insert_fields = array();
    protected $_update_fields = array();
    protected $_select_fields = array();
    protected $_primary_key   = 'id';
    protected $_foreign_key   = '';
    protected $_dao_prefix    = 'Dao_';
    protected $_tree_daos     = array('architecture'=>1);
    protected $_dao           = '';

    /**
     * 继承父类构造函数
     */
    function __construct($active_group='') {
        parent::__construct();
        // 创建数据库连接实例
        //$this->load->database($active_group, FALSE, TRUE);
        //$this->load->driver('cache', NULL, 'cache');
    }
    //根据dao名称获取表名
    function get_table_name($param){
        $table = $this->config->item(substr($param['virtual_table'], strlen($this->_dao_prefix)), 'tableMap');

        if (empty($table)){
            throw new Exception(sprintf('dao:%s \'table not exists.', $param['virtual_table']),
                    $this->config->item('db_err_no', 'err_no')
                    );
        }

        return $table;

    }

    /**
     * _has_operator 
     * 
     * @param mixed $str 
     * @access protected
     * @return mixed
     */
    protected function _has_operator($str){
        $str = trim($str);
        if ( ! preg_match("/(\s|<|>|!|=|is null|is not null)/i", $str))
        {
            return FALSE;
        }
        return TRUE;
    }
    /**
     * _remove_operator 
     * 
     * @param mixed $str 
     * @access protected
     * @return mixed
     */
    protected function _remove_operator($str){
        preg_match_all("/([a-zA-Z0-9_]+)/i", $str, $matches);
        return $matches[1][0];
    }
    public function get_insert_fields(){
        return $this->_insert_fields;
    }
}
