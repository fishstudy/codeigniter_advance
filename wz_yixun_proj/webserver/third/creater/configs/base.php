<?php 
define ('BASEPATH', '/data/release/webserver/webapp/framwork');
define ('APPPATH', '/data/release/webserver/webapp/base');
require_once APPPATH .'/config/development/common.db.php';
$g_filter_tables = array('id_allocators');
$g_dao_map = array(                
             'base' => array(
                    'website_shop' =>array(
                        'path'               => 'shop',//目录
                        'dao'                => 'website_shop', 
                        'model'              => 'shop/website_shop',
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
                    'articleinfo' =>array(
                        'path'               => 'articleinfo',//目录
                        'dao'                => 'articleinfo', 
                        'model'              => 'article/Model_articleinfo',
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
//end of weidian

            );

$g_controller_map = array(
	'base'=> array(
	     'webmaster' =>array(
                'path'                      => 'public',
                'controller'                => 'webmaster',
                'LOGIC'                     => 'article/Logic_webmaster',
                'ILIST_ITEM'                => array(),
                'ILIST_ITEM2'               => array(),
                'SEARCH_INTVAL_ITEM'        => array(),
                'SEARCH_ITEM'               => array(),
                'SEARCH_CONDITIO'           => array(),
         ),
         'website_shop' =>array(
                'path'                      => 'shop',
                'controller'                => 'website_shop',
                'LOGIC'                     => 'shop/Logic_website_shop',
                'ILIST_ITEM'                => array(),
                'ILIST_ITEM2'               => array(),
                'SEARCH_INTVAL_ITEM'        => array(),
                'SEARCH_ITEM'               => array(),
                'SEARCH_CONDITIO'           => array(),
         ),
         'articleinfo' =>array(
                'path'                      => 'articleinfo',
                'controller'                => 'articleinfo',
                'LOGIC'                     => 'article/Logic_articleinfo',
                'ILIST_ITEM'                => array(),
                'ILIST_ITEM2'               => array(),
                'SEARCH_INTVAL_ITEM'        => array(),
                'SEARCH_ITEM'               => array(),
                'SEARCH_CONDITIO'           => array(),
         ),
    ),
);
