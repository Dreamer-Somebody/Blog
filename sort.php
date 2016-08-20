<?php
include_once "common/include.php";
@$key   = $_GET['key'] ? $_GET['key'] : 'false';
@$value = $_GET['value'] ? $_GET['value'] : 'false';
@$page  = $_GET['page'] ? $_GET['page'] : 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>文章分类--天边的博客</title>
	<link rel="stylesheet" href="/blog/index.css" />
	<link rel="stylesheet" href="/blog/common/blog.css" />
	<style>
	div#content{
		display: block;
	}
	div#show_article{
		margin-left: 40px;
	}
	div#classify h3{
		font-weight: 500;
		font-size: 16px;
	}
	a.content{
		width: auto;
	}
	p#tips{
		text-align:center;
		font-size:18px;
		font-weight: 600;
		background-color: #fff;
		padding: 20px;
		border-radius: 6px;
		box-shadow: 0 2px 10px rgba(0,0,0,.25);
	}
	</style>
</head>
<body>
<div id="container">
<div id="content">
<?php
show_classify(1);
?>
<div id="show_article">
<?php
$query     = "select * from article where {$key} like '%{$value}%' order by pub_time desc";
$sub_query = "key={$key}&value={$value}";
if ($key !== "false" && $value !== "false") {
    echo "<p id='header' ><span style='color:#ff5e53'>{$value}</span> 分类的查询结果：</p>";
}

show_article_list($query, $page, $sub_query);
?>
	</div>
	</div>
	</div>
</body>
</html>