-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2018-01-02 08:34:10
-- 服务器版本： 5.7.9
-- PHP Version: 5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- 表的结构 `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `article_id` int(11) NOT NULL COMMENT '文章ID号',
  `title` char(40) NOT NULL DEFAULT '无题' COMMENT '标题',
  `link` char(100) DEFAULT NULL,
  `user` char(20) NOT NULL DEFAULT '天边' COMMENT '用户',
  `content` text COMMENT '内容',
  `head_pic` varchar(200) NOT NULL DEFAULT '/blog/img/title/default.png' COMMENT '文章列表显示的默认头图',
  `class` char(20) NOT NULL DEFAULT '未分类' COMMENT '分类',
  `keywords` char(50) DEFAULT NULL,
  `pub_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发表时间',
  `click` int(11) NOT NULL DEFAULT '0' COMMENT '点击数',
  `comment` int(10) NOT NULL DEFAULT '0',
  `fav` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `article`
--

INSERT INTO `article` (`article_id`, `title`, `link`, `user`, `content`, `head_pic`, `class`, `keywords`, `pub_time`, `click`, `comment`, `fav`) VALUES
(2016070201, '新测试', '/blog/page/2016/07/2016070201.html', '天边', '<p>这是又一篇<span>测试</span>文章</p><pre>int&nbsp;a=b,b=c;</pre><p><br/></p>', '/blog/img/title/default.png', '测试', '测试', '2016-07-02 02:15:04', 14, 3, 16),
(2016070202, '英文命名测试', '/blog/page/2016/07/untitled.html', '天边', '<p>这是一篇测试。</p>', '/blog/img/title/default.png', '测试', '测试', '2016-07-02 02:27:55', 1, 0, 0),
(2016070203, '新测试 ver2.0', '/blog/page/2016/07/2016070203.html', '天边', '<p>&nbsp;</p><pre class="brush:css;toolbar:false;">div#head&nbsp;{\r\n&nbsp;&nbsp;&nbsp;&nbsp;padding:&nbsp;20px&nbsp;0&nbsp;30px;\r\n&nbsp;&nbsp;&nbsp;&nbsp;text-align:&nbsp;center;\r\n&nbsp;&nbsp;&nbsp;&nbsp;background-color:&nbsp;#28282E;\r\n&nbsp;&nbsp;&nbsp;&nbsp;color:&nbsp;#fff;\r\n}</pre><p>&nbsp;</p>', '/blog/img/title/default.png', '测试', '测试', '2016-07-02 05:58:54', 3, 0, 1),
(2016070204, '新测试2222222', '/blog/page/2016/07/2016070204.html', '天边', '<p>这是第一篇经过编辑更新的测试文章</p><pre>int&nbsp;a=b,b=c;</pre><p>2222222222222222222222222222222222222222222</p>', '/blog/img/title/default.png', '测试', '测试', '2016-07-02 05:59:21', 1, 0, 0),
(2016070401, '!', '/blog/page/2016/07/!!.html', '天边', '<p>!</p>', '/blog/img/title/default.png', '!', '!', '2016-07-04 12:34:59', 1, 0, 0),
(2016070801, '测试代码高亮功能', '/blog/page/2016/07/code_highlight.html', '天边', '<p>&nbsp; 测试CSS，JS，Html代码高亮：</p><pre class="brush:css;toolbar:false;">#container&nbsp;{\r\n&nbsp;&nbsp;&nbsp;&nbsp;position:&nbsp;relative;\r\n&nbsp;&nbsp;&nbsp;&nbsp;width:&nbsp;1130px;\r\n&nbsp;&nbsp;&nbsp;&nbsp;left:&nbsp;200px;\r\n&nbsp;&nbsp;&nbsp;&nbsp;border-radius:&nbsp;5px;\r\n&nbsp;&nbsp;&nbsp;&nbsp;-webkit-transition:&nbsp;-webkit-transform&nbsp;1s;\r\n&nbsp;&nbsp;&nbsp;&nbsp;transition:&nbsp;transform&nbsp;1s\r\n}</pre><pre class="brush:js;toolbar:false">jQuery(document).ready(function($)&nbsp;{\r\n	$(&quot;form&quot;).submit(function(event)&nbsp;{\r\n		event.preventDefault();\r\n		$user=&nbsp;$(&quot;input#user&quot;).val();\r\n		$pwd=&nbsp;$(&quot;input#pwd&quot;).val();\r\n	&nbsp;&nbsp;&nbsp;&nbsp;$.ajax({\r\n		&nbsp;&nbsp;&nbsp;&nbsp;url:&nbsp;&#39;control.php?action=login&#39;,\r\n		&nbsp;&nbsp;&nbsp;&nbsp;type:&nbsp;&#39;post&#39;,\r\n		&nbsp;&nbsp;&nbsp;&nbsp;data:&nbsp;{user:&nbsp;$user,pwd:&nbsp;$pwd},\r\n		&nbsp;&nbsp;&nbsp;&nbsp;success:function(msg){\r\n			if(msg==&quot;登陆成功！&quot;){\r\n			&nbsp;	window.location.href=&quot;index.php&quot;;\r\n			}else{\r\n				$(&quot;div#msg&nbsp;p&quot;).html(msg);\r\n			}\r\n		}\r\n	});\r\n	return&nbsp;false;\r\n	});\r\n});</pre><pre class="brush:html;toolbar:false">&nbsp;&lt;div&nbsp;id=&quot;header&quot;&gt;\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;div&nbsp;id=&quot;content&quot;&gt;\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;img&nbsp;src=&quot;/blog/img/avatar/skyside.png&quot;&nbsp;alt=&quot;&quot;&nbsp;/&gt;\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&gt;后台登陆&lt;/p&gt;\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/div&gt;\r\n&nbsp;&nbsp;&nbsp;&nbsp;&lt;/div&gt;</pre><p>&nbsp; &nbsp;测试结束。</p>', '/blog/img/title/default.png', '测试', '测试 代码高亮', '2016-07-08 17:38:18', 1416, 21, 87),
(2016072302, '第二次测试静态生成文章', '/blog/page/2016/07/test2.html', '天边', '<p>第二次测试后台静态生成文章。</p><pre class="brush:css;toolbar:false">input{\r\n	margin-right:&nbsp;40px;\r\n}\r\ninput:focus{\r\n	border:&nbsp;2px&nbsp;solid&nbsp;#FF5E53;\r\n	outline:&nbsp;none;\r\n}</pre><p>这里是加上去的内容。</p>', '/blog/img/title/default.png', '测试', '测试', '2016-07-23 07:11:04', 51, 2, 0),
(2016072402, '新样式测试', '/blog/page/2016/07/new_style.html', '天边', '<p>新样式测试。</p><pre class="brush:css;toolbar:false">#article&nbsp;#title&nbsp;{\r\n&nbsp;&nbsp;&nbsp;&nbsp;text-align:&nbsp;center;\r\n}\r\n#article&nbsp;#title,&nbsp;#article&nbsp;#content{\r\n&nbsp;&nbsp;&nbsp;&nbsp;padding-bottom:&nbsp;40px;\r\n&nbsp;&nbsp;&nbsp;&nbsp;border-bottom:&nbsp;1px&nbsp;solid&nbsp;#ccc;\r\n}\r\nh1&nbsp;{\r\n&nbsp;&nbsp;&nbsp;&nbsp;color:&nbsp;#6d6d6d;\r\n&nbsp;&nbsp;&nbsp;&nbsp;font-size:&nbsp;40px;\r\n&nbsp;&nbsp;&nbsp;&nbsp;padding:&nbsp;0;\r\n&nbsp;&nbsp;&nbsp;&nbsp;margin-bottom:&nbsp;25px;\r\n}\r\n#post-info&nbsp;a&nbsp;{\r\n&nbsp;&nbsp;&nbsp;&nbsp;color:&nbsp;#D93641;\r\n}</pre><p><br/></p>', '/blog/img/title/default.png', '测试', '测试 代码高亮', '2016-07-24 17:10:39', 248, 4, 10),
(2016081401, 'test', '/blog/page/2016/08/test.html', '天边', '<p>测试新功能。</p>', '/blog/img/title/default.png', '测试', '测试', '2016-08-14 10:12:16', 11, 0, 0),
(2016081501, '测试文章', '/blog/page/2016/08/test2.html', '天边', '<p>测测测测测测测测测测测测测测测测测测</p>', '/blog/img/title/default.png', '测试', '标签4 标签6 测试', '2016-08-15 07:06:43', 21, 0, 2),
(2016081502, '新测试文章', '/blog/page/2016/08/new_test.html', '天边', '<p>新测试文章新测试文章新测试文章新测试文章新测试文章</p>', '/blog/img/title/default.png', '测试', '测试', '2016-08-15 07:09:05', 3, 0, 0),
(2016081503, '测试图片上传', '/blog/page/2016/08/pic_upload.html', '天边', '', '/blog/img/upload/20160815/__.png', '测试', '测试 图片上传', '2016-08-15 10:09:21', 8, 0, 0),
(2016081504, '成功的一次头图上传', '/blog/page/2016/08/x.html', '天边', '', '/blog/img/upload/20160815/roadways.png', '测试', '图片上传 测试', '2016-08-15 16:16:09', 17, 0, 0),
(2016081505, '文章头图上传功能成功啦', '/blog/page/2016/08/head_pic_upload.html', '天边', '<p style="text-align: center;">决定放一张冬眠君给予奖励，啊哈哈~~~<img src="/blog/img/upload/20160816/1471277915726715.jpg" title="1471277915726715.jpg" alt="788ac5cdjw1f2yvknm03oj216o0qo7eo.jpg"/></p>', '/blog/img/upload/20160815/01b70055658d090000008ec9dbb50c.jpg', '测试', '图片上传 测试', '2016-08-15 16:18:54', 35, 1, 2),
(2016081602, '今天8月16', '/blog/page/2016/08/0816.html', '天边', '<p>今天8月16</p><pre class="brush:cpp;toolbar:false;">sweqw</pre><p><br/></p>', '/blog/img/upload/20160816/others.png', '生活', '生活', '2016-08-15 17:05:02', 112, 4, 2);

