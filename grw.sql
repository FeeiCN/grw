SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE DATABASE IF NOT EXISTS `grw` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `grw`;

DROP TABLE IF EXISTS `Fei_acl`;
CREATE TABLE IF NOT EXISTS `Fei_acl` (
`aclid` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `controller` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `acl_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=54 ;

INSERT INTO `Fei_acl` (`aclid`, `name`, `controller`, `action`, `acl_name`) VALUES
(6, '登录', 'Grw', 'login', 'Fei_Any'),
(7, '首页', 'Grw', 'index', 'Fei_Admin'),
(8, '退出', 'Grw', 'logout', 'Fei_Admin'),
(9, '设置', 'setting', 'index', 'Fei_Admin'),
(10, '权限设置', 'setting', 'permission', 'Fei_Admin'),
(11, '权限SET', 'setting', 'permission_set', 'Fei_Admin'),
(12, '后台菜单', 'setting', 'menu', 'Fei_Admin'),
(13, '产品项目', 'project', 'index', 'Fei_Admin'),
(14, '预览中心', 'project', 'main', 'Fei_Admin'),
(15, '栏目设置', 'setting', 'category', 'Fei_Admin'),
(16, '数据备份', 'setting', 'databack', 'Fei_Admin'),
(17, '个人中心', 'Grw', 'profile', 'Fei_Admin'),
(18, '修改密码', 'Grw', 'editpwd', 'Fei_Admin'),
(19, '我的消息', 'Grw', 'message', 'Fei_Admin'),
(20, '任务计划', 'project', 'task', 'Fei_Admin'),
(21, '团队协作', 'cooperation', 'index', 'Fei_Admin'),
(22, '团队成员', 'cooperation', 'person', 'Fei_Admin'),
(23, '部门设置', 'cooperation', 'branch', 'Fei_Admin'),
(24, 'AJAX数据备份', 'setting', 'ajax_databack', 'Fei_Admin'),
(25, 'GRWMAIN', 'grw', 'main', 'Fei_Admin'),
(30, '个人信息', 'profile', 'index', 'Fei_Admin'),
(31, '今日待办', 'timegoal', 'today', 'Fei_Admin'),
(32, '将来或许', 'timegoal', 'maybe', 'Fei_Admin'),
(33, '收集汇总', 'timegoal', 'collect', 'Fei_Admin'),
(34, '回顾', 'timegoal', 'review', 'Fei_Admin'),
(35, '预览', 'money', 'review', 'Fei_Admin'),
(36, '收藏夹', 'favorite', 'index', 'Fei_Admin'),
(37, '通讯录', 'relation', 'contacter', 'Fei_Admin'),
(38, '关注新闻', 'news', 'index', 'Fei_Admin'),
(39, '模板大全', 'profile', 'themes', 'Fei_Admin'),
(40, '作品案例', 'profile', 'product', 'Fei_Admin'),
(41, '日记本', 'note', 'index', 'Fei_Admin'),
(42, '将来获取', 'timegoal', 'maybe', 'Fei_Admin'),
(43, '固定提醒', 'timegoal', 'fixed', 'Fei_Admin'),
(44, '提示信息', 'Grw', 'alert', 'Fei_Admin'),
(45, '功能模块', 'setting', 'module', 'Fei_Admin'),
(46, '密码保险箱', 'password', 'index', 'Fei_Admin'),
(47, '云盘', 'yunpan', 'index', 'Fei_Admin'),
(48, '云盘操作', 'yunpan', 'api', 'Fei_Admin'),
(49, '借款欠款', 'money', 'deposit', 'Fei_Admin'),
(50, '银行卡', 'money', 'bank', 'Fei_Admin'),
(51, '固定资产', 'money', 'assets', 'Fei_Admin'),
(52, '获取天气', 'Grw', 'weather', 'Fei_Admin'),
(53, 'api', 'api', 'index', 'Fei_Any');

DROP TABLE IF EXISTS `Fei_category`;
CREATE TABLE IF NOT EXISTS `Fei_category` (
`catid` tinyint(4) NOT NULL,
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
  `addtime` datetime NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=72 ;

INSERT INTO `Fei_category` (`catid`, `parentid`, `uid`, `catname`, `remark`, `url`, `setting`, `listorder`, `ismenu`, `letter`, `ico`, `addtime`) VALUES
(47, 0, 1, '时间目标', '时间目标管理是一件时间目标管理是一件时间目标管理是一件时间目标管理是一件时间目标管理是一件时间目标管理是一件时间目标管理是一件时', '', '', 3, 1, 'timegoal', 'alarm-clock-select', '0000-00-00 00:00:00'),
(5, 0, 1, '设置', '', '', '', 10, 0, 'setting', 'switch', '0000-00-00 00:00:00'),
(9, 7, 1, '个人信息', '', '', '', 1, 1, 'index', 'icon-user', '0000-00-00 00:00:00'),
(31, 0, 1, '控制面板', '', '', '', 0, 1, 'Grw', 'dashboard', '0000-00-00 00:00:00'),
(21, 5, 1, '后台菜单', '', '', '', 2, 1, 'category', 'icon-sitemap', '0000-00-00 00:00:00'),
(22, 5, 1, '常规设置', '', '', '', 1, 1, 'index', 'icon-tasks', '0000-00-00 00:00:00'),
(23, 7, 1, '我的消息', '', '', '', 2, 0, 'message', 'icon-comments', '0000-00-00 00:00:00'),
(25, 5, 1, '功能权限', '', '', '', 3, 1, 'permission', 'icon-th', '0000-00-00 00:00:00'),
(26, 5, 1, '数据备份', '', '', '', 4, 1, 'databack', 'icon-download-alt', '0000-00-00 00:00:00'),
(28, 27, 1, '发现BUG', '', '', '', 1, 1, 'findbug', '', '0000-00-00 00:00:00'),
(29, 27, 1, '发布公告', '', '', '', 2, 1, 'issue', '', '0000-00-00 00:00:00'),
(30, 18, 1, '部门设置', '', '', '', 3, 1, 'branch', '', '0000-00-00 00:00:00'),
(48, 47, 1, '今日待办', '', '', '', 1, 1, 'today', 'icon-check', '0000-00-00 00:00:00'),
(52, 47, 1, '收集汇总', '', '', '', 5, 1, 'collect', 'icon-inbox', '0000-00-00 00:00:00'),
(53, 0, 1, '收藏夹', '', '', '', 6, 0, 'favorite', 'bookmarks', '0000-00-00 00:00:00'),
(55, 54, 1, '预览', '', '', '', 1, 1, 'review', 'icon-bar-chart', '0000-00-00 00:00:00'),
(56, 0, 1, '密码保险箱', '', '', '', 7, 1, 'password', 'key-solid', '0000-00-00 00:00:00'),
(7, 0, 1, '个人网', '', '', '', 1, 1, 'profile', 'card-address', '0000-00-00 00:00:00'),
(57, 0, 1, '人际关系', '', '', '', 7, 0, 'relation', 'users', '0000-00-00 00:00:00'),
(58, 57, 1, '通讯录', '', '', '', 1, 1, 'contacter', 'icon-list-alt', '0000-00-00 00:00:00'),
(60, 7, 1, '模板大全', '', '', '', 4, 0, 'themes', 'icon-th', '0000-00-00 00:00:00'),
(61, 7, 1, '我的作品', '', '', '', 3, 0, 'product', 'icon-folder-open', '0000-00-00 00:00:00'),
(64, 47, 1, '将来或许', '', '', '', 2, 1, 'maybe', 'icon-road', '0000-00-00 00:00:00'),
(65, 47, 1, '固定提醒', '', '', '', 3, 1, 'fixed', 'icon-time', '0000-00-00 00:00:00'),
(66, 5, 0, '功能模块', '', '', '', 2, 1, 'module', 'icon-tasks', '0000-00-00 00:00:00'),
(67, 0, 0, '云盘', '', '', '', 9, 0, 'yunpan', 'server-cloud', '0000-00-00 00:00:00'),
(68, 54, 0, '借款欠款', '', '', '', 2, 1, 'deposit', 'icon-external-link', '0000-00-00 00:00:00'),
(69, 54, 0, '银行卡', '', '', '', 3, 1, 'bank', 'icon-credit-card', '0000-00-00 00:00:00'),
(70, 54, 0, '固定资产', '', '', '', 4, 1, 'assets', 'icon-home', '0000-00-00 00:00:00'),
(54, 0, 0, '我的钱包', '', '', '', 8, 0, 'money', 'wallet', '0000-00-00 00:00:00');

DROP TABLE IF EXISTS `Fei_contact`;
CREATE TABLE IF NOT EXISTS `Fei_contact` (
`id` tinyint(4) NOT NULL,
  `company` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `linkman` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `phone` tinyint(4) NOT NULL,
  `tel` tinyint(4) NOT NULL,
  `fax` tinyint(4) NOT NULL,
  `address` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

DROP TABLE IF EXISTS `Fei_education`;
CREATE TABLE IF NOT EXISTS `Fei_education` (
`id` int(15) NOT NULL,
  `school` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(1) NOT NULL,
  `startime` date NOT NULL,
  `endtime` date NOT NULL,
  `userid` int(15) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

DROP TABLE IF EXISTS `Fei_festival`;
CREATE TABLE IF NOT EXISTS `Fei_festival` (
`fid` int(4) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=160 ;

INSERT INTO `Fei_festival` (`fid`, `name`, `date`) VALUES
(1, '新年元旦', '2013-01-01 00:00:00'),
(2, '元宵节', '2013-01-15 00:00:00'),
(3, '腊八节', '2013-01-26 00:00:00'),
(4, '小年', '2013-02-11 00:00:00'),
(5, '情人节', '2013-02-14 00:00:00'),
(6, '除夕', '2013-02-17 00:00:00'),
(7, '春节', '2013-02-18 00:00:00'),
(8, '元宵节', '2013-03-04 00:00:00'),
(9, '全国爱耳日', '2013-03-03 00:00:00'),
(10, '学习雷锋纪念日', '2013-03-05 00:00:00'),
(11, '国际劳动妇女节', '2013-03-08 00:00:00'),
(12, '国际尊严尊敬日', '2013-03-11 00:00:00'),
(13, '中国植树节', '2013-03-12 00:00:00'),
(14, '国际警察日', '2013-03-14 00:00:00'),
(15, '国际消费者权益日', '2013-03-15 00:00:00'),
(16, '手拉手情系贫困小伙伴全国统一行动日', '2013-03-16 00:00:00'),
(17, '中和节(太阳生日)', '2013-03-19 00:00:00'),
(18, '龙抬头节', '2013-03-20 00:00:00'),
(19, '世界森林日', '2013-03-21 00:00:00'),
(20, '世界儿歌日', '2013-03-21 00:00:00'),
(21, '国际消除种族歧视日', '2013-03-21 00:00:00'),
(22, '世界睡眠日', '2013-03-21 00:00:00'),
(23, '世界水日', '2013-03-22 00:00:00'),
(24, '世界气象日', '2013-03-23 00:00:00'),
(25, '世界防治结核病日', '2013-03-24 00:00:00'),
(26, '中小学生安全教育日', '2013-03-25 00:00:00'),
(27, '春社日', '2013-03-25 00:00:00'),
(28, '花朝节(花神节)', '2013-03-30 00:00:00'),
(29, '耶稣受难日', '2013-03-30 00:00:00'),
(30, '国际愚人节', '2013-04-01 00:00:00'),
(31, '寒食节', '2013-04-04 00:00:00'),
(32, '清明节', '2013-04-05 00:00:00'),
(33, '复活节', '2013-04-06 00:00:00'),
(34, '观音菩萨生日', '2013-04-06 00:00:00'),
(35, '世界卫生日', '2013-04-07 00:00:00'),
(36, '世界帕金森病日', '2013-04-11 00:00:00'),
(37, '傣族泼水节', '2013-04-13 00:00:00'),
(38, '上巳节（女儿节）', '2013-04-19 00:00:00'),
(39, '全国企业家活动日', '2013-04-21 00:00:00'),
(40, '世界地球日', '2013-04-22 00:00:00'),
(41, '世界儿童日', '2013-04-22 00:00:00'),
(42, '世界图书和版权日', '2013-04-23 00:00:00'),
(43, '全国预防接种宣传节日', '2013-04-25 00:00:00'),
(44, '国际秘书日', '2013-04-26 00:00:00'),
(45, '世界知识产权日', '2013-04-26 00:00:00'),
(46, '国际劳动节', '2013-05-01 00:00:00'),
(47, '中国青年节', '2013-05-04 00:00:00'),
(48, '全国碘缺乏病宣传日', '2013-05-05 00:00:00'),
(49, '全国爱眼日', '2013-05-05 00:00:00'),
(50, '世界红十字日', '2013-05-08 00:00:00'),
(51, '世界哮喘日', '2013-05-08 00:00:00'),
(52, '国际护士节', '2013-05-12 00:00:00'),
(53, '母亲节', '2013-05-13 00:00:00'),
(54, '国际家庭日', '2013-05-15 00:00:00'),
(55, '国际牛奶日', '2013-05-15 00:00:00'),
(56, '世界电信日', '2013-05-17 00:00:00'),
(57, '国际博物馆日', '2013-05-18 00:00:00'),
(58, '全国助残日', '2013-05-20 00:00:00'),
(59, '中国学生营养日', '2013-05-20 00:00:00'),
(60, '全国母乳喂养宣传日', '2013-05-20 00:00:00'),
(61, '国际生物多样性日', '2013-05-22 00:00:00'),
(62, '佛诞节', '2013-05-24 00:00:00'),
(63, '世界无烟日', '2013-05-31 00:00:00'),
(64, '国际儿童节', '2013-06-01 00:00:00'),
(65, '世界环境日', '2013-06-05 00:00:00'),
(66, '全国爱眼日', '2013-06-06 00:00:00'),
(67, '中国文化遗产日', '2013-06-09 00:00:00'),
(68, '世界防治荒漠化和干旱日', '2013-06-17 00:00:00'),
(69, '父亲节', '2013-06-17 00:00:00'),
(70, '端午节', '2013-06-19 00:00:00'),
(71, '世界难民日', '2013-06-20 00:00:00'),
(72, '国际奥林匹克日', '2013-06-23 00:00:00'),
(73, '全国土地日', '2013-06-25 00:00:00'),
(74, '国际禁毒日', '2013-06-26 00:00:00'),
(75, '联合国宪章日', '2013-06-26 00:00:00'),
(76, '香港回归纪念日', '2013-07-01 00:00:00'),
(77, '中共建党节', '2013-07-01 00:00:00'),
(78, '国际合作社日', '2013-07-07 00:00:00'),
(79, '中国人民抗日战争纪念日', '2013-07-07 00:00:00'),
(80, '世界人口日', '2013-07-11 00:00:00'),
(81, '世界海事日', '2013-07-11 00:00:00'),
(82, '世界语(言)创立日', '2013-07-26 00:00:00'),
(83, '观音成道日', '2013-08-01 00:00:00'),
(84, '中国人民解放军建军节', '2013-08-01 00:00:00'),
(85, '哈尼族苦扎扎节', '2013-08-06 00:00:00'),
(86, '观莲节(莲花生日)', '2013-08-06 00:00:00'),
(87, '苗族吃新节', '2013-08-06 00:00:00'),
(88, '火把节', '2013-08-06 00:00:00'),
(89, '七夕情人节', '2013-08-19 00:00:00'),
(90, '中元节（鬼节）', '2013-08-27 00:00:00'),
(91, '抗日战争胜利纪念日', '2013-09-03 00:00:00'),
(92, '国际扫盲日', '2013-09-08 00:00:00'),
(93, '中国教师节', '2013-09-10 00:00:00'),
(94, '地藏节', '2013-09-10 00:00:00'),
(95, '世界预防自杀日', '2013-09-10 00:00:00'),
(96, '国际臭氧层保护日', '2013-09-16 00:00:00'),
(97, '中国国耻日', '2013-09-18 00:00:00'),
(98, '全国爱牙日', '2013-09-20 00:00:00'),
(99, '全国公民道德宣传日', '2013-09-20 00:00:00'),
(100, '国际和平日', '2013-09-21 00:00:00'),
(101, '无车日', '2013-09-22 00:00:00'),
(102, '中秋节', '2013-09-25 00:00:00'),
(103, '世界旅游日', '2013-09-27 00:00:00'),
(104, '世界教师节(孔子诞辰)', '2013-09-28 00:00:00'),
(105, '国际聋人节（最后一个星期日）', '2013-09-30 00:00:00'),
(106, '世界建筑日（第一个星期一）', '2013-10-01 00:00:00'),
(107, '国际住房日(人居日)（第一个星期一）', '2013-10-01 00:00:00'),
(108, '国庆节', '2013-10-01 00:00:00'),
(109, '国际老人节', '2013-10-01 00:00:00'),
(110, '世界动物日', '2013-10-04 00:00:00'),
(111, '全国高血压日', '2013-10-08 00:00:00'),
(112, '世界邮政日', '2013-10-09 00:00:00'),
(113, '国际减灾日（第二个星期三）', '2013-10-10 00:00:00'),
(114, '世界精神卫生日', '2013-10-10 00:00:00'),
(115, '世界视觉日（第二个星期四）', '2013-10-11 00:00:00'),
(116, '世界保健日', '2013-10-13 00:00:00'),
(117, '中国少年先锋队诞辰日', '2013-10-13 00:00:00'),
(118, '国际音乐节（10月中旬）', '2013-10-14 00:00:00'),
(119, '世界标准日', '2013-10-14 00:00:00'),
(120, '国际盲人节', '2013-10-15 00:00:00'),
(121, '世界粮食日', '2013-10-16 00:00:00'),
(122, '国际消除贫困日', '2013-10-17 00:00:00'),
(123, '重阳节（敬老节）', '2013-10-18 00:00:00'),
(124, '世界传统医药日', '2013-10-18 00:00:00'),
(125, '联合国日', '2013-10-24 00:00:00'),
(126, '全国男性健康日', '2013-10-28 00:00:00'),
(127, '观音出家日', '2013-10-29 00:00:00'),
(128, '重阳节（敬老节）', '2013-10-18 00:00:00'),
(129, '世界传统医药日', '2013-10-18 00:00:00'),
(130, '联合国日', '2013-10-24 00:00:00'),
(131, '全国男性健康日', '2013-10-28 00:00:00'),
(132, '观音出家日', '2013-10-29 00:00:00'),
(133, '万圣节', '2013-10-31 00:00:00'),
(134, '十月革命纪念日', '2013-11-07 00:00:00'),
(135, '中国记者节', '2013-11-08 00:00:00'),
(136, '中国消防宣传日', '2013-01-09 00:00:00'),
(137, '世界青年节', '2013-11-10 00:00:00'),
(138, '祭祖节', '2013-11-10 00:00:00'),
(139, '光棍节', '2013-11-11 00:00:00'),
(140, '国际科学与和平周（11日所属一周）', '2013-11-11 00:00:00'),
(141, '世界糖尿病日', '2013-11-14 00:00:00'),
(142, '国际大学生节', '2013-11-17 00:00:00'),
(143, '感恩节（第四个星期四）', '2013-11-22 00:00:00'),
(144, '国际消除对妇女的暴力日', '2013-11-25 00:00:00'),
(145, '世界艾滋病日', '2013-12-01 00:00:00'),
(146, '国际残疾人日', '2013-12-03 00:00:00'),
(147, '中国法制宣传日', '2013-12-04 00:00:00'),
(148, '国际志愿人员日', '2013-12-05 00:00:00'),
(149, '国际民航日', '2013-12-07 00:00:00'),
(150, '世界足球日', '2013-12-09 00:00:00'),
(151, '国际儿童广播电视日（第二个星期日）', '2013-12-09 00:00:00'),
(152, '世界人权日', '2013-12-10 00:00:00'),
(153, '南京大屠杀纪念日', '2013-12-13 00:00:00'),
(154, '澳门回归纪念日', '2013-12-20 00:00:00'),
(155, '阔时节', '2013-12-20 00:00:00'),
(156, '国际篮球日', '2013-12-21 00:00:00'),
(157, '冬至节', '2013-12-22 00:00:00'),
(158, '平安夜', '2013-12-24 00:00:00'),
(159, '圣诞节', '2013-12-25 00:00:00');

DROP TABLE IF EXISTS `Fei_follow`;
CREATE TABLE IF NOT EXISTS `Fei_follow` (
`id` int(15) NOT NULL,
  `icon` int(1) NOT NULL,
  `link` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(15) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

DROP TABLE IF EXISTS `Fei_money`;
CREATE TABLE IF NOT EXISTS `Fei_money` (
`id` int(15) NOT NULL,
  `action` tinyint(1) NOT NULL,
  `money` int(15) NOT NULL,
  `mark` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `userid` int(15) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

DROP TABLE IF EXISTS `Fei_money_assets`;
CREATE TABLE IF NOT EXISTS `Fei_money_assets` (
`id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `sum` int(11) NOT NULL,
  `time` date NOT NULL,
  `remark` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

DROP TABLE IF EXISTS `Fei_money_bank`;
CREATE TABLE IF NOT EXISTS `Fei_money_bank` (
`id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `title` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '0信用卡|1储蓄卡',
  `num` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `reserve` float NOT NULL COMMENT '余额',
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

DROP TABLE IF EXISTS `Fei_money_bank_record`;
CREATE TABLE IF NOT EXISTS `Fei_money_bank_record` (
`id` int(11) NOT NULL,
  `cz` tinyint(1) NOT NULL,
  `bankid` tinyint(2) NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sum` int(11) NOT NULL,
  `address` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `remark` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `uid` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

DROP TABLE IF EXISTS `Fei_money_deposit`;
CREATE TABLE IF NOT EXISTS `Fei_money_deposit` (
`id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '0借出1借进',
  `user` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `sum` int(11) NOT NULL,
  `stime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `period` int(4) NOT NULL COMMENT '天',
  `remark` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `uid` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

DROP TABLE IF EXISTS `Fei_note`;
CREATE TABLE IF NOT EXISTS `Fei_note` (
`id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `title` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `keywords` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=23 ;

DROP TABLE IF EXISTS `Fei_password`;
CREATE TABLE IF NOT EXISTS `Fei_password` (
`id` int(11) NOT NULL,
  `catid` tinyint(4) NOT NULL,
  `uid` int(11) NOT NULL,
  `title` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(90) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `remark` varchar(140) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=134 ;

DROP TABLE IF EXISTS `Fei_password_cate`;
CREATE TABLE IF NOT EXISTS `Fei_password_cate` (
`id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `all` tinyint(1) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=24 ;

DROP TABLE IF EXISTS `Fei_product`;
CREATE TABLE IF NOT EXISTS `Fei_product` (
`id` int(15) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `pic` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(15) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

DROP TABLE IF EXISTS `Fei_relation_about`;
CREATE TABLE IF NOT EXISTS `Fei_relation_about` (
`id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(70) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

DROP TABLE IF EXISTS `Fei_relation_contacter`;
CREATE TABLE IF NOT EXISTS `Fei_relation_contacter` (
`id` int(11) NOT NULL,
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
  `avatar` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=41 ;

DROP TABLE IF EXISTS `Fei_rss`;
CREATE TABLE IF NOT EXISTS `Fei_rss` (
`id` int(11) NOT NULL,
  `url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `Fei_site`;
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

DROP TABLE IF EXISTS `Fei_skill`;
CREATE TABLE IF NOT EXISTS `Fei_skill` (
`id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `level` int(3) NOT NULL,
  `time` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

DROP TABLE IF EXISTS `Fei_system`;
CREATE TABLE IF NOT EXISTS `Fei_system` (
`id` tinyint(4) NOT NULL,
  `title` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `branch` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

DROP TABLE IF EXISTS `Fei_todo`;
CREATE TABLE IF NOT EXISTS `Fei_todo` (
`doid` int(10) NOT NULL,
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
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=367 ;

DROP TABLE IF EXISTS `Fei_todo_tags`;
CREATE TABLE IF NOT EXISTS `Fei_todo_tags` (
`tagid` tinyint(8) NOT NULL,
  `icon` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `userid` tinyint(15) NOT NULL,
  `total` int(10) NOT NULL,
  `maybe` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=55 ;

DROP TABLE IF EXISTS `Fei_type`;
CREATE TABLE IF NOT EXISTS `Fei_type` (
  `typeid` tinyint(4) NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `sort` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `isuse` char(4) COLLATE utf8_unicode_ci NOT NULL,
  `addtime` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `Fei_user`;
CREATE TABLE IF NOT EXISTS `Fei_user` (
`id` int(11) NOT NULL,
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
  `status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=137 ;

INSERT INTO `Fei_user` (`id`, `openid`, `url`, `username`, `password`, `realname`, `gender`, `email`, `avatar`, `position`, `address`, `phone`, `qq`, `birthday`, `description`, `question`, `answer`, `roleId`, `status`) VALUES
(136, '052DCBF9B4095CF3D7CEEC4F53ECF138', '', '', '', 'Feei', '男', '', 'http://q.qlogo.cn/qqapp/100457409/052DCBF9B4095CF3D7CEEC4F53ECF138/100', '', '', NULL, NULL, '0000-00-00', '', '', '', 0, 1);

DROP TABLE IF EXISTS `Fei_user_category`;
CREATE TABLE IF NOT EXISTS `Fei_user_category` (
`uid` int(11) NOT NULL,
  `category` tinyint(4) NOT NULL,
  `order` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Fei_user_role`;
CREATE TABLE IF NOT EXISTS `Fei_user_role` (
`id` int(11) unsigned NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

INSERT INTO `Fei_user_role` (`id`, `name`) VALUES
(5, '创始人'),
(4, '核心开发者'),
(3, '开发者'),
(2, '特约体验'),
(1, '普通用户');

DROP TABLE IF EXISTS `Fei_work`;
CREATE TABLE IF NOT EXISTS `Fei_work` (
`id` int(15) NOT NULL,
  `startime` date NOT NULL,
  `endtime` date NOT NULL,
  `company` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `position` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `userid` int NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

DROP TABLE IF EXISTS `Fei_yunpan`;
CREATE TABLE IF NOT EXISTS `Fei_yunpan` (
`id` int(7) unsigned NOT NULL,
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
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=102 ;


ALTER TABLE `Fei_acl`
 ADD PRIMARY KEY (`aclid`);

ALTER TABLE `Fei_category`
 ADD PRIMARY KEY (`catid`), ADD KEY `parentid` (`parentid`);

ALTER TABLE `Fei_contact`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `Fei_education`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `Fei_festival`
 ADD PRIMARY KEY (`fid`);

ALTER TABLE `Fei_follow`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`), ADD KEY `userid` (`userid`), ADD KEY `userid_2` (`userid`);

ALTER TABLE `Fei_money`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`), ADD KEY `id_2` (`id`);

ALTER TABLE `Fei_money_assets`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `Fei_money_bank`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`);

ALTER TABLE `Fei_money_bank_record`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `Fei_money_deposit`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `Fei_note`
 ADD PRIMARY KEY (`id`), ADD KEY `userid` (`userid`);

ALTER TABLE `Fei_password`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `Fei_password_cate`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`);

ALTER TABLE `Fei_product`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`), ADD KEY `id_2` (`id`), ADD KEY `userid` (`userid`);

ALTER TABLE `Fei_relation_about`
 ADD PRIMARY KEY (`id`), ADD KEY `uid` (`uid`);

ALTER TABLE `Fei_relation_contacter`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `Fei_rss`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`);

ALTER TABLE `Fei_skill`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `Fei_system`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `Fei_todo`
 ADD PRIMARY KEY (`doid`), ADD KEY `doid` (`doid`);

ALTER TABLE `Fei_todo_tags`
 ADD PRIMARY KEY (`tagid`), ADD KEY `tagid` (`tagid`);

ALTER TABLE `Fei_user`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `Fei_user_category`
 ADD PRIMARY KEY (`uid`);

ALTER TABLE `Fei_user_role`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `Fei_work`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `Fei_yunpan`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `parent_name` (`parent_id`,`name`), ADD KEY `parent_id` (`parent_id`);


ALTER TABLE `Fei_acl`
MODIFY `aclid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=54;
ALTER TABLE `Fei_category`
MODIFY `catid` tinyint(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=72;
ALTER TABLE `Fei_contact`
MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `Fei_education`
MODIFY `id` int(15) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
ALTER TABLE `Fei_festival`
MODIFY `fid` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=160;
ALTER TABLE `Fei_follow`
MODIFY `id` int(15) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
ALTER TABLE `Fei_money`
MODIFY `id` int(15) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
ALTER TABLE `Fei_money_assets`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
ALTER TABLE `Fei_money_bank`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
ALTER TABLE `Fei_money_bank_record`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
ALTER TABLE `Fei_money_deposit`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
ALTER TABLE `Fei_note`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
ALTER TABLE `Fei_password`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=134;
ALTER TABLE `Fei_password_cate`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
ALTER TABLE `Fei_product`
MODIFY `id` int(15) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
ALTER TABLE `Fei_relation_about`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
ALTER TABLE `Fei_relation_contacter`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=41;
ALTER TABLE `Fei_rss`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
ALTER TABLE `Fei_skill`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
ALTER TABLE `Fei_system`
MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `Fei_todo`
MODIFY `doid` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=367;
ALTER TABLE `Fei_todo_tags`
MODIFY `tagid` tinyint(8) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=55;
ALTER TABLE `Fei_user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=137;
ALTER TABLE `Fei_user_category`
MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `Fei_user_role`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
ALTER TABLE `Fei_work`
MODIFY `id` int(15) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
ALTER TABLE `Fei_yunpan`
MODIFY `id` int(7) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=102;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
