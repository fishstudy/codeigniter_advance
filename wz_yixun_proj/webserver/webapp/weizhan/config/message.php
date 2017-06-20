<?php
/**
 * 系统提示信息
 * @package 
 * @version 1.0
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author allenwsun <allenwsun@yixun.com> <5340182@qq.com>
 * @license 
 */

$config['err_no'] = array(
		'input_validate_err_no'     => '100001',
		'parameter_err_no'          => '100002',
		'db_err_no'                 => '100003',
		'row_not_exists_err_no'     => '100004',
		'exists_err_no'             => '100005',
		'check_err_no'				=> '100006',
		'islogin_err_no'			=> '100007',
		'iscategory_err_no'		    => '100008',
		'data_exist_err_no'		    => '100009',
		'masterid_exist_err_no'		=> '100010',
		'wzid_exist_err_no'			=> '100011',
        'page_not_found'			=> '100404',
        'data_err_no'			    => '200001',
        'master_invalid'			=> '200002',
        'wz_not_exit'			    => '200003',
        'wz_closed'			       	=> '200004',
        'wz_refuse'			       	=> '200005',
        'wz_authit'			       	=> '200006',
        'get_product_failed'		=> '200007',
);
$config['err_msg']= array(
		'100001'=> '您输入格式有误，请核实后再重新输入！',
		'100002'=> '您输入的参数信息有误，请核实后再重新输入！',
		'100003'=> '服务繁忙，请您先喝杯咖啡！',
		'100004'=> '该条目不存在，请进行其他操作！',
		'100005'=> '该条目已经存在，请核实后再重新输入！',
		'100006'=> '您无权进行此操作，请联系你的上级！',
		'100007'=> '您的账号未登陆，请登陆之后再操作！',
		'100008'=> '分类数据不完整，请确认！',
		'100009'=> '数据不存在，请确认！',
		'100010'=> '传递的站长id参数为空或错误，请确认！',
		'100011'=> '传递的微站id参数错误，请确认！',
        '100404'=> '页面木有找到！',
        '200001'=> '获取文章内容服务繁忙，请您先喝杯咖啡！',
        '200002'=> '亲，站长待认证哦！',
        '200003'=> '微站还木有开通呢！',
        '200004'=> '亲，微站被关闭了呢！',
        '200005'=> '亲，微站木有通过审核！',
        '200006'=> '亲，还在审核中呢！',
        '200007'=> '亲，获取商品服务繁忙，请您先休息一下！',
);