-- --------------------------------------------------------

--
-- 表的结构 `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `article_id` int(11) NOT NULL COMMENT '文章ID号',
  `comment_id` int(40) NOT NULL AUTO_INCREMENT,
  `parent` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `children` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  `content` text CHARACTER SET utf8 NOT NULL COMMENT '评论内容',
  `user` char(20) CHARACTER SET utf8 NOT NULL COMMENT '用户',
  `avatar` varchar(100) COLLATE utf8_bin NOT NULL,
  `pub_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `have_read` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=299 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- 转存表中的数据 `comment`
--

INSERT INTO `comment` (`article_id`, `comment_id`, `parent`, `children`, `content`, `user`, `avatar`, `pub_time`, `have_read`) VALUES
(2016070801, 10, NULL, '', '奇迹在天边。', '刘明皓', '/blog/img/avatar/7.jpg', '2016-07-15 11:18:45', 1),
(2016070801, 12, NULL, '21,22,33,34,43,51,52,', '我喜欢阿心', '小飞飞', '/blog/img/avatar/7.jpg', '2016-07-15 14:29:55', 1),
(2016070801, 21, '12', '', '回复&lt;a href=&#039;#comment12&#039;&gt;&lt;span&gt;@小飞飞&lt;/span&gt;&lt;/a&gt;：我也喜欢你，我们在一起吧，会幸福的。', '阿心', '/blog/img/avatar/7.jpg', '2016-07-16 10:41:09', 1),
(2016070801, 22, '12', '', '回复&lt;a href=&#039;#comment21&#039;&gt;&lt;span&gt;@阿心&lt;/span&gt;&lt;/a&gt;：可是我喜欢你，你不要喜欢他好吗？', '大心心', '/blog/img/avatar/7.jpg', '2016-07-16 10:50:06', 1),
(2016070801, 33, '12', '', '回复&lt;a href=#comment22&gt;&lt;span&gt;@大心心&lt;/span&gt;&lt;/a&gt;：你喜欢他，可是我喜欢你啊。', '小心心', '/blog/img/avatar/7.jpg', '2016-07-16 14:26:44', 1),
(2016070801, 34, '12', '', '回复&lt;a href=#comment22&gt;&lt;span&gt;@大心心&lt;/span&gt;&lt;/a&gt;：我不喜欢你，别追我了。', '阿心', '/blog/img/avatar/7.jpg', '2016-07-16 14:31:42', 1),
(2016070801, 43, '12', '', '回复&lt;a href=#comment12&gt;&lt;span&gt;@小飞飞&lt;/span&gt;&lt;/a&gt;：我也喜欢你。', '新心', '/blog/img/avatar/7.jpg', '2016-07-16 14:59:15', 1),
(2016070801, 51, '12', '', '回复&lt;a href=#comment21&gt;&lt;span&gt;@阿心&lt;/span&gt;&lt;/a&gt;：我爱你。', '小甜甜', '/blog/img/avatar/7.jpg', '2016-07-16 15:18:01', 1),
(2016070801, 52, '12', '', '回复&lt;a href=#comment12&gt;&lt;span&gt;@小飞飞&lt;/span&gt;&lt;/a&gt;：我对你的忠贞不渝。', '阿心', '/blog/img/avatar/7.jpg', '2016-07-16 15:18:20', 1),
(2016070801, 69, '', '109,110,111,112,113,', '我是大哥。', '我是大哥', '/blog/img/avatar/7.jpg', '2016-07-16 16:26:02', 1),
(2016070801, 109, '69', '', '回复<a><span>@我是大哥</span></a>：我是二哥', '我是二哥', '/blog/img/avatar/7.jpg', '2016-07-17 10:30:53', 1),
(2016070801, 110, '69', '', '回复&lt;a href=#comment109&gt;&lt;span&gt;@我是二哥&lt;/span&gt;&lt;/a&gt;：我是三哥', '我是三哥', '/blog/img/avatar/7.jpg', '2016-07-17 10:33:48', 1),
(2016070801, 111, '69', '', '回复&lt;a href=#comment110&gt;&lt;span&gt;@我是三哥&lt;/span&gt;&lt;/a&gt;：我是四哥', '我是四哥', '/blog/img/avatar/7.jpg', '2016-07-17 10:35:23', 1),
(2016070801, 112, '69', '', '回复&lt;a href=#comment111&gt;&lt;span&gt;@我是四哥&lt;/span&gt;&lt;/a&gt;：我是五哥', '我是五哥', '/blog/img/avatar/7.jpg', '2016-07-17 10:38:49', 1),
(2016070801, 113, '69', '', '回复&lt;a href=#comment112&gt;&lt;span&gt;@我是五哥&lt;/span&gt;&lt;/a&gt;：像我这么屌的还有一打！！！', '我是N哥', '/blog/img/avatar/7.jpg', '2016-07-17 10:45:56', 1),
(2016070801, 177, '', '', '我是大表哥。', '大表哥', '/blog/img/avatar/1.jpg', '2016-07-19 09:26:32', 1),
(2016070801, 178, '', '', '我是小婊砸。', '小婊砸', '/blog/img/avatar/2.jpg', '2016-07-19 09:32:23', 1),
(2016070801, 189, '', '', '睡了吗？', '冬眠', '/blog/img/avatar/3.jpg', '2016-07-19 12:32:41', 1),
(2016070801, 191, '', '', '明天去哪里玩呢？', '若轩', '/blog/img/avatar/5.jpg', '2016-07-19 14:38:23', 1),
(2016070801, 221, '', '', '尼玛。。。。再也不给这评论部分加需求了，累死爹了！！！', '刘大帅', '/blog/img/avatar/7.jpg', '2016-07-21 08:16:51', 1),
(2016070801, 222, '', '', '最爱你夕阳下的身影。', 'Lydia', '/blog/img/avatar/4.jpg', '2016-07-21 08:21:14', 1),
(2016070201, 224, '', '', '这是新测试文章哦。', '若轩', '/blog/img/avatar/5.jpg', '2016-07-23 03:10:49', 1),
(2016070201, 225, '', '226,', '测试页面静态化。', '冬眠', '/blog/img/avatar/3.jpg', '2016-07-23 03:25:57', 1),
(2016070201, 226, '225', '', '回复&lt;a href=#comment225&gt;&lt;span&gt;@冬眠&lt;/span&gt;&lt;/a&gt;：测试静态页面文章嵌套评论。', '若轩', '/blog/img/avatar/5.jpg', '2016-07-23 03:26:51', 1),
(2016072302, 228, '', '', '这是一个测试评论。', '测试', '/blog/img/avatar/5.jpg', '2016-07-23 10:06:13', 1),
(2016072302, 235, '', '', '这是第二个测试评论。', '测试', 'http://localhost/blog/img/avatar/2.jpg', '2016-07-24 15:52:10', 1),
(2016072402, 239, '', '', '这是第一个新样式的评论测试。', '洛轩', '/blog/img/avatar/5.jpg', '2016-07-24 17:11:28', 1),
(2016072402, 241, '', '', '这是第二个新样式的评论测试。', '冬眠', '/blog/img/avatar/3.jpg', '2016-08-08 16:17:44', 1),
(2016072402, 242, '', '', '这是第三个新样式的评论测试。', '心心', 'http://localhost/blog/img/avatar/psb2.png', '2016-08-08 16:18:34', 1),
(2016072402, 276, '', '', '&amp;lt;author==true&amp;gt;大家好，我是天边。', '天边', '/blog/img/avatar/skyside.png', '2016-08-13 07:49:31', 1),
(2016081602, 291, '', '', '&amp;lt;author==true&amp;gt;这是[:grin:]第一个有表情的评论~~~[:cool:][:cool:][:cool:]', '洛轩', '/blog/img/avatar/5.jpg', '2016-08-16 14:27:55', 1),
(2016081602, 292, '', '294,295,', '&amp;lt;author==true&amp;gt;心心你太帅了 [:lol:][:lol:][:lol:]，我要给你生猴子~~[:cool:][:cool:]~~', '冬眠', '/blog/img/avatar/3.jpg', '2016-08-16 14:32:26', 1),
(2016081602, 294, '292', '', '&amp;lt;author==true&amp;gt;回复@@@292-冬眠@@：冬眠晚上好~', '洛轩', '/blog/img/avatar/5.jpg', '2016-08-17 12:07:56', 1),
(2016081602, 295, '292', '', '&amp;lt;author==true&amp;gt;回复@@@294-洛轩@@：洛轩晚上好啊~', '冬眠', '/blog/img/avatar/3.jpg', '2016-08-17 14:38:32', 1),
(2016081505, 296, '', '', '&amp;lt;author==true&amp;gt;[:lol:][:muse:]好久不见。', '天边', '/blog/img/avatar/5.jpg', '2017-02-11 13:06:37', 0),
(0, 298, '', '', '&amp;lt;author==true&amp;gt;皓，下午好，今天是第一天，将博客重新改了一下，2018加油加油~ [:cool:][:grin:][:eye:]', 'Ming', '/blog/img/avatar/5.jpg', '2018-01-02 08:32:21', 0);

