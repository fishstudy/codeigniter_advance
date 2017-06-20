-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: 10.24.179.138:3306
-- Generation Time: 2015-03-30 15:38:20
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
-- 表的结构 `articleinfo`
--

DROP TABLE IF EXISTS `articleinfo`;
CREATE TABLE IF NOT EXISTS `articleinfo` (
  `id` int(11) unsigned NOT NULL,
  `articleId` bigint(15) unsigned NOT NULL DEFAULT '0' COMMENT '文章id，来自值得买',
  `authorid` bigint(20) unsigned NOT NULL COMMENT '值得买文章作者id',
  `secretid` varchar(15) NOT NULL COMMENT '值得买文章id加密字符串',
  `wzId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '对应的微站的id',
  `title` varchar(256) NOT NULL DEFAULT '' COMMENT '文章在微站的标题',
  `zdmTitle` varchar(256) NOT NULL DEFAULT '' COMMENT '值得买同步的文章标题',
  `brief` varchar(256) NOT NULL DEFAULT '' COMMENT '文章简介',
  `microUrl` varchar(256) NOT NULL DEFAULT '' COMMENT '文章缩略图地址',
  `readNum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章阅读数',
  `praiseNum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `conditions` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '文章可用1-16，不可以17-32，备用33-',
  `hitShelveTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上架时间',
  `createTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updateTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  `updateUser` bigint(20) NOT NULL COMMENT '更新文章的用户',
  `categories` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '文章所属分类，来自值得买',
  `orderNum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章排序，越小排序越靠前'
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 COMMENT='微站文章表';


--
-- Indexes for dumped tables
--

--
-- Indexes for table `articleinfo`
--
ALTER TABLE `articleinfo`
  ADD PRIMARY KEY (`id`), ADD KEY `articleId` (`articleId`), ADD KEY `wzId` (`wzId`), ADD KEY `categories` (`categories`), ADD KEY `wzId_2` (`wzId`,`conditions`,`categories`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articleinfo`
--
ALTER TABLE `articleinfo`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10000;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


ALTER TABLE `articleinfo` ADD `kindId` INT(11) NULL COMMENT '文章类目id' ;