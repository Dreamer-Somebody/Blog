<?php
include_once "../../../common/include.php";
$conn   = db_connect();
$result = $conn->query("select title,user,content,class,keywords,pub_time,upd_time,click,comment,fav from article where article_id= '2016070203'");
$row    = $result->fetch_array();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title><?php echo $row['title'] ?></title>
	<link rel="stylesheet" href="/blog/index.css" />
	<link rel="stylesheet" href="/blog/common/article.css" />
	<script src="/blog/components/ueditor/utf8-php/ueditor.parse.js"></script>
	<link rel="stylesheet" href="/blog/components/ueditor/utf8-php/third-party/SyntaxHighlighter/shCoreDefault.css" />
    <script src="/blog/components/ueditor/utf8-php/third-party/SyntaxHighlighter/shCore.js"></script>
</head>
<body>
	<div id="container">
	<div id="title">
<?php
echo $row['title'];
echo "<br/>分类：" . $row['class'];
echo "<br/>发表时间：{$row['pub_time']}";
echo " 更新时间：{$row['upd_time']}";
?>
	</div>
		<div id="content">
		<?php
echo "{$row['content']}";
?>
		</div>
	</div>
	<script>
	uParse('#content',{
		rootPath:'../../../components/ueditor/utf8-php/'
	});
	SyntaxHighlighter.all();
	</script>
</body>
</html>
