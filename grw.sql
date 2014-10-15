-- phpMyAdmin SQL Dump
-- version 3.4.8
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 10 月 16 日 01:40
-- 服务器版本: 5.5.28
-- PHP 版本: 5.3.28

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `grw`
--

-- --------------------------------------------------------

--
-- 表的结构 `Fei_acl`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2014 年 02 月 08 日 16:01
--

CREATE TABLE IF NOT EXISTS `Fei_acl` (
  `aclid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `controller` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `acl_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`aclid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=54 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_category`
--
-- 创建时间: 2013 年 12 月 14 日 12:21
-- 最后更新: 2013 年 12 月 21 日 07:51
--

CREATE TABLE IF NOT EXISTS `Fei_category` (
  `catid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `parentid` tinyint(4) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL,
  `catname` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `remark` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `setting` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `listorder` tinyint(4) NOT NULL DEFAULT '1',
  `ismenu` tinyint(1) NOT NULL,
  `letter` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `ico` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `addtime` datetime NOT NULL,
  PRIMARY KEY (`catid`),
  KEY `parentid` (`parentid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=72 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_contact`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2013 年 11 月 13 日 14:29
--

CREATE TABLE IF NOT EXISTS `Fei_contact` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `company` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `linkman` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `phone` tinyint(4) NOT NULL,
  `tel` tinyint(4) NOT NULL,
  `fax` tinyint(4) NOT NULL,
  `address` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_education`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2014 年 09 月 19 日 11:48
--

CREATE TABLE IF NOT EXISTS `Fei_education` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `school` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(1) NOT NULL,
  `startime` date NOT NULL,
  `endtime` date NOT NULL,
  `userid` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_festival`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2013 年 11 月 13 日 14:29
--

CREATE TABLE IF NOT EXISTS `Fei_festival` (
  `fid` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`fid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=160 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_follow`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2013 年 11 月 13 日 14:29
-- 最后检查: 2013 年 11 月 13 日 14:29
--

CREATE TABLE IF NOT EXISTS `Fei_follow` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `icon` int(1) NOT NULL,
  `link` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `userid` (`userid`),
  KEY `userid_2` (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_money`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2013 年 11 月 13 日 14:29
-- 最后检查: 2013 年 11 月 13 日 14:29
--

CREATE TABLE IF NOT EXISTS `Fei_money` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `action` tinyint(1) NOT NULL,
  `money` int(15) NOT NULL,
  `mark` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `userid` int(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_money_assets`
--
-- 创建时间: 2013 年 11 月 21 日 07:11
-- 最后更新: 2013 年 11 月 21 日 07:42
--

CREATE TABLE IF NOT EXISTS `Fei_money_assets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `sum` int(11) NOT NULL,
  `time` date NOT NULL,
  `remark` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_money_bank`
--
-- 创建时间: 2013 年 12 月 13 日 06:12
-- 最后更新: 2013 年 12 月 13 日 06:13
--

CREATE TABLE IF NOT EXISTS `Fei_money_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `title` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '0信用卡|1储蓄卡',
  `num` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `reserve` float NOT NULL COMMENT '余额',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_money_bank_record`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2013 年 11 月 13 日 14:29
--

CREATE TABLE IF NOT EXISTS `Fei_money_bank_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cz` tinyint(1) NOT NULL,
  `bankid` tinyint(2) NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sum` int(11) NOT NULL,
  `address` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `remark` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_money_deposit`
--
-- 创建时间: 2013 年 11 月 21 日 05:06
-- 最后更新: 2013 年 12 月 30 日 09:10
--

CREATE TABLE IF NOT EXISTS `Fei_money_deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL COMMENT '0借出1借进',
  `user` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `sum` int(11) NOT NULL,
  `stime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `period` int(4) NOT NULL COMMENT '天',
  `remark` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_note`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2013 年 11 月 13 日 14:29
-- 最后检查: 2013 年 11 月 13 日 14:29
--

CREATE TABLE IF NOT EXISTS `Fei_note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `title` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `keywords` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_password`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2014 年 10 月 15 日 15:48
--

CREATE TABLE IF NOT EXISTS `Fei_password` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catid` tinyint(4) NOT NULL,
  `uid` int(11) NOT NULL,
  `title` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(90) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `remark` varchar(140) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=134 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_password_cate`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2014 年 10 月 15 日 15:48
-- 最后检查: 2013 年 11 月 13 日 14:29
--

CREATE TABLE IF NOT EXISTS `Fei_password_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `name` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `all` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_product`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2013 年 11 月 13 日 14:29
-- 最后检查: 2013 年 11 月 13 日 14:29
--

CREATE TABLE IF NOT EXISTS `Fei_product` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `pic` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `id_2` (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_relation_about`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2014 年 10 月 15 日 05:10
-- 最后检查: 2013 年 11 月 13 日 14:29
--

CREATE TABLE IF NOT EXISTS `Fei_relation_about` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_relation_contacter`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2014 年 10 月 15 日 05:10
--

CREATE TABLE IF NOT EXISTS `Fei_relation_contacter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `firstchar` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `infantname` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `birthtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认0农历，1阳历',
  `birthday` date NOT NULL,
  `qq` bigint(22) NOT NULL DEFAULT '0',
  `mobile` bigint(30) NOT NULL DEFAULT '0',
  `weibo` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `email` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `birthplace` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `avatar` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=41 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_rss`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2013 年 11 月 13 日 14:29
-- 最后检查: 2013 年 11 月 13 日 14:29
--

CREATE TABLE IF NOT EXISTS `Fei_rss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_site`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2013 年 11 月 13 日 14:29
--

CREATE TABLE IF NOT EXISTS `Fei_site` (
  `siteid` tinyint(4) NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `domain` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `keywords` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `setting` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `addtime` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_skill`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2013 年 11 月 13 日 14:29
--

CREATE TABLE IF NOT EXISTS `Fei_skill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `level` int(3) NOT NULL,
  `time` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_system`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2013 年 11 月 13 日 14:29
--

CREATE TABLE IF NOT EXISTS `Fei_system` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `branch` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_todo`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2014 年 10 月 14 日 03:15
-- 最后检查: 2013 年 11 月 13 日 14:29
--

CREATE TABLE IF NOT EXISTS `Fei_todo` (
  `doid` int(10) NOT NULL AUTO_INCREMENT,
  `userid` tinyint(10) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `remark` text COLLATE utf8_unicode_ci NOT NULL,
  `startime` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mark` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `level` tinyint(1) NOT NULL,
  `repeats` tinyint(1) DEFAULT NULL,
  `tags` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`doid`),
  KEY `doid` (`doid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=367 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_todo_tags`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2014 年 10 月 15 日 15:39
-- 最后检查: 2013 年 11 月 13 日 14:29
--

CREATE TABLE IF NOT EXISTS `Fei_todo_tags` (
  `tagid` tinyint(8) NOT NULL AUTO_INCREMENT,
  `icon` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `userid` tinyint(15) NOT NULL,
  `total` int(10) NOT NULL,
  `maybe` int(11) NOT NULL,
  PRIMARY KEY (`tagid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=55 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_type`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2013 年 11 月 13 日 14:29
--

CREATE TABLE IF NOT EXISTS `Fei_type` (
  `typeid` tinyint(4) NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `sort` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `isuse` char(4) COLLATE utf8_unicode_ci NOT NULL,
  `addtime` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_user`
--
-- 创建时间: 2014 年 10 月 15 日 17:02
-- 最后更新: 2014 年 10 月 15 日 17:39
--

CREATE TABLE IF NOT EXISTS `Fei_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `realname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `gender` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(26) COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(222) COLLATE utf8_unicode_ci NOT NULL,
  `position` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `phone` int(11) DEFAULT NULL,
  `qq` int(15) DEFAULT NULL,
  `birthday` date NOT NULL,
  `description` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `question` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `answer` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `roleId` tinyint(1) DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=136 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_user_category`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2013 年 11 月 13 日 14:29
--

CREATE TABLE IF NOT EXISTS `Fei_user_category` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `category` tinyint(4) NOT NULL,
  `order` tinyint(4) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_user_role`
--
-- 创建时间: 2014 年 10 月 15 日 17:00
-- 最后更新: 2014 年 10 月 15 日 17:15
--

CREATE TABLE IF NOT EXISTS `Fei_user_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_work`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2014 年 09 月 19 日 11:47
--

CREATE TABLE IF NOT EXISTS `Fei_work` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `startime` date NOT NULL,
  `endtime` date NOT NULL,
  `company` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `position` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `userid` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 表的结构 `Fei_yunpan`
--
-- 创建时间: 2013 年 11 月 13 日 14:29
-- 最后更新: 2013 年 11 月 13 日 14:29
-- 最后检查: 2013 年 11 月 13 日 14:29
--

CREATE TABLE IF NOT EXISTS `Fei_yunpan` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(7) unsigned NOT NULL,
  `name` varchar(256) NOT NULL,
  `content` longblob NOT NULL,
  `size` int(10) unsigned NOT NULL DEFAULT '0',
  `mtime` int(10) unsigned NOT NULL,
  `mime` varchar(256) NOT NULL DEFAULT 'unknown',
  `read` enum('1','0') NOT NULL DEFAULT '1',
  `write` enum('1','0') NOT NULL DEFAULT '1',
  `locked` enum('1','0') NOT NULL DEFAULT '0',
  `hidden` enum('1','0') NOT NULL DEFAULT '0',
  `width` int(5) NOT NULL,
  `height` int(5) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parent_name` (`parent_id`,`name`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=102 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
