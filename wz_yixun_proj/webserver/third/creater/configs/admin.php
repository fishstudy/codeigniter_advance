<?php 
define ('BASEPATH', '/data/release/webserver/webapp/framwork');
define ('APPPATH', '/data/release/webserver/webapp/admin');
require_once APPPATH .'/config/development/common.db.php';
$g_filter_tables = array('id_allocators');
/****************************************dao的生成配置********************************************************/
$g_dao_map = array(                
                'admin' => array(
                   //表名
                    'webmaster' =>array(
                        'path'               => 'admin',//目录
                        'dao'                => 'master', 
                        'model'              => 'admin/Model_master',
                        'equal_search_items' => "'id'=>'t','orderNum'=>'t'", 
                        'like_search_items'  => '', 
                        'like2_search_items' => '',
                        'foriegn_key'        => '',
                        'fetch_name_columns' => array(),
                        'data_maps_many'     => array(),
                        'data_maps_one'      => array(),
                        'kv_data_maps_many'  => array(),
                        'kv_data_maps_one'   => array(),
                    ),

                ),

            );
/*************************************crontroller,logic,model 生成配置************************************************************/
$g_controller_map = array(
	'admin'=> array(
	     //表名
	     'webmaster' =>array(
                'path'                      => 'admin',
                'controller'                => 'master',
                'LOGIC'                     => 'front/Logic_master',
                'ILIST_ITEM'                => array(),
                'ILIST_ITEM2'               => array(),
                'SEARCH_INTVAL_ITEM'        => array(),
                'SEARCH_ITEM'               => array(),
                'SEARCH_CONDITIO'           => array(),
         ),
    ),
);
