<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>天边的博客</title>
	<link rel="stylesheet" href="/blog/index.css" />
	<link rel="stylesheet" href="/blog/common/article.css" />
</head>
<body>
	<?php
	include_once("components/sidebar/sidebar.php");
	include_once("components/head/head.php");
	?>
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
			<p id="header">最新文章</p>
			<?php
			include_once("common/db_fns.php");
			$conn = db_connect();
			$query = "select * from article";
			$result = $conn->query($query);
			$rows= $result->num_rows;
			for($i=1;$i<=$rows;$i++){
				$row = $result->fetch_array();
				echo "<div class='article'>";
				echo "<span class='tags'>".$row['class']."</span>";
				echo "<a href='".$row['link']."'><strong class='title'>".$row['title']."</strong></a>";
				echo "<p class='info1'>".$row['user']." 发表于 ".$row['pub_time']."</p>";
				echo "<p class='content'>".$row['content']."</p>";
				echo "<p class='info2'>"."<i class='icon-eye'></i>阅读(".$row['click'].") <i class='icon-bubbles'></i>评论(".$row['comment'].") <i class='icon-good'></i>赞(".$row['fav'].") "
				."<i class='icon-price-tag'></i>关键字: ".$row['keywords']."</p>";
				echo "</div>";
			}
			?>
		</div>
		<div id="classify">
			<div class="time">
				<h3>文章归档</h3>
			</div>
			<div class="category">
				<h3>文章分类</h3>
			</div>
			<div class="tags">
				<h3>标签分类</h3>
			</div>
		</div>
		</div>
	</div>
	<script src="/blog/common/jquery.js"></script>
	<script src="/blog/index.js"></script>
</body>
</html>