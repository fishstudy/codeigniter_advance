<?php 
define ('BASEPATH', '/data/release/webserver/webapp/framwork');
define ('APPPATH', '/data/release/webserver/webapp/base');
require_once APPPATH .'/config/development/common.db.php';
$g_filter_tables = array('id_allocators');
/****************************************dao的生成配置********************************************************/
$g_dao_map = array(
                'weizhan' => array(
                                //表名
                                'articleinfo' =>array(
                                                'path'               => 'front',//目录
                                                'dao'                => 'admin',
                                                'model'              => 'front/Model_article',
                                                'equal_search_items' => "'id'=>'t','wzId'=>'t','tfId'=>'t','shopId'=>'t','status'=>'t'",
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
                'weizhan'=> array(
                                'articleinfo' =>array(
                                                'path'                      => 'front',
                                                'controller'                => 'article',
                                                'LOGIC'                     => 'front/Logic_article',
                                                'ILIST_ITEM'                => array(),
                                                'ILIST_ITEM2'               => array(),
                                                'SEARCH_INTVAL_ITEM'        => array(),
                                                'SEARCH_ITEM'               => array(),
                                                'SEARCH_CONDITIO'           => array(),
                                ),
                ),
);
