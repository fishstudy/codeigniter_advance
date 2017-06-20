<?php 
/**
 * Logic__{{$NAME}}
 * 
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author weidian team <fishxyu@yixun.com> 
 * @license 
 */
class Logic_{{$NAME}} extends {{$PREFIX}}_logic {
    {{if !empty($fetch_name_columns)}}
    /**
     * _fetch_name_columns 获取对应名称 
     * 
     * @var array
     * @access protected
     */
    protected  $_fetch_name_columns = array(
            {{foreach $fetch_name_columns as $v}}
            '{{$v['FETCH_NAME']}}'     => array('model'=>'{{$v['FETCH_MODEL']}}', 'id'=>'{{$v['FETCH_ID']}}'),
            {{/foreach}}
            );
    {{/if}}
    {{if !empty($fetch_name_columns_one)}}
    protected  $_fetch_name_columns_one = array(
            {{foreach $fetch_name_columns_one as $v}}
            '{{$v['FETCH_NAME_ONE']}}'     => array('model'=>'{{$v['FETCH_MODEL_ONE']}}', 'id'=>'{{$v['FETCH_ID_ONE']}}'),
            {{/foreach}}
            );
    {{/if}}
    {{if !empty($data_maps_many)}}
    /**
     * _data_maps_many  将逗号分割的数据转化到相应的表中
     * 
     * @var array
     * @access protected
     */
    protected $_data_maps_many = array(
            {{foreach $data_maps_many as $v}} 
            array('ft'=>'{{$v['FROM_NAME_MANY']}}', 'fc'=>'{{$v['FROM_COLUMN_MANY']}}', 'tt'=>'{{$v['TO_NAME_MANY']}}', 'tc'=>'{{$v['TO_COLUMN_MANY']}}', 'redundance_map'=>array({{foreach $v['redundance_map'] as $vv}} {{$vv}}, {{/foreach}})),
            {{/foreach}}  
            );
    {{/if}}
    {{if !empty($data_maps_one)}}
    /**
     * _data_maps_one 将一个表的对应的列转化为对应的表中
     * 
     * @var array
     * @access protected
     */
    protected $_data_maps_one  = array(
            {{foreach $data_maps_one as $v}}
            array('ft'=>'{{$v['FROM_NAME_ONE']}}', 'fc'=>'{{$v['FROM_COLUMN_ONE']}}', 'tt'=>'{{$v['TO_NAME_ONE']}}', 'tc'=>'{{$v['TO_COLUMN_ONE']}}', 'redundance_map'=>array({{foreach $v['redundance_map']  as $vv}} {{$vv}}, {{/foreach}})),
            {{/foreach}}
            );
    {{/if}}
    {{if !empty($kv_data_maps_many)}}
    /**
     * _kv_data_maps_many 将逗号分割的名称分割后，获取对应的Id
     * 
     * @var array
     * @access protected
     */
    protected $_kv_data_maps_many = array(
            {{foreach $kv_data_maps_many as $v}}
            array('ft'=>'{{$v['KV_FROM_NAME_MANY']}}', 'fc'=>'{{$v['KV_FROM_COLUMN_MANY']}}', 'tt'=>'{{$v['KV_TO_NAME_MANY']}}', 'tc'=>'{{$v['KV_TO_COLUMN_MANY']}}', 
                'param_keys'=>array({{$v['KV_FROM_PARAM_MANY']}}), 'model'=>'{{$v['KV_FROM_MODEL_MANY']}}'
                ),
            {{/foreach}}
            );
    {{/if}}
    {{if !empty($kv_data_maps_one)}}
    /**
     * _kv_data_maps_one 根据名称获取对应Id
     * 
     * @var array
     * @access protected
     */
    protected $_kv_data_maps_one = array(
            {{foreach $kv_data_maps_one as $v}}
            array('ft'=>'{{$v['KV_FROM_NAME_ONE']}}', 'fc'=>'{{$v['KV_FROM_COLUMN_ONE']}}', 'tt'=>'{{$v['KV_TO_NAME_ONE']}}', 'tc'=>'{{$v['KV_TO_COLUMN_ONE']}}', 
                'param_keys'=>array({{$v['KV_FROM_PARAM_ONE']}}), 'model'=>'{{$v['KV_FROM_MODEL_ONE']}}', 
                'is_api'=> {{if (isset($v['IS_API'])) }}  TRUE {{else}} FALSE {{/if}}
                ),
            {{/foreach}}
            );
    {{/if}}
    {{if !empty($SEARCH_MAPS_ONE) }}
    /**
     * _search_maps_many 搜索项 获取对应键名
     *
     * @var string
     * @access protected
     */
    protected $_search_maps_one           = array(
            {{foreach $SEARCH_MAPS_ONE as $k => $v }}
            array("{{$v['FROM_COLUMN_NAMY']}}" => "{{$v['TO_COLUMN_NAMY']}}"),{{/foreach}}
            );
    {{/if}}
    {{if !empty($FETCH_INTERVAL_COLUMNS) }}
    /**
     * _fetch_interval_columns 搜索项 获取区间
     *
     * @var string
     * @access protected
     */
    protected  $_fetch_interval_columns = array(
            {{foreach $FETCH_INTERVAL_COLUMNS as $k => $v }} array('from' => "{{$v['FROM_ITEM']}}", 'to' => "{{$v['TO_ITEM']}}"),{{/foreach}}
            );
    {{/if}}

