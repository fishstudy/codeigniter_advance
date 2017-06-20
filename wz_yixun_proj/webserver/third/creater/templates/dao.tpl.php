<?php 
/**
 * Dao_{{$name}}
 * 
 * @uses {{$prefix}}
 * @uses _Dao
 * @package 
 * @version $id$
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author fishxyu <fishxyu@yixun.com> 
 * @license 
 */
class Dao_{{$name}} extends {{$prefix}}_dao {
    /**
     * _table 
     * 
     * @var mixed
     * @access protected
     */
    protected $_table;
    /**
     * _fields 
     * 
     * @var string
     * @access protected
     */
    protected $_insert_fields = array({{$insert_fields}});
    protected $_update_fields = array({{$update_fields}});
    protected $_select_fields = array({{$select_fields}});
    protected $_primary_key = {{$primary_fields}};
    protected $_unique_fields = array({{$unique_fields}});
    protected $_index_fields = array({{foreach $index_fields as $k=>$v}}'{{$k}}' => array({{foreach $v as $kk=>$vv}}{{$kk}} => '{{$vv}}', {{/foreach}}),{{/foreach}});
    protected $_dao = '';
    protected $_auto_increment  = TRUE;
    /**
     * __construct 
     * 
     * @access protected
     * @return mixed
     */
    public function __construct($param=array()) {
        parent::__construct($param['active_group']);
        $this->_table = $this->get_table_name(array('virtual_table'=>__CLASS__, 'id'=>$param['id']));
        $this->_dao = substr(__CLASS__, 4);
    }
    {{if in_array($name, array('corporation', 'position', 'school', 'discipline', 'industry', 'station', 'architecture', 'title'))}}
    /**
     * gen_id_by_unique 
     * 
     * @param array $param 
     * @access public
     * @return mixed
     */
    function gen_id_by_unique($param=array()) {
        if (!is_array($param) || empty($param)) {
            throw new Exception(sprintf('function: %s,table: %s parameters must be array.',
                        __FUNCTION__, $this->_table), $this->config->item('parameter_err_no', 'err_no')
                    );
        }
        $new_param = array();
        foreach ($this->_unique_fields as $field) {
            if ($field == $this->_primary_key) continue;
            if (!isset($param[$field])) {
                throw new Exception(sprintf('function: %s,table: %s parameter: %s not exists.',
                            __FUNCTION__, $this->_table,  $field),$this->config->item('parameter_err_no', 'err_no')
                        );
            }
            $new_param[$field] = $param[$field];
        }
        if (empty($new_param)) { 
            throw new Exception(sprintf('function: %s,table: %s new_param must be not empty array.',
                        __FUNCTION__, $this->_table), $this->config->item('parameter_err_no', 'err_no')
                    );
        }

        $dao_param = array('active_group'=>$this->config->item('active_group'), 'id'=>0);
        $criteria  = array();
        foreach ($new_param as $k=>$v) {
            $criteria[] = sprintf("%s='%s'", $k, $this->db->escape_str($v));
        }
        $strCriteria    =  implode(' AND ', $criteria);
        $in_trans       =  $this->db->conn_id->inTransaction();
        $my_trans       =  TRUE;
        try {
            $id = 0;
            $in_trans ? ( $my_trans = FALSE) : $this->db->conn_id->beginTransaction();
            {{if $name == 'corporation'}}
            $sql = sprintf("SELECT corporation_id FROM  corporations_alias_names WHERE %s FOR UPDATE", $strCriteria);
            $q = $this->db->query($sql);
            if ($q->num_rows() <=0) {
                $this->load->dao('public/Dao_id_allocator', '', $dao_param);
                $new_param['corporation_id'] = $this->Dao_id_allocator->allocate(array('ns'=>'{{$prefix}}','table_name'=>substr(__CLASS__, 4)));
                $new_param['id'] = $this->Dao_id_allocator->allocate(array('ns'=>'{{$prefix}}','table_name'=>'corporation_alias_name'));
                $id = $this->insert_unique(array('id'=> $new_param['corporation_id'], 'name'=> $new_param['name']));
                $this->load->dao('corporation/Dao_corporation_alias_name', '', $dao_param);
                $this->Dao_corporation_alias_name->db->insert('corporations_alias_names',
                        array('id'=>$new_param['id'], 'name'=>$new_param['name'], 'corporation_id'=>$new_param['corporation_id']));
            } else {
                $result = $q->first_row('array');
                $id     =  $result['corporation_id'];
            }
            {{else}}
            $sql = sprintf("SELECT %s FROM %s WHERE %s FOR UPDATE", $this->_primary_key, $this->_table, $strCriteria);
            $q = $this->db->query($sql);
            if ($q->num_rows() <= 0) {
                $this->load->dao('public/Dao_id_allocator', '', $dao_param);
                $new_param[$this->_primary_key] = $this->Dao_id_allocator->allocate(array('ns'=>'{{$prefix}}','table_name'=> substr(__CLASS__, 4)));
                $id = parent :: insert_unique($new_param);
            } else {
                $result = $q->first_row('array');
                $id     = $result[$this->_primary_key];
            }
            {{/if}}
            $my_trans ?  $this->db->conn_id->commit(): '';
            return $id;
        } catch(Exception $e) {
            $my_trans ?  $this->db->conn_id->rollback() : '';
            throw $e;
        }
    }
    {{/if}}

    /**
     * search 
     * 
     * @param array $param 
     * @param int $page 
     * @param int $pagesize 
     * @access public
     * @return mixed
     */
    function search($param = array()) {
        $res = array('num'=>0, 'results'=>array());
        $equal_search_items = array({{$equal_search_items}});
        $like_search_items  = array({{$like_search_items}});
        $like2_search_items = array({{$like2_search_items}});

        foreach ($param as $column=>$v) {
            if (!isset($equal_search_items[$this->_remove_operator($column)])) continue;
            $this->db->where($column, $v);
        }
        foreach ($like_search_items as $column=>$t) {
            if (!isset($param[$column])) continue;
            $this->db->like($column, $param[$column]);
        }
        isset($param['group_by']) ? $this->db->group_by($param['group_by']) : '';
        isset($param['distinct']) ? $this->db->distinct($param['distinct']) : '';
        $res['num'] = $this->db->count_all_results($this->_table);
        if ($res['num'] > 0) {
            $res['results'] = array();
            foreach ($param as $column=>$v) {
                if (!isset($equal_search_items[$this->_remove_operator($column)])) continue;
                $this->db->where($column, $v);
            }
            foreach($like_search_items as $column=>$t) {
                if (!isset($param[$column])) continue;
                $this->db->like($column, $param[$column]);
            }
            if (isset($param['page']) && $param['page'] >0 && isset($param['pagesize']) && $param['pagesize']>0) {
                $page       = $param['page'];
                $pagesize   = $param['pagesize'];
                $start      = (min(max(ceil($res['num']*1.0/$pagesize), 1), $page)-1)*$pagesize;
                $this->db->limit($pagesize, $start);
            }

            isset($param['ordersort']) ? $this->db->order_by($param['ordersort']) : '';
            isset($param['group_by']) ? $this->db->group_by($param['group_by']) : '';
            isset($param['select']) ? $this->db->select(sprintf('%s', $param['select'])) :  $this->db->select($this->_primary_key);

            $q = $this->get($this->_table);
            foreach ($q->result_array() as $result) {
                $res['results'][$result[$this->_primary_key]] = $result;
            }
        }
        return $res;
    }

}
?>
