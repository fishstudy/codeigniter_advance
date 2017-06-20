-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: 10.24.179.138:3306
-- Generation Time: 2015-08-28 11:37:01
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
-- 表的结构 `shopcatalog`
--

CREATE TABLE IF NOT EXISTS `shopcatalog` (
  `id` int(11) unsigned NOT NULL COMMENT '店铺爆款商品类目',
  `wzId` int(11) unsigned NOT NULL COMMENT '微店id',
  `tfId` int(11) unsigned NOT NULL COMMENT '投放id',
  `catalogName` char(20) NOT NULL COMMENT '类目名称',
  `createTime` int(11) NOT NULL COMMENT '创建时间',
  `updateTime` int(11) NOT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺商品类目';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shopcatalog`
--
ALTER TABLE `shopcatalog`
  ADD PRIMARY KEY (`id`), ADD KEY `wzId` (`wzId`), ADD KEY `tfId` (`tfId`);
ALTER TABLE `shopcatalog` CHANGE `id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '店铺爆款商品类目id';

