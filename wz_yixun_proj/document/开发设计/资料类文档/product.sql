-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: 10.24.179.138:3306
-- Generation Time: 2015-04-02 17:49:15
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
-- 表的结构 `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) unsigned NOT NULL,
  `wzId` int(11) unsigned NOT NULL COMMENT '微站id',
  `tfId` int(11) unsigned NOT NULL COMMENT '投放id',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '状态值，默认1生效',
  `createTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `createUser` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '创建人',
  `updateTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `updateUser` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '更新人id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='设置微站和投放、商品池的关系表';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
