-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: 10.24.179.138:3306
-- Generation Time: 2015-08-28 12:01:11
-- 服务器版本： 5.5.40-log
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
-- 表的结构 `shopproduct`
--

CREATE TABLE IF NOT EXISTS `shopproduct` (
  `id` int(11) unsigned NOT NULL COMMENT '店铺推荐商品id',
  `wzId` int(11) NOT NULL COMMENT '微站id',
  `scId` int(11) DEFAULT NULL COMMENT '店铺类目id',
  `pId` int(11) NOT NULL COMMENT '商品id',
  `info` varchar(70) DEFAULT NULL COMMENT '商品介绍',
  `pic` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `type` tinyint(1) NOT NULL COMMENT '推荐商品类型1.达人推荐2.抢购3.热销爆品4.精选商品',
  `title` varchar(30) DEFAULT NULL COMMENT '商品名称',
  `createTime` int(11) NOT NULL COMMENT '创建时间',
  `updateTime` int(11) NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺推荐商品';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shopproduct`
--
ALTER TABLE `shopproduct`
  ADD PRIMARY KEY (`id`), ADD KEY `wzId` (`wzId`), ADD KEY `type` (`type`);
  
  ALTER TABLE `shopproduct` CHANGE `id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '店铺推荐商品id';
  ALTER TABLE `shopproduct` CHANGE `pId` `pId` CHAR(20) NOT NULL COMMENT '商品id';

