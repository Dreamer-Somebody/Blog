<?php
include_once "common/include.php";
@$key   = $_GET['key'] ? $_GET['key'] : '';
@$value = $_GET['value'] ? $_GET['value'] : '';
@$page  = $_GET['page'] ? $_GET['page'] : 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>天边的博客</title>
	<link rel="stylesheet" href="/blog/index.css" />
	<link rel="stylesheet" href="/blog/common/blog.css" />
</head>
<body>
<div id="container">
<div id="content">
<div id="show_article">
	<?php
$query     = "select * from article where {$key} like '%{$value}%'";
$sub_query = "key={$key}&value={$value}";
show_article_list($query, $page, $sub_query);
?>
	</div>
	</div>
	</div>
</body>
</html>