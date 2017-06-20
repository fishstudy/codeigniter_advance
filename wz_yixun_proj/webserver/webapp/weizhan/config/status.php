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

/*
 * 站长状态有效数组
 */
$config['masterValid'] = array(
    1,
);
/*
 * 站长状态无效数组
 */
$config['masterInvalid'] = array(
    2,
);
/*
 *文章内容缓存时间
 */
$config['articleCache'] = 36000;
/*
 * 微站有效状态
 */
$config['articleCondition'] = 1;
/*
 * 文章有效状态
 */
$config['article_valid'] = array(
    1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,
);
/*
 * 站长状态
 */
$config['masterInCondition'] = array(
   17,   //拒绝
   18,   //审核中
   19,   //关闭
);
$config['masterRefuse'] = 17;
$config['masterAuthit'] = 18;
$config['masterClose'] = 19;
/*
 * 商品缓存时间
 */
$config['productCache'] = 600;




