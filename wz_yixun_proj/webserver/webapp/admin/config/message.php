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
		'iscategory_err_no'			=> '100008',
		'data_exist_err_no'			=> '100009',
		'masterid_exist_err_no'		=> '100010',
		'wzid_exist_err_no'			=> '100011',
		'ismaster_err_no'			=> '200001',
		'insertarticle_err_no'		=> '200002',
		'deletearticle_err_no'		=> '200003',
		'editarticle_err_no'		=> '200004',
		'editarticleorder_err_no'	=> '200005',
		'istitle_err_no'			=> '200006',
		'ismax_err_no'				=> '200007',
		'isartid_err_no'			=> '200008',
);

$config['err_msg']= array(
		'100001'=> '您输入格式有误，请核实后再重新输入！',
		'100002'=> '您输入的信息不完整，请核实后再重新输入！',
		'100003'=> '服务繁忙，请您先喝杯咖啡！',
		'100004'=> '该条目不存在，请进行其他操作！',
		'100005'=> '该条目已经存在，请核实后再重新输入！',
		'100006'=> '您无权进行此操作，请联系你的上级！',
		'100007'=> '您的账号未登陆，请登陆之后再操作！',
		'100008'=> '分类数据不完整，请确认！',
		'100009'=> '数据不存在，请确认！',
		'100010'=> '站长id不存在，请确认！',
		'100011'=> '微站id不存在，请确认！',
		'200001'=> '您的账号未开通微站，请开通之后再导入文章！',
		'200002'=> '您的账号已导入过此文章，请不要重复导入！',
		'200003'=> '您的账号不能删除此文章！',
		'200004'=> '您的账号不能修改此文章标题简介！',
		'200005'=> '您的账号不能修改此文章排序！',
		'200006'=> '文章标题不能为空，请重新填写！',
		'200007'=> '文章排序值为0-9999，请重新填写！',
		'200008'=> '删除文章的id不能为空，请重新填写！',
);