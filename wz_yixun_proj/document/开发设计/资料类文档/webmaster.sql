-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: 10.24.179.138:3306
-- Generation Time: 2015-03-30 15:37:31
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
-- 表的结构 `webmaster`
--

DROP TABLE IF EXISTS `webmaster`;
CREATE TABLE IF NOT EXISTS `webmaster` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '微站名称',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '申请人姓名',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `iccard` varchar(32) NOT NULL DEFAULT '' COMMENT '身份证',
  `level` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '微站等级',
  `authentication` tinyint(4) unsigned NOT NULL DEFAULT '2' COMMENT '是否认证 1已认证 2未认证',
  `qq` varchar(16) NOT NULL DEFAULT '' COMMENT 'QQ号码',
  `weChat` varchar(64) NOT NULL DEFAULT '' COMMENT '微信号',
  `wzUrl` varchar(256) NOT NULL DEFAULT '' COMMENT '微站链接',
  `weibo` varchar(256) NOT NULL DEFAULT '' COMMENT '博客地址',
  `createTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `createUser` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '创建人，采用易迅id',
  `updateTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  `updateUser` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新人，采用易迅id',
  `conditions` tinyint(4) unsigned NOT NULL DEFAULT '18' COMMENT '状态，1-16有效，17-32无效(17拒绝18待审核19关闭)，33-备用',
  `logo` varchar(256) NOT NULL DEFAULT '' COMMENT '站长头像',
  `introduction` varchar(64) NOT NULL DEFAULT '' COMMENT '简介',
  `confirmTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '认证时间',
  `confirmUser` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '认证人',
  `rejection` varchar(256) DEFAULT NULL COMMENT '拒绝原因',
  `closereason` varchar(256) DEFAULT NULL COMMENT '关闭原因'
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 COMMENT='微站站长表';


--
-- Indexes for dumped tables
--

--
-- Indexes for table `webmaster`
--
ALTER TABLE `webmaster`
  ADD PRIMARY KEY (`id`), ADD KEY `mobile` (`mobile`), ADD KEY `username` (`username`), ADD KEY `conditions` (`conditions`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `webmaster`
--
ALTER TABLE `webmaster`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10000;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
