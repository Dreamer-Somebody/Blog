<?php
include_once "../../../common/include.php";
$conn    = db_connect();
$result1 = $conn->query("update article set click=click+1 where article_id='2016070801'");
$result2 = $conn->query("select title,user,content,class,keywords,pub_time,upd_time,click,comment
	,fav,avatar from article,user where article_id= '2016070801' and article.user=user.username");
$row = $result2->fetch_array();
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
	<div id="article">
	<div id="title">
<?php
echo "<h1>{$row['title']}</h1>";
echo "<div id='post-info'>";
echo "<a href='###'><img src='/blog/img/avatar/{$row['avatar']}'/>天边</a>";
echo "<span id='time'>{$row['pub_time']}</span>";
echo "</div>";
?>
	</div>
		<div id="content">
		<?php
echo "{$row['content']}";
echo "<div id='addition'>";
$keywords = explode(',', $row['keywords']);
foreach ($keywords as $keyword) {
    echo "<a href='/blog/sort.php?key=keywords&value={$keyword}' class='keyword'>{$keyword} +</a>";
}
echo "</div>";
echo "<div id='share'>分享到： <a class='circle-weibo'><i class='icon-weibo'></i></a><a class='circle-qzone'>
<i class='icon-github'></i></a><a class='circle-wechat'><i class='icon-bubbles'></i></a></div>";
echo "<div id='like'><div id='like-button' data-id='2016070801'><i class='icon-good'></i>支持<span id='likes-count'>
{$row['fav']}</span></div><p id='thanks' fav=0></p></div>";
?>
		</div>
	<?php
echo "<div id='list'>";
echo "<ul id='list_nav'><li class='similar now'>相关文章</li><li class='popular'>热门文章</li><li class='recommended'>推荐文章</li></ul>";
echo "<ul class='show sublist similar'>";
foreach ($keywords as $keyword) {
    $conn   = db_connect();
    $len    = intval(5 / count($keywords));
    $query  = "select title,link from article where keywords like '%{$keyword}%' limit 0,{$len}";
    $result = $conn->query($query);
    $rows   = $result->num_rows;
    for (; $rows > 0; $rows--) {
        $row = $result->fetch_array();
        echo "<li><i class='icon-link'></i><a href='{$row['link']}'>{$row['title']}</a></li>";
    }
}
echo "</ul>";
echo "<ul class='sublist popular'>";
$conn   = db_connect();
$query  = "select title,link,click from article order by click desc limit 0,5";
$result = $conn->query($query);
$rows   = $result->num_rows;
for (; $rows > 0; $rows--) {
    $row = $result->fetch_array();
    echo "<li><i class='icon-link'></i><a href='{$row['link']}'>{$row['title']}</a></li>";
}
echo "</ul>";
echo "<ul class='sublist recommended'>";
$conn   = db_connect();
$query  = "select title,link,fav from article order by fav desc limit 0,5";
$result = $conn->query($query);
$rows   = $result->num_rows;
for (; $rows > 0; $rows--) {
    $row = $result->fetch_array();
    echo "<li><i class='icon-link'></i><a href='{$row['link']}'>{$row['title']}</a></li>";
}
echo "</ul>";
echo "</div>";

echo "<div id='comment'>";
echo "<h1>评论</h1>";
echo "<form><div id='pic'><img src='/blog/img/avatar/skyside2.jpg' name='avatar'/></div>
<input type='text' id='nickname' name='nickname' placeholder='昵称'/><textarea placeholder='评论...' name='content'></textarea><div id='emoji'></div>
<button type='submit'/>发表</button>
</form>";
echo "</div>";
?>
	</div>
	<script>
	uParse('#content',{
	});
	</script>
	<script src="/blog/common/article.js"></script>
</body>
</html>
