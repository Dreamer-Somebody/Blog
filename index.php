<?php
include_once "common/include.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name='keywords' content='天边,个人博客,前端开发,生活感悟,web开发,blog,html,css,js,php'/>
<meta name='description' content='天边的个人博客,前端开发分享与生活交流，利用html,css,js,php等工具将想法创造成现实。'/>
	<title>天边的博客</title>
	<link rel="stylesheet" href="/blog/index.css" />
	<link rel="stylesheet" href="/blog/common/blog.css" />
</head>
<body>
	<div id="container">
		<div id="pic">
		<a id="switch">
			<i class="icon-loop"></i>
			<span>换一换</span>
		</a>
			<img src="/blog/img/headPic/8.jpg" alt="" />
		</div>
		<div id="content">
		<div id="show_article">
			<p id="header"><span>最新文章</span></p>
			<?php
$query = "select * from article order by pub_time desc";
@$page = $_GET['page'] ? $_GET['page'] : 1;
show_article_list($query, $page);
echo "</div>";
show_classify();
?>
		</div>
	</div>
	<script src="/blog/common/jquery.js"></script>
	<script src="/blog/index.js"></script>
</body>
</html>