    /**
     * __construct 
     * 
     * @access protected
     * @return mixed
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * 列表页
     *
     * @param mixed $post_data
     * @access public
     * @return mixed
     */
    public function search($data, $page, $pagesize) {
        $data['page']     = $page;
        $data['pagesize'] = $pagesize;
        try{
            ${{$NAME}} = $this->model('{{$MODEL}}')
            ->search($data, $page, $pagesize);
        }catch(Exception $e){
            throw $e;
        }
         
        return ${{$NAME}};
    }
    
    /**
     * 新增
     *
     * @param mixed $post_data
     * @access public
     * @return mixed
     */
    public function create() {
        $c['{{$NAME}}'] = $this->model('{{$MODEL}}')->create();
         
        return $c;
    }
    
    /**
     * 插入或者保存 
     * 
     * @param mixed $post_data 
     * @access public
     * @return mixed
     */
    public function save($post_data) {
        $this->_adjust($post_data);
        return $this->model('{{$MODEL}}') ->save($post_data);
    }

    /**
     * 查看详情
     * 
     * @param mixed $id 
     * @access public
     * @return mixed
     */
    public  function detail($id) {
        $c = array();
        $c['{{$NAME}}'] = $this->model('{{$MODEL}}')->fetch_one_by_id($id);
        if (empty($c['{{$NAME}}']) ){
            throw new Exception(sprintf('%s: The {{$NAME}} id %d does not exist or has been deleted.',
                        __FUNCTION__, $id), $this->config->item('data_exist_err_no', 'err_no'));  
        } 
        {{if !empty($fetch_name_columns_one)}}
        foreach ($this->_fetch_name_columns_one $key=>$v){
            $results = $this->model($v['model'])->search(array('{{$foriegn_key}}'=>$c['{{$NAME}}'][$v['id']]));
            if ($results['num']>0){
                $c[$key] =  current($results['results']);
            }else{
                $c[$key] = $this->model($v['model'])->new_one();
            }
        }
        {{/if}}
        {{if !empty($fetch_name_columns)}}
        foreach ($this->_fetch_name_columns $key=>$v){
            $results = $this->model($v['model'])->search(array('{{$foriegn_key}}'=>$c['{{$NAME}}'][$v['id']]));
            if ($results['num']>0){
                $c[$key] =  $results['results'];
            }else{
                $c[$key] =  array(); //$this->model($v['model'])->new_one();
            }
        }
        {{/if}}

        return $c; 
    }
    
    /**
     * 编辑页面
     * 
     * @param mixed $id 
     * @access public
     * @return mixed
     */
    public function edit($id) {
        $c = array();
        $c['{{$NAME}}'] = $this->model('{{$MODEL}}')->fetch_one_by_id($id);
        if (empty($c['{{$NAME}}']) ){
            throw new Exception(sprintf('%s: The {{$NAME}} id %d does not exist or has been deleted.',
                        __FUNCTION__, $id), $this->config->item('data_exist_err_no', 'err_no'));  
        } 
        {{if !empty($fetch_name_columns_one)}}
        foreach ($this->_fetch_name_columns_one as  $key=>$v){
            $results = $this->model($v['model'])->search(array('{{$foriegn_key}}'=>$c['{{$NAME}}'][$v['id']]));
            if ($results['num']>0){
                $c[$key] =  current($results['results']);
            }else{
                $c[$key] = $this->model($v['model'])->new_one();
            }
        }
        {{/if}}
        {{if !empty($fetch_name_columns)}}
        foreach ($this->_fetch_name_columns as $key=>$v){
            $results = $this->model($v['model'])->search(array('{{$foriegn_key}}'=>$c['{{$NAME}}'][$v['id']]));
            if ($results['num']>0){
                $c[$key] =  $results['results'];
            }else{
                $c[$key][] = $this->model($v['model'])->new_one();
            }
        }
        {{/if}}

        return $c; 
    }
    
    /**
     * 修改少量字段
     *
     * @param mixed $post_data
     * @access public
     * @return mixed
     */
    public function set($param = array()) {
        $this->_adjust($post_data);
        return $this->model('{{$MODEL}}') ->update_by_id($post_data,$post_data['id']);
    }
    
    /**
     * 删除（真删）
     * id 要删除的主键ID
     * $user_id > 0 判断删除的item的创建用户的所属，否则直接删除
     */
    public function del($id=0, $user_id=0) {
        return $this->model('{{$MODEL}}') ->delete_one_by_id($id=0, $user_id=0);
    }
    
    /**
     * 自动添加属性，如修改时间，修改用户ID等
     */
    protected function _adjust(&$post_data) {
        //$post_data['createTime'] = time();
        $post_data['updateTime'] = time();
    }
    
    /**
     * 魔术函数 当调用的函数不存在或权限被控制，此方法会自动被调用
     *
     * @param mixed $func
     * @param mixed $args
     * @return mixed
     */
    public function __call($func, $args) {
        return call_user_func_array(array($this->model('{{$MODEL}}'), $func), $args);
    }
}
?>