-- --------------------------------------------------------

--
-- 表的结构 `recycle`
--

DROP TABLE IF EXISTS `recycle`;
CREATE TABLE IF NOT EXISTS `recycle` (
  `article_id` int(11) NOT NULL COMMENT '文章ID号',
  `title` char(40) CHARACTER SET utf8 NOT NULL DEFAULT '无题' COMMENT '标题',
  `link` char(100) CHARACTER SET utf8 DEFAULT NULL,
  `user` char(20) CHARACTER SET utf8 NOT NULL DEFAULT '天边' COMMENT '用户',
  `content` text CHARACTER SET utf8 COMMENT '内容',
  `head_pic` varchar(200) COLLATE utf8_bin NOT NULL DEFAULT '/blog/img/title/default.png' COMMENT '文章列表显示的默认头图',
  `class` char(20) CHARACTER SET utf8 NOT NULL DEFAULT '未分类' COMMENT '分类',
  `keywords` char(50) CHARACTER SET utf8 DEFAULT NULL,
  `pub_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发表时间',
  `click` int(11) NOT NULL DEFAULT '0' COMMENT '点击数',
  `comment` int(10) NOT NULL DEFAULT '0',
  `fav` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `username` varchar(100) COLLATE utf8_bin NOT NULL,
  `password` varchar(100) COLLATE utf8_bin NOT NULL,
  `email` char(30) COLLATE utf8_bin NOT NULL,
  `avatar` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`username`, `password`, `email`, `avatar`) VALUES
('天边', '80b9104949fd51e6c71a52aced47c667755d3451', '616315821@qq.com', 'skyside.png');

-- --------------------------------------------------------

--
-- 表的结构 `works`
--

DROP TABLE IF EXISTS `works`;
CREATE TABLE IF NOT EXISTS `works` (
  `works_id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '未命名项目',
  `description` varchar(300) COLLATE utf8_bin NOT NULL,
  `head_pic` varchar(100) COLLATE utf8_bin NOT NULL,
  `url` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`works_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- 转存表中的数据 `works`
--

INSERT INTO `works` (`works_id`, `name`, `description`, `head_pic`, `url`) VALUES
(14, '轮播图demo', '这是第一次jquery实战的demo', '/blog/img/title/0b2cf1273342800f8008fb0281e58469dc0c1eb5b11b-8aUTqX_fw658.png', 'http://dreamer-somebody.github.io/demo/');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
