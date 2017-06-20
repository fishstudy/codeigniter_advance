-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: 10.24.179.138:3306
-- Generation Time: 2015-03-30 15:38:39
-- 服务器版本： 5.5.40
-- PHP Version: 5.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `weidian`
--

-- --------------------------------------------------------

--
-- 表的结构 `website`
--

DROP TABLE IF EXISTS `website`;
CREATE TABLE IF NOT EXISTS `website` (
  `id` int(11) unsigned NOT NULL,
  `webMasterId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '对应站长的id',
  `bgPicture` varchar(256) NOT NULL DEFAULT '' COMMENT '背景图片',
  `articleList` varchar(64) NOT NULL DEFAULT '' COMMENT '文章列表标题',
  `productList` varchar(64) NOT NULL DEFAULT '' COMMENT '商品列表标题',
  `actList` varchar(64) NOT NULL DEFAULT '' COMMENT '活动列表标题',
  `createTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `createUser` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '创建人，采用易迅id',
  `updateTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  `updateUser` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新人，采用易迅id',
  `conditions` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '有效1-16，无效17-32，备用33-',
  `articleModel` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '文章列表模板',
  `productModel` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '商品列表模板',
  `actModel` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '活动展示列表',
  `advModel` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '广告模板'
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 COMMENT='微站属性表';


--
-- Indexes for dumped tables
--

--
-- Indexes for table `website`
--
ALTER TABLE `website`
  ADD PRIMARY KEY (`id`), ADD KEY `webMasterId` (`webMasterId`), ADD KEY `conditions` (`conditions`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `website`
--
ALTER TABLE `website`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10000;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



/*新微站 - 一期 添加的字段 20150818*/

ALTER TABLE `website` ADD `recommend` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '达人推荐理由' ;
ALTER TABLE `website` ADD `actLogo` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '热销活动基本设置-logo' ;
ALTER TABLE `website` ADD `actPic` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '热销活动基本设置-背景图' ;
ALTER TABLE `website` ADD `actTitle` VARCHAR(256) NULL COMMENT '热销活动基本设置-标题' ;
ALTER TABLE `website` ADD `actCoupon` TINYINT(2) UNSIGNED NOT NULL DEFAULT '2' COMMENT '热销活动-是否在首页展示优惠券1是2否（默认）' ;



