<?php
/**
 * 
 * @package 
 * @version 1.0
 * @time 下午4:43:47  PHP
 * @copyright Copyright (c) 2015 yixun Co. All Rights Reserved.
 * @author allenwsun <allenwsun@yixun.com>
 * @license 
 */

$config['sex'] = array(
		'1'=>'女',
		'2'=>'男',
		'3'=>'其他'
);

$config['master_condition'] = array(
		'1'=>'通过',
		'2'=>'有效',
		'17'=> '拒绝',
		'18'=> '审核中',
		'19'=> '关闭',
		'32'=> '初始状态',
		'33'=> '其他',
);

$config['master_valid'] = array(
		'1'=>'通过',
);

$config['master_invalid'] = array(
		'17'=> '拒绝',
		'18'=> '审核中',
		'19'=> '关闭',
);

$config['master_novalid'] = array(
		'17'=> '拒绝',
		'18'=> '审核中',
);

$config['master_close'] = array(
		'19'=> '已关闭',
);

$config['master_initvalid'] = array(
		'32'=> '初始状态',
);

//京东图片url配置
$config['picurl'] = "http://img10.360buyimg.com/yixun_zdm/s200x300_";

//js,css等版本号
$config['versionTimeStamp'] ='201502091015';

